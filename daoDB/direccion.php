<?php

namespace daoDB;

use models\Direccion as Direc;
use interfaces\IDao as IDao;

class Direccion implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($direccion)
    {
        // Guardo como string la consulta sql utilizando como values, marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada
        $sql = "INSERT INTO direcciones (provincia, ciudad, calle, altura) VALUES (:provincia, :ciudad, :calle, :altura)";
        $parameters['provincia'] = $direccion->getProvincia();
        $parameters['ciudad'] = $direccion->getCiudad();
        $parameters['calle'] = $direccion->getCalle();
        $parameters['altura'] = $direccion->getAltura();
        
        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }
    /**
     *
     */
    public function read($idDireccion)
    {   
        $sql = "SELECT * FROM direcciones where idDireccion = $idDireccion";

        try {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($sql);
        } catch(Exception $ex) {
            throw $ex;
        }
        if(!empty($resultSet))
            return $this->mapear($resultSet);
        else
            return false; 
    }
    /**
     *
     */
    public function getAll()
    {
        $sql = "SELECT * FROM direcciones";
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
    /**
     *
     */
    public function update($direccion, $idDireccion)
    {
        $arrayDirecciones = $this->getAll();
        $cineExist = false;
        
        foreach ($arrayDirecciones as $value){
            if ($direccion->getIdDireccion() != $value->getIdDireccion()){
                if ((strcasecmp($direccion->getProvincia(), $value->getProvincia()) == 0) && (strcasecmp($direccion->getCiudad(), $value->getCiudad()) == 0) && (strcasecmp($direccion->getCalle(), $value->getCalle()) == 0) && ($direccion->getAltura() == $value->getAltura())){
                    $cineExist = true;
                    return -1;
                }
            }
        }

        if (!$cineExist){
            $sql = "UPDATE direcciones SET provincia = :provincia, ciudad = :ciudad, calle = :calle, altura = :altura WHERE idDireccion = $idDireccion";
            $parameters['provincia'] = $direccion->getProvincia();
            $parameters['ciudad'] = $direccion->getCiudad();
            $parameters['calle'] = $direccion->getCalle();
            $parameters['altura'] = $direccion->getAltura();

            try {
                // creo la instancia connection
                $this->connection = Connection::getInstance();
                // Ejecuto la sentencia.
                return $this->connection->ExecuteNonQuery($sql, $parameters);
            } catch (\PDOException $ex) {
                throw $ex;
            }
        }
    }

    /**
     *
     */
    public function delete($email) //No es necesario hacer un delete para direcciones, porque nunca existen por si solas y los cines siempre van a tener una direccion
    {/*
        $sql = "UPDATE usuarios SET baja = true where email = $email";
        $obj_pdo = new Conexion();
        try {
            $conexion = $obj_pdo->conectar();
            // Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
            $sentencia = $conexion->prepare($sql);
            $sentencia->bindParam(":email", $email);
            $sentencia->execute();
        } catch (PDOException $Exception) {
            throw new MyDatabaseException($Exception->getMessage(), $Exception->getCode());
        }
    */
    }
    /**
     * Transforma el listado de usuario en
     * objetos de la clase Usuario
     *
     * @param  Array $gente Listado de personas a transformar
     */
    protected function mapear($value)
    {
        $value = is_array($value) ? $value : [];
        $resp = array_map(function ($p) {
            $direccion = new Direc($p['provincia'], $p['ciudad'], $p['calle'], $p['altura']);
            $direccion->setIdDireccion($p['idDireccion']);
            return $direccion;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}