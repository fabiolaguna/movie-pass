<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\Proyeccion as Proyeccion;
use controllers\SalaController as SalaController;

class ProyeccionDao implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($proyeccion)
    {

        $arrayProyecciones = $this->getAll();
        $proyeccionExist = false;
        $sala = SalaController::readSala($proyeccion->getIdSala());
        if (!empty($arrayProyecciones)) {
            if (is_array($arrayProyecciones)) {
                foreach ($arrayProyecciones as $value) { //Una película solo puede ser proyectada en un único cine por día (Pero no puede ser reproducida en mas de una sala del cine)
                    $salaValue = SalaController::readSala($value->getIdSala());
                    if ($proyeccion->getIdPelicula() == $value->getIdPelicula()) {
                        if ($proyeccion->getFecha() == $value->getFecha()) {
                            if ($sala->getIdCine() == $salaValue->getIdCine()) {
                                if ($proyeccion->getIdSala() != $value->getIdSala()) {
                                    $proyeccionExist = true;
                                    return -1;
                                }
                            } else {
                                $proyeccionExist = true;
                                return -2;
                            }
                        }
                    }
                }
            } else {
                $salaValue = SalaController::readSala($arrayProyecciones->getIdSala());
                if ($proyeccion->getIdPelicula() == $arrayProyecciones->getIdPelicula()) {
                    if ($proyeccion->getFecha() == $arrayProyecciones->getFecha()) {
                        if ($sala->getIdCine() == $salaValue->getIdCine()) {
                            if ($proyeccion->getIdSala() != $arrayProyecciones->getIdSala()) {
                                $proyeccionExist = true;
                                return -1;
                            }
                        } else {
                            $proyeccionExist = true;
                            return -2;
                        }
                    }
                }
            }
        }
        //esta comparando con el otro cine
        if (!$proyeccionExist) { //Validar los 15 minutos

            $horarioAnterior = new \Datetime($proyeccion->getHorario());
            $horarioAnterior->modify('-15 minute');
            $horarioSiguiente = new \Datetime($proyeccion->getHorario());
            $horarioSiguiente->modify('+15 minute');
            if (!empty($arrayProyecciones)) {
                if (is_array($arrayProyecciones)) {
                    $proyeccionCineSala = array();
                    foreach ($arrayProyecciones as $value) {
                        $salaValue = SalaController::readSala($value->getIdSala());
                        if (($salaValue->getIdCine() == $sala->getIdCine()) && ($value->getIdSala() == $proyeccion->getIdSala()) && ($value->getFecha() == $proyeccion->getFecha())) {
                            array_push($proyeccionCineSala, $value);
                        }
                    }
                    foreach ($proyeccionCineSala as $value) {
                        $horario = new \DateTime($value->getHorario());
                        if ($horario < $horarioSiguiente && $horario > $horarioAnterior) {
                            $proyeccionExist = true;
                            return -3;
                        }
                    }
                } else {
                    $salaValue = SalaController::readSala($arrayProyecciones->getIdSala());
                    if (($salaValue->getIdCine() == $sala->getIdCine()) && ($arrayProyecciones->getIdSala() == $proyeccion->getIdSala()) && ($arrayProyecciones->getFecha() == $proyeccion->getFecha())) {
                        $horario = new \DateTime($arrayProyecciones->getHorario());
                        if ($horario < $horarioSiguiente && $horario > $horarioAnterior) {
                            $proyeccionExist = true;
                            return -3;
                        }
                    }
                }
            }
        }

        if (!$proyeccionExist) {
            $sql = "INSERT INTO proyecciones (idSala, idPelicula, asientosDisponibles, asientosOcupados, fecha, horario) VALUES (:idSala, :idPelicula, :asientosDisponibles, :asientosOcupados, :fecha, :horario)";
            $parameters['idSala'] = $proyeccion->getIdSala();
            $parameters['idPelicula'] = $proyeccion->getIdPelicula();
            $sala = SalaController::readSala($proyeccion->getIdSala());
            $parameters['asientosDisponibles'] = $sala->getCapacidadButacas();
            $parameters['asientosOcupados'] = $proyeccion->getAsientosOcupados();
            $parameters['fecha'] = $proyeccion->getFecha();
            $parameters['horario'] = $proyeccion->getHorario();

            try {
                // creo la instancia connection
                $this->connection = Connection::getInstance();
                // Ejecuto la sentencia.
                return $this->connection->ExecuteNonQuery($sql, $parameters); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
            } catch (\PDOException $ex) {
                throw $ex;
            }
        }
    }

    public function read($idProyeccion)
    {

        $sql = "SELECT * FROM proyecciones where idProyeccion = $idProyeccion";
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

        $sql = "SELECT * FROM proyecciones";
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

    public function update($proyeccion, $idProyeccion)
    {
        $arrayProyecciones = $this->getAll();

        if (!empty($arrayProyecciones)) { //Para que no se compare con el mismo
            if (is_array($arrayProyecciones)) {
                foreach ($arrayProyecciones as $key => $pro) {
                    if ($pro->getIdProyeccion() == $idProyeccion) {
                        unset($arrayProyecciones[$key]);
                    }
                }
            } else {
                if ($arrayProyecciones->getIdProyeccion() == $idProyeccion) {
                    $arrayProyecciones = null;
                }
            }
        }

        $proyeccionExist = false;
        $sala = SalaController::readSala($proyeccion->getIdSala());
        if (!empty($arrayProyecciones)) {
            if (is_array($arrayProyecciones)) {
                foreach ($arrayProyecciones as $value) { //Una película solo puede ser proyectada en un único cine por día (Pero no puede ser reproducida en mas de una sala del cine)
                    $salaValue = SalaController::readSala($value->getIdSala());
                    if ($proyeccion->getIdPelicula() == $value->getIdPelicula()) {
                        if ($proyeccion->getFecha() == $value->getFecha()) {
                            if ($sala->getIdCine() == $salaValue->getIdCine()) {
                                if ($proyeccion->getIdSala() != $value->getIdSala()) {
                                    $proyeccionExist = true;
                                    return -1;
                                }
                            } else {
                                $proyeccionExist = true;
                                return -2;
                            }
                        }
                    }
                }
            } else {
                $salaValue = SalaController::readSala($arrayProyecciones->getIdSala());
                if ($proyeccion->getIdPelicula() == $arrayProyecciones->getIdPelicula()) {
                    if ($proyeccion->getFecha() == $arrayProyecciones->getFecha()) {
                        if ($sala->getIdCine() == $salaValue->getIdCine()) {
                            if ($proyeccion->getIdSala() != $arrayProyecciones->getIdSala()) {
                                $proyeccionExist = true;
                                return -1;
                            }
                        } else {
                            $proyeccionExist = true;
                            return -2;
                        }
                    }
                }
            }
        }
        //esta comparando con el otro cine
        if (!$proyeccionExist) { //Validar los 15 minutos
            $horarioAnterior = new \Datetime($proyeccion->getHorario());
            $horarioAnterior->modify('-15 minute');
            $horarioSiguiente = new \Datetime($proyeccion->getHorario());
            $horarioSiguiente->modify('+15 minute');
            if (!empty($arrayProyecciones)) {
                if (is_array($arrayProyecciones)) {
                    $proyeccionCineSala = array();
                    foreach ($arrayProyecciones as $value) {
                        $salaValue = SalaController::readSala($value->getIdSala());
                        if (($salaValue->getIdCine() == $sala->getIdCine()) && ($value->getIdSala() == $proyeccion->getIdSala()) && ($value->getFecha() == $proyeccion->getFecha())) {
                            array_push($proyeccionCineSala, $value);
                        }
                    }
                    foreach ($proyeccionCineSala as $value) {
                        $horario = new \DateTime($value->getHorario());
                        if ($horario < $horarioSiguiente && $horario > $horarioAnterior) {
                            $proyeccionExist = true;
                            return -3;
                        }
                    }
                } else {
                    $horario = new \DateTime($proyeccionCineSala->getHorario());
                    if ($horario < $horarioSiguiente && $horario > $horarioAnterior) {
                        $proyeccionExist = true;
                        return -3;
                    }
                }
            }
        }

        if (!$proyeccionExist) {
            $sql = "UPDATE proyecciones SET idSala = :idSala, idPelicula = :idPelicula, asientosDisponibles = :asientosDisponibles, asientosOcupados = :asientosOcupados, fecha = :fecha, horario = :horario WHERE idProyeccion = $idProyeccion";
            $parameters['idSala'] = $proyeccion->getIdSala();
            $parameters['idPelicula'] = $proyeccion->getIdPelicula();
            $parameters['asientosDisponibles'] = $proyeccion->getAsientosDisponibles();
            $parameters['asientosOcupados'] = $proyeccion->getAsientosOcupados();
            $parameters['fecha'] = $proyeccion->getFecha();
            $parameters['horario'] = $proyeccion->getHorario();

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

    public function updateAsientos($asientosDisponibles, $asientosOcupados, $idProyeccion)
    {
        $sql = "UPDATE proyecciones SET asientosDisponibles = :asientosDisponibles, asientosOcupados = :asientosOcupados WHERE idProyeccion = $idProyeccion";
        $parameters['asientosDisponibles'] = $asientosDisponibles;
        $parameters['asientosOcupados'] = $asientosOcupados;

        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql, $parameters);
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function delete($idProyeccion)
    {
        /*$entradaDao = new EntradaDao();
        $entradas = $entradaDao->getAll();
        $entradasEliminar = array();
        if (!empty($entradas)) {
            if (is_array($entradas)) {
                foreach ($entradas as $value) {
                    if ($value->getIdProyeccion() == $idProyeccion)
                        array_push($entradasEliminar, $value);
                }
            } else {
                if ($entradas->getIdProyeccion() == $idProyeccion)
                    array_push($entradasEliminar, $entradas);
            }
        }

        foreach ($entradasEliminar as $value) {
            $entradaDao->delete($value->getIdEntrada());
        }*/
        $sql = "DELETE FROM proyecciones WHERE idProyeccion = $idProyeccion";

        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    protected function mapear($value)
    {

        $value = is_array($value) ? $value : [];
        $resp = array_map(function ($p) {
            $proyeccion = new Proyeccion($p['idSala'], $p['idPelicula'], $p['asientosDisponibles'], $p['asientosOcupados'], $p['fecha'], $p['horario']);
            $proyeccion->setIdProyeccion($p['idProyeccion']);
            return $proyeccion;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
