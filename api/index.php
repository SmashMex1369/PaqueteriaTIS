<?php
$requst_uri = $_SERVER['REQUEST_URI'];
$requst_method = $_SERVER['REQUEST_METHOD'];

if (strpos($requst_uri, '/api/paquetes') === 0) {
    include_once '../src/index.php';
}elseif (strpos($requst_uri, '/api/clientes') === 0) {
    include_once '../src/index.php';
} elseif (strpos($requst_uri, '/api/repartidores') === 0) {
    include_once '../src/index.php';
} else {
    header('Content-Type: application/xml;charset=UTF-8');
    header('HTTP/1.1 404 Not Found');
    echo '<error>Ruta no encontrada</error>';
}
?>