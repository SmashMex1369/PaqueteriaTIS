<?php
require_once __DIR__ . '/../models/Clientes.php';
require_once __DIR__ . '/../config/database.php';

class ClientesService {
    public static function obtenerTodos() {
        return Clientes::obtenerTodos();
    }

    public static function buscarCliente($id) {
        return Clientes::buscarCliente($id);
    }

    public static function registrarCliente($nombre, $rfc, $telefono) {
        return Clientes::registrarCliente($nombre, $rfc, $telefono);
    }

    public static function registrarDestino($idCliente, $codPostal, $colonia, $calle, $num) {
        return Clientes::registrarDestino($idCliente, $codPostal, $colonia, $calle, $num);
    }

    public static function actualizarDestino($id, $codPostal, $colonia, $calle, $num) {
        return Clientes::actualizarDestino($id, $codPostal, $colonia, $calle, $num);
    }
}