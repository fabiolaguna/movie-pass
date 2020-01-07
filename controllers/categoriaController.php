<?php

namespace controllers;

class CategoriaController
{
    function __construct()
    { }

    public static function idToGenreName($ids)
    {
        $arrayGenres = ApiController::getGenresMovies();
        $cantIds = count($ids);
        $cant = count($arrayGenres["genres"]);
        $generos=array();
        for ($i = 0; $i < $cant; $i++) {
            for($j = 0; $j < $cantIds; $j++)
                if ($ids[$j] == $arrayGenres["genres"][$i]["id"])
                    array_push($generos, $arrayGenres["genres"][$i]["name"]);
        }
        return $generos;    
    }
}
