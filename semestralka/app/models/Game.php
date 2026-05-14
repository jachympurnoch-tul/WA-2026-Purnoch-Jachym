<?php

class Game {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create(
        string $title,
        ?int $categoryId,
        int $subcategoryId,
        string $developer,
        int $year,
        float $price,
        string $publisher,
        string $description,
        string $link,
        array $images,
        int $userId
    ) {
        $sql = "INSERT INTO games (title, category, subcategory, developer, release_year, price, publisher, description, link, images, created_by)
                VALUES (:title, :category, :subcategory, :developer, :release_year, :price, :publisher, :description, :link, :images, :created_by)";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([
            ':title' => $title,
            ':category' => $categoryId,
            ':subcategory' => $subcategoryId,
            ':developer' => $developer,
            ':release_year' => $year,
            ':price' => $price,
            ':publisher' => $publisher,
            ':description' => $description,
            ':link' => $link,
            ':images' => json_encode($images),
            ':created_by' => $userId
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getAll($search = '', $tagIds = [], $subcategory = '') {
        $sql = "SELECT g.*, s.name as subcategory_name, 
                       GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR '||') as tag_names,
                       GROUP_CONCAT(t.id ORDER BY t.name SEPARATOR '||') as tag_ids
                FROM games g 
                LEFT JOIN subcategories s ON g.subcategory = s.id
                LEFT JOIN game_tags gt ON g.id = gt.game_id
                LEFT JOIN tags t ON gt.tag_id = t.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (g.title LIKE :search OR g.developer LIKE :search OR g.publisher LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        if (!empty($tagIds) && is_array($tagIds)) {
            // Použijeme AND logiku (hra musí mít všechny vybrané tagy)
            $tagPlaceholders = [];
            foreach ($tagIds as $idx => $tId) {
                $p = ':tag_' . $idx;
                $tagPlaceholders[] = $p;
                $params[$p] = (int)$tId;
            }
            $inClause = implode(',', $tagPlaceholders);
            $count = count($tagIds);
            $sql .= " AND (SELECT COUNT(*) FROM game_tags WHERE game_id = g.id AND tag_id IN ($inClause)) = $count";
        }
        
        if (!empty($subcategory)) {
            $sql .= " AND g.subcategory = :subcategory";
            $params[':subcategory'] = (int)$subcategory;
        }
        
        $sql .= " GROUP BY g.id ORDER BY g.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT g.*, s.name as subcategory_name,
                       GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR '||') as tag_names,
                       GROUP_CONCAT(t.id ORDER BY t.name SEPARATOR '||') as tag_ids
                FROM games g 
                LEFT JOIN subcategories s ON g.subcategory = s.id
                LEFT JOIN game_tags gt ON g.id = gt.game_id
                LEFT JOIN tags t ON gt.tag_id = t.id
                WHERE g.id = :id
                GROUP BY g.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(
        $id, $title, $categoryId, $subcategoryId, $developer, 
        $year, $price, $publisher, $description, $link, $images = [],
        $userId = null
    ) {
        $sql = "UPDATE games 
                SET title = :title, 
                    category = :category,
                    subcategory = :subcategory,
                    developer = :developer, 
                    release_year = :release_year, 
                    price = :price, 
                    publisher = :publisher, 
                    description = :description, 
                    link = :link, 
                    images = :images,
                    updated_by = :updated_by
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':category' => $categoryId,
            ':subcategory' => $subcategoryId,
            ':developer' => $developer,
            ':release_year' => $year,
            ':price' => $price,
            ':publisher' => $publisher,
            ':description' => $description,
            ':link' => $link,
            ':images' => json_encode($images),
            ':updated_by' => $userId
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM games WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
