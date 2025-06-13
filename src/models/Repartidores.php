<?php
require_once __DIR__ . '/../config/database.php';

class Repartidores {
    public static function obtenerTodos() {
        global $conn;
        $sql = "SELECT * FROM repartidor";
        $result = $conn->query($sql);
        $repartidores = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $repartidores[] = [
                    'IDRepartidor' => $row['idrepartidor'],
                    'Nombre' => $row['nombre']
                ];
            }
        }
        return $repartidores;
    }

    public static function buscarRepartidor($id) {
        global $conn;
        $sql = "SELECT * FROM repartidor WHERE idrepartidor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $repartidor = null;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $repartidor = [
                'IDRepartidor' => $row['idrepartidor'],
                'Nombre' => $row['nombre']
            ];
        }
        return $repartidor;
    }

    public static function registrarRepartidor($nombre) {
        global $conn;
        $sql = "INSERT INTO repartidor (nombre) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        return $stmt->execute();
    }

}
?>