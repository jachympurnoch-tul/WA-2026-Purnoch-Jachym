<?php

class Song {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    public function create(
        string $title,
        string $artist,
        string $album,
        string $genre,
        int $release_year,
        string $duration,
        string $description,
        string $link,
        string $audio_file = null,
        array $images,
        int $userId,
        int $playlistId = null
    ): bool {
        $sql = "INSERT INTO songs (title, artist, album, genre, release_year, duration, description, link, audio_file, images, created_by, playlist_id)
                VALUES (:title, :artist, :album, :genre, :release_year, :duration, :description, :link, :audio_file, :images, :created_by, :playlist_id)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':artist' => $artist,
            ':album' => $album ?: null,
            ':genre' => $genre ?: null,
            ':release_year' => $release_year,
            ':duration' => $duration ?: null,
            ':description' => $description,
            ':link' => $link,
            ':audio_file' => $audio_file,
            ':images' => json_encode($images),
            ':created_by' => $userId,
            ':playlist_id' => $playlistId
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM songs ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllByPlaylist(int $playlistId) {
        $sql = "SELECT * FROM songs WHERE playlist_id = :playlistId ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':playlistId' => $playlistId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM songs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(
        $id, $title, $artist, $album, $genre, 
        $release_year, $duration, $description, $link, $audio_file = null, $images = [],
        $userId = null
    ) {
        $sql = "UPDATE songs 
                SET title = :title, 
                    artist = :artist, 
                    album = :album, 
                    genre = :genre, 
                    release_year = :release_year, 
                    duration = :duration, 
                    description = :description, 
                    link = :link, 
                    audio_file = :audio_file,
                    images = :images,
                    updated_by = :updated_by
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':artist' => $artist,
            ':album' => $album ?: null,
            ':genre' => $genre ?: null,
            ':release_year' => $release_year,
            ':duration' => $duration ?: null,
            ':description' => $description,
            ':link' => $link,
            ':audio_file' => $audio_file,
            ':images' => json_encode($images),
            ':updated_by' => $userId
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM songs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
}