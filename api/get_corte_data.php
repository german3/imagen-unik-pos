<?php
// api/get_corte_data.php
// Returns sales summary for a date range to power the Corte de Caja screen.
require_once 'db.php';

header('Content-Type: application/json');

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-d');
$fecha_fin    = isset($_GET['fecha_fin'])    ? $_GET['fecha_fin']    : date('Y-m-d');

$dt_inicio = $fecha_inicio . ' 00:00:00';
$dt_fin    = $fecha_fin    . ' 23:59:59';

try {
    // ── Summary totals ────────────────────────────────────────────────────
    $stmtTot = $pdo->prepare("
        SELECT
            COUNT(*)                   AS num_ventas,
            COALESCE(SUM(subtotal),0)  AS subtotal_ventas,
            COALESCE(SUM(descuento_total),0) AS descuentos_ventas,
            COALESCE(SUM(iva),0)       AS iva_ventas,
            COALESCE(SUM(total),0)     AS total_ventas
        FROM ventas
        WHERE fecha_hora BETWEEN ? AND ?
    ");
    $stmtTot->execute([$dt_inicio, $dt_fin]);
    $totales = $stmtTot->fetch(PDO::FETCH_ASSOC);

    // ── Individual sales list ─────────────────────────────────────────────
    $stmtVentas = $pdo->prepare("
        SELECT
            v.id,
            v.fecha_hora,
            v.total,
            TRIM(CONCAT(COALESCE(cl.nombre,'Público'), ' ', COALESCE(cl.apellidos,'General'))) AS cliente
        FROM ventas v
        LEFT JOIN clientes cl ON v.cliente_id = cl.id
        WHERE v.fecha_hora BETWEEN ? AND ?
        ORDER BY v.fecha_hora ASC
    ");
    $stmtVentas->execute([$dt_inicio, $dt_fin]);
    $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'totales' => $totales,
        'ventas'  => $ventas,
    ]);

} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
