<?php
require_once __DIR__ .  '/../config/database.php';

class Clientes {

    private $apiUrl = 'http://localhost:5000/api/clientes';

    private function sendRequest($url, $method, $xmlData = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
        ["Content-Type: application/xml"]);

        if ($xmlData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            die('Error en la solicitud: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

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

    public static function buscarClientes($id) {
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

    
}
?>
