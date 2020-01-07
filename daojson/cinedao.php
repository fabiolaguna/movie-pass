<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\Cine as Cine;
use models\Direccion as Direccion;
use daoDB\Direccion as daoDireccion;
use daoDB\SalaDao as salaDao;

class CineDao implements IDao
{
    private $cinesList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/cines.json";
    }

    public function add($cine) //Fijarse que no este repetido y si esta eliminado, nomas cambiar baja a true
    {
        $this->cinesList=array();
        $this->retrieveData();
        $direccionDao = new daoDireccion();
        $cineExist = false;
        if (!empty($this->cinesList)){
            foreach ($this->cinesList as $value) { //Lo cambiamos a solo por direccion porque si agregabamos un cine con una direccion existente pero con un nombre distinto, lo agregaba igual (no se pueden repetir direcciones). CAMBIARLO EN EL DAO JSON
                $direc = $direccionDao->read($value->getDireccion());
                $value->setDireccion($direc);
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
                        foreach ($salas as $sal){
                            if ($value->getIdCine() == $sal->getIdCine())     
                                $capacidad = $capacidad + ($sal->getCapacidadButacas());
                        }
                        $value->setCapacidadTotal($capacidad);
                        return $this->update($value, -1);
                    } else
                        $cineExist = true;
                }
            }
        }

        $successMje = 0;
        if (!$cineExist){
            $direccion = new Direccion($cine->getDireccion()->getProvincia(), $cine->getDireccion()->getCiudad(), $cine->getDireccion()->getCalle(), $cine->getDireccion()->getAltura());
            $daoDir = new daoDireccion();
            $daoDir->add($direccion); //No es necesario hacer validacion en el add porque la direccion ya llega verificada de que no se repita
            $direcciones = $daoDir->getAll();
            $idDireccion = null;
            if (!empty($direcciones)) {
                $cant = count($direcciones);
                $idDireccion = $cant;
            } else {
                $idDireccion = 1;
            }
            $indice = 1;
            if (!empty($this->cinesList)) {
                $indice = count($this->cinesList) + 1;
            }
            $cine->setIdCine($indice);
            $cine->setDireccion($idDireccion);
            array_push($this->cinesList, $cine);
            $successMje = 1;
        }
        if (!empty($this->cinesList)){
            $i=1;
            foreach ($this->cinesList as $key => $val){
                $this->cinesList[$key]->setDireccion($i);
                $i++;
            }
        }

        $this->saveData();
        return $successMje;
    }

    public function getAll()
    {
        $this->cinesList=array();
        $this->retrieveData();
        $direccionDao = new daoDireccion();
        $arrayCines = array();
        foreach ($this->cinesList as $cine){
            $direccion = $direccionDao->read($cine->getDireccion());
            $cine->setDireccion($direccion);
            array_push($arrayCines, $cine);
        }
        return $this->cinesList;
    }

    public function read($idCine){
        $this->cinesList=array();
        $this->retrieveData();
        $direccionDao = new daoDireccion();
        $value = null;
        foreach ($this->cinesList as $cine) {
            if ($cine->getIdCine() == $idCine){
                $direccion = $direccionDao->read($cine->getDireccion());
                $cine->setDireccion($direccion);
                $value = $cine;
            }
        }
        return $value;
    }

    public function delete($idCine) 
    {
        $this->cinesList=array();
        $this->retrieveData();
        $msg = 0;
        foreach ($this->cinesList as $cine) {
            if ($cine->getIdCine() == $idCine) {
                $cine->setBaja(true);
                $msg = 1;
            }
        }

        $salaDao = new SalaDao();
        $arraySalas = $salaDao->getAll();
        if (!empty($arraySalas)){
            foreach ($arraySalas as $sala) {
                if ($idCine == $sala->getIdCine()) {
                    $salaDao->delete($sala->getIdSala());
                }
            }
        }
        $this->saveData();

        return $msg;
    }

    public function updateCapacidad($capacidadTotal, $idCine){
        $this->cinesList=array();
        $this->retrieveData();
        foreach ($this->cinesList as $key => $cine){
            if ($cine->getIdCine() == $idCine){
                $this->cinesList[$key]->setCapacidadTotal($capacidadTotal);
            }
        }

        $this->saveData();
    }

    public function update($cine, $idCine){

        $flag = null;
        if ($idCine > 0) { //Cuando viene de cineController/modificarCine, te trae el id del cine a modificar (>0)
            $daoDir = new daoDireccion();
            $flag = 0;
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
            foreach ($salasList as $value) {
                if ($value->getIdCine() == $idCine)
                    array_push($salasCine, $value);
            }
        }

        foreach ($salasCine as $sala) {
            $salaDao->updatePrecio($cine->getPrecioEntrada(), $sala->getIdSala());
        }

        if ($flag >= 0) {
            $this->cinesList=array();
            $this->retrieveData();
            $i = 0;
            $j = 0;

            foreach ($this->cinesList as $value) {
                if ($value->getIdCine() == $idCine)
                    $j = $i;
                $i++;
            }
            if (isset($cine) && !empty($cine)) {
                foreach ($this->cinesList as $key => $value){
                    if ($key == $j){
                        $this->cinesList[$key]->setBaja($cine->getBaja());
                        $this->cinesList[$key]->setCapacidadTotal($cine->getCapacidadTotal());
                        $this->cinesList[$key]->setPrecioEntrada($cine->getPrecioEntrada());
                        $this->cinesList[$key]->setNombre($cine->getNombre());
                    }
                }
            }

            $this->saveData();
            return 1;
        }
    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->cinesList as $cine) {
            $valuesArray["id"] = $cine->getIdCine();
            $valuesArray["idDireccion"] = $cine->getDireccion();
            $valuesArray["nombre"] = $cine->getNombre();
            $valuesArray["capacidad"] = $cine->getCapacidadTotal();
            $valuesArray["precio"] = $cine->getPrecioEntrada();
            $valuesArray["baja"] = $cine->getBaja();
            
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
        
        file_put_contents('data/cines.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->cinesList = array();

        if (file_exists('data/cines.json')) {
            $jsonContent = file_get_contents('data/cines.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();
            foreach ($arrayToDecode as $valuesArray) {
                $cine = new Cine($valuesArray["idDireccion"], $valuesArray["nombre"], $valuesArray["capacidad"], $valuesArray["precio"]);
                $cine->setIdCine($valuesArray["id"]);
                $cine->setBaja($valuesArray["baja"]);
                array_push($this->cinesList, $cine);
            }
        }
    }
}
