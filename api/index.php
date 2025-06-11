<?php
$requst_uri = $_SERVER['REQUEST_URI'];
$requst_method = $_SERVER['REQUEST_METHOD'];

if (strpos($requst_uri, '/api/paquetes') === 0) {
    include_once '../src/index.php';
} else {
    header('HTTP/1.1 404 Not Found');
}
?>