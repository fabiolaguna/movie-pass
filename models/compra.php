<?php

namespace models;

class Compra
{
    private $idCompra;
    private $idTarjetaCredito;
    private $idCliente;
    private $cantEntradas;
    private $descuento;
    private $fecha;
    private $total;

    function __construct($idTarjetaCredito, $idCliente, $cantEntradas, $descuento, $fecha, $total)
    {
        $this->idCompra = null;
        $this->idTarjetaCredito = $idTarjetaCredito;
        $this->idCliente = $idCliente;
        $this->cantEntradas = $cantEntradas;
        $this->descuento = $descuento;
        $this->fecha = $fecha;
        $this->total = $total;
    }

    public function getIdCompra()
    {
        return $this->idCompra;
    }
    public function getIdTarjetaCredito()
    {
        return $this->idTarjetaCredito;
    }
    public function getIdCliente()
    {
        return $this->idCliente;
    }
    public function getCantEntradas()
    {
        return $this->cantEntradas;
    }
    public function getDescuento()
    {
        return $this->descuento;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getTotal()
    {
        return $this->total;
    }

    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
    }
    public function setIdTarjetaCredito($idTarjetaCredito)
    {
        $this->idTarjetaCredito = $idTarjetaCredito;
    }
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }
    public function setCantEntradas($cantEntradas)
    {
        $this->cantEntradas = $cantEntradas;
    }
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setTotal($total)
    {
        $this->total = $total;
    }
}
