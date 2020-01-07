<?php

namespace controllers;

use daojson\ProvinciasDao as ProvinciasDao;

class ProvinciaController
{
    function __construct()
    { }
    
    public function getNombresProvincia()
    {
        $provincias = new ProvinciasDao();
        $provinciasList = $provincias->getAll();
        $cant = count($provinciasList["provincias"]);
        $listaNombresProvincia = array();
        for ($i = 0; $i < $cant; $i++) {
            array_push($listaNombresProvincia, $provinciasList["provincias"][$i]["nombre"]);
        }
        return $listaNombresProvincia;
    }
}
