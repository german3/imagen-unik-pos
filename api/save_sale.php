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

    $cliente_id         = $data['cliente_id'] ?? 1;
    $subtotal           = $data['subtotal'] ?? 0;
    $descuento_total    = $data['descuento_total'] ?? 0;
    $iva                = $data['iva'] ?? 0;
    $total              = $data['total'] ?? 0;
    $metodo_pago        = $data['metodo_pago'] ?? 'efectivo';
    $detalles           = $data['detalles'] ?? [];
    $estatus            = in_array($data['estatus'] ?? '', ['confirmada','cancelada'])
                          ? $data['estatus'] : 'confirmada';
    $motivo_cancelacion = ($estatus === 'cancelada') ? trim($data['motivo_cancelacion'] ?? '') : null;

    if (empty($detalles)) {
        echo json_encode(['success' => false, 'message' => 'La venta no tiene productos.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Guardar la venta principal
        $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, subtotal, descuento_total, iva, total, estatus, motivo_cancelacion, metodo_pago) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cliente_id, $subtotal, $descuento_total, $iva, $total, $estatus, $motivo_cancelacion, $metodo_pago]);
        $venta_id = $pdo->lastInsertId();

        // 1b. Asignar folio global único (compartido con cotizaciones)
        $folio = getNextFolio($pdo, 'venta', (int)$venta_id);
        $pdo->prepare("UPDATE ventas SET folio = ? WHERE id = ?")->execute([$folio, $venta_id]);

        // 2. Guardar los detalles
        $stmt_detalle = $pdo->prepare("INSERT INTO ventas_detalle (venta_id, producto_id, nombre_producto, cantidad, costo_unitario, descuento_porcentaje, descuento_mxn, total_linea, alto, ancho) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($detalles as $item) {
            $producto_id = isset($item['producto_id']) && $item['producto_id'] ? $item['producto_id'] : null;
            $nombre_producto = $item['producto'];
            $cantidad = $item['cantidad'];
            $costo_unitario = $item['costo_unitario'];
            $descuento_porcentaje = $item['descuento_porcentaje'] ?? 0;
            $descuento_mxn = $item['descuento_mxn'] ?? 0;
            $total_linea = $item['total_linea'];
            $alto = isset($item['alto']) && $item['alto'] !== '' && $item['alto'] !== null ? (float)$item['alto'] : null;
            $ancho = isset($item['ancho']) && $item['ancho'] !== '' && $item['ancho'] !== null ? (float)$item['ancho'] : null;

            $stmt_detalle->execute([
                $venta_id, 
                $producto_id, 
                $nombre_producto, 
                $cantidad, 
                $costo_unitario, 
                $descuento_porcentaje, 
                $descuento_mxn, 
                $total_linea,
                $alto,
                $ancho
            ]);

            // Descontar stock solo en ventas confirmadas
            if ($producto_id && $estatus === 'confirmada') {
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
