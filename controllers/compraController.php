<?php

namespace controllers;

use PHPMailer\PHPMailer\PHPMailer;

use models\Compra as Compra;
use models\Entrada as Entrada;
use models\PagoTC;

use daoDB\CompraDao as CompraDao;
use daoDB\EntradaDao as EntradaDao;
use daoDB\PagoTCDao;
use daoDB\Usuario as UsuarioDao;
use daoDB\TarjetaCreditoDao as TarjetaCreditoDao;

require(ROOT . '/PHPMailer/src/Exception.php');
require(ROOT . '/PHPMailer/src/PHPMailer.php');
require(ROOT . '/PHPMailer/src/SMTP.php');

class CompraController
{
    function __construct()
    { }

    public function index($msg = null)
    {
        include_once(VIEWS . '/header.php');
        include_once(VIEWS . '/navClient.php');
        if (isset($_GET["idProyeccion"])) {
            include_once(VIEWS . '/compra.php');
        }
        if (isset($msg)) {
            include_once(VIEWS . '/homeClient.php');
        }
        include_once(VIEWS . '/footer.php');
    }
    public function generarPago($cantEntradas, $descuento, $total, $nombreCompania, $nroTarjeta, $codigoSeguridad)
    {
        $tarjetaCreditoDao = new TarjetaCreditoDao();
        $tarjeta = $tarjetaCreditoDao->read($nroTarjeta);
        $pago = null;
        if (!empty($tarjeta)) { //el nro no lo comprobas porque ya esta cuando lo buscas
            if ($tarjeta->getNombreCompania() == $nombreCompania && $tarjeta->getCodigoSeguridad() == $codigoSeguridad) {
                $idTarjetaCredito = $tarjetaCreditoDao->readIdTarjeta($nroTarjeta);

                $usuarioDao = new UsuarioDao();
                $idCliente = $usuarioDao->readIdUsuario($_SESSION["loggedEmail"]);

                $compra = new Compra($idTarjetaCredito, $idCliente, $cantEntradas, $descuento, date("Y-m-d"), $total);
                $compraDao = new CompraDao();
                $compraDao->add($compra);
                $compra->setIdCompra($compraDao->lastIdInsert());
                $pago = new PagoTC($compra->getIdCompra(), 1, date("Y-m-d"), $total);
            }
        }
        return $pago;
    }
    public function generarCompra($idProyeccion, $cantEntradas, $descuento, $total, $nombreCompania, $nroTarjeta, $codigoSeguridad)
    {
        $pago = $this->generarPago($cantEntradas, $descuento, $total, $nombreCompania, $nroTarjeta, $codigoSeguridad);
        $msg = null;
        if ($pago != null) {
            $entradaDao = new EntradaDao();

            $pagoDao = new PagoTCDao();
            $pagoDao->add($pago);

            $proyeccionController = new ProyeccionController();
            $proyeccion = ProyeccionController::buscarProyeccion($idProyeccion);
            $sala = SalaController::readSala($proyeccion->getIdSala());
            $cine = CineController::readCine($sala->getIdCine());
            $pelicula = PeliculaController::readPelicula($proyeccion->getIdPelicula());
            $asientosDisponibles = $proyeccion->getAsientosDisponibles() - $cantEntradas;
            $proyeccionController->modificarAsientos($idProyeccion, $proyeccion->getIdSala(), $asientosDisponibles);
            $precio = $total / $cantEntradas;
            $usuarioDao = new UsuarioDao();
            $idCliente = $usuarioDao->readIdUsuario($_SESSION["loggedEmail"]);
            for ($i = 0; $i < $cantEntradas; $i++) {
                $entrada = new Entrada($idProyeccion, $idCliente, $pago->getIdCompra());
                $entrada->setIdEntrada($entradaDao->lastIdInsert());

                $mensajeCodigoQR= " NRO DE ENTRADA: " . $entrada->getIdEntrada() .
                " - CINE: " . $cine->getNombre() .
                " - SALA: " . $sala->getNombre() .
                " - PELICULA: " . $pelicula->getNombrePelicula() .
                " - PRECIO: " . $precio .
                " - FECHA: " . $proyeccion->getFecha() .
                " - HORARIO: " . $proyeccion->getHorario();
                $entrada->setCodigoQRMail($mensajeCodigoQR);
                $entradaDao->add($entrada);
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
                $mail->addAddress($_SESSION["loggedEmail"]); // Name is optional
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Entrada cine';
                $mail->Body    = "Se adjunta la informacion de la compra con un codigo qr que debera presentar al momento de entrar a la funcion" . "<br>" . "<br>"  .
                    "Número de entrada: " . $entrada->getIdEntrada() . "<br>" .
                    "<br>" . "Cine: " . $cine->getNombre() .
                    "<br>" . "Sala: " . $sala->getNombre() .
                    "<br>" . "Pelicula: " . $pelicula->getNombrePelicula() .
                    "<br>" . "Precio: " . $precio .
                    "<br>" . "Fecha: " . $proyeccion->getFecha() .
                    "<br>" . "Horario: " . $proyeccion->getHorario() .
                    "<br>" . $entrada->getCodigoQR();
                $mail->send();
            }
            $msg = "Compra realizada con éxito. Se ha enviado una copia de la/s entrada/s a su mail.";
        } else
            $msg = "No se pudo realizar la compra.";
        $this->index($msg);
    }

    //LOS ERRORES DE LOS EMAIL SON POR EL SERVER SMTP. LO PROBAMOS CON UNA APP "SMTPDummy" (FAKE MAIL) Y SE MANDAN BIEN
}
