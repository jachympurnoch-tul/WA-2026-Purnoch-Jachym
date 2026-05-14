<?php

class ExternalSong {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->ensureTable();
    }

    private function ensureTable(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS playlist_external_songs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                playlist_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                artist VARCHAR(255) NOT NULL,
                image VARCHAR(500),
                preview_url VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
                UNIQUE KEY unique_song_playlist (playlist_id, title, artist)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        try {
            $this->db->exec("ALTER TABLE playlist_external_songs ADD COLUMN album VARCHAR(255) AFTER artist");
        } catch (PDOException $e) {}
        try {
            $this->db->exec("ALTER TABLE playlist_external_songs ADD COLUMN album_id VARCHAR(100) AFTER album");
        } catch (PDOException $e) {}
        try {
            $this->db->exec("ALTER TABLE playlist_external_songs ADD COLUMN artist_id VARCHAR(100) AFTER artist");
        } catch (PDOException $e) {}
    }

    /** Získá ID playlistů, do kterých je tato skladba uložena */
    public function getPlaylistsForSong(string $title, string $artist): array {
        $stmt = $this->db->prepare(
            "SELECT playlist_id FROM playlist_external_songs WHERE title = :title AND artist = :artist"
        );
        $stmt->execute([':title' => $title, ':artist' => $artist]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'playlist_id');
    }

    /** Uloží skladbu do daného playlistu */
    public function addToPlaylist(int $playlistId, string $title, string $artist, string $album, string $image, string $previewUrl, string $albumId = '', string $artistId = ''): bool {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO playlist_external_songs (playlist_id, title, artist, album, image, preview_url, album_id, artist_id)
             VALUES (:playlist_id, :title, :artist, :album, :image, :preview_url, :album_id, :artist_id)"
        );
        return $stmt->execute([
            ':playlist_id' => $playlistId,
            ':title'       => $title,
            ':artist'      => $artist,
            ':album'       => $album,
            ':image'       => $image,
            ':preview_url' => $previewUrl,
            ':album_id'    => $albumId,
            ':artist_id'   => $artistId,
        ]);
    }

    /** Odebere skladbu z daného playlistu */
    public function removeFromPlaylist(int $playlistId, string $title, string $artist): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM playlist_external_songs WHERE playlist_id = :playlist_id AND title = :title AND artist = :artist"
        );
        return $stmt->execute([
            ':playlist_id' => $playlistId,
            ':title'       => $title,
            ':artist'      => $artist,
        ]);
    }

    /** Hromadná synchronizace: uloží do vybraných, odebere z ostatních */
    public function syncPlaylists(array $selectedIds, string $title, string $artist, string $album, string $image, string $previewUrl, array $allUserPlaylistIds, string $albumId = '', string $artistId = ''): bool {
        foreach ($allUserPlaylistIds as $pid) {
            if (in_array($pid, $selectedIds)) {
                $this->addToPlaylist($pid, $title, $artist, $album, $image, $previewUrl, $albumId, $artistId);
            } else {
                $this->removeFromPlaylist($pid, $title, $artist);
            }
        }
        return true;
    }

    /** Všechny skladby v daném playlistu */
    public function getByPlaylist(int $playlistId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM playlist_external_songs WHERE playlist_id = :pid ORDER BY created_at DESC"
        );
        $stmt->execute([':pid' => $playlistId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
