<?php
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$pass = 'postgres';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Pripojenie k databáze prebehlo úspešne.";
} catch (PDOException $e) {
    die("Chyba pri pripojení k databáze: " . $e->getMessage());
}
?>
