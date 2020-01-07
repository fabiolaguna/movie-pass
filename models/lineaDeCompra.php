<?php

namespace models;

class lineaDeCompra
{
    private $idLineaCompra;
    private $idCompra;
    private $cantidadEntradas;
    private $dia;

    function __construct($idLineaCompra, $idCompra, $cantidadEntradas, $dia)
    {
        $this->idLineaCompra = $idLineaCompra;
        $this->idCompra = $idCompra;
        $this->cantidadEntradas = $cantidadEntradas;
        $this->dia = $dia;
    }

    public function getIdLineaCompra()
    {
        return $this->idLineaCompra;
    }
    public function getIdCompra()
    {
        return $this->idCompra;
    }
    public function getCantidadEntradas()
    {
        return $this->cantidadEntradas;
    }
    public function getDia()
    {
        return $this->dia;
    }

    public function setIdLineaCompra($idLineaCompra)
    {
        $this->idLineaCompra = $idLineaCompra;
    }
    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
    }
    public function setCantidadEntradas($cantidadEntradas)
    {
        $this->cantidadEntradas = $cantidadEntradas;
    }
    public function setDia($dia)
    {
        $this->dia = $dia;
    }
}
