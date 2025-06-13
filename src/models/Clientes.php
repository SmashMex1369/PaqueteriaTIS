<?php
require_once __DIR__ .  '/../config/database.php';

class Clientes {

    public static function obtenerTodos() {
        global $conn;
        $sql = "SELECT * FROM cliente c INNER JOIN destino d ON c.idcliente = d.cliente_idcliente";
        $result = $conn->query($sql);
        $clientes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clientes[] = [
                    'IDCliente' => $row['idcliente'],
                    'Nombre' => $row['nombre'],
                    'RFC' => $row['rfc'],
                    'Telefono' => $row['telefono'],
                    'Direccion' => [
                        'C.P.' => $row['codPostal'],
                        'Colonia' => $row['colonia'],
                        'Calle' => $row['calle'],
                        'Numero' => $row['num'],
                    ],
                ];
            }
        }
        return $clientes;
    }

    public static function buscarCliente($id) {
        global $conn;
        $sql = "SELECT * FROM cliente c INNER JOIN destino d ON c.idcliente = d.cliente_idcliente WHERE c.idcliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $clientes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clientes[] = [
                    'IDCliente' => $row['idcliente'],
                    'Nombre' => $row['nombre'],
                    'RFC' => $row['rfc'],
                    'Telefono' => $row['telefono'],
                    'Direccion' => [
                        'C.P.' => $row['codPostal'],
                        'Colonia' => $row['colonia'],
                        'Calle' => $row['calle'],
                        'Numero' => $row['num'],
                    ],
                ];
            }
        }
        return $clientes;
    }

    public static function registrarCliente($nombre, $rfc, $telefono) {
        global $conn;
        $sql = "INSERT INTO cliente (nombre, rfc, telefono) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $rfc, $telefono);
        return $stmt->execute();
    }

    public static function registrarDestino($idCliente, $codPostal, $colonia, $calle, $num) {
        global $conn;
        $sql = "INSERT INTO destino (cliente_idcliente, codPostal, colonia, calle, num) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $idCliente, $codPostal, $colonia, $calle, $num);
        return $stmt->execute();
    }
    
}
?>
