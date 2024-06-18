<?php

require_once __DIR__ . '/../modelos/ConexionDB.php';
require_once __DIR__ . '/../modelos/ReservasDao.php';
require_once __DIR__ . '/../modelos/Reserva.php';

class ControladorReservas {
    private $conexionDB;
    private $reservasDao;
   

    public function __construct() {
        $this->conexionDB = new ConexionDB();
        $conn = $this->conexionDB->getConexion();
        $this->reservasDao = new ReservasDao($conn);
    }

    public function obtenerReservas() {
        // Obtener todas las reservas
        $reservas = $this->reservasDao->getAll();
        // Aquí puedes incluir lógica adicional, como enviar las reservas a una vista
        return $reservas;
    }

    public function agregarReserva($id_usuario, $id_tramo, $fecha) {
        // Verificar si el usuario existe (esto ya está implementado en tu código)
    
        // Crear el objeto Reserva
        $reserva = new Reserva();
        $reserva->setIdUsuario($id_usuario);
        $reserva->setIdTramo($id_tramo);
        $reserva->setFecha($fecha);
    
        // Insertar la reserva en la base de datos
        if ($this->reservasDao->insert($reserva)) {
            echo "Reserva agregada con éxito";
        } else {
            echo "Error al agregar la reserva";
        }
    }
    
    

    public function actualizarReserva($id_reserva, $nuevoIdUsuario, $nuevoIdTramo, $nuevaFecha) {
        // Obtener la reserva por su ID
        $reserva = $this->reservasDao->getById($id_reserva);
        if ($reserva) {
            // Actualizar las propiedades de la reserva
            $reserva->setIdUsuario($nuevoIdUsuario);
            $reserva->setIdTramo($nuevoIdTramo);
            $reserva->setFecha($nuevaFecha);
            if ($this->reservasDao->update($reserva)) {
                echo "Reserva actualizada con éxito";
            } else {
                echo "Error al actualizar la reserva";
            }
        } else {
            echo "Reserva no encontrada";
        }
    }

    public function eliminarReserva($id_reserva) {
        // Eliminar la reserva por su ID
        if ($this->reservasDao->delete($id_reserva)) {
            echo "Reserva eliminada con éxito";
        } else {
            echo "Error al eliminar la reserva";
        }
    }
}

