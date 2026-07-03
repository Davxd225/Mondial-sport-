<?php
require_once __DIR__ . '/functions.php';

if (!hasDatabaseConnection()) {
    die('Erreur: impossible de se connecter a la base de donnees.');
}

$email = 'contact@mondialsport.com';
$password = 'passe123';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('UPDATE user SET password = ? WHERE email = ?');
if (!$stmt) {
    die('Erreur preparation requete: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('ss', $passwordHash, $email);

if ($stmt->execute()) {
    echo "Mot de passe mis a jour pour: " . htmlspecialchars($email) . "<br>";
    echo "Identifiants: " . htmlspecialchars($email) . " / " . htmlspecialchars($password) . "<br>";
    echo "<br><a href='admis.php'>Acceder a la connexion admin</a>";
} else {
    die('Erreur lors de la mise a jour: ' . htmlspecialchars($stmt->error));
}
?>
