<?php
// api/save_movimiento.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $tipo = in_array($data['tipo'] ?? '', ['ingreso', 'retiro']) ? $data['tipo'] : 'retiro';
    $descripcion = trim($data['descripcion'] ?? '');
    $monto = (float)($data['monto'] ?? 0);

    if (empty($descripcion) || $monto <= 0) {
        echo json_encode(['success' => false, 'message' => 'Descripción y monto válido son requeridos.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO gastos_caja (descripcion, monto, tipo) VALUES (?, ?, ?)");
        $stmt->execute([$descripcion, $monto, $tipo]);
        
        echo json_encode(['success' => true]);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error BD: ' . $e->getMessage()]);
    }
}
