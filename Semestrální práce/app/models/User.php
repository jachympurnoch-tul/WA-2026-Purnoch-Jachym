<?php

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // 1. Registrace nového uživatele (nyní přijímá i jméno, příjmení a přezdívku)
    public function register(
        string $username, 
        string $email, 
        string $password, 
        ?string $firstName = null, 
        ?string $lastName = null, 
        ?string $nickname = null
    ): bool {
        // Kontrola, zda uživatel s tímto emailem už neexistuje
        if ($this->findByEmail($email)) {
            return false; // Email už je zabraný
        }

        // ZABEZPEČENÍ: Vytvoření bezpečného hashe z hesla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL dotaz rozšířen o nová pole
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, nickname) 
                VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword, 
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname
        ])) {
            $userId = $this->db->lastInsertId();
            
            // AUTOMATICKY: Vytvoříme výchozí playlist "Oblíbené"
            $sqlPl = "INSERT INTO playlists (user_id, name, is_default) VALUES (:user_id, 'Oblíbené', 1)";
            $stmtPl = $this->db->prepare($sqlPl);
            $stmtPl->execute([':user_id' => $userId]);
            
            return true;
        }
        return false;
    }

    // 2. Nalezení uživatele podle emailu (použijeme při registraci)
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2b. Nalezení uživatele podle emailu NEBO uživatelského jména (použijeme při přihlašování)
    public function findByIdentifier(string $identifier) {
        $sql = "SELECT * FROM users WHERE email = :identifier OR username = :identifier";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':identifier' => $identifier]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 3. Získání uživatele podle ID (hodí se pro zobrazení profilu atd.)
    public function findById(int $id) {
        // Zde jsme také přidali nová pole, aby se nám při načtení profilu vypsalo všechno
        $sql = "SELECT id, username, email, first_name, last_name, nickname, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}