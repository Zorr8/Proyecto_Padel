<?php

class Reserva {
    private $id_reserva;
    private $id_usuario;
    private $id_tramo;
    private $fecha;

    /**
     * Get the value of id_reserva
     */
    public function getIdReserva() {
        return $this->id_reserva;
    }

    /**
     * Set the value of id_reserva
     */
    public function setIdReserva($id_reserva) {
        $this->id_reserva = $id_reserva;
        return $this;
    }

    /**
     * Get the value of id_usuario
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * Set the value of id_usuario
     */
    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
        return $this;
    }

    /**
     * Get the value of id_tramo
     */
    public function getIdTramo() {
        return $this->id_tramo;
    }

    /**
     * Set the value of id_tramo
     */
    public function setIdTramo($id_tramo) {
        $this->id_tramo = $id_tramo;
        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;
        return $this;
    }
}
