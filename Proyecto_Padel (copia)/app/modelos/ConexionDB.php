<?php

require_once __DIR__ . '/../config/config.php';

class ConexionDB {
    private $host = MYSQL_HOST;
    private $usuario = MYSQL_USER;
    private $contrasena = MYSQL_PASS;
    private $nombre_base_de_datos = MYSQL_DB;

    private $conn; // Aquí almacenaremos la conexión mysqli

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->usuario, $this->contrasena, $this->nombre_base_de_datos);

        // Verificar si hay errores en la conexión
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        // Establecer juego de caracteres UTF-8
        $this->conn->set_charset("utf8");
    }

    public function getConexion() {
        return $this->conn;
    }

    public function cerrarConexion() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>

