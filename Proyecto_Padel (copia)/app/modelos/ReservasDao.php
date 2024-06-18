<?php

require_once 'ConexionDB.php'; // Asegúrate de incluir la clase ConexionDB
require_once 'Reserva.php'; // Incluir la clase Reserva

class ReservasDao {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function usuarioExiste($id_usuario) {
        $sql = "SELECT COUNT(*) as cnt FROM Usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['cnt'] > 0;
    }
    
    // Crear una nueva reserva
    public function insert(Reserva $reserva) {
        $sql = "INSERT INTO Reservas (id_usuario, id_tramo, fecha) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $id_usuario = $reserva->getIdUsuario();
        $id_tramo = $reserva->getIdTramo();
        $fecha = $reserva->getFecha();
        
        $stmt->bind_param('iis', $id_usuario, $id_tramo, $fecha);
        
        if ($stmt->execute()) {
            $reserva->setIdReserva($this->conn->insert_id); // Establecer el ID de la reserva recién creada
            return true;
        } else {
            return false;
        }
    }

    // Leer una reserva por su ID
    public function getById($id_reserva) {
        $sql = "SELECT * FROM Reservas WHERE id_reserva = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_reserva);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $reserva = new Reserva();
                $reserva->setIdReserva($row['id_reserva']);
                $reserva->setIdUsuario($row['id_usuario']);
                $reserva->setIdTramo($row['id_tramo']);
                $reserva->setFecha($row['fecha']);
                return $reserva;
            }
        }
        return null;
    }

    // Actualizar una reserva
    public function update(Reserva $reserva) {
        $sql = "UPDATE Reservas SET id_usuario = ?, id_tramo = ?, fecha = ? WHERE id_reserva = ?";
        $stmt = $this->conn->prepare($sql);
        $id_usuario = $reserva->getIdUsuario();
        $id_tramo = $reserva->getIdTramo();
        $fecha = $reserva->getFecha();
        $id_reserva = $reserva->getIdReserva();
        
        $stmt->bind_param('iisi', $id_usuario, $id_tramo, $fecha, $id_reserva);
        
        return $stmt->execute();
    }

    // Eliminar una reserva
    public function delete($id_reserva) {
        $sql = "DELETE FROM Reservas WHERE id_reserva = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_reserva);
        
        return $stmt->execute();
    }

    // Listar todas las reservas
    public function getAll() {
        $sql = "SELECT * FROM Reservas";
        $result = $this->conn->query($sql);
        $reservas = [];
        
        while ($row = $result->fetch_assoc()) {
            $reserva = new Reserva();
            $reserva->setIdReserva($row['id_reserva']);
            $reserva->setIdUsuario($row['id_usuario']);
            $reserva->setIdTramo($row['id_tramo']);
            $reserva->setFecha($row['fecha']);
            $reservas[] = $reserva;
        }
        
        return $reservas;
    }
}
