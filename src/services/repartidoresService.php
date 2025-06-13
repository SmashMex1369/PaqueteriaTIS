<?php
require_once __DIR__ . '/../models/Repartidores.php';
require_once __DIR__ . '/../config/database.php';

class RepartidoresService {
    public static function obtenerTodos() {
        return Repartidores::obtenerTodos();
    }

    public static function buscarRepartidor($id) {
        return Repartidores::buscarRepartidor($id);
    }

    public static function registrarRepartidor($nombre) {
        return Repartidores::registrarRepartidor($nombre);
    }
}