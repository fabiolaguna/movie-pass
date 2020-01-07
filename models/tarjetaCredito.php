<?php

namespace models;

class TarjetaCredito
{
    private $nombreCompania;
    private $nroTarjeta;
    private $codigoSeguridad;

    function __construct($nombreCompania, $nroTarjeta, $codigoSeguridad)
    {
        $this->nombreCompania = $nombreCompania;
        $this->nroTarjeta = $nroTarjeta;
        $this->codigoSeguridad = $codigoSeguridad;
    }

    public function getNombreCompania()
    {
        return $this->nombreCompania;
    }
    public function getNroTarjeta()
    {
        return $this->nroTarjeta;
    }
    public function getCodigoSeguridad()
    {
        return $this->codigoSeguridad;
    }

    public function setNombreCompania($nombreCompania)
    {
        $this->nombreCompania = $nombreCompania;
    }
    public function setNroTarjeta($nroTarjeta)
    {
        $this->nroTarjeta = $nroTarjeta;
    }
    public function setCodigoSeguridad($codigoSeguridad)
    {
        $this->codigoSeguridad = $codigoSeguridad;
    }
}
