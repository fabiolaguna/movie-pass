<?php

namespace models;

class Pelicula
{
    private $idPelicula;
    private $idCategoria;
    private $nombrePelicula; //titulo
    private $descripcion;
    //private $duracion;
    private $imagen;
    private $lenguaje;
    private $fechaEstreno;
    private $poster;

    function __construct($idPelicula, $idCategoria, $nombrePelicula, $descripcion, /*$duracion,*/ $imagen, $lenguaje, $fechaEstreno, $poster)
    {
        $this->idPelicula = $idPelicula;
        $this->idCategoria = $idCategoria;
        $this->nombrePelicula = $nombrePelicula;
        $this->descripcion = $descripcion;
        /*$this->duracion = $duracion;*/
        $this->imagen = $imagen;
        $this->lenguaje = $lenguaje;
        $this->fechaEstreno = $fechaEstreno;
        $this->poster = $poster;
    }
    public function getIdPelicula()
    {
        return $this->idPelicula;
    }
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }
    public function getNombrePelicula()
    {
        return $this->nombrePelicula;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    /*public function getDuracion()
    {
        return $this->duracion;
    }*/
    public function getImagen()
    {
        return $this->imagen;
    }
    public function getLenguaje()
    {
        return $this->lenguaje;
    }
    public function getFechaEstreno()
    {
        return $this->fechaEstreno;
    }
    public function getPoster()
    {
        return $this->poster;
    }

    public function setIdPelicula($idPelicula)
    {
        $this->idPelicula = $idPelicula;
    }
    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;
    }
    public function setNombrePelicula($nombrePelicula)
    {
        $this->nombrePelicula = $nombrePelicula;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    /*public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }*/
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
    public function setLenguaje($lenguaje)
    {
        $this->lenguaje = $lenguaje;
    }
    public function setFechaEstreno($fechaEstreno)
    {
        $this->fechaEstreno = $fechaEstreno;
    }
    public function setPoster($poster)
    {
        $this->poster=$poster;
    }
}
