<?php
require_once __DIR__ . '/db.php';

function getDefaultProducts() {
    return [
        [
            'id' => 1,
            'nom' => 'Nike Air Max',
            'prix' => 45000,
            'categorie' => 'Chaussures',
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1170&auto=format&fit=crop',
            'description' => 'Chaussure premium sport',
            'stock' => 12
        ],
        [
            'id' => 2,
            'nom' => 'Maillot Senegal',
            'prix' => 25000,
            'categorie' => 'Maillot',
            'image' => 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?q=80&w=1170&auto=format&fit=crop',
            'description' => 'Maillot officiel Senegal',
            'stock' => 8
        ],
        [
            'id' => 3,
            'nom' => 'Ballon Pro',
            'prix' => 18000,
            'categorie' => 'Ballon',
            'image' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=1170&auto=format&fit=crop',
            'description' => 'Ballon professionnel',
            'stock' => 5
        ],
        [
            'id' => 4,
            'nom' => 'Sac Sport Elite',
            'prix' => 32000,
            'categorie' => 'Accessoires',
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1170&auto=format&fit=crop',
            'description' => 'Sac premium sportif',
            'stock' => 10
        ],
        [
            'id' => 5,
            'nom' => 'Survetement Training',
            'prix' => 38000,
            'categorie' => 'Survetements',
            'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1170&auto=format&fit=crop',
            'description' => 'Survetement confortable pour entrainement',
            'stock' => 7
        ]
    ];
}

function hasDatabaseConnection() {
    global $conn;
    return isset($conn) && $conn instanceof mysqli && !$conn->connect_error;
}

function tableExists($table) {
    global $conn;

    if (!hasDatabaseConnection()) {
        return false;
    }

    $safeTable = $conn->real_escape_string($table);
    $result = $conn->query("SHOW TABLES LIKE '$safeTable'");

    return $result && $result->num_rows > 0;
}

function columnExists($table, $column) {
    global $conn;

    if (!hasDatabaseConnection()) {
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        return false;
    }

    $safeColumn = $conn->real_escape_string($column);
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$safeColumn'");

    return $result && $result->num_rows > 0;
}

function ensureProductColumns() {
    global $conn;

    if (!hasDatabaseConnection() || !tableExists('produits')) {
        return false;
    }

    if (!columnExists('produits', 'image')) {
        if (!$conn->query('ALTER TABLE produits ADD image TEXT NULL AFTER id_categorie')) {
            error_log($conn->error);
            return false;
        }
    }

    if (!columnExists('produits', 'description')) {
        if (!$conn->query('ALTER TABLE produits ADD description TEXT NULL AFTER image')) {
            error_log($conn->error);
            return false;
        }
    }

    if (columnExists('produits', 'descripion')) {
        $conn->query("UPDATE produits SET description = descripion WHERE (description IS NULL OR description = '')");
    }

    return true;
}

function initializeDatabaseSchema() {
    global $conn;
    static $ready = false;
    static $initializing = false;

    if ($ready) {
        return true;
    }

    if ($initializing) {
        return hasDatabaseConnection();
    }

    if (!hasDatabaseConnection()) {
        return false;
    }

    $initializing = true;

    $queries = [
        "CREATE TABLE IF NOT EXISTS categorie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        "CREATE TABLE IF NOT EXISTS produits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            prix DECIMAL(10,2) NOT NULL DEFAULT 0,
            id_categorie INT NULL,
            image TEXT NULL,
            description TEXT NULL,
            stock INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_produits_categorie
                FOREIGN KEY (id_categorie) REFERENCES categorie(id)
                ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    ];

    foreach ($queries as $query) {
        if (!$conn->query($query)) {
            error_log($conn->error);
            $initializing = false;
            return false;
        }
    }

    if (!ensureProductColumns()) {
        $initializing = false;
        return false;
    }

    $categories = ['Chaussures', 'Maillot', 'Ballon', 'Accessoires', 'Survetements'];
    $stmt = $conn->prepare('INSERT IGNORE INTO categorie (nom) VALUES (?)');
    if (!$stmt) {
        $initializing = false;
        return false;
    }

    foreach ($categories as $category) {
        $stmt->bind_param('s', $category);
        $stmt->execute();
    }

    $count = $conn->query('SELECT COUNT(*) AS total FROM produits');
    $total = $count ? (int) $count->fetch_assoc()['total'] : 0;

    if ($total === 0) {
        foreach (getDefaultProducts() as $product) {
            unset($product['id']);
            saveProduit($product);
        }
    }

    $initializing = false;
    $ready = true;

    return true;
}

