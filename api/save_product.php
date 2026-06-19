<?php
// api/save_product.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer datos JSON o Form Data
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        $data = $_POST;
    }

    $sku = $data['sku'] ?? '';
    $descripcion = $data['descripcion'] ?? '';
    $precio = isset($data['precio']) && is_numeric($data['precio']) ? (float)$data['precio'] : 0;
    $costo = isset($data['costo']) && is_numeric($data['costo']) ? (float)$data['costo'] : 0;
    $proveedor = $data['proveedor'] ?? '';
    $existencia = isset($data['existencia']) && is_numeric($data['existencia']) ? (float)$data['existencia'] : 0;
    $categoria = $data['categoria'] ?? '';
    $utilidad = isset($data['utilidad']) && is_numeric($data['utilidad']) ? (float)$data['utilidad'] : 0;
    $codigo_barras = $data['codigo_barras'] ?? '';

    if (empty($sku) || empty($descripcion)) {
        echo json_encode(['success' => false, 'message' => 'SKU y Descripción son obligatorios.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO productos (sku, descripcion, precio, costo, proveedor, existencia, categoria, utilidad, codigo_barras) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$sku, $descripcion, $precio, $costo, $proveedor, $existencia, $categoria, $utilidad, $codigo_barras]);
        echo json_encode(['success' => true, 'message' => 'Producto registrado correctamente.', 'id' => $pdo->lastInsertId()]);
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'El SKU ya existe.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el producto: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
