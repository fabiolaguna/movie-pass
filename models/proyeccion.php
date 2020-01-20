<?php

namespace models;

class Proyeccion
{
    private $idProyeccion;
    private $idSala;
    private $idPelicula;
    private $asientosDisponibles;
    private $asientosOcupados;
    private $fecha;
    private $horario;
    private $baja;

    function __construct($idSala, $idPelicula, $asientosDisponibles, $asientosOcupados, $fecha, $horario)
    {
        $this->idProyeccion = null;
        $this->idSala = $idSala;
        $this->idPelicula = $idPelicula;
        $this->asientosDisponibles = $asientosDisponibles;
        $this->asientosOcupados = $asientosOcupados;
        $this->fecha = $fecha;
        $this->horario = $horario;
        $this->baja = false;
    }

    public function getIdProyeccion()
    {
        return $this->idProyeccion;
    }
    public function getIdSala()
    {
        return $this->idSala;
    }
    public function getIdPelicula()
    {
        return $this->idPelicula;
    }
    public function getAsientosDisponibles()
    {
        return $this->asientosDisponibles;
    }
    public function getAsientosOcupados()
    {
        return $this->asientosOcupados;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHorario()
    {
        return $this->horario;
    }
    public function getBaja()
    {
        return $this->baja;
    }    

    public function setIdProyeccion($idProyeccion)
    {
        $this->idProyeccion = $idProyeccion;
    }
    public function setIdSala($idSala)
    {
        $this->idSala = $idSala;
    }
    public function setIdPelicula($idPelicula)
    {
        $this->idPelicula = $idPelicula;
    }
    public function setAsientosDisponibles($asientosDisponibles)
    {
        $this->asientosDisponibles = $asientosDisponibles;
    }
    public function setAsientosOcupados($asientosOcupados)
    {
        $this->asientosOcupados = $asientosOcupados;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setHorario($horario)
    {
        $this->horario = $horario;
    }
    public function setBaja($baja)
    {
        $this->baja = $baja;
    }
}
