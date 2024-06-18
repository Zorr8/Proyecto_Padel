<?php 
class Usuario {
    private $id_usuario;
    private $nombre;
    private $email;
    private $password;

    public function __construct($id_usuario, $nombre, $email, $password) {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
    }

    // Getters and setters...

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario): self {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre): self {
        $this->nombre = $nombre;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email): self {
        $this->email = $email;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password): self {
        $this->password = $password;
        return $this;
    }
}
