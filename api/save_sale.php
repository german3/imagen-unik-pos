<?php
// api/save_sale.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No se recibieron datos de la venta.']);
        exit;
    }

    $cliente_id = $data['cliente_id'] ?? 1; // Por defecto 1 (Público General)
    $subtotal = $data['subtotal'] ?? 0;
    $descuento_total = $data['descuento_total'] ?? 0;
    $iva = $data['iva'] ?? 0;
    $total = $data['total'] ?? 0;
    $detalles = $data['detalles'] ?? [];

    if (empty($detalles)) {
        echo json_encode(['success' => false, 'message' => 'La venta no tiene productos.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Guardar la venta principal
        $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, subtotal, descuento_total, iva, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$cliente_id, $subtotal, $descuento_total, $iva, $total]);
        $venta_id = $pdo->lastInsertId();

        // 2. Guardar los detalles
        $stmt_detalle = $pdo->prepare("INSERT INTO ventas_detalle (venta_id, producto_id, nombre_producto, cantidad, costo_unitario, descuento_porcentaje, descuento_mxn, total_linea) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($detalles as $item) {
            $producto_id = isset($item['producto_id']) && $item['producto_id'] ? $item['producto_id'] : null;
            $nombre_producto = $item['producto'];
            $cantidad = $item['cantidad'];
            $costo_unitario = $item['costo_unitario'];
            $descuento_porcentaje = $item['descuento_porcentaje'] ?? 0;
            $descuento_mxn = $item['descuento_mxn'] ?? 0;
            $total_linea = $item['total_linea'];

            $stmt_detalle->execute([
                $venta_id, 
                $producto_id, 
                $nombre_producto, 
                $cantidad, 
                $costo_unitario, 
                $descuento_porcentaje, 
                $descuento_mxn, 
                $total_linea
            ]);

            // Si hay producto_id, opcionalmente descontar stock (existencia)
            if ($producto_id) {
                $stmt_stock = $pdo->prepare("UPDATE productos SET existencia = existencia - ? WHERE id = ?");
                $stmt_stock->execute([$cantidad, $producto_id]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Venta registrada exitosamente.', 'venta_id' => $venta_id]);

    } catch (\PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al guardar la venta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
