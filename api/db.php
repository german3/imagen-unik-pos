<?php
// api/db.php
// Reads from environment variables (Render) or falls back to local XAMPP defaults

$host    = trim(getenv('DB_HOST') ?: '127.0.0.1');
$db      = trim(getenv('DB_NAME') ?: 'imagen_unik_pos');
$user    = trim(getenv('DB_USER') ?: 'root');
$pass    = trim(getenv('DB_PASS') ?: '');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // Auto-migrate tables for Cotizaciones
     $pdo->exec("CREATE TABLE IF NOT EXISTS cotizaciones (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cliente_id INT,
        fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
        subtotal DECIMAL(10,2) NOT NULL,
        descuento_total DECIMAL(10,2) DEFAULT 0,
        iva DECIMAL(10,2) NOT NULL,
        total DECIMAL(10,2) NOT NULL
     )");
     $pdo->exec("CREATE TABLE IF NOT EXISTS cotizaciones_detalle (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cotizacion_id INT NOT NULL,
        producto_id INT NULL,
        nombre_producto VARCHAR(255) NOT NULL,
        cantidad DECIMAL(10,2) NOT NULL,
        costo_unitario DECIMAL(10,2) NOT NULL,
        descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
        descuento_mxn DECIMAL(10,2) DEFAULT 0,
        total_linea DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (cotizacion_id) REFERENCES cotizaciones(id) ON DELETE CASCADE
     )");

     // Auto-migrate: Corte de Caja tables
     $pdo->exec("CREATE TABLE IF NOT EXISTS cortes_caja (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fecha_inicio DATETIME NOT NULL,
        fecha_fin DATETIME NOT NULL,
        fondo_inicial DECIMAL(10,2) DEFAULT 0,
        num_ventas INT DEFAULT 0,
        subtotal_ventas DECIMAL(10,2) DEFAULT 0,
        descuentos_ventas DECIMAL(10,2) DEFAULT 0,
        iva_ventas DECIMAL(10,2) DEFAULT 0,
        total_ventas DECIMAL(10,2) DEFAULT 0,
        total_gastos DECIMAL(10,2) DEFAULT 0,
        efectivo_esperado DECIMAL(10,2) DEFAULT 0,
        efectivo_contado DECIMAL(10,2) DEFAULT 0,
        diferencia DECIMAL(10,2) DEFAULT 0,
        notas TEXT,
        creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
     )");

     $pdo->exec("CREATE TABLE IF NOT EXISTS gastos_caja (
        id INT AUTO_INCREMENT PRIMARY KEY,
        corte_id INT NULL,
        descripcion VARCHAR(255) NOT NULL,
        monto DECIMAL(10,2) NOT NULL,
        fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (corte_id) REFERENCES cortes_caja(id) ON DELETE CASCADE
     )");

     // Auto-migrate: add venta_por_metros, costo_m2, precio_m2 to productos if they don't exist
     $columns = $pdo->query("DESCRIBE productos")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('venta_por_metros', $columns)) {
         $pdo->exec("ALTER TABLE productos ADD COLUMN venta_por_metros TINYINT DEFAULT 0");
     }
     if (!in_array('costo_m2', $columns)) {
         $pdo->exec("ALTER TABLE productos ADD COLUMN costo_m2 DECIMAL(10,2) DEFAULT 0.00");
     }
     if (!in_array('precio_m2', $columns)) {
         $pdo->exec("ALTER TABLE productos ADD COLUMN precio_m2 DECIMAL(10,2) DEFAULT 0.00");
     }

     // Auto-migrate: add alto, ancho to ventas_detalle if they don't exist
     $cols_vd = $pdo->query("DESCRIBE ventas_detalle")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('alto', $cols_vd)) {
         $pdo->exec("ALTER TABLE ventas_detalle ADD COLUMN alto DECIMAL(10,2) NULL");
     }
     if (!in_array('ancho', $cols_vd)) {
         $pdo->exec("ALTER TABLE ventas_detalle ADD COLUMN ancho DECIMAL(10,2) NULL");
     }

     // Auto-migrate: add alto, ancho to cotizaciones_detalle if they don't exist
     $cols_cd = $pdo->query("DESCRIBE cotizaciones_detalle")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('alto', $cols_cd)) {
         $pdo->exec("ALTER TABLE cotizaciones_detalle ADD COLUMN alto DECIMAL(10,2) NULL");
     }
     if (!in_array('ancho', $cols_cd)) {
         $pdo->exec("ALTER TABLE cotizaciones_detalle ADD COLUMN ancho DECIMAL(10,2) NULL");
     }

     // Auto-migrate: add estatus and motivo_cancelacion to ventas
     $cols_v = $pdo->query("DESCRIBE ventas")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('estatus', $cols_v)) {
         $pdo->exec("ALTER TABLE ventas ADD COLUMN estatus VARCHAR(20) NOT NULL DEFAULT 'confirmada'");
     }
     if (!in_array('motivo_cancelacion', $cols_v)) {
         $pdo->exec("ALTER TABLE ventas ADD COLUMN motivo_cancelacion TEXT NULL");
     }

     // Auto-migrate: add documento to clientes if it doesn't exist
     $cols_c = $pdo->query("DESCRIBE clientes")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('documento', $cols_c)) {
         $pdo->exec("ALTER TABLE clientes ADD COLUMN documento VARCHAR(255) NULL");
     }

     // ── Folio Global (contador compartido entre ventas y cotizaciones) ──────
     // 1. Tabla secuenciadora
     $pdo->exec("CREATE TABLE IF NOT EXISTS folio_global (
         id INT AUTO_INCREMENT PRIMARY KEY,
         tipo VARCHAR(20) NOT NULL COMMENT 'venta | cotizacion',
         referencia_id INT NOT NULL COMMENT 'id real en su tabla de origen',
         creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
     )");

     // 2. Columna folio en ventas
     $cols_v2 = $pdo->query("DESCRIBE ventas")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('folio', $cols_v2)) {
         $pdo->exec("ALTER TABLE ventas ADD COLUMN folio INT NULL UNIQUE");
     }

     // 3. Columna folio en cotizaciones
     $cols_c2 = $pdo->query("DESCRIBE cotizaciones")->fetchAll(PDO::FETCH_COLUMN);
     if (!in_array('folio', $cols_c2)) {
         $pdo->exec("ALTER TABLE cotizaciones ADD COLUMN folio INT NULL UNIQUE");
     }

     // 4. Back-fill: asignar folios consecutivos a los registros existentes sin folio,
     //    ordenados cronológicamente mezclando ventas y cotizaciones.
     $pendientes = $pdo->query("
         SELECT 'venta' AS tipo, id, fecha_hora FROM ventas WHERE folio IS NULL
         UNION ALL
         SELECT 'cotizacion' AS tipo, id, fecha_hora FROM cotizaciones WHERE folio IS NULL
         ORDER BY fecha_hora ASC, tipo ASC
     ")->fetchAll(PDO::FETCH_ASSOC);

     foreach ($pendientes as $row) {
         // Insertar en secuenciadora para obtener el siguiente folio
         $ins = $pdo->prepare("INSERT INTO folio_global (tipo, referencia_id, creado_en) VALUES (?, ?, ?)");
         $ins->execute([$row['tipo'], $row['id'], $row['fecha_hora']]);
         $nuevoFolio = (int)$pdo->lastInsertId();

         if ($row['tipo'] === 'venta') {
             $pdo->prepare("UPDATE ventas SET folio = ? WHERE id = ?")->execute([$nuevoFolio, $row['id']]);
         } else {
             $pdo->prepare("UPDATE cotizaciones SET folio = ? WHERE id = ?")->execute([$nuevoFolio, $row['id']]);
         }
     }

} catch (\PDOException $e) {
     die(json_encode([
         'success' => false,
         'message' => 'Connection failed: ' . $e->getMessage()
     ]));
}

// ── Helper: genera el siguiente folio global y lo registra ───────────────────
function getNextFolio(PDO $pdo, string $tipo, int $referencia_id): int {
    $stmt = $pdo->prepare("INSERT INTO folio_global (tipo, referencia_id) VALUES (?, ?)");
    $stmt->execute([$tipo, $referencia_id]);
    return (int)$pdo->lastInsertId();
}
