<?php

require_once 'ConexionDB.php'; // Asegúrate de incluir la clase ConexionDB
require_once 'Tramo.php'; // Incluir la clase Tramo

class TramosDao {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Crear un nuevo tramo
    public function insert(Tramo $tramo) {
        $sql = "INSERT INTO Tramos (hora) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $hora = $tramo->getHora();
        
        $stmt->bind_param('s', $hora);
        
        if ($stmt->execute()) {
            $tramo->setIdTramo($this->conn->insert_id); // Establecer el ID del tramo recién creado
            return true;
        } else {
            return false;
        }
    }

    // Leer un tramo por su ID
    public function getById($id_tramo) {
        $sql = "SELECT * FROM Tramos WHERE id_tramo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_tramo);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $tramo = new Tramo();
                $tramo->setIdTramo($row['id_tramo']);
                $tramo->setHora($row['hora']);
                return $tramo;
            }
        }
        return null;
    }

    // Actualizar un tramo
    public function update(Tramo $tramo) {
        $sql = "UPDATE Tramos SET hora = ? WHERE id_tramo = ?";
        $stmt = $this->conn->prepare($sql);
        $hora = $tramo->getHora();
        $id_tramo = $tramo->getIdTramo();
        
        $stmt->bind_param('si', $hora, $id_tramo);
        
        return $stmt->execute();
    }

    // Eliminar un tramo
    public function delete($id_tramo) {
        $sql = "DELETE FROM Tramos WHERE id_tramo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_tramo);
        
        return $stmt->execute();
    }

    // Listar todos los tramos
    public function getAll() {
        $sql = "SELECT * FROM Tramos";
        $result = $this->conn->query($sql);
        $tramos = [];
        
        while ($row = $result->fetch_assoc()) {
            $tramo = new Tramo();
            $tramo->setIdTramo($row['id_tramo']);
            $tramo->setHora($row['hora']);
            $tramos[] = $tramo;
        }
        
        return $tramos;
    }
    public function getTramosDisponiblesPorFecha($fecha) {
        $tramosDisponibles = [];
        // Una consulta que selecciona tramos que no están reservados en la fecha especificada
        $sql = "SELECT t.* FROM Tramos t WHERE NOT EXISTS (SELECT NULL FROM Reservas r WHERE r.id_tramo = t.id_tramo AND r.fecha = ?)";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $fecha);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $tramosDisponibles[] = $row; // Asume que quieres devolver un array asociativo
            }
        }
    
        return $tramosDisponibles;
    }
    
}
