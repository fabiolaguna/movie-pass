<?php

namespace models;

class Cine
{
    private $idCine;
    private $direccion; 
    private $nombre;
    private $capacidadTotal;
    private $precioEntrada;
    private $baja;

    function __construct($direccion, $nombre, $capacidadTotal, $precioEntrada)
    {
        $this->idCine=null;
        $this->direccion = $direccion;
        $this->nombre = $nombre;
        $this->capacidadTotal = $capacidadTotal;
        $this->precioEntrada = $precioEntrada;
        $this->baja=false;
    }

    public function getIdCine()
    {
        return $this->idCine;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getCapacidadTotal()
    {
        return $this->capacidadTotal;
    }
    public function getPrecioEntrada()
    {
        return $this->precioEntrada;
    }
    public function getBaja()
    {
        return $this->baja;
    }

    public function setIdCine($idCine)
    {
        $this->idCine = $idCine;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setCapacidadTotal($capacidadTotal)
    {
        $this->capacidadTotal = $capacidadTotal;
    }
    public function setPrecioEntrada($precioEntrada)
    {
        $this->precioEntrada = $precioEntrada;
    }
    public function setBaja($baja)
    {
        $this->baja = $baja;
    }
    
}
