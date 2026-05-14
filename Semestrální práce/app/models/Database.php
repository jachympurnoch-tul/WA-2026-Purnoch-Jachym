<?php

class Database {
    private $host = "127.0.0.1";
    private $db_name = "semestralni_prace_2026";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        
        // Odpojí připojení k databázi tím, že změní proměnnou $this->conn na null.
        // Ukončí existující PDO objekt, což může být užitečné pro správu paměti.
        $this->conn = null;
        
        try {
            
            // PDO (PHP Data Objects) – Bezpečné a univerzální připojení k databázi
            // PDO je rozhraní pro práci s databázemi v PHP.
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $exception) {
            echo "Chyba připojení: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
