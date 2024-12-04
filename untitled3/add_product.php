<?php
// Zahrnutie pripojenia k databáze
include 'db_config.php';

// Načítanie produktov s limitom
try {
    $limit = 3; // Počet produktov na stránku
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Aktuálna stránka
    $offset = ($page - 1) * $limit; // Výpočet offsetu

    // Zistenie celkového počtu produktov
    $countQuery = "SELECT COUNT(*) as total FROM products";
    $countStmt = $pdo->query($countQuery);
    $totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Dotaz na produkty s limitom a offsetom
    $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba pri načítaní produktov: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product.css">
</head>
<body>
<header>
    <div class="header-container">
        <h1>Bezlepkový e-shop</h1>
        <!-- Tlačidlo hamburger menu -->
        <button class="hamburger" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <!-- Navigácia -->
        <nav class="menu-container">
            <ul class="menu">
                <li><a href="index.php">Domov</a></li>
                <li><a href="add_product.php">Produkty</a></li>
                <li class="dropdown">
                    <a href="add_product.php" class="dropbtn">Kategórie</a>
                    <div class="dropdown-content">
                        <a href="page3.html">Chleby</a>
                        <a href="add_product.php">Múky</a>
                        <a href="add_product.php">Sladkosti</a>
                        <a href="add_product.php">Cestoviny</a>
                    </div>
                </li>
                <li><a href="#">Blog</a></li>
                <li><a href="login.html">Prihlásenie</a></li>
                <li><a href="contact.html">Kontakt</a></li>
                <li><a href="card.html" class="cart-link">Košík (<span id="cart-count">0</span>)</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">

    <!-- Filtrovacie možnosti -->
    <div class="filters">
        <div>
            <label for="category">Kategória:</label>
            <select id="category">
                <option value="all">Všetky</option>
                <option value="electronics">Elektronika</option>
                <option value="clothing">Oblečenie</option>
                <option value="home">Domov</option>
            </select>
        </div>

        <div>
            <label for="price">Cena:</label>
            <input type="number" id="price" min="0" value="1000">
        </div>

        <div>
            <label for="rating">Hodnotenie:</label>
            <select id="rating">
                <option value="all">Všetky</option>
                <option value="1">1 hviezdička</option>
                <option value="2">2 hviezdičky</option>
                <option value="3">3 hviezdičky</option>
                <option value="4">4 hviezdičky</option>
                <option value="5">5 hviezdičiek</option>
            </select>
        </div>

        <div>
            <label for="availability">Dostupnosť:</label>
            <select id="availability">
                <option value="all">Všetky</option>
                <option value="in-stock">Na sklade</option>
                <option value="out-of-stock">Vypredané</option>
            </select>
        </div>

        <div>
            <label for="brand">Značka:</label>
            <select id="brand">
                <option value="all">Všetky</option>
                <option value="apple">Apple</option>
                <option value="nike">Nike</option>
                <option value="samsung">Samsung</option>
            </select>
        </div>
    </div>

    <!-- Zobrazenie produktov -->
    <div id="product-list" class="product-list"></div>
    <!-- Stránkovanie -->
    <div class="pagination">
        <?php
        $totalPages = ceil($totalProducts / $limit); // Počet strán

        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a href="?page=' . $i . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
        }
        ?>
    </div>
</div>
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>O nás</h4>
            <p>Bezlepkový e-shop prináša kvalitné produkty pre ľudí s intoleranciou na lepok.</p>
        </div>
        <div class="footer-section">
            <h4>Kontakt</h4>
            <p>Email: <a href="mailto:info@bezlepkovyeshop.sk">info@bezlepkovyeshop.sk</a></p>
            <p>Tel: <a href="tel:+421123456789">+421 123 456 789</a></p>
        </div>
        <div class="footer-section">
            <h4>Sledujte nás</h4>
            <p>
                <a href="#" class="social-link"><i class="fab fa-facebook"></i> Facebook</a> |
                <a href="#" class="social-link"><i class="fab fa-instagram"></i> Instagram</a>
            </p>
        </div>
    </div>
    <p>&copy; 2023 Bezlepkový e-shop. Všetky práva vyhradené.</p>
</footer>

<script>
    // Generovanie produktov z PHP do JavaScriptu
    const products = <?php echo json_encode($products); ?>;

    // Filtrovacie funkcie
    function filterProducts() {
        const category = document.getElementById('category').value;
        const price = parseInt(document.getElementById('price').value);
        const rating = document.getElementById('rating').value;
        const availability = document.getElementById('availability').value;
        const brand = document.getElementById('brand').value;

        const filteredProducts = products.filter(product => {
            return (
                (category === 'all' || product.category === category) &&
                product.price <= price &&
                (rating === 'all' || product.rating >= parseInt(rating)) &&
                (availability === 'all' || product.availability === availability) &&
                (brand === 'all' || product.brand === brand)
            );
        });

        displayProducts(filteredProducts);
    }

    // Zobrazenie produktov na stránke
    function displayProducts(productsToDisplay) {
        const productList = document.getElementById('product-list');
        productList.innerHTML = ''; // Vyčistí zoznam pred zobrazením

        if (productsToDisplay.length === 0) {
            productList.innerHTML = '<p>Žiadne produkty zodpovedajúce filtrom.</p>';
            return;
        }

        productsToDisplay.forEach(product => {
            const productCard = document.createElement('div');
            productCard.classList.add('product-card');
            productCard.innerHTML = `
                <img src="https://via.placeholder.com/200" alt="${product.name}">
                <h3>${product.name}</h3>
                <p>${product.category}</p>
                <p class="price">${product.price} €</p>
                <p>Hodnotenie: ${'★'.repeat(product.rating)} (${product.rating} hviezdičiek)</p>
                <button class="buy-button">Pridať do košíka</button>
            `;
            productList.appendChild(productCard);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        displayProducts(products); // Zobraziť všetky produkty pri načítaní stránky

        document.getElementById('category').addEventListener('change', filterProducts);
        document.getElementById('price').addEventListener('input', function() {
            filterProducts();
        });
        document.getElementById('rating').addEventListener('change', filterProducts);
        document.getElementById('availability').addEventListener('change', filterProducts);
        document.getElementById('brand').addEventListener('change', filterProducts);
    });
</script>

</body>
</html>
