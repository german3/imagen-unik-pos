<?php
// api/save_corte.php
// Persists a completed Corte de Caja (header + expense rows) to the database.
require_once 'db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Payload inválido.']);
    exit;
}

$fecha_inicio      = $input['fecha_inicio']      ?? date('Y-m-d') . ' 00:00:00';
$fecha_fin         = $input['fecha_fin']         ?? date('Y-m-d') . ' 23:59:59';
$fondo_inicial     = (float)($input['fondo_inicial']     ?? 0);
$num_ventas        = (int)  ($input['num_ventas']        ?? 0);
$subtotal_ventas   = (float)($input['subtotal_ventas']   ?? 0);
$descuentos_ventas = (float)($input['descuentos_ventas'] ?? 0);
$iva_ventas        = (float)($input['iva_ventas']        ?? 0);
$total_ventas      = (float)($input['total_ventas']      ?? 0);
$total_ingresos    = (float)($input['total_ingresos']    ?? 0);
$total_gastos      = (float)($input['total_gastos']      ?? 0);
$efectivo_esperado = (float)($input['efectivo_esperado'] ?? 0);
$efectivo_contado  = (float)($input['efectivo_contado']  ?? 0);
$diferencia        = (float)($input['diferencia']        ?? 0);
$notas             = trim($input['notas']                ?? '');
$gastos            = $input['gastos']                    ?? []; // This now includes 'tipo'

try {
    $pdo->beginTransaction();

    // ── Insert corte header ───────────────────────────────────────────────
    $stmt = $pdo->prepare("
        INSERT INTO cortes_caja
            (fecha_inicio, fecha_fin, fondo_inicial, num_ventas,
             subtotal_ventas, descuentos_ventas, iva_ventas, total_ventas,
             total_ingresos, total_gastos, efectivo_esperado, efectivo_contado, diferencia, notas)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $fecha_inicio, $fecha_fin, $fondo_inicial, $num_ventas,
        $subtotal_ventas, $descuentos_ventas, $iva_ventas, $total_ventas,
        $total_ingresos, $total_gastos, $efectivo_esperado, $efectivo_contado, $diferencia, $notas
    ]);
    $corte_id = $pdo->lastInsertId();

    // ── Insert expense/income rows ───────────────────────────────────────────────
    if (!empty($gastos)) {
        $stmtG = $pdo->prepare("
            INSERT INTO gastos_caja (corte_id, descripcion, monto, tipo)
            VALUES (?, ?, ?, ?)
        ");
        foreach ($gastos as $g) {
            $desc  = trim($g['descripcion'] ?? '');
            $monto = (float)($g['monto'] ?? 0);
            $tipo  = in_array($g['tipo'] ?? '', ['ingreso', 'retiro']) ? $g['tipo'] : 'retiro';
            if ($desc && $monto > 0) {
                $stmtG->execute([$corte_id, $desc, $monto, $tipo]);
            }
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'corte_id' => $corte_id]);

} catch (\PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
