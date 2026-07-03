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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>Mondial Sport Sénégal</title>

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- REMIX ICON -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/page-1.png">
</head>
<body>

    <!-- ========================= -->
    <!-- LOADER -->
    <!-- ========================= -->

    <div class="loader">
        <div class="loader-content">
            <span>Mondial Sport</span>
        </div>
    </div>

    <!-- ========================= -->
    <!-- HEADER -->
    <!-- ========================= -->

    <header class="header" id="header">

        <nav class="navbar container">

            <!-- LOGO -->
            <a href="#" class="logo">
                MONDIAL <span>SPORT</span>
            </a>

            <!-- MENU -->
            <div class="nav-menu" id="nav-menu">

                <ul class="nav-list">

                    <li>
                        <a href="#" class="nav-link active">
                            Accueil
                        </a>
                    </li>

                    <li>
                        <a href="#categories" class="nav-link">
                            CatÃƒÂ©gories
                        </a>
                    </li>

                    <li>
                        <a href="nos-boutiques.php" class="nav-link">
                            Nos Boutiques
                        </a>
                    </li>


                    <li>
                        <a href="boutique.php" class="nav-link">
                            Nos produits
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
    <!-- HERO -->
    <!-- ========================= -->

    <section class="hero">

        <div class="hero-overlay"></div>

        <div class="hero-content container">

      
            <h1 class="hero-title">
                Ãƒâ€°quipe-toi Comme Un Champion
            </h1>

            <p class="hero-description">
                Chaussures, maillots, survÃƒÂªtements et accessoires sportifs premium disponibles dans nos 3 boutiques.
            </p>

            <div class="hero-buttons">

                <a href="boutique.php" class="primary-btn">
                    Voir les produits
                </a>

                <a href="#" class="whatsapp-link secondary-btn" data-whatsapp-text="Bonjour Mondial Sport Ã°Å¸â€˜â€¹ Je souhaite commander un produit.">
                    Commander sur WhatsApp
                </a>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- CATEGORIES -->
    <!-- ========================= -->

    <section class="categories section" id="categories">

        <div class="container">

            <div class="section-header">

                <p class="section-subtitle">
                    CatÃƒÂ©gories
                </p>

                <h2 class="section-title">
                    Nos Univers Sportifs
                </h2>

            </div>

            <div class="categories-grid">

                <a href="chaussures.php" class="category-card">
    <i class="ri-football-line"></i>
    <h3>Chaussures</h3>
</a>

                <a href="maillots.php" class="category-card">
                    <i class="ri-shirt-line"></i>
                    <h3>Maillots</h3>
                </a>

                <a href="ballons.php" class="category-card">
                    <i class="ri-basketball-line"></i>
                    <h3>Ballons</h3>
                </a>

                <a href="sac.php" class="category-card">
                    <i class="ri-briefcase-4-line"></i>
                    <h3>Sacs Sport</h3>
                </a>

                <a href="accessoires.php" class="category-card">
                    <i class="ri-boxing-line"></i>
                    <h3>Accessoires</h3>
                </a>

                <a href="survetements.php" class="category-card">
                    <i class="ri-run-line"></i>
                    <h3>SurvÃƒÂªtements</h3>
                </a>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- POPULAR PRODUCTS -->
    <!-- ========================= -->

    <section class="products section" id="products">

        <div class="container">

            <div class="section-header">

                <p class="section-subtitle">
                    Produits populaires
                </p>

                <h2 class="section-title">
                    Les meilleures ventes
                </h2>

            </div>
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

    <!-- FILTER -->
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
            <div class="products-grid" id="products-container">
                <?php
                    foreach ($produits as $product) {
                        echo '
                        <div class="product-card">
                            <div class="product-image">
                                <img src="'.$product['image'].'" alt="'.$product['nom'].'">
                            </div>
                            <div class="product-content">
                                <span class="product-category">
                                    '.$product['categorie'].'
                                </span>
                                <h3 class="product-title">
                                    '.$product['nom'].'
                                </h3>
                                <p class="product-price">
                                    '.number_format($product['prix'], 0, ',', ' ').' FCFA
                                </p>
                                <div class="product-stock">
                                    Stock : '.$product['stock'].'
                                </div>
                                <button class="product-btn" onclick="orderOnWhatsApp('.$product['id'].')">
                                    Commander WhatsApp
                                </button>
                            </div>
                        </div>
                        ';
                    }
                ?>
            </div>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- STORES -->
    <!-- ========================= -->

    <section class="stores section" id="stores">

        <div class="container">

            <div class="section-header">

                <p class="section-subtitle">
                    Nos Boutiques
                </p>

                <h2 class="section-title">
                    Retrouvez-nous au SÃƒÂ©nÃƒÂ©gal
                </h2>

            </div>

            <div class="stores-grid">

                <div class="store-card">

                    <h3>Boutique Dakar Centre</h3>

                    <p>Dakar Plateau</p>

                    <p>+221 77 000 00 00</p>

                    <p>08h00 - 20h00</p>

                    <a href="#" class="store-btn">
                        Voir lÃ¢â‚¬â„¢itinÃƒÂ©raire
                    </a>

                </div>

                <div class="store-card">

                    <h3>Boutique Parcelles</h3>

                    <p>Parcelles Assainies</p>

                    <p>+221 77 000 00 00</p>

                    <p>08h00 - 20h00</p>

                    <a href="#" class="store-btn">
                        Voir lÃ¢â‚¬â„¢itinÃƒÂ©raire
                    </a>

                </div>

                <div class="store-card">

                    <h3>Boutique ThiÃƒÂ¨s</h3>

                    <p>Centre Ville</p>

                    <p>+221 77 000 00 00</p>

                    <p>08h00 - 20h00</p>

                    <a href="#" class="store-btn">
                        Voir lÃ¢â‚¬â„¢itinÃƒÂ©raire
                    </a>

                </div>

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

                    <h3>Liens rapides</h3>

                    <a href="#">Accueil</a>
                    <a href="#">Produits</a>
                    <a href="#">Boutiques</a>

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

