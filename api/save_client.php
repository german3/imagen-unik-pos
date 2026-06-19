<?php
// api/save_client.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        $data = $_POST;
    }

    $nombre = $data['nombre'] ?? '';
    $apellidos = $data['apellidos'] ?? '';
    $colonia = $data['colonia'] ?? '';
    $calle = $data['calle'] ?? '';
    $codigo_postal = $data['codigo_postal'] ?? '';
    $numero_casa = $data['numero_casa'] ?? '';
    $telefono = $data['telefono'] ?? '';
    $correo_electronico = $data['correo_electronico'] ?? '';
    $rfc = $data['rfc'] ?? '';
    $razon_social = $data['razon_social'] ?? '';
    $curp = $data['curp'] ?? '';

    if (empty($nombre) || empty($apellidos)) {
        echo json_encode(['success' => false, 'message' => 'Nombre y Apellidos son obligatorios.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO clientes (nombre, apellidos, colonia, calle, codigo_postal, numero_casa, telefono, correo_electronico, rfc, razon_social, curp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellidos, $colonia, $calle, $codigo_postal, $numero_casa, $telefono, $correo_electronico, $rfc, $razon_social, $curp]);
        echo json_encode(['success' => true, 'message' => 'Cliente registrado correctamente.', 'id' => $pdo->lastInsertId()]);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el cliente: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
