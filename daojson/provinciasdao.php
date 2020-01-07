<?php

namespace daojson;

class ProvinciasDao 
{
    private $provinciasList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/provincias.json";
    }

    public function getAll()
    {
        $this->retrieveData();
        return $this->provinciasList;
    }

    private function retrieveData()
    {
        $this->provinciasList = array();
        if (file_exists('data/provincias.json')) {
            $jsonContent = file_get_contents('data/provincias.json');
            $this->provinciasList = ($jsonContent) ? json_decode($jsonContent, true) : array();
        }
    }
}
