<?php
require_once 'ConexionDB.php';
require_once 'Usuario.php'; // Asegúrate de incluir el modelo Usuario

class UsuariosDao {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para crear un nuevo usuario
    public function insert($usuario) {
        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();

        if (empty($nombre) || empty($email) || empty($password)) {
            die("Los valores de nombre, email y password no pueden ser nulos");
        }

        $sql = "INSERT INTO Usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }

        $stmt->bind_param('sss', $nombre, $email, $password);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    // Método para obtener un usuario por ID
    public function obtenerPorId($id_usuario) {
        $sql = "SELECT * FROM Usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows == 1) {
                $row = $resultado->fetch_assoc();
                return new Usuario(
                    $row['id_usuario'],
                    $row['nombre'],
                    $row['email'],
                    $row['password']
                );
            }
        }
        return null;
    }

    // Método para actualizar un usuario existente
    public function actualizar($usuario) {
        $id_usuario = $usuario->getId(); // Suponiendo que tienes un método getId en la clase Usuario
        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();

        $sql = "UPDATE Usuarios SET nombre = ?, email = ?, password = ? WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta update: " . $this->conn->error);
        }

        $stmt->bind_param('sssi', $nombre, $email, $password, $id_usuario);
        return $stmt->execute();
    }

    // Método para eliminar un usuario
    public function eliminar($id_usuario) {
        $sql = "DELETE FROM Usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta delete: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    // Método para obtener un usuario por su email
    public function obtenerPorEmail($email) {
        $sql = "SELECT * FROM Usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows == 1) {
                $row = $resultado->fetch_assoc();
                return new Usuario(
                    $row['id_usuario'],
                    $row['nombre'],
                    $row['email'],
                    $row['password']
                );
            }
        }
        return null;
    }

    // Método para obtener todos los usuarios
    public function obtenerTodos() {
        $sql = "SELECT * FROM Usuarios";
        $resultado = $this->conn->query($sql);
        if ($resultado->num_rows > 0) {
            $usuarios = [];
            while ($row = $resultado->fetch_assoc()) {
                $usuarios[] = new Usuario(
                    $row['id_usuario'],
                    $row['nombre'],
                    $row['email'],
                    $row['password']
                );
            }
            return $usuarios;
        } else {
            return [];
        }
    }
}
?>
