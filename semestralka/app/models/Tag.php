<?php

class Tag {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllTags() {
        $sql = "SELECT * FROM tags ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsForGame(int $gameId) {
        $sql = "SELECT t.* FROM tags t 
                JOIN game_tags gt ON t.id = gt.tag_id 
                WHERE gt.game_id = :game_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':game_id' => $gameId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateGameTags(int $gameId, array $tagIds) {
        // Nejprve smažeme staré tagy pro tuto hru
        $sqlDelete = "DELETE FROM game_tags WHERE game_id = :game_id";
        $stmtDelete = $this->db->prepare($sqlDelete);
        $stmtDelete->execute([':game_id' => $gameId]);

        // Vložíme nové tagy (max 5)
        $tagIds = array_slice($tagIds, 0, 5); // Pojistka na max 5 tagů
        if (!empty($tagIds)) {
            $sqlInsert = "INSERT INTO game_tags (game_id, tag_id) VALUES (:game_id, :tag_id)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            
            foreach ($tagIds as $tagId) {
                $stmtInsert->execute([
                    ':game_id' => $gameId,
                    ':tag_id' => (int)$tagId
                ]);
            }
        }
    }
}
