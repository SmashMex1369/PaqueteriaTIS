<?php
require_once __DIR__ . '/../services/repartidoresService.php';
require_once __DIR__ . '/../../handler/XmlHandler.php';
require_once __DIR__ . '/../models/Repartidores.php';

class RepartidoresController {

    public static function obtenerTodos() {
        $repartidores = RepartidoresService::obtenerTodos();
        $xml = XmlHandler::generarXML($repartidores, 'Repartidores', 'Repartidor');
        return $xml;
    }

    public static function buscarRepartidor($id) {
        $repartidor = RepartidoresService::buscarRepartidor($id);
        if(!$repartidor) {
            header('HTTP/1.1 404 Not Found');
            return '<error>Repartidor no encontrado</error>';
        }
        echo XmlHandler::generarXML($repartidor, 'Repartidores', 'Repartidor');
    }

    public static function registrarRepartidor() {
        $data = file_get_contents('php://input');
        $xml = simplexml_load_string($data);

        $nombre = (string)$xml->Nombre;

        if(RepartidoresService::registrarRepartidor($nombre)) {
            header('HTTP/1.1 201 Created');
            echo "<Respuesta>Repartidor registrado exitosamente</Respuesta>";
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo "<Error>Error al registrar el repartidor</Error>";
        }
    }
}