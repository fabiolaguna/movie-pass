<?php

namespace Config;

class Autoload
{
    public static function start()
    {
        spl_autoload_register(function ($classNotFound) {
            // Armo la url de la clase a partir del namespace y la instancia.
            $url = str_replace("\\", "/", ROOT.$classNotFound)  . ".php";
            // Incluyo la url que, si todo esta bien, debería contener la clase que intento instanciar.
            include($url);
        });
    }
}
