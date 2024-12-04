<?php
// Konfigurácia pripojenia k databáze
$host = 'localhost';
$dbname = 'postgres';
$user = 'postgres';
$password = 'postgres';

try {
    // Pripojenie k databáze
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Načítanie recenzií z databázy
    $sql = "SELECT * FROM reviews ORDER BY id DESC"; // Zobraziť recenzie v opačnom chronologickom poradí
    $stmt = $pdo->query($sql);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Chyba: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bezlepkový e-shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Header a Navigácia -->
<header>
    <div class="header-container">
        <h1>Bezlepkový e-shop</h1>
        <!-- Hamburger menu -->
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
                        <a href="add_product.php">Chleby</a>
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
<div class="newsletter-popup" id="newsletterPopup">
    <div class="popup-content">
        <h2>Prihláste sa na odber noviniek!</h2>
        <p>Získajte najnovšie informácie priamo do vašej schránky.</p>
        <form id="newsletterForm">
            <input type="email" id="email" placeholder="Vaša e-mailová adresa" required>
            <button type="submit">Prihlásiť sa</button>
        </form>
        <button class="close-btn" id="closePopup">Zavrieť</button>
    </div>
</div>

<!-- Carousel -->
<section class="carousel">
    <div class="carousel-slide">
        <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Carousel obrázok 1">
        <div class="carousel-caption">
            <h2>Bezlepkový chlieb</h2>
            <a href="add_product.php">  <button class="carousel-btn">Zistiť viac</button></a>
        </div>
    </div>
    <div class="carousel-slide">
        <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/Pecenie-chleba-z-kvasku-vs-drozdia.jpg" alt="Carousel obrázok 2">
        <div class="carousel-caption">
            <h2>Chrumkavé a čerstvé chleby pre každého</h2>
            <a href="add_product.php">  <button class="carousel-btn">Objednať teraz</button></a>
        </div>
    </div>
    <div class="carousel-indicators">
        <span class="dot active"></span>
        <span class="dot"></span>
    </div>
</section>
<div class="black-friday-banner">
    <div class="banner-content">
        <h1>BLACK<br>FRIDAY</h1>
        <p>ZĽAVA AŽ <span>30 %</span></p>
        <p class="small-text">Platí len do vypredania zásob!</p>
        <button onclick="window.location.href='add_product.php'">Nakupujte teraz</button>
    </div>
    <div class="animated-stars"></div>
    <div class="shiny-effect"></div>
</div>



<!-- Sekcia produktov -->
<main><!-- Reklamný box -->

    <section class="product-section">

        <h2>Naše Bezlepkové Produkty</h2>
        <div class="product-grid">
            <!-- Produkty -->
            <div class="product-card" data-name="Bezlepkový chlieb" data-description="Čerstvý chlieb bez lepku" data-price="5.99">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepkový chlieb</h3>
                <p>5.99 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>
            <div class="product-card" data-name="Bezlepková múka" data-description="Výborná múka na varenie a pečenie bez lepku" data-price="3.49">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepková múka</h3>
                <p>3.49 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>
            <div class="product-card" data-name="Bezlepkové sladkosti" data-description="Sladké potešenie bez lepku" data-price="2.99">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepkové sladkosti</h3>
                <p>2.99 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>
            <div class="product-card" data-name="Bezlepkové cestoviny" data-description="Chutné cestoviny bez lepku" data-price="4.49">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepkové cestoviny</h3>
                <p>4.49 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>

        </div>
        <h2>Naše Bezlepkové Produkty</h2>
        <div class="product-grid">
            <!-- Produkty -->
            <div class="product-card" data-name="Bezlepkový chlieb" data-description="Čerstvý chlieb bez lepku" data-price="5.99">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepkový chlieb</h3>
                <p>5.99 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>
            <div class="product-card" data-name="Bezlepková múka" data-description="Výborná múka na varenie a pečenie bez lepku" data-price="3.49">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepková múka</h3>
                <p>3.49 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>
            <div class="product-card" data-name="Bezlepkové sladkosti" data-description="Sladké potešenie bez lepku" data-price="2.99">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepkové sladkosti</h3>
                <p>2.99 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>
            <div class="product-card" data-name="Bezlepkové cestoviny" data-description="Chutné cestoviny bez lepku" data-price="4.49">
                <img src="https://assets.onecompiler.app/42vg5uhgk/42vffaa4g/LC-chleba.jpg" alt="Bezlepkový chlieb">
                <h3>Bezlepkové cestoviny</h3>
                <p>4.49 €</p>
                <button class="view-details-btn">Detail</button>
                <button class="add-to-cart-btn">Pridať do košíka</button>
            </div>

        </div>
    </section>
    <section class="articles-section">
        <h2>Najnovšie články</h2>
        <div class="articles-container">
            <?php
            try {
                // Načítanie článkov z databázy
                $sql = "SELECT title, excerpt, image_url, link FROM articles ORDER BY created_at DESC LIMIT 4";
                $stmt = $pdo->query($sql);
                $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($articles)):
                    foreach ($articles as $article): ?>
                        <div class="article-card">
                            <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                            <div class="article-content">
                                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                                <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                                <a href="<?php echo htmlspecialchars($article['link']); ?>" class="read-more">Čítať viac</a>
                            </div>
                        </div>
                    <?php endforeach;
                else: ?>
                    <p>Žiadne články na zobrazenie.</p>
                <?php endif;
            } catch (PDOException $e) {
                echo "<p>Chyba pri načítavaní článkov: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </section>
    <!-- Sekcia recenzií -->
    <section class="reviews-section">
        <h2>Recenzie</h2>
        <?php if (!empty($reviews)): ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <h3><?php echo htmlspecialchars($review['item_name']); ?></h3>
                        <p><strong>Recenzia:</strong> <?php echo htmlspecialchars($review['review_text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Žiadne recenzie na zobrazenie.</p>
        <?php endif; ?>
    </section>

</main>


<!-- Footer -->
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
    const hamburger = document.querySelector('.hamburger');
    const menuContainer = document.querySelector('.menu-container');

    hamburger.addEventListener('click', () => {
        menuContainer.classList.toggle('active');
    });
    const popup = document.getElementById('newsletterPopup');
    const closePopupBtn = document.getElementById('closePopup');

    // Funkcia na zobrazenie banneru
    window.onload = () => {
        setTimeout(() => {
            popup.style.display = 'flex';
        }, 1000);
    };

    // Funkcia na zavretie banneru
    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    // Funkcia na odoslanie formulára
    document.getElementById('newsletterForm').addEventListener('submit', (event) => {
        event.preventDefault();
        const email = document.getElementById('email').value;
        console.log('Prihlásený e-mail:', email);
        popup.style.display = 'none';
    });

</script>
<!-- Skripty -->
<script src="main.js"></script>

</body>
</html>
