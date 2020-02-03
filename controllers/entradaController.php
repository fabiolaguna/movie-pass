<?php

namespace controllers;

use daojson\CompraDao as CompraDao;
use daojson\EntradaDao as EntradaDao;
use daojson\Usuario as UsuarioDao;
use daojson\CineDao as CineDao;

class EntradaController
{
    function __construct()
    {
    }

    public function index($msg = null)
    {
        include_once(VIEWS . '/header.php');
        if ($_SESSION["loggedRole"] == "admin") {
            include_once(VIEWS . '/navAdmin.php');
            if(!isset($_GET["action"]))
            {
                if(isset($_GET["fecha1"]) && isset($_GET["fecha2"]))
                    include_once(VIEWS . '/consultaVendidosPelicula.php');
                else
                    include_once(VIEWS . '/consultaVendidos.php');
            }
            else{
                if ($_GET["action"] == "consultarPorFechaCine")
                    include_once(VIEWS . '/consultaVendidos.php');
                else
                    include_once(VIEWS . '/consultaVendidosPelicula.php');
            }    
        }
        if ($_SESSION["loggedRole"] == "cliente") {
            include_once(VIEWS . '/navClient.php');
            include_once(VIEWS . '/entradas.php');
        }
        include_once(VIEWS . '/footer.php');
    }

