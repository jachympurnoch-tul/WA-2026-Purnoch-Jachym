<?php

class Playlist {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        try {
            $this->db->exec("ALTER TABLE playlists ADD COLUMN image VARCHAR(500) DEFAULT NULL AFTER name");
        } catch (PDOException $e) {
            // Sloupec už pravděpodobně existuje
        }
    }

    public function create(int $userId, string $name, bool $isDefault = false): bool {
        $sql = "INSERT INTO playlists (user_id, name, is_default) VALUES (:user_id, :name, :is_default)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':name' => $name,
            ':is_default' => $isDefault ? 1 : 0
        ]);
    }

    public function getByUser(int $userId) {
        $sql = "SELECT p.*, 
                ((SELECT COUNT(*) FROM songs s WHERE s.playlist_id = p.id) + 
                 (SELECT COUNT(*) FROM playlist_external_songs es WHERE es.playlist_id = p.id)) as song_count 
                FROM playlists p WHERE user_id = :userId ORDER BY is_default DESC, created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDefault(int $userId): bool {
        return $this->create($userId, 'Oblíbené', true);
    }

    public function getById(int $id) {
        $sql = "SELECT * FROM playlists WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getDefaultByUser(int $userId) {
        $sql = "SELECT * FROM playlists WHERE user_id = :userId AND is_default = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id, int $userId): bool {
        // Zabrání smazání výchozího playlistu
        $sql = "DELETE FROM playlists WHERE id = :id AND user_id = :userId AND is_default = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':userId' => $userId
        ]);
    }

    public function updateImage(int $id, int $userId, string $image): bool {
        $sql = "UPDATE playlists SET image = :image WHERE id = :id AND user_id = :userId AND is_default = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':userId' => $userId,
            ':image' => $image
        ]);
    }

    public function clearImage(int $id, int $userId): bool {
        $sql = "UPDATE playlists SET image = NULL WHERE id = :id AND user_id = :userId AND is_default = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':userId' => $userId]);
    }
}
