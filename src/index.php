<?php
$request_uri = $_SERVER['REQUEST_URI'];

if (strpos($request_uri, '/api/paquetes') === 0) {
    require_once __DIR__ . '/routes/paquetesRoute.php';
} elseif (strpos($request_uri, '/api/clientes') === 0) {
    require_once __DIR__ . '/routes/clientesRoute.php';
}elseif (strpos($request_uri, '/api/repartidores') === 0) {
    require_once __DIR__ . '/routes/repartidoresRoute.php';
} else {
    header('HTTP/1.1 404 Not Found');
    echo '<error>Endpoint no encontrado</error>';
}
?>