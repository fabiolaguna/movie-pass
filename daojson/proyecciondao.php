<?php

namespace daojson;

use PHPMailer\PHPMailer\PHPMailer;

use interfaces\IDao as IDao;
use models\Proyeccion as Proyeccion;
use controllers\SalaController as SalaController;
use controllers\PeliculaController as PeliculaController;

class ProyeccionDao implements IDao
{
    private $proyeccionesList = array();
    private $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/data/proyecciones.json";
    }

    public function add($proyeccion) //Fijarse que no este repetido y si esta eliminado, nomas cambiar baja a true
    {
        $this->proyeccionesList = array();
        $this->retrieveData();
        $proyeccionExist = false;
        $sala = SalaController::readSala($proyeccion->getIdSala());
        if (!empty($this->proyeccionesList)) {
            foreach ($this->proyeccionesList as $value) { //Una película solo puede ser proyectada en un único cine por día (Pero no puede ser reproducida en mas de una sala del cine)
                $salaValue = SalaController::readSala($value->getIdSala());
                if ($proyeccion->getIdPelicula() == $value->getIdPelicula()) {
                    if ($proyeccion->getFecha() == $value->getFecha()) {
                        if ($sala->getIdCine() == $salaValue->getIdCine()) {
                            if ($proyeccion->getIdSala() != $value->getIdSala()) {
                                $proyeccionExist = true;
                                return -1;
                            }
                        } else {
                            $proyeccionExist = true;
                            return -2;
                        }
                    }
                }
            }
        }
        //esta comparando con el otro cine
        if (!$proyeccionExist) {

            $horarioAnterior = new \Datetime($proyeccion->getHorario());
            $horarioAnterior->modify('-15 minute');
            $horarioSiguiente = new \Datetime($proyeccion->getHorario());
            $horarioSiguiente->modify('+15 minute');
            if (!empty($this->proyeccionesList)) {
                $proyeccionCineSala = array();
                foreach ($this->proyeccionesList as $value) {
                    $salaValue = SalaController::readSala($value->getIdSala());
                    if (($salaValue->getIdCine() == $sala->getIdCine()) && ($value->getIdSala() == $proyeccion->getIdSala()) && ($value->getFecha() == $proyeccion->getFecha())) {
                        array_push($proyeccionCineSala, $value);
                    }
                }
                foreach ($proyeccionCineSala as $value) {
                    $horario = new \DateTime($value->getHorario());
                    if ($horario < $horarioSiguiente && $horario > $horarioAnterior) {
                        $proyeccionExist = true;
                        return -3;
                    }
                }
            }
        }
        if (!$proyeccionExist) {
            $id = 0;
            if ($this->proyeccionesList != null)
                $cant = count($this->proyeccionesList);
            $id=$this->proyeccionesList[$cant-1]->getIdProyeccion();
            $proyeccion->setIdProyeccion($id+1);
            array_push($this->proyeccionesList, $proyeccion);
            $this->saveData();
            $successMje = 'Agregado con éxito';
            return $successMje;
        } else {
            $errorMje = 'No se pudo agregar porque ya existe';
            return $errorMje;
        }
    }
    public function read($idProyeccion)
    {
        $this->proyeccionesList = array();
        $this->retrieveData();
        $value = null;
        foreach ($this->proyeccionesList as $proyeccion) {
            if ($proyeccion->getIdProyeccion() == $idProyeccion)
                $value = $proyeccion;
        }
        return $value;
    }
    public function getAll()
    {
        $this->proyeccionesList = array();
        $this->retrieveData();
        return $this->proyeccionesList;
    }

    public function delete($idProyeccion)
    {
        $proyeccion = $this->read($idProyeccion);
        if ($proyeccion->getFecha() > date('Y-m-d')) {
            $entradaDao = new EntradaDao();
            $entradas = $entradaDao->getAll();
            $entradasEliminar = array();
            if (!empty($entradas)) {
                if (is_array($entradas)) {
                    foreach ($entradas as $value) {
                        if ($value->getIdProyeccion() == $idProyeccion)
                            array_push($entradasEliminar, $value);
                    }
                } else {
                    if ($entradas->getIdProyeccion() == $idProyeccion)
                        array_push($entradasEliminar, $entradas);
                }
            }
            if (!empty($entradasEliminar)) {
                foreach ($entradasEliminar as $value) {
                    $entradaDao->delete($value->getIdEntrada());

                    $userDao = new Usuario();
                    $salaDao = new SalaDao();
                    $cineDao = new CineDao();
                    $mailUser = $userDao->readEmail($value->getIdCliente());
                    $proyec = $this->read($value->getIdProyeccion());
                    $sala = $salaDao->read($proyec->getIdSala());
                    $cine = $cineDao->read($sala->getIdCine());
                    $pelicula = PeliculaController::readPelicula($proyec->getIdPelicula());

                    $mail = new PHPMailer(true);
                    //Server settings
                    //Enable SMTP debugging
                    // 0 = off (for production use)
                    // 1 = client messages
                    // 2 = client and server messages
                    $mail->isSMTP();                                            // Send using SMTP
                    $mail->SMTPDebug = 0;
                    //Ask for HTML-friendly debug output
                    $mail->Host       = "smtp.gmail.com";                    // Set the SMTP server to send through
                    $mail->SMTPAuth = true;
                    $mail->Username   = "moviepassxd@gmail.com";                     // SMTP username
                    $mail->Password   = "fedeyfabio";                               // SMTP password
                    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                    $mail->Port       = '587';                                    // TCP port to connect to
                    $mail->CharSet = 'UTF-8';

                    //Recipients
                    $mail->setFrom('moviepassxd@gmail.com', 'Movie Pass');
                    $mail->addAddress($mailUser); // Name is optional
                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Entrada cine';
                    $mail->Body    = "Lamentamos informarle que por ciertos inconvenientes en la proyeccion de x pelicula en x cine, hemos eliminado la entrada que usted ha adquirido. El monto de la entrada sera retribuido a la cuenta con la que ha efectuado la compra." . "<br>" . "<br>"  .
                        "Número de entrada: " . $value->getIdEntrada() . "<br>" .
                        "<br>" . "Cine: " . $cine->getNombre() .
                        "<br>" . "Sala: " . $sala->getNombre() .
                        "<br>" . "Pelicula: " . $pelicula->getNombrePelicula() .
                        "<br>" . "Fecha: " . $proyec->getFecha() .
                        "<br>" . "Horario: " . $proyec->getHorario();
                    $mail->send();
                }
            }
        }

        $this->proyeccionesList = array();
        $this->retrieveData();
        $i = 0;
        foreach ($this->proyeccionesList as $key => $proyeccion) {
            if ($proyeccion->getIdProyeccion() == $idProyeccion)
                unset($this->proyeccionesList[$key]);
        }
        $this->saveData();
    }

    public function update($proyeccion, $idProyeccion)
    {
        $this->proyeccionesList = array();
        $this->retrieveData();
        $msg = null;
        $arrayProyecciones = $this->proyeccionesList;
        if (!empty($arrayProyecciones)) { //Para que no se compare con el mismo
            foreach ($arrayProyecciones as $key => $pro) {
                if ($pro->getIdProyeccion() == $idProyeccion) {
                    unset($arrayProyecciones[$key]);
                }
            }
        }

        $proyeccionExist = false;
        $sala = SalaController::readSala($proyeccion->getIdSala());
        if (!empty($arrayProyecciones)) {
            foreach ($arrayProyecciones as $value) { //Una película solo puede ser proyectada en un único cine por día (Pero no puede ser reproducida en mas de una sala del cine)
                $salaValue = SalaController::readSala($value->getIdSala());
                if ($proyeccion->getIdPelicula() == $value->getIdPelicula()) {
                    if ($proyeccion->getFecha() == $value->getFecha()) {
                        if ($sala->getIdCine() == $salaValue->getIdCine()) {
                            if ($proyeccion->getIdSala() != $value->getIdSala()) {
                                $proyeccionExist = true;
                                return -1;
                            }
                        } else {
                            $proyeccionExist = true;
                            return -2;
                        }
                    }
                }
            }
        }
        //esta comparando con el otro cine
        if (!$proyeccionExist) { //Validar los 15 minutos
            $horarioAnterior = new \Datetime($proyeccion->getHorario());
            $horarioAnterior->modify('-15 minute');
            $horarioSiguiente = new \Datetime($proyeccion->getHorario());
            $horarioSiguiente->modify('+15 minute');
            if (!empty($arrayProyecciones)) {
                $proyeccionCineSala = array();
                foreach ($arrayProyecciones as $value) {
                    $salaValue = SalaController::readSala($value->getIdSala());
                    if (($salaValue->getIdCine() == $sala->getIdCine()) && ($value->getIdSala() == $proyeccion->getIdSala()) && ($value->getFecha() == $proyeccion->getFecha())) {
                        array_push($proyeccionCineSala, $value);
                    }
                }
                foreach ($proyeccionCineSala as $value) {
                    $horario = new \DateTime($value->getHorario());
                    if ($horario < $horarioSiguiente && $horario > $horarioAnterior) {
                        $proyeccionExist = true;
                        return -3;
                    }
                }
            }
        }
        if (!$proyeccionExist) {
            $i = 0;
            $j = 0;
            foreach ($this->proyeccionesList as $value) {
                if ($value->getIdProyeccion() == $idProyeccion)
                    $j = $i;
                $i++;
            }
            if (isset($proyeccion) && !empty($proyeccion)) {
                foreach ($this->proyeccionesList as $key => $value) {
                    if ($key == $j) {
                        $this->proyeccionesList[$key]->setIdSala($proyeccion->getIdSala());
                        $this->proyeccionesList[$key]->setIdPelicula($proyeccion->getIdPelicula());
                        $this->proyeccionesList[$key]->setAsientosDisponibles($proyeccion->getAsientosDisponibles());
                        $this->proyeccionesList[$key]->setAsientosOcupados($proyeccion->getAsientosOcupados());
                        $this->proyeccionesList[$key]->setFecha($proyeccion->getFecha());
                        $this->proyeccionesList[$key]->setHorario($proyeccion->getHorario());
                    }
                }
            }

            $msg = 0;
        }

        $this->saveData();
        return $msg;
    }
    public function updateAsientos($asientosDisponibles, $asientosOcupados, $idProyeccion)
    {
        $this->proyeccionesList = array();
        $this->retrieveData();

        $i = 0;
        $j = 0;

        foreach ($this->proyeccionesList as $value) {
            if ($value->getIdProyeccion() == $idProyeccion)
                $j = $i;
            $i++;
        }

        $this->proyeccionesList[$j]->setAsientosDisponibles($asientosDisponibles);
        $this->proyeccionesList[$j]->setAsientosOcupados($asientosOcupados);

        $this->saveData();
    }
    private function saveData()
    {
        $arrayToEncode = array();
        foreach ($this->proyeccionesList as $proyeccion) {
            $valuesArray["idProyeccion"] = $proyeccion->getIdProyeccion();
            $valuesArray["idSala"] = $proyeccion->getIdSala();
            $valuesArray["idPelicula"] = $proyeccion->getIdPelicula();
            $valuesArray["asientosDisponibles"] = $proyeccion->getAsientosDisponibles();
            $valuesArray["asientosOcupados"] = $proyeccion->getAsientosOcupados();
            $valuesArray["fecha"] = $proyeccion->getFecha();
            $valuesArray["horario"] = $proyeccion->getHorario();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
        file_put_contents('data/proyecciones.json', $jsonContent);
    }

    private function retrieveData()
    {
        if (file_exists('data/proyecciones.json')) {
            $jsonContent = file_get_contents('data/proyecciones.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();
            foreach ($arrayToDecode as $valuesArray) {
                $proyeccion = new Proyeccion($valuesArray['idSala'], $valuesArray['idPelicula'], $valuesArray["asientosDisponibles"], $valuesArray["asientosOcupados"],  $valuesArray["fecha"], $valuesArray["horario"]);
                $proyeccion->setIdProyeccion($valuesArray["idProyeccion"]);
                array_push($this->proyeccionesList, $proyeccion);
            }
        }
    }
}
