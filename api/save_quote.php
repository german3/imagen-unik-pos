<?php
// api/save_quote.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO cotizaciones (cliente_id, subtotal, descuento_total, iva, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['cliente_id'],
            $data['subtotal'],
            $data['descuento_total'],
            $data['iva'],
            $data['total']
        ]);
        
        $cotizacionId = $pdo->lastInsertId();

        $stmtDetalle = $pdo->prepare("INSERT INTO cotizaciones_detalle (cotizacion_id, producto_id, nombre_producto, cantidad, costo_unitario, descuento_porcentaje, descuento_mxn, total_linea) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($data['detalles'] as $prod) {
            $producto_id = isset($prod['producto_id']) && $prod['producto_id'] !== '' ? $prod['producto_id'] : null;
            $stmtDetalle->execute([
                $cotizacionId,
                $producto_id,
                $prod['producto'],
                $prod['cantidad'],
                $prod['costo_unitario'],
                $prod['descuento_porcentaje'],
                $prod['descuento_mxn'],
                $prod['total_linea']
            ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'cotizacion_id' => $cotizacionId]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al guardar cotización: ' . $e->getMessage()]);
    }
}