    public function listarEntradasCliente()
    {
        $entradaDao = new EntradaDao();
        $usuarioDao = new UsuarioDao();
        $entradas = $entradaDao->getAll();
        $idCliente = $usuarioDao->readIdUsuario($_SESSION["loggedEmail"]);
        $entradasCliente = array();
        if (!empty($entradas)) {
            if (is_array($entradas)) {
                foreach ($entradas as $value) {
                    if ($value->getIdCliente() == $idCliente) {
                        array_push($entradasCliente, $value);
                    }
                }
            } else {
                if ($entradas->getIdCliente() == $idCliente) {
                    array_push($entradasCliente, $entradas);
                }
            }
        }
        return $entradasCliente;
    }
    public function mostrarEntradasEnVista()
    {
        $entradasCliente = $this->listarEntradasCliente();
        $valoresAMostrar = array();
        if (!empty($entradasCliente)) {
            foreach ($entradasCliente as $value) {
                if ($value->getBaja() == false) {
                    $proyeccion = ProyeccionController::buscarProyeccion($value->getIdProyeccion());
                    $sala = SalaController::readSala($proyeccion->getIdSala());
                    $cine = CineController::readCine($sala->getIdCine());
                    $pelicula = PeliculaController::readPelicula($proyeccion->getIdPelicula());
                    $compraDao = new CompraDao();
                    $compra = $compraDao->read($value->getIdCompra());
                    $precio = $compra->getTotal() / $compra->getCantEntradas();

                    $valor["idEntrada"] = $value->getIdEntrada();
                    $valor["cine"] = $cine->getNombre();
                    $valor["sala"] = $sala->getNombre();
                    $valor["pelicula"] = $pelicula->getNombrePelicula();
                    $valor["precio"] = $precio;
                    $valor["fecha"] = $proyeccion->getFecha();
                    $valor["horario"] = $proyeccion->getHorario();
                    $valor["qr"] = str_replace('chs=300x300', 'chs=70x70', $value->getCodigoQR());
                    array_push($valoresAMostrar, $valor);
                }
            }
        }
        return $valoresAMostrar;
    }
    public function ordenarPorPelicula()
    {
        $valoresAMostrar = $this->mostrarEntradasEnVista();
        usort($valoresAMostrar, function ($a, $b) {
            return (strcasecmp($a['pelicula'], $b['pelicula']));
        });
        return $valoresAMostrar;
    }
    public function ordenarPorFecha()
    {
        $valoresAMostrar = $this->mostrarEntradasEnVista();
        usort($valoresAMostrar, function ($a, $b) {
            return ($a['horario'] > $b['horario']);
        });
        usort($valoresAMostrar, function ($a, $b) {
            return ($a['fecha'] > $b['fecha']);
        });
        return $valoresAMostrar;
    }
    public static function totalVendidoCine($idCine)
    {
        $entradaDao = new EntradaDao();
        $entradas = $entradaDao->getAll();
        $total = 0;
        if (!empty($entradas)) {
            if (is_array($entradas)) {
                foreach ($entradas as $entrada) {
                    $proyeccion = ProyeccionController::buscarProyeccion($entrada->getIdProyeccion());
                    $sala = SalaController::readSala($proyeccion->getIdSala());
                    if ($sala->getIdCine() == $idCine) {
                        $compraDao = new CompraDao();
                        $compra = $compraDao->read($entrada->getIdCompra());
                        $total += $compra->getTotal() / $compra->getCantEntradas();
                    }
                }
            } else {
                $proyeccion = ProyeccionController::buscarProyeccion($entradas->getIdProyeccion());
                $sala = SalaController::readSala($proyeccion->getIdSala());
                if ($sala->getIdCine() == $idCine) {
                    $compraDao = new CompraDao();
                    $compra = $compraDao->read($entradas->getIdCompra());
                    $total += $compra->getTotal() / $compra->getCantEntradas();
                }
            }
        }
        return $total;
    }
    public static function totalVendidoPelicula($idPelicula)
    {
        $entradaDao = new EntradaDao();
        $entradas = $entradaDao->getAll();
        $total = 0;
        if (!empty($entradas)) {
            if (is_array($entradas)) {
                foreach ($entradas as $entrada) {
                    $proyeccion = ProyeccionController::buscarProyeccion($entrada->getIdProyeccion());
                    if ($proyeccion->getIdPelicula() == $idPelicula) {
                        $compraDao = new CompraDao();
                        $compra = $compraDao->read($entrada->getIdCompra());
                        $total += $compra->getTotal() / $compra->getCantEntradas();
                    }
                }
            } else {
                $proyeccion = ProyeccionController::buscarProyeccion($entradas->getIdProyeccion());
                if ($proyeccion->getIdPelicula() == $idPelicula) {
                    $compraDao = new CompraDao();
                    $compra = $compraDao->read($entradas->getIdCompra());
                    $total += $compra->getTotal() / $compra->getCantEntradas();
                }
            }
        }
        return $total;
    }
    public static function totalVendidoEntreFechasCine($fecha1, $fecha2, $idCine)
    {
        $entradaDao = new EntradaDao();
        $entradas = $entradaDao->getAll();
        $total = 0;
        if ($fecha2 < $fecha1) {
            $aux = $fecha2;
            $fecha2 = $fecha1;
            $fecha1 = $aux;
        }

        if (!empty($entradas)) {
            if (is_array($entradas)) {
                foreach ($entradas as $entrada) {
                    $proyeccion = ProyeccionController::buscarProyeccion($entrada->getIdProyeccion());
                    $sala = SalaController::readSala($proyeccion->getIdSala());
                    if ($sala->getIdCine() == $idCine) {
                        $compraDao = new CompraDao();
                        $compra = $compraDao->read($entrada->getIdCompra());
                        if ($fecha1 < $compra->getFecha() && $fecha2 > $compra->getFecha())
                            $total += $compra->getTotal() / $compra->getCantEntradas();
                    }
                }
            } else {
                $proyeccion = ProyeccionController::buscarProyeccion($entradas->getIdProyeccion());
                $sala = SalaController::readSala($proyeccion->getIdSala());
                if ($sala->getIdCine() == $idCine) {
                    $compraDao = new CompraDao();
                    $compra = $compraDao->read($entradas->getIdCompra());
                    if ($fecha1 < $compra->getFecha() && $fecha2 > $compra->getFecha())
                        $total += $compra->getTotal() / $compra->getCantEntradas();
                }
            }
        }
        return $total;
    }
    public static function totalVendidoEntreFechasPelicula($fecha1, $fecha2, $idPelicula)
    {
        $entradaDao = new EntradaDao();
        $entradas = $entradaDao->getAll();
        $total = 0;
        if ($fecha2 < $fecha1) {
            $aux = $fecha2;
            $fecha2 = $fecha1;
            $fecha1 = $aux;
        }
        if (!empty($entradas)) {
            if (is_array($entradas)) {
                foreach ($entradas as $entrada) {
                    $proyeccion = ProyeccionController::buscarProyeccion($entrada->getIdProyeccion());
                    if ($proyeccion->getIdPelicula() == $idPelicula) {
                        $compraDao = new CompraDao();
                        $compra = $compraDao->read($entrada->getIdCompra());
                        if ($fecha1 < $compra->getFecha() && $fecha2 > $compra->getFecha()) {
                            $total += $compra->getTotal() / $compra->getCantEntradas();
                        }
                    }
                }
            } else {
                $proyeccion = ProyeccionController::buscarProyeccion($entradas->getIdProyeccion());
                if ($proyeccion->getIdPelicula() == $idPelicula) {
                    $compraDao = new CompraDao();
                    $compra = $compraDao->read($entradas->getIdCompra());
                    if ($fecha1 < $compra->getFecha() && $fecha2 > $compra->getFecha())
                        $total += $compra->getTotal() / $compra->getCantEntradas();
                }
            }
        }
        return $total;
    }
}
