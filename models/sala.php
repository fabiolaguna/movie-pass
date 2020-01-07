<?php

namespace models;

class Sala
{
    private $idSala;
    private $idCine;
    private $nombre;
    private $precio;
    private $capacidadButacas;
    private $baja;

    function __construct($idCine, $nombre, $precio, $capacidadButacas)
    {
        $this->idSala=null;
        $this->baja=false;
        $this->idCine = $idCine;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->capacidadButacas = $capacidadButacas;
    }

    public function getIdSala()
    {
        return $this->idSala;
    }
    public function getIdCine()
    {
        return $this->idCine;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getCapacidadButacas()
    {
        return $this->capacidadButacas;
    }
    public function getBaja()
    {
        return $this->baja;
    }

    public function setIdSala($idSala)
    {
        $this->idSala = $idSala;
    }
    public function setIdCine($idCine)
    {
        $this->idCine = $idCine;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function setCapacidadButacas($capacidadButacas)
    {
        $this->capacidadButacas = $capacidadButacas;
    }
    public function setBaja($baja)
    {
        $this->baja=$baja;;
    }
}
