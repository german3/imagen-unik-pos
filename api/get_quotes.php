<?php
// api/get_quotes.php
require_once 'db.php';

header('Content-Type: application/json');

$start = isset($_GET['start']) ? $_GET['start'] . ' 00:00:00' : date('Y-m-01 00:00:00');
$end = isset($_GET['end']) ? $_GET['end'] . ' 23:59:59' : date('Y-m-d 23:59:59');

try {
    $stmt = $pdo->prepare("
        SELECT c.*, IFNULL(cl.nombre, 'Público General') as cliente_nombre 
        FROM cotizaciones c
        LEFT JOIN clientes cl ON c.cliente_id = cl.id
        WHERE c.fecha_hora BETWEEN ? AND ?
        ORDER BY c.fecha_hora DESC
    ");
    $stmt->execute([$start, $end]);
    $cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $cotizaciones]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
