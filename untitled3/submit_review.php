<?php
// Konfigurácia pripojenia k databáze
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$password = 'postgres';
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $item_name = htmlspecialchars($_POST['item_name']);
        $review_text = htmlspecialchars($_POST['review_text']);

        $sql = "INSERT INTO reviews (item_name, review_text) VALUES (:item_name, :review_text)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':item_name' => $item_name,
            ':review_text' => $review_text
        ]);

        echo "Review successfully submitted!";
    } else {
        echo "Invalid request method.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
