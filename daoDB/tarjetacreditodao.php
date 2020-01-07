<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\TarjetaCredito as TarjetaCredito;

class TarjetaCreditoDao implements IDao
{
    private $connection;

    function __construct()
    { }
    //No implementamos todos los metodos porque al no poder buscar realmente las tarjetas visa y mastercard existentes,
    //recurrimos a ingresar manualmente tarjetas a la base de datos para hacer un simulacro. Por ende no tiene sentido
    //agregar, modificar y eliminar tarjetas.
    public function add($value = null)
    { }
    public function read($nroTarjeta)
    {
        $sql = "SELECT * FROM tarjetasCredito where nroTarjeta = $nroTarjeta";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
        if (!empty($resultSet))
            return $this->mapear($resultSet);
        else
            return false;
    }
    //los hicimos para crear la compra
    public function readIdTarjeta($nroTarjeta)
    {
        $sql = "SELECT idTarjetaCredito FROM tarjetasCredito where nroTarjeta = $nroTarjeta";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
            $resp = array_map(function ($p) {
                $idTarjetaCredito = ($p['idTarjetaCredito']);
                return $idTarjetaCredito;
            }, $resultSet);
            return (count($resp) > 1 ? $resp : $resp['0']);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function getAll()
    {
        $sql = "SELECT * FROM tarjetasCredito";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        if (!empty($resultSet))
            return $this->mapear($resultSet);
        else
            return false;
    }
    public function update($value = null, $value2 = null)
    { }
    public function delete($value = null)
    { }
    protected function mapear($value)
    {
        $value = is_array($value) ? $value : [];
        $resp = array_map(function ($p) {
            $tarjetaCredito = new TarjetaCredito($p['nombreCompania'], $p['nroTarjeta'], $p['codigoSeguridad']);
            return $tarjetaCredito;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
