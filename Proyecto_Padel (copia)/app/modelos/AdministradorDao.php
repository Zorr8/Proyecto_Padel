<?php
require_once __DIR__ . '/ConexionDB.php';
require_once __DIR__ . '/Reserva.php'; // Asegúrate de incluir Reserva.php si no lo has hecho

class AdministradorDao {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert(Administrador $administrador) {
        $stmt = $this->conn->prepare("INSERT INTO administradores (nombre, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }

        $nombre = $administrador->getNombre();
        $email = $administrador->getEmail();
        $password = $administrador->getPassword();
        $stmt->bind_param("sss", $nombre, $email, $password);

        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM administradores WHERE email = ?");
        if (!$stmt) {
            die("Error al preparar la consulta getByEmail: " . $this->conn->error);
        }

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows == 1) {
                $row = $resultado->fetch_assoc();
                return new Administrador(
                    $row['id'],
                    $row['nombre'],
                    $row['email'],
                    $row['password']
                );
            }
        }

        return null;
    }

    public function obtenerTodasLasReservas() {
        $sql = "SELECT * FROM Reservas";
        $resultado = $this->conn->query($sql);
        if ($resultado->num_rows > 0) {
            $reservas = [];
            while ($row = $resultado->fetch_assoc()) {
                $reserva = new Reserva(
                    $row['id_reserva'],
                    $row['id_usuario'],
                    $row['id_tramo'],
                    $row['fecha']
                );
                $reservas[] = $reserva;
            }
            return $reservas;
        } else {
            return [];
        }
    }
}
?>



