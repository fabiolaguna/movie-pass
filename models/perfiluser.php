<?php

namespace models;

class PerfilUser
{
    private $nombre;
    private $apellido;
    private $dni;

    function __construct($nombre, $apellido, $dni)
    {
        $this->nombre=$nombre;
        $this->apellido=$apellido;
        $this->dni=$dni;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getDni()
    {
        return $this->dni;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
}
