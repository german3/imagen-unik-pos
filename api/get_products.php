<?php
// api/get_products.php
require_once 'db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query(
        "SELECT id, sku, codigo_barras, descripcion, categoria, proveedor,
                costo, utilidad, precio, existencia,
                venta_por_metros, costo_m2, precio_m2
         FROM productos
         ORDER BY descripcion ASC"
    );
    $products = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $products]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
