<?php
// api/get_next_sku.php
require_once 'db.php';
header('Content-Type: application/json');

try {
    // Obtener el máximo id de la tabla productos
    $stmt = $pdo->query("SELECT MAX(id) as max_id FROM productos");
    $row = $stmt->fetch();
    $next_id = ($row['max_id'] ?? 0) + 1;
    
    // Formatear el SKU como PROD-00001, PROD-00002, etc. (relleno con 5 ceros)
    $next_sku = 'PROD-' . str_pad($next_id, 5, '0', STR_PAD_LEFT);
    
    // Asegurarse de que sea realmente único en la BD (por si acaso hay huecos o inserciones manuales)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE sku = ?");
    $stmt->execute([$next_sku]);
    $exists = $stmt->fetchColumn() > 0;
    
    while ($exists) {
        $next_id++;
        $next_sku = 'PROD-' . str_pad($next_id, 5, '0', STR_PAD_LEFT);
        $stmt->execute([$next_sku]);
        $exists = $stmt->fetchColumn() > 0;
    }

    echo json_encode(['success' => true, 'sku' => $next_sku]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
