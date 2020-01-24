<?php

namespace daoDB;

use interfaces\IDao as IDao;
use models\Sala as Sala;
use daoDB\CineDao as CineDao;
use daoDB\ProyeccionDao as ProyeccionDao;

class SalaDao implements IDao
{
    private $connection;

    function __construct()
    { }

    public function add($sala)
    {
        $arraySalas = $this->getAll();
        $salaExist = false;
        if (!empty($arraySalas)) {
            if (is_array($arraySalas)) {
                foreach ($arraySalas as $value) {
                    if ((strcasecmp($sala->getNombre(), $value->getNombre()) == 0) && $sala->getIdCine() == $value->getIdCine()) {
                        if ($value->getBaja() == true) {
                            $value->setBaja(false);
                            $value->setNombre($sala->getNombre());
                            $value->setPrecio($sala->getPrecio());
                            $value->setCapacidadButacas($sala->getCapacidadButacas());
                            return $this->update($value, $value->getIdSala());
                        }
                        $salaExist = true;
                    }
                }
            } else {
                if ((strcasecmp($sala->getNombre(), $arraySalas->getNombre()) == 0) && $sala->getIdCine() == $arraySalas->getIdCine()) {
                    if ($arraySalas->getBaja() == true) {
                        $arraySalas->setBaja(false);
                        $arraySalas->setNombre($sala->getNombre());
                        $arraySalas->setPrecio($sala->getPrecio());
                        $arraySalas->setCapacidadButacas($sala->getCapacidadButacas());
                        return $this->update($arraySalas, $arraySalas->getIdSala());
                    } else
                        $salaExist = true;
                }
            }
        }
        if (!$salaExist) {

            // AGREGA LA CAPACIDAD AL CINE ACA CUANDO AGREGO UNA SALA
            $cineDao = new CineDao();
            $cine = $cineDao->read($sala->getIdCine());
            $capacidadTotal = $cine->getCapacidadTotal();
            $capacidadTotal += $sala->getCapacidadButacas();
            $cineDao->updateCapacidad($capacidadTotal, $cine->getIdCine());

            $sql = "INSERT INTO salas (idCine, nombre, precio, capacidadButacas, baja) VALUES (:idCine, :nombre, :precio, :capacidadButacas, :baja)";
            $parameters['idCine'] = $sala->getIdCine();
            $parameters['nombre'] = $sala->getNombre();
            $parameters['precio'] = $sala->getPrecio(); //cuando llamas esta funcion le pones el precio del cine a la sala que vas a agregar
            $parameters['capacidadButacas'] = $sala->getCapacidadButacas();
            $baja = $sala->getBaja();
            if ($baja)
                $parameters['baja'] = 1;
            else
                $parameters['baja'] = 0;
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
    public function read($idSala)
    {
        $sql = "SELECT * FROM salas where idSala = $idSala";
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

    public function darAlta($idCine){ //Cuando se vuelve a agregar un cine que estaba eliminado usamos este metodo

        $sql = "UPDATE salas SET baja = 0 WHERE idCine = $idCine";

        try {
            // creo la instancia connection
            $this->connection = Connection::getInstance();
            // Ejecuto la sentencia.
            return $this->connection->ExecuteNonQuery($sql); //ExecuteNonQuery returns the number of rows affected, you use it when you do insert or update
        } catch (\PDOException $ex) {
            throw $ex;
        }
    } 
    /**
     *
     */
    public function getAll()
    {
        $sql = "SELECT * FROM salas";
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
    public function update($sala, $idSala)
    {
        $salaVieja = $this->read($idSala);
        $cineDao = new CineDao();
        $cineViejo = $cineDao->read($salaVieja->getIdCine());
        $cine = $cineDao->read($sala->getIdCine());
        
        if ($cineViejo == $cine) {
            $capacidadTotal = $cine->getCapacidadTotal();
            $capacidadTotal = $capacidadTotal - $salaVieja->getCapacidadButacas();
            $capacidadTotal = $capacidadTotal + $sala->getCapacidadButacas();
            $cineDao->updateCapacidad($capacidadTotal, $cine->getIdCine());
        } else {
            $capacidadTotal = $cineViejo->getCapacidadTotal();
            $capacidadTotal = $capacidadTotal - $salaVieja->getCapacidadButacas();
            $cineDao->updateCapacidad($capacidadTotal, $cineViejo->getIdCine());
            $capacidadTotal = $cine->getCapacidadTotal();
            $capacidadTotal = $capacidadTotal + $sala->getCapacidadButacas();
            $cineDao->updateCapacidad($capacidadTotal, $cine->getIdCine());
        }

        $proyeccionDao = new ProyeccionDao();
        $arrayProyecciones = $proyeccionDao->getAll();

        if (!empty($arrayProyecciones)) {
            if (is_array($arrayProyecciones)) {
                foreach ($arrayProyecciones as $proyeccion) {
                    if ($idSala == $proyeccion->getIdSala()) {
                        $asientosDisponibles = $sala->getCapacidadButacas();
                        $asientosOcupados = ($sala->getCapacidadButacas()) - $asientosDisponibles;
                        $proyeccionDao->updateAsientos($asientosDisponibles, $asientosOcupados, $proyeccion->getIdProyeccion());
                    }
                }
            } else {
                if ($idSala == $arrayProyecciones->getIdSala()) {
                    $asientosDisponibles = $sala->getCapacidadButacas();
                    $asientosOcupados = ($sala->getCapacidadButacas()) - $asientosDisponibles;
                    $proyeccionDao->updateAsientos($asientosDisponibles, $asientosOcupados, $proyeccion->getIdProyeccion());
                }
            }
        }
        $salaExist=false;
        $arraySalas = $this->getAll();
        if (!empty($arraySalas)) {
            if (is_array($arraySalas)) {
                foreach ($arraySalas as $value) {
                    if (strcasecmp($sala->getNombre(), $value->getNombre()) == 0 && $sala->getIdSala()!=$value->getIdSala()) {
                        $salaExist=true;
                    }
                }
            } else {
                if (strcasecmp($sala->getNombre(), $arraySalas->getNombre()) == 0 && $sala->getIdSala()!=$arraySalas->getIdSala()) {
                    $salaExist=true;
                }
            }
        }
        if(!$salaExist)
        {
            $sql = "UPDATE salas SET idCine = :idCine, nombre = :nombre, precio = :precio, capacidadButacas = :capacidadButacas, baja = :baja WHERE idSala = $idSala";
            $parameters['idCine'] = $sala->getIdCine();
            $parameters['nombre'] = $sala->getNombre();
            $parameters['precio'] = $cine->getPrecioEntrada();
            $parameters['capacidadButacas'] = $sala->getCapacidadButacas();
            $baja = $sala->getBaja();
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
        }else
            return -1;
    }
    public function updatePrecio($precio, $idSala)
    {
        $sql = "UPDATE salas SET precio = :precio WHERE idSala = $idSala";
        $parameters['precio'] = $precio;

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
    public function delete($idSala)
    {
        $sala = $this->read($idSala);
        $cineDao = new CineDao();
        $cine = $cineDao->read($sala->getIdCine());
        $capacidadTotal = $cine->getCapacidadTotal();
        $capacidadTotal = $capacidadTotal - $sala->getCapacidadButacas();
        $cineDao->updateCapacidad($capacidadTotal, $cine->getIdCine());

        $sql = "UPDATE salas SET baja = true where idSala = $idSala";
        $proyeccionDao = new ProyeccionDao();
        $arrayProyecciones = $proyeccionDao->getAll();

        if (!empty($arrayProyecciones)) {
            if (is_array($arrayProyecciones)) {
                foreach ($arrayProyecciones as $proyeccion) {
                    if ($idSala == $proyeccion->getIdSala()) {
                        $proyeccionDao->delete($proyeccion->getIdProyeccion());
                    }
                }
            } else {
                if ($idSala == $arrayProyecciones->getIdSala()) {
                    $proyeccionDao->delete($arrayProyecciones->getIdProyeccion());
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
            $sala = new Sala($p['idCine'], $p['nombre'], $p['precio'], $p['capacidadButacas']);
            if ($p['baja'] == 1)
                $sala->setBaja(true);
            else
                $sala->setBaja(false);
            $sala->setIdSala($p['idSala']);
            return $sala;
        }, $value);
        return count($resp) > 1 ? $resp : $resp['0'];
    }
}
