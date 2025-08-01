<?php
require_once __DIR__ . '/../controllers/clientesController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if($request_method === "GET" && $request_uri === '/api/clientes/obtenerTodos') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = ClientesController::obtenerTodos();
    echo $xml;
}elseif($request_method === "GET" && $request_uri === '/api/clientes/obtenerTodosSinDestinos') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = ClientesController::obtenerTodosSinDestinos();
    echo $xml;
} elseif($request_method === "GET" && preg_match('/\/api\/clientes\/buscarCliente\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = ClientesController::buscarCliente($id);
    echo $xml;
} elseif($request_method === "POST" && $request_uri === '/api/clientes/registrarCliente') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = ClientesController::registrarCliente();
    echo $xml;
} elseif($request_method === "POST" && $request_uri === '/api/clientes/registrarDestino') {
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = ClientesController::registrarDestino();
    echo $xml;
} elseif($request_method === "PUT" && preg_match('/\/api\/clientes\/actualizarDestino\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    header('Content-Type: application/xml;charset=UTF-8');
    $xml = ClientesController::actualizarDestino($id);
    echo $xml;
}else {
    header('Content-Type: application/xml;charset=UTF-8');
    header('HTTP/1.1 404 Not Found');
    echo '<error>Endpoint no encontrado</error>';
}

