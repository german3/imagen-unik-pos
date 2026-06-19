<?php
// api/get_clients.php
require_once 'db.php';
header('Content-Type: application/json');

try {
    $search = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '%';
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nombre LIKE ? OR apellidos LIKE ? OR telefono LIKE ? ORDER BY nombre ASC");
    $stmt->execute([$search, $search, $search]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
