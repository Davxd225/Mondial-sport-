<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacs Sport | Mondial Sport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header scroll-header">
        <nav class="navbar container">
            <a href="index.php" class="logo">MONDIAL <span>SPORT</span></a>
            <div class="nav-menu" id="nav-menu">
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">Accueil</a></li>
                    <li><a href="boutique.php" class="nav-link">Boutique</a></li>
                </ul>
            </div>
            <div class="nav-actions">
                <a href="admis.php" class="admin-btn">Admin</a>
                <a href="#" class="whatsapp-link whatsapp-btn" data-whatsapp-text="Bonjour Mondial Sport, je voudrais plus d'informations sur vos sacs sport.">
                    <i class="ri-whatsapp-line"></i>
                    WhatsApp
                </a>
                <div class="menu-toggle" id="menu-toggle"><i class="ri-menu-3-line"></i></div>
            </div>
        </nav>
    </header>

    <section class="category-hero">
        <div class="category-overlay"></div>
        <div class="category-hero-content container">
            <p class="category-subtitle">Collection Premium</p>
            <h1 class="category-title">Sacs Sport</h1>
            <p class="category-description">Decouvrez notre selection de sacs et accessoires disponibles dans nos boutiques Mondial Sport.</p>
        </div>
    </section>

    <section class="shop section">
        <div class="container">
            <div class="products-grid" id="products-container"></div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom"><p>&copy; 2026 Mondial Sport</p></div>
        </div>
    </footer>

    <script src="js/api-service.js"></script>
    <script src="js/products.js"></script>
    <script src="js/whatsapp.js"></script>
    <script src="js/category.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
