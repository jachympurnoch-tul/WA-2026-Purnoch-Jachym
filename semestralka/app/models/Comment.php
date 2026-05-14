<?php

class Comment {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getByGameId(int $gameId) {
        $sql = "SELECT c.*, u.username, u.nickname 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.game_id = :game_id
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':game_id' => $gameId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(int $gameId, int $userId, string $content, int $rating = 5): bool {
        $sql = "INSERT INTO comments (game_id, user_id, content, rating) VALUES (:game_id, :user_id, :content, :rating)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':game_id' => $gameId,
            ':user_id' => $userId,
            ':content' => $content,
            ':rating'  => $rating
        ]);
    }

    public function update(int $id, string $content, int $rating): bool {
        $sql = "UPDATE comments SET content = :content, rating = :rating WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':content' => $content,
            ':rating'  => $rating,
            ':id'      => $id
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Ověření, zda uživatel již napsal komentář k dané hře
    public function hasUserCommented(int $gameId, int $userId): bool {
        $sql = "SELECT COUNT(*) FROM comments WHERE game_id = :game_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':game_id' => $gameId, ':user_id' => $userId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
