<?php
require_once __DIR__ . '/../controllers/paquetesController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if($request_method === "GET" && $request_uri === '/api/paquetes/obtenerTodos') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = PaquetesController::obtenerTodos();
    echo $xml;
} elseif($request_method === "GET" && preg_match('/\/api\/paquetes\/buscarPaquetes\/([A-Za-z0-9]+)/', $request_uri, $matches)) {
    $guia = $matches[1];
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = PaquetesController::buscarPaquetes($guia);
    echo $xml;
} elseif($request_method === "POST" && $request_uri === '/api/paquetes/crearPaquete') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = PaquetesController::crearPaquete();
    echo $xml;
}elseif($request_method === "DELETE" && $request_uri === '/api/paquetes/completarEnvio') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = PaquetesController::completarEnvio();
    echo $xml;
} elseif($request_method === "GET" && preg_match('/\/api\/paquetes\/obtenerGuiasPorIdRepartidor\/(\d+)/', $request_uri, $matches)) {
    $idRepartidor = $matches[1];
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = PaquetesController::obtenerGuiasPorIdRepartidor($idRepartidor);
    echo $xml;
}else {
    header('Content-Type: application/xml;charset=UTF-8');
    header('HTTP/1.1 404 Not Found');
    echo '<error>Endpoint no encontrado</error>';
}

