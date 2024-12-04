<?php
include 'db_config.php'; // Pripojenie k databáze

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získanie údajov z formulára
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);

    // Overenie, či sa heslá zhodujú
    if ($password !== $confirmPassword) {
        die("Heslá sa nezhodujú.");
    }

    // Hashovanie hesla pred uložením do databázy
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Overenie, či používateľ s rovnakým e-mailom už neexistuje
    $stmt = $pdo->prepare("SELECT * FROM usersss WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        die("Tento e-mail už je zaregistrovaný.");
    }

    // Vloženie nového používateľa do databázy
    $stmt = $pdo->prepare("INSERT INTO usersss (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
    header("Location: loginUser.html"); // Ak je to bežný používateľ, presmerujeme na HTML stránku

    echo "Registrácia bola úspešná!";
}
?>
