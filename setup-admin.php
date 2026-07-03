<?php
require_once __DIR__ . '/functions.php';

if (!hasDatabaseConnection()) {
    die('Erreur: impossible de se connecter a la base de donnees.');
}

$createUserTable = "CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($createUserTable)) {
    die('Erreur creation table user: ' . htmlspecialchars($conn->error));
}

echo "Table user prete.<br>";

$email = 'contact@mondialsport.com';
$nom = 'Admin';
$prenom = 'Mondial';
$password = 'passe123';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('SELECT id FROM user WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();

if ($existing) {
    $stmt = $conn->prepare('UPDATE user SET nom = ?, prenom = ?, password = ? WHERE email = ?');
    $stmt->bind_param('ssss', $nom, $prenom, $passwordHash, $email);
    $stmt->execute();
    echo "Admin existant mis a jour.<br>";
} else {
    $stmt = $conn->prepare('INSERT INTO user (nom, prenom, email, password) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $nom, $prenom, $email, $passwordHash);
    $stmt->execute();
    echo "Admin cree.<br>";
}

if (initializeDatabaseSchema()) {
    echo "Tables categories et produits pretes.<br>";
}

echo "<br><strong>Identifiants admin:</strong><br>";
echo "Email: " . htmlspecialchars($email) . "<br>";
echo "Mot de passe: " . htmlspecialchars($password) . "<br>";
echo "<br><a href='admis.php'>Aller a la page de connexion</a>";
?>
