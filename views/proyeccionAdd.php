<style>
    body {
        background-color: #212529;
    }
</style>
<br> <br> <br> <br>
<?php
$_SESSION["idPelicula"] = $_GET["idPelicula"];
$peliculaController = new controllers\PeliculaController();
$pelicula = $peliculaController->buscarPelicula($_SESSION["idPelicula"]);
$salaController = new controllers\SalaController();
$arregloSalas = $salaController->listarSalas();
unset($_GET["idPelicula"]);
if (!empty($arregloSalas)) {
    ?>
    <header class="formularios">
        <div class="container">
            <div class="intro-text">
                <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
                <div class="intro-heading">Crear proyección</div>
            </div>
        </div>
    </header>
    <div class="container">
        <form class="modal-content" style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/proyeccion/agregarProyeccion" method="POST">

            <div class="modal-body">



                <div class="form-group">
                    <label><strong>Id de la sala</strong></label>
                    <select class="form-control" name="idCine" id="idCine" required>
                        <?php foreach ($arregloSalas as $sala) {
                                $cine = controllers\CineController::readCine($sala->getIdCine());
                                ?>
                            <option value="<?php echo ($sala->getIdSala()); ?>"> <?php echo ($sala->getIdSala() . ", " . $sala->getNombre() . ", " . $sala->getCapacidadButacas() . " butacas - (" . $cine->getNombre() . ", " . $cine->getDireccion()->getProvincia() . ", " . $cine->getDireccion()->getCiudad() . ", " . $cine->getDireccion()->getCalle() . " " . $cine->getDireccion()->getAltura() . ")");
                                                                                        } ?> </option>
                    </select>
                </div>

                <div class="form-group">
                    <label><strong>Fecha</strong></label>
                    <!--BUSCAR RESTRICCION-->
                    <input type="date" class="form-control" name="fecha" min=<?php
                                                                                    $fecha = date('Y-m-d');
                                                                                    $nuevafecha = strtotime('+1 day', strtotime($fecha));
                                                                                    $nuevafecha = date('Y-m-d', $nuevafecha);
                                                                                    if($nuevafecha > $pelicula->getFechaEstreno())
                                                                                        echo ($nuevafecha);
                                                                                    else
                                                                                        echo($pelicula->getFechaEstreno());
                                                                                     ?> max="2030-01-01" required>
                </div>

                <div class="form-group">
                    <!--BUSCAR RESTRICCION-->
                    <label><strong>Horario</strong></label>
                    <input name="horario" type="time" required>
                </div>

                <div class="modal-footer">
                    <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/home/index">Volver</a>
                    <button type="submit" class="btn btn-primary"> Cargar </button>
                </div>

            </div>
        </form>
    <?php } else
        $msg = "No se puede realizar esta accion porque no se encuentran salas cargadas."; ?>
    </div>
    <?php if (isset($msg)) { ?>
        <div class="container">
            <div class="alert
                    <?php
                            if (isset($msg))
                                echo 'alert-success';
                            else
                                echo 'alert-danger'; ?> 
                        alert-dismissible fade show mt-3" role="alert">
                <strong>
                    <?php
                        if (isset($msg))
                            echo $msg;
                        ?>
                </strong>
            </div>
            <div class="modal-footer" style="border: 2px solid #212529;">
                <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/home/index">Volver</a>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br>
        </div>
    <?php } ?>
