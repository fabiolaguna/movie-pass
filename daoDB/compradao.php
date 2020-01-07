<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\Compra as Compra;

class CompraDao implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($compra)
    {
        $sql = "INSERT INTO compras (idTarjetaCredito, idCliente, cantEntradas, descuento, fecha, total) VALUES (:idTarjetaCredito, :idCliente, :cantEntradas, :descuento, :fecha, :total)";
        $parameters['idTarjetaCredito'] = $compra->getIdTarjetaCredito();
        $parameters['idCliente'] = $compra->getIdCliente();
        $parameters['cantEntradas'] = $compra->getCantEntradas();
        $parameters['descuento'] = $compra->getDescuento();
        $parameters['fecha'] = $compra->getFecha(); 
        $parameters['total'] = $compra->getTotal();
        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
    public function read($idCompra)
    {
        $sql = "SELECT * FROM compras where idCompra = $idCompra";
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
    /**
     * nomas funciona despues de justo hacer el add
     */
    public function lastIdInsert()
    {
        $sql = "SELECT LAST_INSERT_ID()";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
            $resp = array_map(function ($p) {
                $idCompra = ($p['LAST_INSERT_ID()']);
                return $idCompra;
            }, $resultSet);
            return (count($resp) > 1 ? $resp : $resp['0']);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function getAll()
    {
        $sql = "SELECT * FROM compras";
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
    //no voy a editar una compra
    public function update($value = null, $value2 = null) 
    {
    }
    public function delete($idCompra)
    {
        $sql = "DELETE FROM compras WHERE idCompra = $idCompra";
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
            $compra = new Compra($p['idTarjetaCredito'], $p['idCliente'], $p['cantEntradas'], $p['descuento'], $p['fecha'], $p['total']);
            $compra->setIdCompra($p['idCompra']);
            return $compra;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
