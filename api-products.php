<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

header('Content-Type: application/json; charset=utf-8');

function jsonResponse($payload, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit();
}

function requireAdmin() {
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(['success' => false, 'message' => 'Connexion admin requise.'], 401);
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'list';

if ($method === 'GET') {
    $products = getProduits();
    $category = trim($_GET['category'] ?? '');

    if ($category !== '') {
        $products = array_values(array_filter($products, function ($product) use ($category) {
            return $product['categorie'] === $category;
        }));
    }

    $categories = array_values(array_unique(array_map(function ($product) {
        return $product['categorie'];
    }, $products)));

    jsonResponse([
        'success' => true,
        'products' => $products,
        'stats' => [
            'totalProducts' => count($products),
            'outStock' => count(array_filter($products, function ($product) {
                return (int) $product['stock'] <= 0;
            })),
            'totalCategories' => count($categories)
        ]
    ]);
}

requireAdmin();

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    $payload = $_POST;
}

if ($method === 'POST' && in_array($action, ['save', 'create', 'update'], true)) {
    $result = saveProduit($payload);
    jsonResponse($result, $result['success'] ? 200 : 422);
}

if ($method === 'POST' && $action === 'delete') {
    $result = deleteProduit($payload['id'] ?? 0);
    jsonResponse($result, $result['success'] ? 200 : 422);
}

jsonResponse(['success' => false, 'message' => 'Action non supportee.'], 400);
?>
