<?php
require_once 'app/models/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->query("DESCRIBE books");
$schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($schema);
echo "</pre>";
