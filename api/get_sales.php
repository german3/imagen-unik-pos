<?php
// api/get_sales.php
require_once 'db.php';

header('Content-Type: application/json');

$start = isset($_GET['start']) ? $_GET['start'] . ' 00:00:00' : date('Y-m-01 00:00:00');
$end = isset($_GET['end']) ? $_GET['end'] . ' 23:59:59' : date('Y-m-d 23:59:59');

try {
    $stmt = $pdo->prepare("
        SELECT v.*, TRIM(CONCAT(IFNULL(cl.nombre, 'Público'), ' ', IFNULL(cl.apellidos, 'General'))) as cliente_nombre 
        FROM ventas v
        LEFT JOIN clientes cl ON v.cliente_id = cl.id
        WHERE v.fecha_hora BETWEEN ? AND ?
        ORDER BY v.fecha_hora DESC
    ");
    $stmt->execute([$start, $end]);
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $ventas]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
