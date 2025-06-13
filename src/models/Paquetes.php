<?php
require_once __DIR__ .  '/../config/database.php';

class Paquetes {

    public static function obtenerTodos() {
        global $conn;
        $sql = "SELECT * FROM paquetes";
        $result = $conn->query($sql);
        $paquetes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $paquetes[] = [
                    'NoGuia' => $row['numGuia'],
                    'Descripcion' => $row['descripcion'],
                    'Peso' => $row['peso'] . ' kg',
                    'Dimensiones' => [
                        'Alto' => $row['alto'] . ' cm',
                        'Ancho' => $row['ancho'] . ' cm',
                        'Largo' => $row['profundidad'] . ' cm',
                    ],
                    'IDRepartidor' => $row['repartidor_idrepartidor'],
                    'IDDestino' => $row['destino_iddestino']
                ];
            }
        }
        return $paquetes;
    }

    public static function buscarPaquetes($guia) {
        global $conn;
        $sql = "SELECT * FROM paquetes WHERE numGuia LIKE ?";
        $stmt = $conn->prepare($sql);
        $guia = '%' . $guia . '%';
        $stmt->bind_param("s", $guia);
        $stmt->execute();
        $result = $stmt->get_result();
        $paquetes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $paquetes[] = [
                    'NoGuia' => $row['numGuia'],
                    'Descripcion' => $row['descripcion'],
                    'Peso' => $row['peso'] . ' kg',
                    'Dimensiones' => [
                        'Alto' => $row['alto'] . ' cm',
                        'Ancho' => $row['ancho'] . ' cm',
                        'Largo' => $row['profundidad'] . ' cm',
                    ],
                    'IDRepartidor' => $row['repartidor_idrepartidor'],
                    'IDDestino' => $row['destino_iddestino']
                ];
            }
        }
        return $paquetes;
    }

    public static function crearPaquete($guia, $descripcion, $peso, $alto, $ancho, $profundidad, $idRepartidor, $idDestino) {
        global $conn;
        $sql = "INSERT INTO paquetes (numGuia, descripcion, peso, alto, ancho, profundidad, repartidor_idrepartidor, destino_iddestino) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddddii", $guia, $descripcion, $peso, $alto, $ancho, $profundidad, $idRepartidor, $idDestino);
        return $stmt->execute();
    }

    public static function completarEnvio($guia) {
        global $conn;
        $sql = "DELETE FROM paquetes WHERE numGuia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $guia);
        return $stmt->execute();
    }

    public static function obtenerGuiasPorIdRepartidor($idRepartidor) {
        global $conn;
        $sql = "SELECT numGuia FROM paquetes WHERE repartidor_idrepartidor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idRepartidor);
        $stmt->execute();
        $result = $stmt->get_result();
        $paquetes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $paquetes[] = [
                    'NoGuia' => $row['numGuia'],
                ];
            }
        }
        return $paquetes;
    }
}
?>
