<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\Cine as Cine;
use models\Direccion as Direccion;
use daoDB\Direccion as daoDireccion;
use daoDB\SalaDao as salaDao;

class CineDao implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($cine)
    {
        $arrayCines = $this->getAll();
        $cineExist = false;
        if (!empty($arrayCines)) {
            if (is_array($arrayCines)) { //Por si el mapear te devuelve un array
                foreach ($arrayCines as $value) { //Lo cambiamos a solo por direccion porque si agregabamos un cine con una direccion existente pero con un nombre distinto, lo agregaba igual (no se pueden repetir direcciones). CAMBIARLO EN EL DAO JSON
                    if ((strcasecmp($cine->getDireccion()->getProvincia(), $value->getDireccion()->getProvincia()) == 0) && (strcasecmp($cine->getDireccion()->getCiudad(), $value->getDireccion()->getCiudad()) == 0) && (strcasecmp($cine->getDireccion()->getCalle(), $value->getDireccion()->getCalle()) == 0) && ($cine->getDireccion()->getAltura() == $value->getDireccion()->getAltura())) {
                        if ($value->getBaja() == true) { //Se fija si el cine esta eliminado (baja=true), y lo vuelve a agregar
                            $value->setBaja(false);
                            $value->setNombre($cine->getNombre());
                            $value->setPrecioEntrada($cine->getPrecioEntrada());
                            // NO PEDIR LA CAPACIDAD EN VIEWS
                            $salaDao = new salaDao();
                            $salaDao->darAlta($value->getIdCine());
                            $capacidad = 0;
                            $salas = $salaDao->getAll();
                            if (!empty($salas)) {
                                if (is_array($salas)) {
                                    foreach ($salas as $sal){
                                        if ($value->getIdCine() == $sal->getIdCine())     
                                            $capacidad = $capacidad + ($sal->getCapacidadButacas());
                                    }
                                }else
                                {
                                    if ($value->getIdCine() == $sal->getIdCine())     
                                            $capacidad = $capacidad + ($sal->getCapacidadButacas());
                                }
                            }
                            $value->setCapacidadTotal($capacidad);
                            return $this->update($value, -1);
                        } else
                            $cineExist = true;
                    }
                }
            } else { //Si el mapear te devuelve un object
                if ((strcasecmp($cine->getDireccion()->getProvincia(), $arrayCines->getDireccion()->getProvincia()) == 0) && (strcasecmp($cine->getDireccion()->getCiudad(), $arrayCines->getDireccion()->getCiudad()) == 0) && (strcasecmp($cine->getDireccion()->getCalle(), $arrayCines->getDireccion()->getCalle()) == 0) && ($cine->getDireccion()->getAltura() == $arrayCines->getDireccion()->getAltura())) {
                    if ($arrayCines->getBaja() == true) {
                        $arrayCines->setBaja(false);
                        $arrayCines->setNombre($cine->getNombre());
                        $arrayCines->setPrecioEntrada($cine->getPrecioEntrada());
                        $salaDao = new salaDao();
                        $salaDao->darAlta($arrayCines->getIdCine());
                        $capacidad = 0;
                        $salas = $salaDao->getAll();
                        if (!empty($salas)) {
                            if (is_array($salas)) {
                                foreach ($salas as $sal){
                                    if ($arrayCines->getIdCine() == $sal->getIdCine())     
                                        $capacidad = $capacidad + ($sal->getCapacidadButacas());
                                }
                            }else
                            {
                                if ($arrayCines->getIdCine() == $sal->getIdCine())     
                                        $capacidad = $capacidad + ($sal->getCapacidadButacas());
                            }
                        }
                        $arrayCines->setCapacidadTotal($capacidad);
                        return $this->update($arrayCines, -1);
                    } else
                        $cineExist = true;
                }
            }
        }

        if (!$cineExist) {
            $sql = "INSERT INTO cines (idDireccion, nombre, capacidadTotal, precioEntrada, baja) VALUES (:idDireccion, :nombre, :capacidadTotal, :precioEntrada, :baja)";
            $parameters['nombre'] = $cine->getNombre();
            //fijarse este capacidad, creo que no va
            $parameters['capacidadTotal'] = $cine->getCapacidadTotal();
            $parameters['precioEntrada'] = $cine->getPrecioEntrada();
            $baja = $cine->getBaja();
            if ($baja)
                $parameters['baja'] = 1;
            else
                $parameters['baja'] = 0;

            $direccion = new Direccion($cine->getDireccion()->getProvincia(), $cine->getDireccion()->getCiudad(), $cine->getDireccion()->getCalle(), $cine->getDireccion()->getAltura());
            $daoDir = new daoDireccion();
            $daoDir->add($direccion); //No es necesario hacer validacion en el add porque la direccion ya llega verificada de que no se repita
            $direcciones = $daoDir->getAll();
            if (is_array($direcciones)) {
                $cant = count($direcciones);
                $parameters['idDireccion'] = $cant;
            } else {
                $parameters['idDireccion'] = 1;
            }

            try {
                // creo la instancia connection
                $this->connection = Connection::getInstance();
                // Ejecuto la sentencia.
                return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
            } catch (\PDOException $ex) {
                throw $ex;
            }
        } else {
            return 0;
        }
    }
    /**
     *
     */
    public function read($idCine)
    {
        $sql = "SELECT * FROM cines where idCine = $idCine";
        //$parameters['idCine'] = $idCine;
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
     *
     */
    public function getAll()
    {
        $sql = "SELECT * FROM cines";
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
    public function update($cine, $idCine)
    {
        $flag = null;
        if ($idCine > 0) { //Cuando viene de cineController/modificarCine, te trae el id del cine a modificar (>0)
            $daoDir = new daoDireccion();
            $flag = $daoDir->update($cine->getDireccion(), $cine->getDireccion()->getIdDireccion());
            if ($flag == -1) {
                return $flag;
            }
        } else { //Cuando viene del metodo add el $idcine es = -1, asi no tiene que volver a comparar las direcciones. Si quiero agregar un cine que esta eliminado (baja=true) no me dejaba, pero evitando esta comparacion si
            $flag = 1;
            $idCine = $cine->getIdCine();
        }

        $salaDao = new salaDao();
        $salasList = $salaDao->getAll();
        $salasCine = array();

        if (!empty($salasList)) {
            if (is_array($salasList)) {
                foreach ($salasList as $value) {
                    if ($value->getIdCine() == $idCine)
                        array_push($salasCine, $value);
                }
            } else {
                if ($salasList->getIdCine() == $idCine)
                    array_push($salasCine, $salasList);
            }
        }

        foreach ($salasCine as $sala) {
            $salaDao->updatePrecio($cine->getPrecioEntrada(), $sala->getIdSala());
        }

        if ($flag >= 0) {
            $sql = "UPDATE cines SET nombre = :nombre, precioEntrada = :precioEntrada, capacidadTotal = :capacidadTotal, baja = :baja WHERE idCine = $idCine";
            $parameters['nombre'] = $cine->getNombre();
            $parameters['precioEntrada'] = $cine->getPrecioEntrada();
            $parameters['capacidadTotal'] = $cine->getCapacidadTotal();
            $baja = $cine->getBaja();
            if ($baja)
                $parameters['baja'] = 1;
            else
                $parameters['baja'] = 0;

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
    public function updateCapacidad($capacidadTotal, $idCine)
    {
        $sql = "UPDATE cines SET capacidadTotal = :capacidadTotal WHERE idCine = $idCine";
        $parameters['capacidadTotal'] = $capacidadTotal;

        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql, $parameters);
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    /**
     *
     */
    public function delete($idCine)
    {
        $sql = "UPDATE cines SET baja = true where idCine = $idCine";
        $salaDao = new SalaDao();
        $arraySalas = $salaDao->getAll();

        if (!empty($arraySalas)) {
            if (is_array($arraySalas)) {
                foreach ($arraySalas as $sala) {
                    if ($idCine == $sala->getIdCine()) {
                        $salaDao->delete($sala->getIdSala());
                    }
                }
            } else {
                if ($idCine == $arraySalas->getIdCine()) {
                    $salaDao->delete($arraySalas->getIdSala());
                }
            }
        }

        try {
            $this->connection = Connection::getInstance();
            return $this->connection->ExecuteNonQuery($sql);
        } catch (\PDOException $ex) {
            throw $ex;
        }
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
            $daoDir = new daoDireccion();
            $direccion = $daoDir->read($p['idDireccion']);
            $cine = new Cine($direccion, $p['nombre'], $p['capacidadTotal'], $p['precioEntrada']);
            if ($p['baja'] == 1)
                $cine->setBaja(true);
            else
                $cine->setBaja(false);
            $cine->setIdCine($p['idCine']);
            return $cine;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
