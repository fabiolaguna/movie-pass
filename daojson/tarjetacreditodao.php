<?php

namespace daojson;

use interfaces\IDao as IDao;
use models\TarjetaCredito as TarjetaCredito;

class TarjetaCreditoDao implements IDao
{        
    private $tarjetaList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/tarjetasCredito.json";
    }

    public function add($tarjeta)
    {
        $this->tarjetaList=array();
        $this->retrieveData();
        array_push($this->tarjetaList, $tarjeta);
        $this->saveData();
        $successMje = 'Agregado con éxito';
        return $successMje;
    }

    public function getAll()
    {
        $this->tarjetaList=array();
        $this->retrieveData();
        return $this->tarjetaList;
    }

    public function read($nroTarjeta)
    {
        $this->tarjetaList=array();
        $this->retrieveData();
        $value = null;
        foreach ($this->tarjetaList as $tarjeta) {
            if ($tarjeta->getNroTarjeta() == $nroTarjeta)
                $value = $tarjeta;
        }
        return $value;
    }
    //los hicimos para crear la compra
    public function readIdTarjeta($nroTarjeta)
    {
        $this->tarjetaList=array();
        $this->retrieveData();
        $value = null;
        $id=1;
        foreach($this->tarjetaList as $tarjeta)
        {
            if($tarjeta->getNroTarjeta()==$nroTarjeta)   
                $value=$id;      
            $id++;
        }
        return $value;
    }
    public function delete($value) 
    { }

    public function update($tarjeta, $nroTarjeta) 
    {
        $this->tarjetaList=array();
        $this->retrieveData();
        $i = 0;
        $j = 0;
        $tarjetaCredito = false;
        foreach ($this->tarjetaList as $value) {
            if ($tarjeta->getNroTarjeta() != $value->getNroTarjeta()) {
                    $tarjetaCredito = true;
                    return -1;
            }
        }
        if (!$tarjetaCredito) {
            foreach ($this->tarjetaList as $value) {
                $i++;
                if ($value->getNroTarjeta() == $nroTarjeta)
                    $j = $i;
            }
        }
        if (isset($tarjeta) && !empty($tarjeta)) {
            if (($tarjeta->getNombreCompania() != null) && !empty($tarjeta->getNombreCompania()))
                $this->tarjetaList[$j]->setNombreCompania($tarjeta->getNombreCompania());

            if (($tarjeta->getNroTarjeta() != null) && !empty($tarjeta->getNroTarjeta()))
                $this->tarjetaList[$j]->setNroTarjeta($tarjeta->getNroTarjeta());

            if (($tarjeta->getCodigoSeguridad() != null) && !empty($tarjeta->getCodigoSeguridad()))
                $this->tarjetaList[$j]->setCodigoSeguridad($tarjeta->getCodigoSeguridad());
        }
        $this->saveData();
    }

    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->tarjetaList as $tarjeta) {
            $valuesArray["nombreCompania"] = $tarjeta->getNombreCompania();
            $valuesArray["nroTarjeta"] = $tarjeta->getNroTarjeta();
            $valuesArray["codigoSeguridad"] = $tarjeta->getCodigoSeguridad();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents('Data/tarjetasCredito.json', $jsonContent);
    }

    private function retrieveData()
    {
        $this->usersList = array();

        if (file_exists('Data/tarjetasCredito.json')) {
            $jsonContent = file_get_contents('Data/tarjetasCredito.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $tarjeta = new TarjetaCredito($valuesArray["nombreCompania"], $valuesArray["nroTarjeta"], $valuesArray["codigoSeguridad"]);
                array_push($this->tarjetaList, $tarjeta);
            }
        }
    }
}
