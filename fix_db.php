<?php
require_once 'app/models/Database.php';
try {
    $db = (new Database())->getConnection();
    // Změníme typ sloupce na TEXT, aby se JSON string ("['book_123.jpg']") neukládal jako 0
    $stmt = $db->query("ALTER TABLE books MODIFY COLUMN images TEXT");
    echo "<h1>Úspěšně opraveno!</h1><p>Sloupec 'images' v databázi nyní bez problému udrží názvy vašich fotek.</p>";
} catch (Exception $e) {
    echo "<h1>Chyba!</h1><p>" . $e->getMessage() . "</p>";
}
