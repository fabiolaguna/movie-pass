<?php

namespace controllers;

use models\Proyeccion as Proyeccion;
use daoDB\ProyeccionDao as ProyeccionDao;
use controllers\SalaController as SalaController;
use models\Sala;

class ProyeccionController
{
    function __construct()
    {
    }

    public function index($msg = null)
    {
        include_once(VIEWS . '/header.php');
        if (($_SESSION['loggedRole']) == 'admin') {
            include_once(VIEWS . '/navAdmin.php');
            if ((!isset($_GET["idPelicula"])) && (!isset($_GET["idProyeccion"])) && (!isset($_SESSION["peliculaCartelera"])) && (!isset($_GET["action"])) && (!isset($_GET["date"])) && (!isset($_GET["genre"]))) {
                include_once(VIEWS . '/proyecciones.php');
            }
            if (isset($_GET["idProyeccion"]))
                include_once(VIEWS . '/proyeccionUpdate.php');
            if (isset($_GET["idPelicula"]))
                include_once(VIEWS . '/proyeccionAdd.php');
        }
        if (($_SESSION['loggedRole']) == 'cliente') {
            include_once(VIEWS . '/navClient.php');
            if (isset($_SESSION["peliculaCartelera"]))
                include_once(VIEWS . '/peliculaCartelera.php');
            if (isset($_GET["action"])) {
                if ($_GET["action"] == "consultarPeliculasFecha")
                    include_once(VIEWS . '/carteleraFecha.php');
                if ($_GET["action"] == "consultarPeliculasGenero")
                    include_once(VIEWS . '/carteleraGenero.php');
                if ($_GET["action"] == "consultarPeliculasFechaYGenero")
                    include_once(VIEWS . '/carteleraFechaYGenero.php');
            }
            if (isset($_GET["date"]) && !isset($_GET["genre"])) {
                $_SESSION["proyeccionFecha"] = $this->proyeccionesToCarteleraFecha($_GET["date"]);
                unset($_GET["date"]);
                include_once(VIEWS . '/carteleraFecha.php');
            }
            if (isset($_GET["genre"]) && !isset($_GET["date"])) {
                $_SESSION["proyeccionGenero"] = $this->proyeccionesToCarteleraGenero($_GET["genre"]);
                unset($_GET["genre"]);
                include_once(VIEWS . '/carteleraGenero.php');
            }
            if (isset($_GET["date"]) && isset($_GET["genre"])) {
                $_SESSION["proyeccionGeneroYFecha"] = $this->proyeccionesToCarteleraGeneroYFecha($_GET["genre"], $_GET["date"]);
                unset($_GET["genre"]);
                unset($_GET["date"]);
                include_once(VIEWS . '/carteleraFechaYGenero.php');
            }
        }
        include_once(VIEWS . '/footer.php');
    }
    public function agregarProyeccion($idSala, $fecha, $horario)
    {
        $idPelicula = ($_SESSION["idPelicula"]);
        $sala = SalaController::readSala($idSala);
        $proyeccion = new Proyeccion($idSala, $idPelicula, $sala->getCapacidadButacas(), '0', $fecha, $horario);
        $proyeccionDao = new ProyeccionDao();
        $msg = null;
        $msg = $proyeccionDao->add($proyeccion);
        if ($msg >= 0) {
            $msg = 'Agregada con exito';
        } else {
            if ($msg == -1)
                $msg = 'No se ha podido agregar. Una película no puede ser reproducida en más de una sala del cine';
            if ($msg == -2)
                $msg = 'No se ha podido agregar. Una película solo puede ser proyectada en un único cine por día';
            if ($msg == -3)
                $msg = 'No se ha podido agregar. Las funciones deben comenzar 15 minutos despues de la anterior';
        }
        $this->index($msg);
    }

