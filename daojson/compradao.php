<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\Compra as Compra;

class CompraDao implements IDao
{
    private $comprasList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/compras.json";
    }
    //$idTarjetaCredito, $idCliente, $cantEntradas, $descuento, $fecha, $total
    public function add($compra)
    {
        $this->comprasList=array();
        $this->retrieveData();
        $indice = 1;
        if (!empty($this->comprasList)) {
            $indice = count($this->comprasList) + 1;
        }
        $compra->setIdCompra($indice);
        array_push($this->comprasList, $compra);
        $this->saveData();
        $successMje = 'Agregado con éxito';
        return $successMje;
    }

    public function getAll()
    {
        $this->comprasList=array();
        $this->retrieveData();
        return $this->comprasList;
    }

    public function read($idCompra)
    {
        $this->comprasList=array();
        $this->retrieveData();
        $value = null;
        foreach ($this->comprasList as $compra) {
            if ($compra->getIdCompra() == $idCompra)
                $value = $compra;
        }
        return $value;
    }

    public function delete($idCompra) 
    {
        $this->comprasList=array();
        $this->retrieveData();
        foreach ($this->comprasList as $key => $compra) {
            if ($compra->getIdCompra() == $idCompra)
                $compra->setBaja(true);
        }
        $this->saveData();
    }
    public function lastIdInsert()
    {
        $this->comprasList=array();
        $this->retrieveData();
        $indice = null;
        if (!empty($this->comprasList)) {
            $indice = count($this->comprasList);
        }
        return $indice;
    }
    public function update($compra, $idCompra)//no voy a editar una compra
    {
    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->comprasList as $compra) {
            $valuesArray["idCompra"] = $compra->getIdCompra();
            $valuesArray["tarjetaCredito"] = $compra->getIdTarjetaCredito();
            $valuesArray["idCliente"] = $compra->getIdCliente();
            $valuesArray["cantEntradas"] = $compra->getCantEntradas();
            $valuesArray["descuento"] = $compra->getDescuento();
            $valuesArray["fecha"] = $compra->getFecha();
            $valuesArray["total"] = $compra->getTotal();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/compras.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->comprasList = array();

        if (file_exists('Data/compras.json')) {
            $jsonContent = file_get_contents('Data/compras.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $compra = new Compra($valuesArray["tarjetaCredito"], $valuesArray["idCliente"], $valuesArray["cantEntradas"], $valuesArray["descuento"], $valuesArray["fecha"], $valuesArray["total"]);
                $compra->setIdCompra($valuesArray["idCompra"]);
                array_push($this->comprasList, $compra);
            }
        }
    }
}
