<?php

namespace models;

class Entrada
{
    private $idEntrada; //nro de entrada
    private $idProyeccion;
    private $idCliente;
    private $idCompra;
    private $codigoQR;

    function __construct($idProyeccion, $idCliente, $idCompra)
    {
        $this->idEntrada = null;
        $this->idProyeccion = $idProyeccion;
        $this->idCliente = $idCliente;
        $this->idCompra = $idCompra;
        $this->codigoQR = null;
    }

    public function getIdEntrada()
    {
        return $this->idEntrada;
    }
    public function getIdProyeccion()
    {
        return $this->idProyeccion;
    }
    public function getIdCliente()
    {
        return $this->idCliente;
    }
    public function getIdCompra()
    {
        return $this->idCompra;
    }
    public function getCodigoQR()
    {
        return $this->codigoQR;
    }

    public function setIdEntrada($idEntrada)
    {
        $this->idEntrada = $idEntrada;
    }
    public function setIdProyeccion($idProyeccion)
    {
        $this->idProyeccion = $idProyeccion;
    }
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }
    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
    }
    public function setCodigoQR($codigoQr)
    {
        $this->codigoQR=$codigoQr;  
    }
    public function setCodigoQRMail($mensajeCodigoQr)
    {
        $this->codigoQR='<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=Puede ingresar al cine. ' . $mensajeCodigoQr . '" title="Acceso al cine"/>';  
    }
}
