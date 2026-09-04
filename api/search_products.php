<?php
// api/search_products.php
header('Content-Type: application/json');

// Captura el error de conexión de db.php (que hace die() con JSON)
// antes de hacer require_once para poder manejarlo
ob_start();
require_once 'db.php';
$dbOutput = ob_get_clean();

// Si db.php ya imprimió algo (error de conexión), devuélvelo tal cual
if (!empty($dbOutput)) {
    // db.php emitió un error de conexión; lo devolvemos con clave 'error'
    $decoded = json_decode($dbOutput, true);
    $msg = isset($decoded['message']) ? $decoded['message'] : $dbOutput;
    echo json_encode(['error' => 'DB connection failed: ' . $msg]);
    exit;
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Buscar por descripción o sku
    $stmt = $pdo->prepare(
        "SELECT id, sku, descripcion, precio, costo, venta_por_metros, costo_m2, precio_m2
         FROM productos
         WHERE descripcion LIKE ? OR sku LIKE ?
         LIMIT 10"
    );
    $searchTerm = "%" . $query . "%";
    $stmt->execute([$searchTerm, $searchTerm]);

    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (\PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
