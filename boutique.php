<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';
$produits = getProduits();
?>
<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Boutique | Mondial Sport</title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- REMIX ICON -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- ========================= -->
    <!-- HEADER -->
    <!-- ========================= -->

    <header class="header scroll-header">

        <nav class="navbar container">

            <!-- LOGO -->
            <a href="index.php" class="logo">
                MONDIAL <span>SPORT</span>
            </a>

            <!-- MENU -->
            <div class="nav-menu" id="nav-menu">

                <ul class="nav-list">

                    <li>
                        <a href="index.php" class="nav-link">
                            Accueil
                        </a>
                    </li>

                    <li>
                        <a href="nos-boutiques.php" class="nav-link active">
                            Nos boutiques
                        </a>
                    </li>

                  

                </ul>

            </div>

            <!-- ACTIONS -->
            <div class="nav-actions">

                <a href="admis.php" class="admin-btn">
                    Admin
                </a>

                <a href="#" class="whatsapp-link whatsapp-btn" data-whatsapp-text="Bonjour Mondial Sport Ã°Å¸â€˜â€¹ Je voudrais plus d'informations sur vos produits.">
                    <i class="ri-whatsapp-line"></i>
                    WhatsApp
                </a>

                <div class="menu-toggle" id="menu-toggle">
                    <i class="ri-menu-3-line"></i>
                </div>

            </div>

        </nav>

    </header>

    <!-- ========================= -->
    <!-- SHOP HERO -->
    <!-- ========================= -->

    <section class="shop-hero">

        <div class="shop-overlay"></div>

        <div class="shop-hero-content container">

            <p class="shop-subtitle">
                Collection Sport Premium
            </p>

            <h1 class="shop-title">
                Boutique Mondial Sport
            </h1>

            <p class="shop-description">
                DÃƒÂ©couvrez les meilleurs ÃƒÂ©quipements sportifs premium disponibles dans nos boutiques au SÃƒÂ©nÃƒÂ©gal.
            </p>

        </div>

    </section>

    <!-- ========================= -->
    <!-- SHOP -->
    <!-- ========================= -->

    <section class="shop section">

        <div class="container">

            <!-- CONTROLS -->
            <div class="products-controls">

                <!-- SEARCH -->
                <div class="search-box">

                    <i class="ri-search-line"></i>

                    <input 
                        type="text"
                        id="search-input"
                        placeholder="Rechercher un produit..."
                    >

                </div>

                <!-- CATEGORY -->
                <select id="category-filter">

                    <option value="all">
                        Toutes les catÃƒÂ©gories
                    </option>

                    <option value="Chaussures">
                        Chaussures
                    </option>

                    <option value="Maillot">
                        Maillots
                    </option>

                    <option value="Ballon">
                        Ballons
                    </option>

                    <option value="Accessoires">
                        Accessoires
                    </option>

                    <option value="Survetements">
                        Survetements
                    </option>

                </select>

                <!-- SORT -->
                <select id="price-sort">

                    <option value="default">
                        Trier par prix
                    </option>

                    <option value="low">
                        Prix croissant
                    </option>

                    <option value="high">
                        Prix dÃƒÂ©croissant
                    </option>

                </select>

            </div>

            <!-- PRODUCTS -->
            <div class="products-grid" id="products-container">

            </div>

            <!-- PAGINATION -->
            <div class="pagination">

                <button class="pagination-btn active">
                    1
                </button>

                <button class="pagination-btn">
                    2
                </button>

                <button class="pagination-btn">
                    3
                </button>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- FOOTER -->
    <!-- ========================= -->

    <footer class="footer">

        <div class="container">

            <div class="footer-content">

                <div class="footer-brand">

                    <h2>
                        MONDIAL SPORT
                    </h2>

                    <p>
                        Votre rÃƒÂ©fÃƒÂ©rence sport premium au SÃƒÂ©nÃƒÂ©gal.
                    </p>

                </div>

                <div class="footer-links">

                    <h3>Navigation</h3>

                    <a href="index.php">
                        Accueil
                    </a>

                    <a href="boutique.php">
                        Boutique
                    </a>

                </div>

                <div class="footer-links">

                    <h3>Contact</h3>

                    <p>+221 77 000 00 00</p>

                    <p>contact@mondialsport.sn</p>

                </div>

            </div>

            <div class="footer-bottom">

                <p>
                    Ã‚Â© 2026 Mondial Sport Ã¢â‚¬â€ Tous droits rÃƒÂ©servÃƒÂ©s
                </p>

            </div>

        </div>

    </footer>

    <!-- JS -->
    <script src="js/api-service.js"></script>
    <script src="js/products.js"></script>
    <script src="js/whatsapp.js"></script>
    <script src="js/app.js"></script>

</body>
</html>

