<?php
// api/get_products.php
require_once 'db.php';
header('Content-Type: application/json');

try {
    $search = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '%';
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE descripcion LIKE ? OR sku LIKE ? OR categoria LIKE ? ORDER BY descripcion ASC");
    $stmt->execute([$search, $search, $search]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
