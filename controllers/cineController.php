<?php

namespace controllers;

use daojson\CineDao as CineDao;
use models\Cine as Cine;
use models\Direccion as Direccion;

class CineController
{
    function __construct()
    { }
    
    public function index($msg = null)
    {
        include_once(VIEWS . '/header.php');
        include_once(VIEWS . '/navAdmin.php');
        if (!isset($_GET["action"]))
            include_once(VIEWS . '/cine.php');
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "agregarCine")
                include_once(VIEWS . '/cineAdd.php');
            if ($_GET["action"] == "modificarCine")
                include_once(VIEWS . '/cineUpdate.php');
            unset($_GET["action"]);
        }
        include_once(VIEWS . '/footer.php');
    }
    public function agregarCine($nombre, $provincia, $ciudad, $calle, $altura, $precio)
    { 
        $direccion = new Direccion($provincia, $ciudad, $calle, $altura);
        $cine = new Cine($direccion, $nombre, 0, $precio);
        $cineDao = new CineDao();
        $msg = $cineDao->add($cine);
        if ($msg > 0)
            $msg = 'Cine agregado con exito';
        else 
            $msg = 'No se ha podido agregar el cine, la direccion cargada ya existe';
        $this->index($msg);
    }
    public function eliminarCine()
    {
        $idCine = null;
        if (isset($_GET["idCine"])) {
            $idCine = $_GET["idCine"];
            $cineDao = new CineDao();
            $msg = $cineDao->delete($idCine);
            if ($msg > 0)
            $msg = 'Cine eliminado con exito';
            else 
            $msg = 'No se ha podido eliminar el cine';
            $this->index($msg);
        }
    }
    public function modificarCine($idCine, $nombre = null, $precio = null, $provincia = null, $ciudad = null, $calle = null, $altura = null) 
    {
        $cineDao = new CineDao();
        //Busca el cine a modificar (read) para setear los valores nulos con los que el cine tenia cargados desde antes
        $cineAux = $cineDao->read($idCine); //implementarlo en json (y modificar el metodo de update)
        $idDirec = $cineAux->getDireccion()->getIdDireccion();

        if ($nombre == null){
            $nombre = $cineAux->getNombre();
        }            
        
        $capacidad = $cineAux->getCapacidadTotal();
        
        if ($precio == null){
            $precio = $cineAux->getPrecioEntrada();
        }
        if ($provincia == null){
            $provincia = $cineAux->getDireccion()->getProvincia();
        }
        if ($ciudad == null){
            $ciudad = $cineAux->getDireccion()->getCiudad();
        }
        if ($calle == null){
            $calle = $cineAux->getDireccion()->getCalle();
        }
        if ($altura == null){
            $altura = $cineAux->getDireccion()->getAltura();
        }

        $direc = new Direccion($provincia, $ciudad, $calle, $altura);
        $direc->setIdDireccion($idDirec);
        
        $cine = new Cine ($direc, $nombre, $capacidad, $precio);
        $msg = null;
        $msg = $cineDao->update($cine, $idCine);

        if ($msg == -1){
            $msg = 'No se pudo modificar, la direccion ya existe';
        } else{
            $msg = 'Modificado con exito';
        }
        $this->index($msg);
    }
    public static function listarCines()
    {
        $cineDao = new CineDao();
        $arrayAux = $cineDao->getAll();
        $arrayCines = array();
        if (!empty($arrayAux)){
            if (is_array($arrayAux)){
                foreach ($arrayAux as $value) {
                    if ($value->getBaja() == false)
                        array_push($arrayCines, $value);
                }
            } else{
                if ($arrayAux->getBaja() == false)
                    array_push($arrayCines, $arrayAux);
            }   
            $_SESSION["cines"] = $arrayCines;
        }
    }
    
    public function buscarCine($id)
    {
        $cineDao = new CineDao();
        $arrayAux = $cineDao->getAll();
        $cine = null;
        foreach ($arrayAux as $value) {
            if ($value->getIdCine() == $id)
                $cine = $value;
        }
        return $cine;
    }

    public static function readCine($idCine){

        $cineDao = new CineDao();
        $cine = $cineDao->read($idCine);
        return $cine;
    }
}
