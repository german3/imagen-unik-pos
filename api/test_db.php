<?php
// api/test_db.php
// Endpoint de diagnóstico temporal — ELIMINAR en producción
header('Content-Type: application/json');

$host    = trim(getenv('DB_HOST') ?: '127.0.0.1');
$port    = trim(getenv('DB_PORT') ?: '3306');
$db      = trim(getenv('DB_NAME') ?: 'imagen_unik_pos');
$user    = trim(getenv('DB_USER') ?: 'root');
$pass    = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

if (strpos($host, ':') !== false) {
    list($host, $port) = explode(':', $host, 2);
}

$info = [
    'DB_HOST' => $host,
    'DB_PORT' => $port,
    'DB_NAME' => $db,
    'DB_USER' => $user,
    'DB_PASS' => $pass !== '' ? '***set***' : '(empty)',
    'php_version' => PHP_VERSION,
    'ssl_ca_1_exists' => file_exists('/etc/ssl/certs/ca-certificates.crt'),
    'ssl_ca_2_exists' => file_exists('/etc/pki/tls/certs/ca-bundle.crt'),
];

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT            => 10,
];

// Intentar SIN SSL primero
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $count = $pdo->query("SELECT COUNT(*) as total FROM productos")->fetchColumn();
    echo json_encode([
        'status'    => 'OK - Sin SSL',
        'productos' => (int)$count,
        'info'      => $info,
    ]);
    exit;
} catch (\PDOException $e) {
    $info['error_sin_ssl'] = $e->getMessage();
}

// Intentar CON SSL
$sslOptions = $options;
if (file_exists('/etc/ssl/certs/ca-certificates.crt')) {
    $sslOptions[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
} elseif (file_exists('/etc/pki/tls/certs/ca-bundle.crt')) {
    $sslOptions[PDO::MYSQL_ATTR_SSL_CA] = '/etc/pki/tls/certs/ca-bundle.crt';
}
if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
    $sslOptions[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $sslOptions);
    $count = $pdo->query("SELECT COUNT(*) as total FROM productos")->fetchColumn();
    echo json_encode([
        'status'    => 'OK - Con SSL',
        'productos' => (int)$count,
        'info'      => $info,
    ]);
    exit;
} catch (\PDOException $e) {
    $info['error_con_ssl'] = $e->getMessage();
}

echo json_encode(['status' => 'FAILED', 'info' => $info]);
