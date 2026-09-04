-- Script completo para la base de datos de Imagen Unik POS

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    costo DECIMAL(10,2) NOT NULL,
    proveedor VARCHAR(100),
    existencia DECIMAL(10,2) DEFAULT 0,
    categoria VARCHAR(100),
    utilidad DECIMAL(10,2),
    codigo_barras VARCHAR(100),
    venta_por_metros TINYINT DEFAULT 0,
    costo_m2 DECIMAL(10,2) DEFAULT 0.00,
    precio_m2 DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    colonia VARCHAR(100),
    calle VARCHAR(100),
    codigo_postal VARCHAR(20),
    numero_casa VARCHAR(50),
    telefono VARCHAR(50),
    correo_electronico VARCHAR(100),
    rfc VARCHAR(20),
    razon_social VARCHAR(150),
    curp VARCHAR(20),
    documento VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar cliente "Público General" por defecto
INSERT IGNORE INTO clientes (id, nombre, apellidos) VALUES (1, 'Público', 'General');

CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    descuento_total DECIMAL(10,2) DEFAULT 0,
    iva DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estatus VARCHAR(20) NOT NULL DEFAULT 'confirmada',
    motivo_cancelacion TEXT NULL,
    metodo_pago VARCHAR(50) NULL DEFAULT 'efectivo',
    folio INT NULL UNIQUE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE IF NOT EXISTS ventas_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NULL,
    nombre_producto VARCHAR(255) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    costo_unitario DECIMAL(10,2) NOT NULL,
    descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
    descuento_mxn DECIMAL(10,2) DEFAULT 0,
    total_linea DECIMAL(10,2) NOT NULL,
    alto DECIMAL(10,2) NULL,
    ancho DECIMAL(10,2) NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE TABLE IF NOT EXISTS cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    descuento_total DECIMAL(10,2) DEFAULT 0,
    iva DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    observaciones TEXT NULL,
    folio INT NULL UNIQUE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE IF NOT EXISTS cotizaciones_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cotizacion_id INT NOT NULL,
    producto_id INT NULL,
    nombre_producto VARCHAR(255) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    costo_unitario DECIMAL(10,2) NOT NULL,
    descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
    descuento_mxn DECIMAL(10,2) DEFAULT 0,
    total_linea DECIMAL(10,2) NOT NULL,
    alto DECIMAL(10,2) NULL,
    ancho DECIMAL(10,2) NULL,
    FOREIGN KEY (cotizacion_id) REFERENCES cotizaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE TABLE IF NOT EXISTS cortes_caja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    fondo_inicial DECIMAL(10,2) DEFAULT 0,
    num_ventas INT DEFAULT 0,
    subtotal_ventas DECIMAL(10,2) DEFAULT 0,
    descuentos_ventas DECIMAL(10,2) DEFAULT 0,
    iva_ventas DECIMAL(10,2) DEFAULT 0,
    total_ventas DECIMAL(10,2) DEFAULT 0,
    total_ingresos DECIMAL(10,2) DEFAULT 0,
    total_gastos DECIMAL(10,2) DEFAULT 0,
    efectivo_esperado DECIMAL(10,2) DEFAULT 0,
    efectivo_contado DECIMAL(10,2) DEFAULT 0,
    diferencia DECIMAL(10,2) DEFAULT 0,
    notas TEXT,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS gastos_caja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    corte_id INT NULL,
    descripcion VARCHAR(255) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    tipo VARCHAR(20) DEFAULT 'retiro',
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (corte_id) REFERENCES cortes_caja(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS folio_global (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20) NOT NULL COMMENT 'venta | cotizacion',
    referencia_id INT NOT NULL COMMENT 'id real en su tabla de origen',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);
