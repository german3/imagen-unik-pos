<?php
// api/get_next_sale_id.php
require_once 'db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT MAX(id) as max_id FROM ventas");
    $row = $stmt->fetch();
    $next_id = ($row['max_id'] ?? 0) + 1;
    echo json_encode(['success' => true, 'next_id' => $next_id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
