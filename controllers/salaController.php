<?php

namespace controllers;

use daoDB\SalaDao as SalaDao;
use models\Sala as Sala;
use controllers\CineController as CineController;

class SalaController
{
    function __construct()
    { }

    public function index($msg = null)
    {
        include_once(VIEWS . '/header.php');
        include_once(VIEWS . '/navAdmin.php');
        if (!isset($_GET["action"]))
            include_once(VIEWS . '/sala.php');
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "agregarSala")
                include_once(VIEWS . '/salaAdd.php');
            if ($_GET["action"] == "modificarSala")
                include_once(VIEWS . '/salaUpdate.php');
            unset($_GET["action"]);
        }
        include_once(VIEWS . '/footer.php');
    }
    public function agregarSala($idCine, $nombre, $capacidadButacas)
    {
        $cine = CineController::readCine($idCine);
        $sala = new Sala($idCine, $nombre, $cine->getPrecioEntrada(), $capacidadButacas);
        $salaDao = new SalaDao();
        $msg = $salaDao->add($sala);
        if ($msg > 0)
            $msg = 'Sala agregada con exito';
        else
            $msg = 'No se ha podido agregar la sala, sala ya existente en este cine.';
        $this->index($msg);
    }
    public function eliminarSala()
    {
        if (isset($_GET["idSala"])) {
            $idSala = $_GET["idSala"];
            $salaDao = new SalaDao();
            $msg = $salaDao->delete($idSala);
            if ($msg > 0)
                $msg = 'Sala eliminada con exito';
            else
                $msg = 'No se ha podido eliminar la sala';
            $this->index($msg);
        }
    }
    public function modificarSala($idSala, $idCine = null, $nombre = null, $capacidadButacas = null)
    {
        $salaDao = new SalaDao();
        $salaAux = $salaDao->read($idSala);

        if ($idCine == null) {
            $idCine = $salaAux->getIdCine();
        }
        if ($nombre == null) {
            $nombre = $salaAux->getNombre();
        }
            
        $precio = $salaAux->getPrecio();
        
        if ($capacidadButacas == null) {
            $capacidadButacas = $salaAux->getCapacidadButacas();
        }
        $sala = new Sala($idCine, $nombre, $precio, $capacidadButacas);
        $sala->setIdSala($idSala);
        $msg = null;
        $msg = $salaDao->update($sala, $idSala);

        if ($msg == -1) {
            $msg = 'No se ha podido modificar, sala existente';
        } else {
            $msg = 'Sala modificada con exito';
        }

        $this->index($msg);
    }
    public function listarSalas()
    {
        $salaDao = new SalaDao();
        $arrayAux = $salaDao->getAll();
        $arraySalas = array();
        if (!empty($arrayAux)) {
            if (is_array($arrayAux)) {
                foreach ($arrayAux as $value) {
                    if ($value->getBaja() == false)
                        array_push($arraySalas, $value);
                }
            } else {
                if ($arrayAux->getBaja() == false)
                    array_push($arraySalas, $arrayAux);
            }
        }
        return $arraySalas;
    }
    public static function readSala($id)
    {
        $salaDao = new SalaDao();
        $sala = $salaDao->read($id);
        return $sala;
    }

}
