<?php
// api/get_next_sale_id.php
// Devuelve el próximo folio global (compartido entre ventas y cotizaciones)
require_once 'db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_folio FROM folio_global");
    $row  = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'next_id' => (int)$row['next_folio']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
