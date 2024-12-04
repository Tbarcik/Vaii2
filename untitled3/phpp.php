<?php
// Zahrnúť pripojenie k databáze
include 'db_config.php'; // Tento súbor obsahuje pripojenie k databáze

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získanie údajov z formulára
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Overenie, či používateľ existuje v databáze
    $stmt = $pdo->prepare("SELECT * FROM usersss WHERE email = ?");
    $stmt->execute([$email]);

    // Skontrolovať, či je používateľ nájdený
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Overenie, či sa heslo zhoduje s tým, čo je uložené v databáze
        if (password_verify($password, $user['password'])) {
            // Prihlásenie úspešné - nastavíme session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];

            // Ak je používateľ administrátor, presmeruj ho na administrátorské rozhranie
            if ($_SESSION['is_admin'] == 1) {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: loginUser.html"); // Ak je to bežný používateľ, presmerujeme na HTML stránku
            }
        } else {
            die("Chybné heslo.");
        }
    } else {
        die("Používateľ s týmto e-mailom neexistuje.");
    }
}
?>
