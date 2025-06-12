<?php
require_once __DIR__ . '/../services/clientesService.php';
require_once __DIR__ . '/../../handler/XmlHandler.php';
require_once __DIR__ . '/../models/Clientes.php';

class ClientesController {

    private $modelo;
    public function __construct() {
        $this->modelo = new Clientes();
    }

    public static function obtenerTodos() {
        $clientes = ClientesService::obtenerTodos();
        $xml = XmlHandler::generarXML($clientes, 'Clientes', 'Cliente');
        return $xml;
    }

    public static function buscarClientes($id) {
        $cliente = ClientesService::buscarClientes($id);
        if(!$cliente) {
            header('HTTP/1.1 404 Not Found');
            return '<error>Cliente no encontrado</error>';
        }
        echo XmlHandler::generarXML($cliente, 'Clientes', 'Cliente');
    }

    public static function registrarCliente() {
        $data = file_get_contents('php://input');
        $xml = simplexml_load_string($data);

        $nombre = (string)$xml->Nombre;
        $rfc = (string)$xml->RFC;
        $telefono = (string)$xml->Telefono;

        if(ClientesService::registrarCliente($nombre, $rfc, $telefono)) {
            header('HTTP/1.1 201 Created');
            echo "<Respuesta>Cliente registrado exitosamente</Respuesta>";
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo "<Error>Error al registrar el cliente</Error>";
        }
    }

}