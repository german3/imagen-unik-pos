<?php
// api/get_quote_details.php
require_once 'db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("
        SELECT cd.* 
        FROM cotizaciones_detalle cd
        WHERE cd.cotizacion_id = ?
    ");
    $stmt->execute([$id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt2 = $pdo->prepare("
        SELECT c.*, IFNULL(cl.nombre, 'Público General') as cliente_nombre 
        FROM cotizaciones c
        LEFT JOIN clientes cl ON c.cliente_id = cl.id
        WHERE c.id = ?
    ");
    $stmt2->execute([$id]);
    $master = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'master' => $master, 'detalles' => $detalles]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
