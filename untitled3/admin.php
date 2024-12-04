<?php
// Zahrnutie pripojenia k databáze
include 'db_config.php';

// Pridanie nového používateľa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $query = "INSERT INTO usersss (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $email, $hashedPassword]);
        echo "<p class='success-message'>Nový používateľ bol úspešne pridaný.</p>";
    } catch (PDOException $e) {
        die("Chyba pri pridávaní používateľa: " . $e->getMessage());
    }
}

// Vymazanie používateľa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    try {
        $query = "DELETE FROM usersss WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        echo "<p class='success-message'>Používateľ bol úspešne vymazaný.</p>";
    } catch (PDOException $e) {
        die("Chyba pri vymazávaní používateľa: " . $e->getMessage());
    }
}

// Načítanie používateľov
try {
    $query = "SELECT * FROM usersss";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Chyba pri načítaní používateľov: " . $e->getMessage());
}

// Pridanie produktu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $availability = $_POST['availability'] === 'in-stock' ? true : false; // Úprava
    $brand = trim($_POST['brand']);

    // Kontrola, či sú všetky povinné polia vyplnené
    if (empty($name) || empty($category) || empty($price) || empty($brand)) {
        echo "<p class='error-message'>Všetky polia musia byť vyplnené.</p>";
        exit;
    }

    // Skúška vloženia produktu do databázy
    try {
        $query = "INSERT INTO products (name, category, price, rating, availability, brand) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $category, $price, $rating, $availability, $brand]);

        echo "<p class='success-message'>Produkt bol úspešne pridaný.</p>";
    } catch (PDOException $e) {
        // Zobrazenie chýb pri vkladaní do databázy
        echo "<p class='error-message'>Chyba pri pridávaní produktu: " . $e->getMessage() . "</p>";
    }
}

// Vymazanie produktu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    try {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$product_id]);
        echo "<p class='success-message'>Produkt bol úspešne vymazaný.</p>";
    } catch (PDOException $e) {
        die("Chyba pri vymazávaní produktu: " . $e->getMessage());
    }
}

// Načítanie produktov
try {
    $query = "SELECT * FROM products ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
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
    <title>Administrátorské prostredie</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Základné nastavenia */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            color: #333;
            margin: 0;
        }

        /* Kontajner */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Nadpis */
        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
            color: #4b6584;
        }

        .filter-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
            justify-content: space-between;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filter-group {
            flex: 1 1 220px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        select, input {
            padding: 12px;
            font-size: 0.9rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            transition: all 0.3s ease;
        }

        select:hover, input:hover {
            border-color: #6c63ff;
            box-shadow: 0 0 5px rgba(108, 99, 255, 0.2);
        }

        select:focus, input:focus {
            outline: none;
            border-color: #6c63ff;
            box-shadow: 0 0 8px rgba(108, 99, 255, 0.4);
        }

        /* Tlačidlo na odhlásenie */
        .logout-btn {
            display: inline-block;
            padding: 12px 20px;
            background: linear-gradient(135deg, #ff6a6a, #ff3d3d);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
            margin-top: 10px;
            text-align: center;
            max-width: 150px;
            margin-left: auto;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #ff3d3d, #d00000);
        }

        /* Zobrazenie produktov */
        #product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Karty produktov */
        .product-card {
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .product-card h3 {
            font-size: 1.2rem;
            color: #4b6584;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
        }

        .product-card .price {
            font-size: 1.4rem;
            color: #2d98da;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-card .buy-button {
            padding: 12px 18px;
            background: linear-gradient(135deg, #6c63ff, #4858e8);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
            box-shadow: 0 4px 8px rgba(108, 99, 255, 0.2);
        }

        .product-card .buy-button:hover {
            background: linear-gradient(135deg, #4858e8, #6c63ff);
            box-shadow: 0 6px 12px rgba(108, 99, 255, 0.4);
        }

        @media (max-width: 768px) {
            .filter-section {
                flex-direction: column;
            }

            .filter-group {
                flex: 1 1 100%;
            }

            .logout-btn {
                margin: 20px auto;
            }
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Administrátorské prostredie</h1>

    <!-- Správa používateľov -->
    <h2>Správa používateľov:</h2>
    <form action="admin.php" method="POST">
        <label for="username">Meno:</label>
        <input type="text" name="username" id="username" required><br>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password" required><br>
        <input type="submit" name="add_user" value="Pridať používateľa">
    </form>

    <!-- Zoznam používateľov -->
    <?php if (!empty($users)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Meno</th>
                <th>E-mail</th>
                <th>Akcie</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <input type="submit" name="delete_user" value="Vymazať">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Žiadni používatelia v databáze.</p>
    <?php endif; ?>

    <!-- Pridanie produktu -->
    <h2>Pridanie produktu:</h2>
    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <label for="name">Názov:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="category">Kategória:</label>
        <input type="text" name="category" id="category" required><br>
        <label for="price">Cena (€):</label>
        <input type="number" name="price" id="price" step="0.01" required><br>

        <label for="rating">Hodnotenie:</label>
        <input type="number" name="rating" id="rating" min="1" max="5"><br>
        <label for="availability">Dostupnosť:</label>
        <select name="availability" id="availability">
            <option value="in-stock">Na sklade</option>
            <option value="out-of-stock">Vypredané</option>
        </select><br>
        <label for="brand">Značka:</label>
        <input type="text" name="brand" id="brand"><br>
        <input type="submit" name="add_product" value="Pridať produkt">
    </form>

    <!-- Zoznam produktov -->
    <?php if (!empty($products)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Názov</th>
                <th>Kategória</th>
                <th>Cena</th>
                <th>Hodnotenie</th>
                <th>Dostupnosť</th>
                <th>Značka</th>
                <th>Akcie</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?> €</td>
                    <td><?= htmlspecialchars($product['rating']) ?></td>
                    <td><?= $product['availability'] ? 'Na sklade' : 'Vypredané' ?></td>
                    <td><?= htmlspecialchars($product['brand']) ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="submit" name="delete_product" value="Vymazať">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Žiadne produkty v databáze.</p>
    <?php endif; ?>

    <a href="logout.php" class="logout-btn">Odhlásiť sa</a>
</div>

</body>
</html>