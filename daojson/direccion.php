<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\Direccion as Direc;

class Direccion implements IDao
{    
    private $direccionList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/direccion.json";
    }

    public function add($direccion)
    {
        $this->direccionList=array();
        $this->retrieveData();
        $indice = 1;
        if (!empty($this->direccionList)) {
            $indice = count($this->direccionList) + 1;
        }
        $direccion->setIdDireccion($indice);
        array_push($this->direccionList, $direccion);
        $this->saveData();
        $successMje = 'Agregado con éxito';
        return $successMje;
    }

    public function getAll()
    {
        $this->direccionList=array();
        $this->retrieveData();
        return $this->direccionList;
    }

    public function read($idDireccion)
    {
        $this->direccionList=array();
        $this->retrieveData();
        $value = null;
        foreach ($this->direccionList as $direccion) {
            if ($direccion->getIdDireccion() == $idDireccion)
                $value = $direccion;
        }
        return $value;
    }

    public function delete($value) //No es necesario hacer un delete para direcciones, porque nunca existen por si solas y los cines siempre van a tener una direccion
    { }

    public function update($direccion, $idDireccion) //traer por GET en un arreglo los valores a modificar, que lo conseguis con un checkbox
    {
        $this->direccionList=array();
        $this->retrieveData();
        $i = 0;
        $j = 0;
        $direccionExist = false;
        foreach ($this->direccionList as $value) {
            if ($direccion->getIdDireccion() != $value->getIdDireccion()) {
                if ((strcasecmp($direccion->getProvincia(), $value->getProvincia()) == 0) && (strcasecmp($direccion->getCiudad(), $value->getCiudad()) == 0) && (strcasecmp($direccion->getCalle(), $value->getCalle()) == 0) && ($direccion->getAltura() == $value->getAltura())) {
                    $direccionExist = true;
                    return -1;
                }
            }
        }
        if (!$direccionExist) {
            foreach ($this->direccionList as $value) {
                if ($value->getIdDireccion() == $idDireccion)
                    $j = $i;
                $i++;
            }
            if (isset($direccion) && !empty($direccion)) {
                foreach ($this->direccionList as $key => $value){
                    if ($key == $j){
                        $this->direccionList[$key]->setCalle($direccion->getCalle());
                        $this->direccionList[$key]->setAltura($direccion->getAltura());
                        $this->direccionList[$key]->setProvincia($direccion->getProvincia());
                        $this->direccionList[$key]->setCiudad($direccion->getCiudad());
                    }
                }
            }
        }
        
        $this->saveData();

    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->direccionList as $direccion) {
            $valuesArray["idDireccion"] = $direccion->getIdDireccion();
            $valuesArray["calle"] = $direccion->getCalle();
            $valuesArray["altura"] = $direccion->getAltura();
            $valuesArray["provincia"] = $direccion->getProvincia();
            $valuesArray["ciudad"] = $direccion->getCiudad();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/direccion.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->direccionList = array();

        if (file_exists('Data/direccion.json')) {
            $jsonContent = file_get_contents('Data/direccion.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $direccion = new Direc($valuesArray["provincia"], $valuesArray["ciudad"], $valuesArray["calle"], $valuesArray["altura"]);
                $direccion->setIdDireccion($valuesArray["idDireccion"]);
                array_push($this->direccionList, $direccion);
            }
        }
    }
}
