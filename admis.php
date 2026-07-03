<?php
// 1. Activer l'affichage des erreurs pour le débogage (À supprimer en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Inclusion des fonctions
require_once __DIR__ . '/functions.php';

$is_authenticated = isset($_SESSION['user_id']);
$error_message = '';

// 4. Gestion de la déconnexion
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: admis.php');
    exit();
}

// 5. Gestion de la connexion POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_authenticated) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error_message = 'Veuillez remplir tous les champs.';
    } elseif (!hasDatabaseConnection()) {
        $error_message = 'Impossible de se connecter à la base de données.';
    } else {
        // Rendre la variable de connexion globale accessible si elle vient de functions.php
        global $conn; 

        if (!isset($conn) || !$conn) {
            $error_message = 'Erreur technique : La connexion à la base de données ($conn) n\'est pas initialisée.';
        } else {
            $stmt = $conn->prepare('SELECT id, nom, prenom, email, password FROM user WHERE email = ? LIMIT 1');
            $user = null;

            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result ? $result->fetch_assoc() : null;
                $stmt->close(); // Bonne pratique : fermer le statement
            }

            $passwordIsValid = false;
            if ($user) {
                $storedPassword = $user['password'];
                // Vérification multi-formats (Bcrypt, Texte Brut, MD5)
                $passwordIsValid =
                    password_verify($password, $storedPassword) ||
                    hash_equals($storedPassword, $password) ||
                    hash_equals($storedPassword, md5($password));
            }

            if ($user && $passwordIsValid) {
                // Si le mot de passe était en MD5 ou Texte brut, on le met à jour vers Bcrypt
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $update = $conn->prepare('UPDATE user SET password = ? WHERE id = ?');
                    if ($update) {
                        $update->bind_param('si', $newHash, $user['id']);
                        $update->execute();
                        $update->close();
                    }
                }

                // Initialisation de la session
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_email'] = $user['email'];

                header('Location: admis.php');
                exit();
            }

            // Message générique si l'email n'existe pas ou si le mot de passe est faux
            $error_message = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Mondial Sport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-body">
    <?php if (!$is_authenticated): ?>
    <section class="admin-login" id="admin-login">
        <div class="login-card">
            <div class="login-header">
                <h1>MONDIAL SPORT</h1>
                <p>Connexion Administrateur</p>
            </div>

            <?php if ($error_message !== ''): ?>
                <div class="alert alert-danger" style="color:#ff3b30;background:#ffe5e5;padding:10px;border-radius:5px;margin-bottom:15px;text-align:center;font-size:14px;">
                    <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form id="login-form" action="admis.php" method="POST">
                <div class="form-group">
                    <label for="admin-email">Email</label>
                    <input type="email" id="admin-email" name="email" placeholder="contact@mondialsport.com" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="form-group">
                    <label for="admin-password">Mot de passe</label>
                    <div class="password-box">
                        <input type="password" id="admin-password" name="password" placeholder="********" required>
                        <button type="button" id="toggle-password" aria-label="Afficher le mot de passe">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-btn">Connexion</button>
            </form>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($is_authenticated): ?>
    <section class="dashboard" id="dashboard">
        <aside class="sidebar">
            <div>
                <div class="sidebar-logo"><a href="index.php">MONDIAL <span>SPORT</span></a></div>
                <ul class="sidebar-menu">
                    <li class="menu-item active" data-target="page-dashboard"><i class="ri-dashboard-line"></i> Dashboard</li>
                    <li class="menu-item" data-target="page-produits"><i class="ri-shopping-bag-line"></i> Produits</li>
                    <li class="menu-item" data-target="page-boutiques"><i class="ri-store-2-line"></i> Boutiques</li>
                    <li class="menu-item" data-target="page-commandes"><i class="ri-file-list-3-line"></i> Commandes</li>
                    <li class="menu-item" data-target="page-parametres"><i class="ri-settings-3-line"></i> Parametres</li>
                </ul>
            </div>
            <a href="admis.php?logout=1" class="logout-btn" style="text-align:center; text-decoration:none; display:block;" id="logout-btn">Déconnexion</a>
        </aside>

        <main class="dashboard-main">
            <div id="page-dashboard" class="admin-page">
                <div class="dashboard-topbar">
                    <div>
                        <h1>Dashboard</h1>
                        <p>Bienvenue dans l'espace administrateur.</p>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="ri-shopping-bag-line"></i></div>
                        <div><h3 id="total-products">0</h3><p>Produits</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="ri-alert-line"></i></div>
                        <div><h3 id="out-stock">0</h3><p>Rupture</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="ri-file-list-line"></i></div>
                        <div><h3 id="total-orders">0</h3><p>Commandes</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="ri-price-tag-3-line"></i></div>
                        <div><h3 id="total-categories">0</h3><p>Categories</p></div>
                    </div>
                </div>

                <div class="admin-section">
                    <div class="section-title-admin">
                        <h2>Produits recents</h2>
                    </div>
                    <div class="admin-products-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Categorie</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody id="recent-products"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="page-produits" class="admin-page hidden">
                <div class="admin-section">
                    <div class="section-title-admin">
                        <h2>Gestion des produits</h2>
                    </div>
                    <form id="product-form" class="product-form">
                        <input type="hidden" id="product-id">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="product-name">Nom du produit</label>
                                <input type="text" id="product-name" required>
                            </div>
                            <div class="form-group">
                                <label for="product-price">Prix</label>
                                <input type="number" id="product-price" min="0" step="1" required>
                            </div>
                            <div class="form-group">
                                <label for="product-category">Categorie</label>
                                <select id="product-category" required>
                                    <option value="Chaussures">Chaussures</option>
                                    <option value="Maillot">Maillot</option>
                                    <option value="Ballon">Ballon</option>
                                    <option value="Accessoires">Accessoires</option>
                                    <option value="Survetements">Survetements</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product-stock">Stock</label>
                                <input type="number" id="product-stock" min="0" step="1" required>
                            </div>
                            <div class="form-group">
                                <label for="product-image">Image URL</label>
                                <input type="url" id="product-image" placeholder="https://..." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product-description">Description</label>
                            <textarea id="product-description"></textarea>
                        </div>
                        <button type="submit" class="save-product-btn">Enregistrer le produit</button>
                    </form>
                </div>

                <div class="admin-section">
                    <div class="section-title-admin">
                        <h2>Liste des produits</h2>
                    </div>
                    <div class="admin-products-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Categorie</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="page-boutiques" class="admin-page hidden">
                <div class="admin-section">
                    <div class="section-title-admin"><h2>Gestion des boutiques</h2></div>
                    <p>Espace de configuration de vos points de vente physiques.</p>
                </div>
            </div>

            <div id="page-commandes" class="admin-page hidden">
                <div class="admin-section">
                    <div class="section-title-admin"><h2>Suivi des commandes</h2></div>
                    <p>Historique des demandes envoyees vers WhatsApp.</p>
                </div>
            </div>

            <div id="page-parametres" class="admin-page hidden">
                <div class="admin-section">
                    <div class="section-title-admin"><h2>Parametres generaux</h2></div>
                    <p>Gestion de la configuration du site.</p>
                </div>
            </div>
        </main>
    </section>
    <?php endif; ?>

    <script src="js/api-service.js"></script>
    <script src="js/admis.js"></script>
</body>
</html>