<?php
require_once __DIR__ . '/../controllers/repartidoresController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if($request_method === "GET" && $request_uri === '/api/repartidores/obtenerTodos') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = RepartidoresController::obtenerTodos();
    echo $xml;
} elseif($request_method === "GET" && preg_match('/\/api\/repartidores\/buscarRepartidor\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = RepartidoresController::buscarRepartidor($id);
    echo $xml;
} elseif($request_method === "POST" && $request_uri === '/api/repartidores/registrarRepartidor') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = RepartidoresController::registrarRepartidor();
    echo $xml;
} else {
    header('Content-Type: application/xml;charset=UTF-8');
    header('HTTP/1.1 404 Not Found');
    echo '<error>Endpoint no encontrado</error>';
}