<?php
require_once __DIR__ . '/../services/paquetesService.php';
require_once __DIR__ . '/../../handler/XmlHandler.php';
require_once __DIR__ . '/../models/Paquetes.php';

class PaquetesController {

    private $modelo;
    public function __construct() {
        $this->modelo = new Paquetes();
    }

    public static function obtenerTodos() {
        $paquetes = PaquetesService::obtenerTodos();
        $xml = XmlHandler::generarXml($paquetes, 'Paquetes', 'Paquete');
        return $xml;
    }

    public static function buscarPaquetes($guia) {
        $paquete = PaquetesService::buscarPaquetes($guia);
        if(!$paquete) {
            header('HTTP/1.1 404 Not Found');
            return '<error>Paquete no encontrado</error>';
        }
        echo XmlHandler::generarXml($paquete, 'Paquetes', 'Paquete');
    }

    public static function crearPaquete() {
        $data = file_get_contents('php://input');
        $xml = simplexml_load_string($data);

        $guia = (string)$xml->NoGuia;
        $desc = (string)$xml->Descripcion;
        $peso = (float)$xml->Peso;
        $alto = (float)$xml->Dimensiones->Alto;
        $ancho = (float)$xml->Dimensiones->Ancho;
        $profundidad = (float)$xml->Dimensiones->Largo;
        $idRepartidor = (int)$xml->IDRepartidor;
        $idDestino = (int)$xml->IDDestino;

        if(PaquetesService::crearPaquete($guia, $desc, $peso, $alto, $ancho, $profundidad, $idRepartidor, $idDestino)) {
            header('HTTP/1.1 201 Created');
            echo "<Respuesta>Paquete creado exitosamente</Respuesta>";
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo "<Error>Error al crear el paquete</Error>";
        }
    }

    public static function completarEnvio() {
        $data = file_get_contents('php://input');
        $xml = simplexml_load_string($data);

        $guia = (string)$xml->NoGuia;

        $paquete = PaquetesService::buscarPaquetes($guia);
        if(!$paquete) {
            header('HTTP/1.1 404 Not Found');
            return '<error>Paquete no encontrado</error>';
        }else{
            if(PaquetesService::completarEnvio($guia)) {
                header('HTTP/1.1 200 OK');
                echo "<Respuesta>Envio completado exitosamente</Respuesta>";
            }
        }
    }
}