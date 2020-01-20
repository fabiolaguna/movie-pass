<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\PagoTC as PagoTC;

class PagoTCDao implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($pago)
    {
        $sql = "INSERT INTO pagoTC (idCompra, codigoAut, fecha, total) VALUES (:idCompra, :codigoAut, :fecha, :total)";
        $parameters['idCompra'] = $pago->getIdCompra();
        $parameters['codigoAut'] = $pago->getCodigoAut();
        $parameters['fecha'] = $pago->getFecha(); 
        $parameters['total'] = $pago->getTotal();
        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
    public function read($idPagoTC)
    {
        $sql = "SELECT * FROM pagoTC where idPagoTC = $idPagoTC";
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
    public function getAll()
    {
        $sql = "SELECT * FROM pagoTC";
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
    //no voy a editar un pago
    public function update($value = null, $value2 = null) 
    {
    }
    public function delete($idPagoTC)
    {
        $sql = "UPDATE pagoTC SET baja = true WHERE idPagoTC = $idPagoTC";
        try {
            $this->connection = Connection::getInstance();
            return $this->connection->ExecuteNonQuery($sql);
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
    protected function mapear($value)
    {
        $value = is_array($value) ? $value : [];
        $resp = array_map(function ($p) {
            $pago = new PagoTC($p['idCompra'], $p['codigoAut'], $p['fecha'], $p['total']);
            $pago->setIdPagoTC($p['idPagoTC']);
            return $pago;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
