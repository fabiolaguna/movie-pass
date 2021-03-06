<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\Entrada as Entrada;

class EntradaDao implements IDao
{
    private $entradaList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/entrada.json";
    }

    public function add($entrada)
    {
        $this->entradaList=array();
        $this->retrieveData();
        $indice = 1;
        if (!empty($this->entradaList)) {
            $indice = count($this->entradaList) + 1;
        }
        $entrada->setIdEntrada($indice);
        array_push($this->entradaList, $entrada);
        $this->saveData();
        $successMje = 'Agregado con éxito';
        return $successMje;
    }

    public function getAll()
    {
        $this->entradaList=array();
        $this->retrieveData();
        return $this->entradaList;
    }

    public function read($idEntrada)
    {
        $this->entradaList=array();
        $this->retrieveData();
        $value = null;
        foreach ($this->entradaList as $entrada) {
            if ($entrada->getIdEntrada() == $idEntrada)
                $value = $entrada;
        }
        return $value;
    }
    
    public function readProyeccion($idProyeccion)
    {
        $this->entradaList=array();
        $this->retrieveData();
        $value = false;
        foreach ($this->entradaList as $entrada) {
            if ($entrada->getIdProyeccion() == $idProyeccion)
                $value = $entrada;
        }
        return $value;
    }

    public function delete($idEntrada) 
    {
        $this->entradaList=array();
        $this->retrieveData();
        foreach ($this->entradaList as $key => $entrada) {
            if ($entrada->getIdEntrada() == $idEntrada)
                $entrada->setBaja(true);
        }
        $this->saveData();
    }

    public function deleteAbsolut($idEntrada) 
    {
        $this->entradaList=array();
        $this->retrieveData();
        foreach ($this->entradaList as $key => $entrada) {
            if ($entrada->getIdEntrada() == $idEntrada)
                unset($this->entradaList[$key]);
        }
        $this->saveData();
    }

    public function update($direccion, $idDireccion) //no voy a editar una entrada
    { }

    public function lastIdInsert(){
        $this->entradaList=array();
        $this->retrieveData();
        $indice = null;
        if (!empty($this->entradaList)) {
            $indice = count($this->entradaList);
        }
        return $indice;
    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->entradaList as $entrada) {
            $valuesArray["idEntrada"] = $entrada->getIdEntrada();
            $valuesArray["idProyeccion"] = $entrada->getIdProyeccion();
            $valuesArray["idCliente"] = $entrada->getIdCliente();
            $valuesArray["idCompra"] = $entrada->getIdCompra();
            $valuesArray["codigoQR"] = $entrada->getCodigoQR();
            $valuesArray["baja"] = $entrada->getBaja();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/entrada.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->entradaList = array();

        if (file_exists('Data/entrada.json')) {
            $jsonContent = file_get_contents('Data/entrada.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $entrada = new Entrada($valuesArray["idProyeccion"], $valuesArray["idCliente"], $valuesArray["idCompra"]);
                $entrada->setIdEntrada($valuesArray["idEntrada"]);
                $entrada->setCodigoQr($valuesArray["codigoQR"]);
                $entrada->setBaja($valuesArray["baja"]);
                array_push($this->entradaList, $entrada);
            }
        }
    }
}