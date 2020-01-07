<?php

namespace controllers;

use models\Pelicula as Pelicula;

class PeliculaController
{
    function __construct()
    { }

    public function index()
    {
        include_once(VIEWS . '/header.php');
        if ($_SESSION['loggedRole'] == 'admin')
            include_once(VIEWS . '/navAdmin.php');
        else
            include_once(VIEWS . '/navClient.php');   
                 
        include_once(VIEWS . '/movies.php');
        if (isset($_GET["action"])) {
            if ($_GET["action"] == "consultarTodasPeliculas") {
                $_SESSION["allmovies"] = $this->consultarTodasPeliculas();
                include_once(VIEWS . '/allMovies.php');
            }
            if ($_GET["action"] == "consultarPeliculasFecha")
                include_once(VIEWS . '/movieDate.php');
            if ($_GET["action"] == "consultarPeliculasGenero")
                include_once(VIEWS . '/movieGenre.php');
            if ($_GET["action"] == "consultarPeliculasFechaYGenero")
                include_once(VIEWS . '/movieDateAndGenre.php');
        }
        if (isset($_GET["date"]) && !isset($_GET["genre"])) {
            $_SESSION["movieDate"] = $this->consultarPeliculasFecha($_GET["date"]);
            include_once(VIEWS . '/movieDate.php');
        }
        if (isset($_GET["genre"]) && !isset($_GET["date"])) {
            $_SESSION["movieGenre"] = $this->consultarPeliculasGenero($_GET["genre"]);
            include_once(VIEWS . '/movieGenre.php');
        }
        if (isset($_GET["date"]) && isset($_GET["genre"])) {
            $_SESSION["movieDateGenre"] = $this->consultarPeliculasFechaYGenero($_GET["date"], $_GET["genre"]);
            include_once(VIEWS . '/movieDateAndGenre.php');
        }
        include_once(VIEWS . '/footer.php');
    }
    public function consultarTodasPeliculas()
    {
        return $showMoviesArray = ApiController::getMovies();
    }
    public function consultarPeliculasFecha($fecha)
    {
        $showMoviesArray = array();
        $arrayMovies = ApiController::getMovies();
        $cant = count($arrayMovies["results"]);
        $fecha = str_replace("/", "-", $fecha);//creo que esto no es necesario, probar
        for ($i = 0; $i < $cant; $i++) {
            if ($arrayMovies["results"][$i]["release_date"] == $fecha)
                array_push($showMoviesArray, $arrayMovies["results"][$i]);
        }
        return $showMoviesArray;
    }
    public function consultarPeliculasGenero($genero) 
    {
        $showMoviesArray = array();
        $arrayMovies = ApiController::getMovies();
        $arrayGenres = ApiController::getGenresMovies();
        $idGenero = 0;

        $cant = count($arrayGenres["genres"]);
        for ($i = 0; $i < $cant; $i++) {
            if ($genero == $arrayGenres["genres"][$i]["name"])
                $idGenero = $arrayGenres["genres"][$i]["id"];
        }

        $cant = count($arrayMovies["results"]);
        for ($i = 0; $i < $cant; $i++) {
            $cantIds = count($arrayMovies["results"][$i]["genre_ids"]);
            for ($j = 0; $j < $cantIds; $j++) {
                if ($arrayMovies["results"][$i]["genre_ids"][$j] == $idGenero)
                    array_push($showMoviesArray, $arrayMovies["results"][$i]);
            }
        }
        return $showMoviesArray;
    }
    public function consultarPeliculasFechaYGenero($fecha, $genero)
    {
        $showMoviesArray = array();
        $arrayGenres = ApiController::getGenresMovies();
        $idGenero = 0;
        $cant = count($arrayGenres["genres"]);
        for ($i = 0; $i < $cant; $i++) {
            if ($genero == $arrayGenres["genres"][$i]["name"])
                $idGenero = $arrayGenres["genres"][$i]["id"];
        }

        $moviesDate = $this->consultarPeliculasFecha($fecha);
        $cant = count($moviesDate);
        for ($i = 0; $i < $cant; $i++) {
            $cantIds = count($moviesDate[$i]["genre_ids"]);
            for ($j = 0; $j < $cantIds; $j++) {
                if ($moviesDate[$i]["genre_ids"][$j] == $idGenero)
                    array_push($showMoviesArray, $moviesDate[$i]);
            }
        }
        return $showMoviesArray;
    }
    public function apiToList()
    {
        $arrayMovies = ApiController::getMovies();
        $peliculas = array();
        $cant = count($arrayMovies["results"]);
        for ($i = 0; $i < $cant; $i++) {
            $idPelicula = $arrayMovies['results'][$i]['id'];
            $idCategoria = $arrayMovies['results'][$i]['genre_ids'];
            $nombrePelicula = $arrayMovies['results'][$i]['title'];
            $descripcion = $arrayMovies['results'][$i]['overview'];
            $imagen = $arrayMovies['results'][$i]['poster_path'];
            $lenguaje = $arrayMovies['results'][$i]['original_language'];
            $fechaEstreno = $arrayMovies['results'][$i]['release_date'];
            $poster = $arrayMovies['results'][$i]['backdrop_path'];
            $pelicula = new Pelicula($idPelicula, $idCategoria, $nombrePelicula, $descripcion, $imagen, $lenguaje, $fechaEstreno, $poster);
            array_push($peliculas, $pelicula);
        }
        return $peliculas;
    }
    public static function apiToListStatic()
    {
        $arrayMovies = ApiController::getMovies();
        $peliculas = array();
        $cant = count($arrayMovies["results"]);
        for ($i = 0; $i < $cant; $i++) {
            $idPelicula = $arrayMovies['results'][$i]['id'];
            $idCategoria = $arrayMovies['results'][$i]['genre_ids'];
            $nombrePelicula = $arrayMovies['results'][$i]['title'];
            $descripcion = $arrayMovies['results'][$i]['overview'];
            $imagen = $arrayMovies['results'][$i]['poster_path'];
            $lenguaje = $arrayMovies['results'][$i]['original_language'];
            $fechaEstreno = $arrayMovies['results'][$i]['release_date'];
            $poster = $arrayMovies['results'][$i]['backdrop_path'];
            $pelicula = new Pelicula($idPelicula, $idCategoria, $nombrePelicula, $descripcion, $imagen, $lenguaje, $fechaEstreno, $poster);
            array_push($peliculas, $pelicula);
        }
        return $peliculas;
    }
    public function buscarPelicula($id)
    {
        $peliculas=$this->apiToList();
        $pelicula=null;
        foreach($peliculas as $value)
        {
            if($value->getIdPelicula() == $id)
            {
                $pelicula=$value;
            }
        }
        return $pelicula;
    }

    public static function readPelicula($idPelicula){

        $peliculas=PeliculaController::apiToListStatic();
        $pelicula=null;
        foreach($peliculas as $value)
        {
            if($value->getIdPelicula() == $idPelicula)
            {
                $pelicula=$value;
            }
        }
        return $pelicula;
    }
}
