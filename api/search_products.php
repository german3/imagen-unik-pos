<?php
// api/search_products.php
require_once 'db.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Buscar por descripción o sku
    $stmt = $pdo->prepare("SELECT id, sku, descripcion, precio, costo FROM productos WHERE descripcion LIKE ? OR sku LIKE ? LIMIT 10");
    $searchTerm = "%" . $query . "%";
    $stmt->execute([$searchTerm, $searchTerm]);
    
    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (\PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
