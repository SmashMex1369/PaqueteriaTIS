<?php
require_once __DIR__ . '/../models/Paquetes.php';
require_once __DIR__ . '/../config/database.php';


class PaquetesService {
    public static function obtenerTodos() {
        return Paquetes::obtenerTodos();
    }

    public static function buscarPaquetes($guia) {
        return Paquetes::buscarPaquetes($guia);
    }

    public static function crearPaquete($guia, $descripcion, $peso, $alto, $ancho, $profundidad, $idRepartidor, $idDestino) {
        return Paquetes::crearPaquete($guia, $descripcion, $peso, $alto, $ancho, $profundidad, $idRepartidor, $idDestino);
    }

    public static function completarEnvio($guia) {
        return Paquetes::completarEnvio($guia);
    }
}