function normalizeProductRow($row) {
    $fallbackImage = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1170&auto=format&fit=crop';

    return [
        'id' => (int) $row['id'],
        'nom' => $row['nom'],
        'name' => $row['nom'],
        'prix' => (float) $row['prix'],
        'price' => (float) $row['prix'],
        'categorie' => $row['categorie'] ?? 'Sans categorie',
        'category' => $row['categorie'] ?? 'Sans categorie',
        'image' => $row['image'] ?: $fallbackImage,
        'description' => $row['description'] ?? '',
        'stock' => (int) ($row['stock'] ?? 0)
    ];
}

function getProduits() {
    global $conn;

    if (!initializeDatabaseSchema()) {
        return array_map('normalizeProductRow', getDefaultProducts());
    }

    $sql = "SELECT p.id, p.nom, p.prix, p.image, p.description, p.stock, c.nom AS categorie
            FROM produits p
            LEFT JOIN categorie c ON p.id_categorie = c.id
            ORDER BY p.id DESC";
    $result = $conn->query($sql);

    if (!$result) {
        error_log($conn->error);
        return array_map('normalizeProductRow', getDefaultProducts());
    }

    $produits = [];
    while ($row = $result->fetch_assoc()) {
        $produits[] = normalizeProductRow($row);
    }

    return $produits;
}

function getProduitsByCategory($categorie) {
    return array_values(array_filter(getProduits(), function ($product) use ($categorie) {
        return $product['categorie'] === $categorie;
    }));
}

function getCategorieIdByName($category) {
    global $conn;

    if (!initializeDatabaseSchema()) {
        return null;
    }

    $stmt = $conn->prepare('INSERT IGNORE INTO categorie (nom) VALUES (?)');
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $category);
    $stmt->execute();

    $stmt = $conn->prepare('SELECT id FROM categorie WHERE nom = ? LIMIT 1');
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;

    return $row ? (int) $row['id'] : null;
}

function saveProduit($data) {
    global $conn;

    if (!initializeDatabaseSchema()) {
        return ['success' => false, 'message' => 'Base de donnees indisponible.'];
    }

    $id = isset($data['id']) && $data['id'] !== '' ? (int) $data['id'] : null;
    $nom = trim($data['nom'] ?? $data['name'] ?? '');
    $prix = (float) ($data['prix'] ?? $data['price'] ?? 0);
    $categorie = trim($data['categorie'] ?? $data['category'] ?? '');
    $stock = (int) ($data['stock'] ?? 0);
    $image = trim($data['image'] ?? '');
    $description = trim($data['description'] ?? '');

    if ($nom === '' || $prix < 0 || $categorie === '' || $stock < 0 || $image === '') {
        return ['success' => false, 'message' => 'Veuillez remplir correctement tous les champs obligatoires.'];
    }

    $categoryId = getCategorieIdByName($categorie);
    if (!$categoryId) {
        return ['success' => false, 'message' => 'Categorie invalide.'];
    }

    if ($id) {
        $stmt = $conn->prepare('UPDATE produits SET nom = ?, prix = ?, id_categorie = ?, image = ?, description = ?, stock = ? WHERE id = ?');
        if (!$stmt) {
            return ['success' => false, 'message' => $conn->error];
        }
        $stmt->bind_param('sdissii', $nom, $prix, $categoryId, $image, $description, $stock, $id);
        $success = $stmt->execute();

        return [
            'success' => $success,
            'message' => $success ? 'Produit mis a jour.' : $stmt->error,
            'id' => $id
        ];
    }

    $stmt = $conn->prepare('INSERT INTO produits (nom, prix, id_categorie, image, description, stock) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$stmt) {
        return ['success' => false, 'message' => $conn->error];
    }
    $stmt->bind_param('sdissi', $nom, $prix, $categoryId, $image, $description, $stock);
    $success = $stmt->execute();

    return [
        'success' => $success,
        'message' => $success ? 'Produit ajoute.' : $stmt->error,
        'id' => $success ? $conn->insert_id : null
    ];
}

function deleteProduit($id) {
    global $conn;

    if (!initializeDatabaseSchema()) {
        return ['success' => false, 'message' => 'Base de donnees indisponible.'];
    }

    $id = (int) $id;
    if ($id <= 0) {
        return ['success' => false, 'message' => 'Produit invalide.'];
    }

    $stmt = $conn->prepare('DELETE FROM produits WHERE id = ?');
    if (!$stmt) {
        return ['success' => false, 'message' => $conn->error];
    }
    $stmt->bind_param('i', $id);
    $success = $stmt->execute();

    return ['success' => $success, 'message' => $success ? 'Produit supprime.' : $stmt->error];
}
?>
