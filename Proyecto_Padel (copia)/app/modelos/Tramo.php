<?php 
class Tramo {
    private $id_tramo;
    private $hora;
    

    /**
     * Get the value of id_tramo
     */
    public function getIdTramo()
    {
        return $this->id_tramo;
    }

    /**
     * Set the value of id_tramo
     */
    public function setIdTramo($id_tramo): self
    {
        $this->id_tramo = $id_tramo;

        return $this;
    }

    /**
     * Get the value of hora
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set the value of hora
     */
    public function setHora($hora): self
    {
        $this->hora = $hora;

        return $this;
    }
}
?>