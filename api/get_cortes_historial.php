<?php
// api/get_cortes_historial.php
require_once 'db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT id, fecha_inicio, fecha_fin, fondo_inicial, num_ventas,
               subtotal_ventas, descuentos_ventas, iva_ventas, total_ventas,
               total_ingresos, total_gastos, efectivo_esperado, efectivo_contado,
               diferencia, notas, creado_en
        FROM cortes_caja
        ORDER BY id DESC
    ");
    $cortes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'cortes' => $cortes]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
