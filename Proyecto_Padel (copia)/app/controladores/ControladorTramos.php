<?php

require_once __DIR__ . '/../modelos/ConexionDB.php';
require_once __DIR__ . '/../modelos/TramosDao.php';

class ControladorTramos {
    private $conexionDB;
    private $tramosDao;

    public function __construct() {
        $this->conexionDB = new ConexionDB();
        $conn = $this->conexionDB->getConexion();
        $this->tramosDao = new TramosDao($conn);
    }
   
    public function obtenerTramosConEstado() {
        $conn = $this->conexionDB->getConexion();
        $fecha = $_POST['fecha'] ?? null; // Asegúrate de recibir la fecha si es necesario para tu lógica
    
        // Modifica tu consulta SQL si es necesario para incluir la lógica de filtrado por fecha
        $sql = "SELECT t.id_tramo, t.hora, IF(r.id_reserva IS NULL, false, true) AS reservado
                FROM Tramos t
                LEFT JOIN Reservas r ON t.id_tramo = r.id_tramo AND r.fecha = ?
                ORDER BY t.hora ASC";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tramos = [];
        while ($fila = $result->fetch_assoc()) {
            $tramos[] = [
                'id_tramo' => $fila['id_tramo'],
                'hora' => $fila['hora'],
                'reservado' => (bool) $fila['reservado'] // Convierte a booleano
            ];
        }
    
        $stmt->close();
        $conn->close();
    
        header('Content-Type: application/json');
        echo json_encode($tramos);
        exit;
    }
    

    public function obtenerTramos() {
        $tramos = $this->tramosDao->getAll();
        return $tramos;
    }

    public function agregarTramo($hora) {
        $tramo = new Tramo();
        $tramo->setHora($hora);
        if ($this->tramosDao->insert($tramo)) {
            echo "Tramo agregado con éxito";
        } else {
            echo "Error al agregar el tramo";
        }
    }

    public function actualizarTramo($id_tramo, $nuevaHora) {
        $tramo = $this->tramosDao->getById($id_tramo);
        if ($tramo) {
            $tramo->setHora($nuevaHora);
            if ($this->tramosDao->update($tramo)) {
                echo "Tramo actualizado con éxito";
            } else {
                echo "Error al actualizar el tramo";
            }
        } else {
            echo "Tramo no encontrado";
        }
    }

    public function eliminarTramo($id_tramo) {
        if ($this->tramosDao->delete($id_tramo)) {
            echo "Tramo eliminado con éxito";
        } else {
            echo "Error al eliminar el tramo";
        }
    }

    public function obtenerTramosDisponiblesPorFecha($fecha) {
        try {
            $tramosDisponibles = $this->tramosDao->getTramosDisponiblesPorFecha($fecha);
            header('Content-Type: application/json');
            echo json_encode($tramosDisponibles);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Error interno del servidor']);
        }
        exit; // Asegura que no haya salida adicional
    }
    
    

    public function manejarPeticion() {
        // Esto debería permitir solo peticiones POST
       
    
        // Si es POST, continúa con la lógica para manejar la acción
        if (isset($_POST['accion'])) {
            // Aquí manejas las acciones
        } 
    }
    
    
}    
$controlador = new ControladorTramos();
$controlador->manejarPeticion();
// Aquí termina la definición de la clase ControladorTramos

// Fuera de la definición de la clase




