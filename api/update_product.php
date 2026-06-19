<?php
// api/update_product.php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("UPDATE productos SET sku=?, codigo_barras=?, descripcion=?, categoria=?, proveedor=?, costo=?, utilidad=?, precio=?, existencia=? WHERE id=?");
        $stmt->execute([
            $data['sku'], $data['codigo_barras'] ?? '', $data['descripcion'],
            $data['categoria'] ?? '', $data['proveedor'] ?? '',
            $data['costo'] ?? 0, $data['utilidad'] ?? 0, $data['precio'], $data['existencia'] ?? 0,
            $data['id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
