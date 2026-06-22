<?php
// api/get_sale_details.php
require_once 'db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("
        SELECT vd.* 
        FROM ventas_detalle vd
        WHERE vd.venta_id = ?
    ");
    $stmt->execute([$id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt2 = $pdo->prepare("
        SELECT v.*, TRIM(CONCAT(IFNULL(cl.nombre, 'Público'), ' ', IFNULL(cl.apellidos, 'General'))) as cliente_nombre 
        FROM ventas v
        LEFT JOIN clientes cl ON v.cliente_id = cl.id
        WHERE v.id = ?
    ");
    $stmt2->execute([$id]);
    $master = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'master' => $master, 'detalles' => $detalles]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
