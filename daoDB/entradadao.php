<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\Entrada as Entrada;

class EntradaDao implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($entrada)
    {
        $sql = "INSERT INTO entradas (idProyeccion, idCliente, idCompra, codigoQr) VALUES (:idProyeccion, :idCliente, :idCompra, :codigoQr)";
        $parameters['idProyeccion'] = $entrada->getIdProyeccion();
        $parameters['idCliente'] = $entrada->getIdCliente();
        $parameters['idCompra'] = $entrada->getIdCompra();
        $parameters['codigoQr'] = $entrada->getCodigoQR();
        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
    public function read($idEntrada)
    {
        $sql = "SELECT * FROM entradas where idEntrada = $idEntrada";
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
    public function readProyeccion($idProyeccion)
    {
        $sql = "SELECT * FROM entradas where idProyeccion = $idProyeccion";
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
        $sql = "SELECT * FROM entradas";
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
    //no voy a editar una entrada
    public function update($value = null, $value2 = null) 
    {
    }
    public function delete($idEntrada)
    {
        $sql = "UPDATE entradas SET baja = true where idEntrada = $idEntrada";
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
            $entrada = new Entrada($p['idProyeccion'], $p['idCliente'], $p['idCompra']);
            $entrada->setIdEntrada($p['idEntrada']);
            $entrada->setCodigoQR($p['codigoQr']);
            return $entrada;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
    public function lastIdInsert()
    {
        $sql = "SELECT LAST_INSERT_ID()";
        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql); //Sino va execute($sql, $parameters);
            $resp = array_map(function ($p) {
                $idEntrada = ($p['LAST_INSERT_ID()']);
                return $idEntrada;
            }, $resultSet);
            return (count($resp) > 1 ? $resp : $resp['0']);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
