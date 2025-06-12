<?php
require_once __DIR__ . '/../models/Clientes.php';
require_once __DIR__ . '/../config/database.php';


class ClientesService {
    public static function obtenerTodos() {
        return Clientes::obtenerTodos();
    }

    public static function buscarClientes($id) {
        return Clientes::buscarClientes($id);
    }

    public static function registrarCliente($nombre, $rfc, $telefono) {
        return Clientes::registrarCliente($nombre, $rfc, $telefono);
    }
}