    public function eliminarProyeccion()
    {
        $idProyeccion = null;
        if (isset($_GET["idProyeccion"])) {
            $idProyeccion = $_GET["idProyeccion"];
            unset($_GET["idProyeccion"]);
            $proyeccionDao = new ProyeccionDao();
            $msg = $proyeccionDao->delete($idProyeccion);
            if ($msg > 0)
                $msg = 'Proyeccion eliminada con exito';
            else
                $msg = 'No se ha podido eliminar la proyeccion';
            $this->index($msg);
        }
    }

    public function modificarProyeccion($idProyeccion, $idSala = null, $idPelicula = null, $asientosDisponibles = null, $fecha = null, $horario = null)
    {

        $proyeccionDao = new ProyeccionDao();
        $proyeccionAux = $proyeccionDao->read($idProyeccion);
        $capacidadExcedida = false;

        if ($idSala == null) {
            $idSala = $proyeccionAux->getIdSala();
        } else {
            /*$sala = SalaController::readSala($idSala); ESTO POR SI HACEMOS QUE SE SETEE CON LA CAPACIDAD DE LA SALA
            $asientosDisponibles = $sala->getCapacidadButacas();*/
        }
        if ($idPelicula == null) {
            $idPelicula = $proyeccionAux->getIdPelicula();
        }
        if ($asientosDisponibles == null) {
            $asientosDisponibles = $proyeccionAux->getAsientosDisponibles();
            $sala = SalaController::readSala($idSala);
            if ($asientosDisponibles > $sala->getCapacidadButacas()) {
                $capacidadExcedida = true;
            }
        } else {
            $sala = SalaController::readSala($idSala);
            if ($asientosDisponibles > $sala->getCapacidadButacas()) {
                $capacidadExcedida = true;
            }
        }
        if ($fecha == null) {
            $fecha = $proyeccionAux->getFecha();
        }
        if ($horario == null) {
            $horario = $proyeccionAux->getHorario();
        }

        $sala = SalaController::readSala($idSala);
        $asientosOcupados = ($sala->getCapacidadButacas()) - $asientosDisponibles;

        if (!$capacidadExcedida) {
            $proyeccion = new Proyeccion($idSala, $idPelicula, $asientosDisponibles, $asientosOcupados, $fecha, $horario);
            $msg = null;
            $msg = $proyeccionDao->update($proyeccion, $idProyeccion);
            if ($msg >= 0) {
                $msg = 'Modificada con exito';
            } else {
                if ($msg == -1) {
                    $msg = 'No se ha podido modificar. Una pelicula no puede ser reproducida en mas de una sala del cine';
                }
                if ($msg == -2) {
                    $msg = 'No se ha podido modificar. Una película solo puede ser proyectada en un único cine por día';
                }
                if ($msg == -3) {
                    $msg = 'No se ha podido modificar. Las funciones deben comenzar 15 minutos despues de la anterior';
                }
            }
        } else
            $msg = 'No se ha podido modificar, los asientos disponibles exceden la capacidad de la sala';

        $this->index($msg);
    }
    public function modificarAsientos($idProyeccion, $idSala, $asientosDisponibles)
    {
        $proyeccionDao = new ProyeccionDao();
        $sala = SalaController::readSala($idSala);
        $asientosOcupados = ($sala->getCapacidadButacas()) - $asientosDisponibles;
        $proyeccionDao->updateAsientos($asientosDisponibles, $asientosOcupados, $idProyeccion);
    }

    public static function listarProyecciones()
    {
        $proyeccionDao = new ProyeccionDao();
        $arrayAux = $proyeccionDao->getAll();
        $arrayProyecciones = array();
        if (!empty($arrayAux)) {
            if (is_array($arrayAux)) {
                foreach ($arrayAux as $value) {
                    if($value->getBaja()==false)
                        array_push($arrayProyecciones, $value);
                }
            } else {
                if($arrayAux->getBaja()==false)
                    array_push($arrayProyecciones, $arrayAux);
            }
            $_SESSION["proyecciones"] = $arrayProyecciones;
        }
    }

