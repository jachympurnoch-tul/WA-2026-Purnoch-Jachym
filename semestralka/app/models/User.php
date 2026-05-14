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

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword, // Do DB ukládáme hash, nikoliv čisté heslo!
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname
        ]);
    }

    // 2. Nalezení uživatele podle emailu (použijeme při registraci)
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2c. Nalezení uživatele podle uživatelského jména (kontrola duplicit)
    public function findByUsername(string $username) {
        $sql = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
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

    // 4. Aktualizace profilu uživatele
    public function update(int $id, string $firstName, string $lastName, string $nickname): bool {
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, nickname = :nickname WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname,
            ':id' => $id
        ]);
    }

    // 5. Získání všech uživatelů (pro admina)
    public function getAll() {
        $sql = "SELECT id, username, email, first_name, last_name, nickname, created_at FROM users ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Smazání uživatele
    public function delete(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 7. Získání hashe hesla
    public function getPasswordHash(int $id) {
        $sql = "SELECT password FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['password'] : null;
    }

    // 8. Změna hesla
    public function updatePassword(int $id, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $id
        ]);
    }

    // 9. Počet přidaných her
    public function getGamesCount(int $userId): int {
        $sql = "SELECT COUNT(*) FROM games WHERE created_by = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    // 10. Počet napsaných komentářů
    public function getCommentsCount(int $userId): int {
        $sql = "SELECT COUNT(*) FROM comments WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    // 11. Seznam přidaných her
    public function getGamesByUserId(int $userId) {
        $sql = "SELECT id, title, created_at FROM games WHERE created_by = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 12. Seznam komentářů včetně názvu hry
    public function getCommentsByUserId(int $userId) {
        $sql = "SELECT c.id, c.content, c.rating, c.created_at, g.id as game_id, g.title as game_title 
                FROM comments c 
                JOIN games g ON c.game_id = g.id 
                WHERE c.user_id = :user_id 
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}