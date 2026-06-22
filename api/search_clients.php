<?php
// api/search_clients.php
require_once 'db.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Buscar por nombre, apellidos, rfc o telefono
    $stmt = $pdo->prepare("
        SELECT id, nombre, apellidos, rfc, telefono 
        FROM clientes 
        WHERE nombre LIKE ? OR apellidos LIKE ? OR rfc LIKE ? OR telefono LIKE ? 
        LIMIT 10
    ");
    $searchTerm = "%" . $query . "%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    
    $results = $stmt->fetchAll();
    echo json_encode($results);
} catch (\PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
