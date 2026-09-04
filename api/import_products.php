<?php
// api/import_products.php
// Importa los productos locales a TiDB Cloud via HTTP POST
// ELIMINAR después de usar
header('Content-Type: application/json');

require_once 'db.php';

$products = [
    ['sku'=>'PROD-00001','descripcion'=>'papas','precio'=>7.00,'costo'=>5.00,'proveedor'=>'ninguno','existencia'=>0.00,'categoria'=>'verduras','utilidad'=>2.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
    ['sku'=>'PROD-00002','descripcion'=>'fresas','precio'=>25.00,'costo'=>22.00,'proveedor'=>'ganadero','existencia'=>0.00,'categoria'=>'frutas','utilidad'=>3.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
    ['sku'=>'PROD-00003','descripcion'=>'Lona de morena','precio'=>350.00,'costo'=>300.00,'proveedor'=>'Morena','existencia'=>12.00,'categoria'=>'Politica','utilidad'=>50.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
    ['sku'=>'PROD-00004','descripcion'=>'Bolantes de Estetica','precio'=>4.00,'costo'=>2.00,'proveedor'=>'Ninguno','existencia'=>400.00,'categoria'=>'Estetica','utilidad'=>2.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
    ['sku'=>'PROD-00005','descripcion'=>'Lona de morena','precio'=>30.00,'costo'=>10.00,'proveedor'=>'Morena','existencia'=>0.00,'categoria'=>'Politica','utilidad'=>20.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
    ['sku'=>'PROD-00006','descripcion'=>'papas','precio'=>6.00,'costo'=>4.00,'proveedor'=>'ninguno','existencia'=>0.00,'categoria'=>'verduras','utilidad'=>2.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
    ['sku'=>'PROD-00007','descripcion'=>'Lona de morena','precio'=>20.00,'costo'=>10.00,'proveedor'=>'ninguno','existencia'=>0.00,'categoria'=>'verduras','utilidad'=>10.00,'codigo_barras'=>'','venta_por_metros'=>0,'costo_m2'=>0.00,'precio_m2'=>0.00],
];

$inserted = 0;
$skipped  = 0;
$errors   = [];

$sql = "INSERT IGNORE INTO productos 
        (sku, descripcion, precio, costo, proveedor, existencia, categoria, utilidad, codigo_barras, venta_por_metros, costo_m2, precio_m2)
        VALUES (:sku, :descripcion, :precio, :costo, :proveedor, :existencia, :categoria, :utilidad, :codigo_barras, :venta_por_metros, :costo_m2, :precio_m2)";

$stmt = $pdo->prepare($sql);

foreach ($products as $p) {
    try {
        $stmt->execute($p);
        if ($stmt->rowCount() > 0) {
            $inserted++;
        } else {
            $skipped++;
        }
    } catch (\PDOException $e) {
        $errors[] = $p['sku'] . ': ' . $e->getMessage();
    }
}

$total = $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();

echo json_encode([
    'success'  => true,
    'inserted' => $inserted,
    'skipped'  => $skipped,
    'errors'   => $errors,
    'total_en_db' => (int)$total
]);
