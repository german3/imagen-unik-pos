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

} catch (\PDOException $e) {
     die(json_encode([
         'success' => false,
         'message' => 'Connection failed: ' . $e->getMessage()
     ]));
}
