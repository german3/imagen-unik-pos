-- NOTA PARA BASES DE DATOS EN LA NUBE (ej. FreeSQLDatabase, db4free, etc.):
-- En plataformas gratuitas de MySQL, la base de datos ya viene creada.
-- Si usas una de ellas, NO ejecutes las siguientes dos líneas (o coméntalas):
-- CREATE DATABASE IF NOT EXISTS imagen_unik_pos DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE imagen_unik_pos;

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

-- Tabla de folio global compartido (ventas confirmadas, canceladas y cotizaciones)
CREATE TABLE IF NOT EXISTS folio_global (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20) NOT NULL COMMENT 'venta | cotizacion',
    referencia_id INT NOT NULL COMMENT 'id real en su tabla de origen',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