    public static function buscarProyeccion($idProyeccion)
    {

        $proyeccionDao = new ProyeccionDao();
        $proyeccion = null;
        $proyeccion = $proyeccionDao->read($idProyeccion);
        if (!empty($proyeccion))
            return $proyeccion;
    }

    public function proyeccionesToCartelera()
    {
        $peliculaController = new PeliculaController();
        $proyeccionDao = new ProyeccionDao();
        $proyeccionesList = $proyeccionDao->getAll();
        $cartelera = null;
        $i = 0;
        if (!empty($proyeccionesList)) {
            if (is_array($proyeccionesList)) {
                foreach ($proyeccionesList as $proyeccion) {
                    $idPelicula = $proyeccion->getIdPelicula();
                    $pelicula = $peliculaController->buscarPelicula($idPelicula);
                    if ($pelicula != null) {
                        if ($cartelera == null) {
                            $cartelera[$i]["titulo"] = $pelicula->getNombrePelicula();
                            $cartelera[$i]["imagen"] = $pelicula->getImagen();
                            $cartelera[$i]["poster"] = $pelicula->getPoster();
                            $cartelera[$i]["idPelicula"] = $pelicula->getIdPelicula();
                            $cartelera[$i]["generos"] = CategoriaController::idToGenreName($pelicula->getIdCategoria());
                            $cartelera[$i]["fecha"] = $proyeccion->getFecha();
                            $i++;
                        } else {
                            $rta = true;
                            $cant = count($cartelera);
                            for ($j = 0; $j < $cant; $j++) {
                                if ($pelicula->getIdPelicula() == $cartelera[$j]["idPelicula"]) {
                                    $rta = false;
                                }
                            }
                            if ($rta) {
                                $cartelera[$i]["titulo"] = $pelicula->getNombrePelicula();
                                $cartelera[$i]["imagen"] = $pelicula->getImagen();
                                $cartelera[$i]["poster"] = $pelicula->getPoster();
                                $cartelera[$i]["idPelicula"] = $pelicula->getIdPelicula();
                                $cartelera[$i]["generos"] = CategoriaController::idToGenreName($pelicula->getIdCategoria());
                                $cartelera[$i]["fecha"] = $proyeccion->getFecha();
                                $i++;
                            }
                        }
                    }
                }
            } else {
                $idPelicula = $proyeccionesList->getIdPelicula();
                $pelicula = $peliculaController->buscarPelicula($idPelicula);
                if ($pelicula != null) {
                    if ($cartelera == null) {
                        $cartelera[$i]["titulo"] = $pelicula->getNombrePelicula();
                        $cartelera[$i]["imagen"] = $pelicula->getImagen();
                        $cartelera[$i]["poster"] = $pelicula->getPoster();
                        $cartelera[$i]["idPelicula"] = $pelicula->getIdPelicula();
                        $cartelera[$i]["generos"] = CategoriaController::idToGenreName($pelicula->getIdCategoria());
                        $cartelera[$i]["fecha"] = $proyeccionesList->getFecha();
                        $i++;
                    } else {
                        $rta = true;
                        $cant = count($cartelera);
                        for ($j = 0; $j < $cant; $j++) {
                            if ($pelicula->getIdPelicula() == $cartelera[$j]["idPelicula"]) {
                                $rta = false;
                            }
                        }
                        if ($rta) {
                            $cartelera[$i]["titulo"] = $pelicula->getNombrePelicula();
                            $cartelera[$i]["imagen"] = $pelicula->getImagen();
                            $cartelera[$i]["poster"] = $pelicula->getPoster();
                            $cartelera[$i]["idPelicula"] = $pelicula->getIdPelicula();
                            $cartelera[$i]["generos"] = CategoriaController::idToGenreName($pelicula->getIdCategoria());
                            $cartelera[$i]["fecha"] = $proyeccionesList->getFecha();
                            $i++;
                        }
                    }
                }
            }
        }

        return $cartelera;
    }
    public function proyeccionesToCarteleraGenero($genero)
    {
        $cartelera = $this->proyeccionesToCartelera();
        $carteleraGenero = array();
        if ($cartelera != null) {
            $cant = count($cartelera);
            for ($i = 0; $i < $cant; $i++) {
                $cantGeneros = count($cartelera[$i]["generos"]);
                for ($j = 0; $j < $cantGeneros; $j++) {
                    if ($cartelera[$i]["generos"][$j] == $genero)
                        array_push($carteleraGenero, $cartelera[$i]);
                }
            }
        }
        return $carteleraGenero;
    }
    public function proyeccionesToCarteleraFecha($fecha)
    {
        $cartelera = $this->proyeccionesToCartelera();
        $carteleraFecha = array();
        if ($cartelera != null) {
            $cant = count($cartelera);
            $carteleraFecha = array();
            for ($i = 0; $i < $cant; $i++) {
                if ($fecha <= $cartelera[$i]["fecha"])
                    array_push($carteleraFecha, $cartelera[$i]);
            }
        }
        return $carteleraFecha;
    }
    public function proyeccionesToCarteleraGeneroYFecha($genero, $fecha)
    {
        $cartelera = $this->proyeccionesToCartelera();
        $carteleraGeneroYFecha = array();
        if ($cartelera != null) {
            $cant = count($cartelera);
            $carteleraGeneroYFecha = array();
            for ($i = 0; $i < $cant; $i++) {
                $cantGeneros = count($cartelera[$i]["generos"]);
                for ($j = 0; $j < $cantGeneros; $j++) {
                    if ($cartelera[$i]["generos"][$j] == $genero && $fecha <= $cartelera[$i]["fecha"])
                        array_push($carteleraGeneroYFecha, $cartelera[$i]);
                }
            }
        }
        return $carteleraGeneroYFecha;
    }
    public function proyeccionDePelicula()
    {
        $idPelicula = $_GET["idPelicula"];
        unset($_GET["idPelicula"]);
        $peliculaController = new PeliculaController();
        $proyeccionDao = new ProyeccionDao();
        $idSala = array();
        $proyeccionesList = $proyeccionDao->getAll();
        $proyecciones = array();
        if (is_array($proyeccionesList)) {
            foreach ($proyeccionesList as $proyeccion) {
                if ($idPelicula == $proyeccion->getIdPelicula() && ($proyeccion->getAsientosDisponibles() > 0)) {
                    array_push($proyecciones, $proyeccion);
                }
            }
        } else {
            if ($idPelicula == $proyeccionesList->getIdPelicula() && ($proyeccionesList->getAsientosDisponibles() > 0)) {
                array_push($proyecciones, $proyeccionesList);
            }
        }

        foreach ($proyecciones as $proyeccion) {
            if (empty($idSala))
                array_push($idSala, $proyeccion->getIdSala());
            else {
                for ($i = 0; $i < count($idSala); $i++) {
                    if (!(in_array($proyeccion->getIdSala(), $idSala)))
                        array_push($idSala, $proyeccion->getIdSala());
                }
            }
        }
        $pelicula = $peliculaController->buscarPelicula($idPelicula);
        $nombrePelicula = $pelicula->getNombrePelicula();
        $descripcion = $pelicula->getDescripcion();
        $imagen = $pelicula->getImagen();
        $poster = $pelicula->getPoster();
        $generos = CategoriaController::idToGenreName($pelicula->getIdCategoria());
        $salas = array();
        for ($i = 0; $i < count($idSala); $i++) {
            array_push($salas, SalaController::readSala($idSala[$i]));
        }
        $datosAMostrar = array();
        $datosAMostrar["titulo"] = $nombrePelicula;
        $datosAMostrar["descripcion"] = $descripcion;
        $datosAMostrar["imagen"] = $imagen;
        $datosAMostrar["poster"] = $poster;
        $datosAMostrar["generos"] = $generos;
        $datosAMostrar["salas"] = $salas;
        $datosAMostrar["proyecciones"] = $proyecciones;

        $_SESSION["peliculaCartelera"] = $datosAMostrar;
        $this->index();
    }
}
