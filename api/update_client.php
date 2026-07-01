<?php
// api/update_client.php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        $data = $_POST;
    }

    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $id = (int)$data['id'];
    $nombre = $data['nombre'] ?? '';
    $apellidos = $data['apellidos'] ?? '';
    $telefono = $data['telefono'] ?? '';
    $correo_electronico = $data['correo_electronico'] ?? '';
    $rfc = $data['rfc'] ?? '';
    $curp = $data['curp'] ?? '';
    $razon_social = $data['razon_social'] ?? '';
    $calle = $data['calle'] ?? '';
    $numero_casa = $data['numero_casa'] ?? '';
    $colonia = $data['colonia'] ?? '';
    $codigo_postal = $data['codigo_postal'] ?? '';

    if (empty($nombre) || empty($apellidos)) {
        echo json_encode(['success' => false, 'message' => 'Nombre y Apellidos son obligatorios.']);
        exit;
    }

    // Procesar archivo adjunto si existe
    $documento_path = null;
    $has_new_file = false;
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
            $has_new_file = true;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el nuevo archivo adjunto.']);
            exit;
        }
    }

    try {
        if ($has_new_file) {
            // Eliminar archivo anterior si existía uno nuevo
            $stmt_old = $pdo->prepare("SELECT documento FROM clientes WHERE id = ?");
            $stmt_old->execute([$id]);
            $old_doc = $stmt_old->fetchColumn();
            if ($old_doc && file_exists('../' . $old_doc)) {
                @unlink('../' . $old_doc);
            }

            $stmt = $pdo->prepare("UPDATE clientes SET nombre=?, apellidos=?, telefono=?, correo_electronico=?, rfc=?, curp=?, razon_social=?, calle=?, numero_casa=?, colonia=?, codigo_postal=?, documento=? WHERE id=?");
            $stmt->execute([
                $nombre, $apellidos, $telefono, $correo_electronico, $rfc, $curp,
                $razon_social, $calle, $numero_casa, $colonia, $codigo_postal, $documento_path, $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE clientes SET nombre=?, apellidos=?, telefono=?, correo_electronico=?, rfc=?, curp=?, razon_social=?, calle=?, numero_casa=?, colonia=?, codigo_postal=? WHERE id=?");
            $stmt->execute([
                $nombre, $apellidos, $telefono, $correo_electronico, $rfc, $curp,
                $razon_social, $calle, $numero_casa, $colonia, $codigo_postal, $id
            ]);
        }
        echo json_encode(['success' => true, 'message' => 'Cliente actualizado correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
