<?php
// api/update_client.php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }
    try {
        $stmt = $pdo->prepare("UPDATE clientes SET nombre=?, apellidos=?, telefono=?, correo_electronico=?, rfc=?, curp=?, razon_social=?, calle=?, numero_casa=?, colonia=?, codigo_postal=? WHERE id=?");
        $stmt->execute([
            $data['nombre'], $data['apellidos'], $data['telefono'] ?? '',
            $data['correo_electronico'] ?? '', $data['rfc'] ?? '', $data['curp'] ?? '',
            $data['razon_social'] ?? '', $data['calle'] ?? '', $data['numero_casa'] ?? '',
            $data['colonia'] ?? '', $data['codigo_postal'] ?? '', $data['id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Cliente actualizado correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
