<?php
// api/cancel_sale.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de venta requerido.']);
    exit;
}

$id     = (int)$data['id'];
$motivo = trim($data['motivo'] ?? '');

if (empty($motivo)) {
    echo json_encode(['success' => false, 'message' => 'El motivo de cancelación es requerido.']);
    exit;
}

try {
    // Verify sale exists and is not already cancelled
    $check = $pdo->prepare("SELECT id, estatus FROM ventas WHERE id = ?");
    $check->execute([$id]);
    $venta = $check->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        echo json_encode(['success' => false, 'message' => 'Venta no encontrada.']);
        exit;
    }
    if ($venta['estatus'] === 'cancelada') {
        echo json_encode(['success' => false, 'message' => 'Esta venta ya fue cancelada.']);
        exit;
    }

    $pdo->beginTransaction();

    // Mark as cancelled
    $stmt = $pdo->prepare("UPDATE ventas SET estatus = 'cancelada', motivo_cancelacion = ? WHERE id = ?");
    $stmt->execute([$motivo, $id]);

    // Revert stock: restore existencia for each product in the sale
    $items = $pdo->prepare("SELECT producto_id, cantidad FROM ventas_detalle WHERE venta_id = ? AND producto_id IS NOT NULL");
    $items->execute([$id]);
    $detalles = $items->fetchAll(PDO::FETCH_ASSOC);

    foreach ($detalles as $item) {
        $restore = $pdo->prepare("UPDATE productos SET existencia = existencia + ? WHERE id = ?");
        $restore->execute([$item['cantidad'], $item['producto_id']]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Venta cancelada correctamente.']);

} catch (\PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al cancelar la venta: ' . $e->getMessage()]);
}
?>
