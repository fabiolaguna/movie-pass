<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\Sala as Sala;
use daoDB\CineDao as CineDao;
use daoDB\ProyeccionDao as ProyeccionDao;

class SalaDao implements IDao
{
    private $salaList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/sala.json";
    }

    public function add($sala)
    {
        $this->salaList=array();
        $this->retrieveData();
        $salaExist = false;
        if (!empty($this->salaList)){
            foreach ($this->salaList as $value) {
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
        }

        $successMje = 0;
        if (!$salaExist){

            // AGREGA LA CAPACIDAD AL CINE ACA CUANDO AGREGO UNA SALA
            $cineDao = new CineDao();
            $cine = $cineDao->read($sala->getIdCine());
            $capacidadTotal = $cine->getCapacidadTotal();
            $capacidadTotal += $sala->getCapacidadButacas();
            $cineDao->updateCapacidad($capacidadTotal, $cine->getIdCine());

            $indice = 1;
            if (!empty($this->salaList)) {
                $indice = count($this->salaList) + 1;
            }
            $sala->setIdSala($indice);
            array_push($this->salaList, $sala);
            $this->saveData();
            $successMje = 1;
        }

        $this->saveData();
        return $successMje;
    }

    public function getAll()
    {
        $this->salaList=array();
        $this->retrieveData();
        return $this->salaList;
    }

    public function read($idSala)
    {
        $this->salaList=array();
        $this->retrieveData();
        $value = null;
        foreach ($this->salaList as $sala) {
            if ($sala->getIdSala() == $idSala)
                $value = $sala;
        }
        return $value;
    }

    public function darAlta($idCine){ //Cuando se vuelve a agregar un cine que estaba eliminado usamos este metodo
        $this->salaList=array();
        $this->retrieveData();
        foreach ($this->salaList as $key => $sala){
            if ($sala->getIdCine() == $idCine){
                $this->salaList[$key]->setBaja(false);
            }
        }

        $this->saveData();
    }

    public function delete($idSala) 
    { 
        $sala = $this->read($idSala);
        $cineDao = new CineDao();
        $cine = $cineDao->read($sala->getIdCine());
        $capacidadTotal = $cine->getCapacidadTotal();
        $capacidadTotal = $capacidadTotal - $sala->getCapacidadButacas();
        $cineDao->updateCapacidad($capacidadTotal, $cine->getIdCine());
        $msg = 0;

        $proyeccionDao = new ProyeccionDao();
        $arrayProyecciones = $proyeccionDao->getAll();

        if (!empty($arrayProyecciones)){
            foreach ($arrayProyecciones as $proyeccion) {
                if ($idSala == $proyeccion->getIdSala()) {
                    $proyeccionDao->delete($proyeccion->getIdProyeccion());
                }
            }
        }
        
        $this->salaList=array();
        $this->retrieveData();

        foreach ($this->salaList as $key => $value) {
            if ($value->getIdSala() == $idSala)
                $this->salaList[$key]->setBaja(true);
            $msg = 1;
        }

        $this->saveData();
        return $msg;
    }

    public function update($sala, $idSala) 
    {
        $salaVieja = $this->read($idSala);
        $cineDao = new CineDao();
        $cineViejo = $cineDao->read($salaVieja->getIdCine());
        $cine = $cineDao->read($sala->getIdCine());
        $msg = null;

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
            foreach ($arrayProyecciones as $proyeccion) {
                if ($idSala == $proyeccion->getIdSala()) {
                    $asientosDisponibles = $sala->getCapacidadButacas();
                    $asientosOcupados = ($sala->getCapacidadButacas()) - $asientosDisponibles;
                    $proyeccionDao->updateAsientos($asientosDisponibles, $asientosOcupados, $proyeccion->getIdProyeccion());
                }
            }    
        }
        $this->salaList=array();
        $this->retrieveData();
        $i = 0;
        $j = 0;
        $salaExist = false;
        foreach ($this->salaList as $value) {
            if ($idSala != $value->getIdSala()) { //$sala->getIdSala()
                if ((strcasecmp($sala->getNombre(), $value->getNombre()) == 0) && $sala->getIdCine() == $value->getIdCine()) {
                    $salaExist = true;
                    return -1;
                }
            }
        }
        if (!$salaExist) {
            foreach ($this->salaList as $value) {
                if ($value->getIdSala() == $idSala)
                    $j = $i;
                $i++;
            }
        
            if (isset($sala) && !empty($sala)) {
                foreach ($this->salaList as $key => $value){
                    if ($key == $j){
                        $this->salaList[$key]->setIdCine($sala->getIdCine());
                        $this->salaList[$key]->setNombre($sala->getNombre());
                        $this->salaList[$key]->setPrecio($sala->getPrecio());
                        $this->salaList[$key]->setCapacidadButacas($sala->getCapacidadButacas());
                        $this->salaList[$key]->setBaja($sala->getBaja());
                    }
                }
            }
            
            $msg = 1;
        }

        $this->saveData();
        return $msg;
    }

    public function updatePrecio($precio, $idSala){
        $this->salaList=array();
        $this->retrieveData();
        $i = 0;
        $j = 0;
        foreach ($this->salaList as $value) {
            if ($value->getIdSala() == $idSala)
                $j = $i;
            $i++;
        }

        $this->salaList[$j]->setPrecio($precio);
        $this->saveData();
    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->salaList as $sala) {
            $valuesArray["idSala"] = $sala->getIdSala();
            $valuesArray["idCine"] = $sala->getIdCine();
            $valuesArray["nombre"] = $sala->getNombre();
            $valuesArray["precio"] = $sala->getPrecio();
            $valuesArray["capacidadButacas"] = $sala->getCapacidadButacas();
            $valuesArray["baja"] = $sala->getBaja();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/sala.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->salaList = array();

        if (file_exists('Data/sala.json')) {
            $jsonContent = file_get_contents('Data/sala.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $sala = new Sala($valuesArray["idCine"], $valuesArray["nombre"], $valuesArray["precio"], $valuesArray["capacidadButacas"]);
                $sala->setIdSala($valuesArray["idSala"]);
                $sala->setBaja($valuesArray["baja"]);
                array_push($this->salaList, $sala);
            }
        }
    }
}