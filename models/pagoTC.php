<?php

namespace models;

class PagoTC
{
    private $idPagoTC;
    private $idCompra;
    private $codigoAut;
    private $fecha;
    private $total;
    private $baja;

    function __construct($idCompra, $codigoAut, $fecha, $total)
    {
        $this->idPagoTC = null;
        $this->idCompra = $idCompra;
        $this->codigoAut = $codigoAut;
        $this->fecha = $fecha;
        $this->total = $total;
        $this->baja = false;
    }

    public function getIdPagoTC()
    {
        return $this->idPagoTC;
    }
    public function getIdCompra()
    {
        return $this->idCompra;
    }
    public function getCodigoAut()
    {
        return $this->codigoAut;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function getBaja()
    {
        return $this->baja;
    }

    public function setIdPagoTC($idPagoTC)
    {
        $this->idPagoTC = $idPagoTC;
    }
    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
    }
    public function setCodigoAut($codigoAut)
    {
        $this->codigoAut = $codigoAut;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setTotal($total)
    {
        $this->total = $total;
    }
    public function setBaja($baja)
    {
        $this->baja = $baja;
    }
}
