<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\PagoTC as PagoTC;

class PagoTCDao implements IDao
{
    private $pagoList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/pagoTC.json";
    }

    public function add($pago)
    {
        $this->pagoList=array();
        $this->retrieveData();
        $indice = 1;
        if (!empty($this->pagoList)) {
            $indice = count($this->pagoList) + 1;
        }
        $pago->setIdPagoTC($indice);
        array_push($this->pagoList, $pago);
        $this->saveData();
        $successMje = 'Agregado con éxito';
        return $successMje;
    }

    public function getAll()
    {
        $this->pagoList=array();
        $this->retrieveData();
        return $this->pagoList;
    }

    public function read($idPagoTC)
    {
        $this->pagoList=array();
        $this->retrieveData();
        $value = null;
        foreach ($this->pagoList as $pago) {
            if ($pago->getIdPagoTC() == $idPagoTC)
                $value = $pago;
        }
        return $value;
    }

    public function delete($idPagoTC) 
    { 
        $this->pagoList=array();
        $this->retrieveData();
        foreach ($this->pagoList as $key => $pago) {
            if ($pago->getIdPagoTC() == $idPagoTC)
                unset($this->pagoList[$key]);
        }
        $this->saveData();
    }

    //no voy a editar un pago
    public function update($direccion, $idDireccion) 
    { }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->pagoList as $pago) {
            $valuesArray["idPagoTC"] = $pago->getIdPagoTC();
            $valuesArray["idCompra"] = $pago->getIdCompra();
            $valuesArray["codigoAut"] = $pago->getCodigoAut();
            $valuesArray["fecha"] = $pago->getFecha();
            $valuesArray["total"] = $pago->getTotal();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/pagoTC.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->pagoList = array();

        if (file_exists('Data/pagoTC.json')) {
            $jsonContent = file_get_contents('Data/pagoTC.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $pago = new PagoTC($valuesArray["idCompra"], $valuesArray["codigoAut"], $valuesArray["fecha"], $valuesArray["total"]);
                $pago->setIdPagoTC($valuesArray["idPagoTC"]);
                array_push($this->pagoList, $pago);
            }
        }
    }
}