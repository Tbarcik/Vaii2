<?php
include 'db_config.php'; // Pripojenie k databáze

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    echo '<div class="product-card">';
    echo '<img src="' . htmlspecialchars($product['image_path']) . '" alt="' . htmlspecialchars($product['name']) . '">';
    echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
    echo '<p>' . htmlspecialchars($product['category']) . '</p>';
    echo '<p class="price">' . htmlspecialchars($product['price']) . ' €</p>';
    echo '<p>Hodnotenie: ' . str_repeat('★', $product['rating']) . '</p>';
    echo '<p>' . ($product['availability'] === 'in-stock' ? 'Na sklade' : 'Vypredané') . '</p>';
    echo '<p>Značka: ' . htmlspecialchars($product['brand']) . '</p>';
    echo '</div>';
}
?>
