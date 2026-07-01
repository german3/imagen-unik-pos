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

    // Procesar archivo adjunto si existe
    $documento_path = null;
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['documento']['tmp_name'];
        $fileName = $_FILES['documento']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Limpiar el nombre del archivo para evitar caracteres extraños y generar un nombre único
        $cleanFileName = preg_replace("/[^a-zA-Z0-9_\.]/", "", $fileNameCmps[0]);
        $newFileName = time() . '_' . $cleanFileName . '.' . $fileExtension;

        // Directorio de subida
        $uploadFileDir = '../uploads/clientes/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $documento_path = 'uploads/clientes/' . $newFileName;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo adjunto.']);
            exit;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO clientes (nombre, apellidos, colonia, calle, codigo_postal, numero_casa, telefono, correo_electronico, rfc, razon_social, curp, documento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellidos, $colonia, $calle, $codigo_postal, $numero_casa, $telefono, $correo_electronico, $rfc, $razon_social, $curp, $documento_path]);
        echo json_encode(['success' => true, 'message' => 'Cliente registrado correctamente.', 'id' => $pdo->lastInsertId()]);
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el cliente: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
