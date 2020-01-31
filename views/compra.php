<style>
    body {
        <?php if (isset($_GET["cantidadEntradas"])) {   ?>
            background: linear-gradient(to bottom, grey 10%, black);
        <?php } else { ?>  
            background: linear-gradient(to bottom, black 10%, white);
        <?php } ?>
    }
</style>
<?php
        if (isset($_GET["idProyeccion"])) {
            $idProyeccion = $_GET["idProyeccion"];
            unset($_GET["idProyeccion"]);
            $proyeccion = controllers\ProyeccionController::buscarProyeccion($idProyeccion);
            $sala = controllers\SalaController::readSala($proyeccion->getIdSala());
?>
    <header class="formularios">
        <div class="container">
            <div class="intro-text">
                <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
                <div class="intro-heading">Comprar entradas</div>
            </div>
        </div>
    </header>
    <div class="container col-md-6 col-sm-6">
        <form class="modal-content" style=" border-radius: 20px" action="<?php echo (FRONT_ROOT) ?>/compra/index" method="GET">
            <br>
            <div class="modal-body">
                <input type="hidden" name="idProyeccion" value="<?php echo ($idProyeccion); ?>">
                <table class="table table-borderless" style="text-align:center;">
                    <thead class="">
                        <tr>
                            <th>Cantidad de entradas</th>
                            <th>Precio <?php
                                $dia = strtotime($proyeccion->getFecha());
                                $dia = date('l', $dia);
                                if ($dia == 'Tuesday' || $dia == 'Wednesday') {
                                    $precio = $sala->getPrecio() * (0.75);
                                    echo ("(con descuento)");
                                }
                            ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div align="center">
                                    <select name='cantidadEntradas' class="form-control" style="border-radius: 20px">
                                        <option value="0">0</option>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 1) { ?> <option value="1">1</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 2) { ?> <option value="2">2</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 3) { ?> <option value="3">3</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 4) { ?> <option value="4">4</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 5) { ?> <option value="5">5</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 6) { ?> <option value="6">6</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 7) { ?> <option value="7">7</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 8) { ?> <option value="8">8</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 9) { ?> <option value="9">9</option> <?php } ?>
                                        <?php if ($proyeccion->getAsientosDisponibles() >= 10) { ?> <option value="10">10</option> <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                $<?php
                                        $dia = strtotime($proyeccion->getFecha());
                                        $dia = date('l', $dia);
                                        if ($dia == 'Tuesday' || $dia == 'Wednesday') {
                                            $precio = $sala->getPrecio() * (0.75);
                                            echo ($precio);
                                        } else {
                                            echo ($sala->getPrecio());
                                        }
                                    ?>
                            </td>
                        </tr>
                </table>
            </div>
            <div class="modal-footer">
                <?php if (!isset($_GET["cantidadEntradas"]) || $_GET["cantidadEntradas"] == 0) { ?>
                    <a style="border-radius: 20px" class="btn btn-danger" href="<?php echo (FRONT_ROOT); ?>/proyeccion/index">Volver</a>
                <?php } ?>
                <button style="border-radius: 20px" type="submit" class="btn btn-primary"> Seleccionar </button>
            </div>
        </form>
    </div>
    <?php
        if (!isset($_GET["cantidadEntradas"]))
            echo ("<br><br><br><br><br>");
        if (isset($_GET["cantidadEntradas"])) {   ?>
        <br>
        <div class="container">
            <?php if ($_GET["cantidadEntradas"] == 0) { ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <strong>
                        <?php
                                                                                                                                        echo ("Debe seleccionar al menos 1 entrada para continuar");
                        ?>
                    </strong>
                </div>
            <?php } else {
                $cine = controllers\CineController::readCine($sala->getidCine());
                $dia = strtotime($proyeccion->getFecha());
                $dia = date('l', $dia);
                $precio = $sala->getPrecio();
                if ($dia == 'Tuesday' || $dia == 'Wednesday') {
                    $precio = $precio * (0.75);
                }
                $total = $precio * $_GET["cantidadEntradas"];
                $pelicula = controllers\PeliculaController::readPelicula($proyeccion->getIdPelicula());
            ?>
                <br>
                <h4 class="ml-4 text-white">Finaliza tu operación</h4>
                <br>
                <p class="ml-3 text-white">
                    Para completar su operación verifique que todos los datos, tanto de la película, fecha, horario como los personales sean correctos. Luego presione el botón FINALIZAR. Muchas gracias.
                </p>
                <br>
                <div class="ml-4 text-white"><ins> Nombre del cine</ins>: <?php echo ($cine->getNombre()); ?></div>
                <br>
                <div class="ml-4 text-white"><ins> Pelicula</ins>: <?php echo ($pelicula->getNombrePelicula()); ?></div>
                <br>
                <div class="ml-4 text-white"><ins> Fecha</ins>: <?php echo ($proyeccion->getFecha()); ?></div>
                <br>
                <div class="ml-4 text-white"><ins> Horario</ins>: <?php echo ($proyeccion->getHorario()); ?></div>
                <br>
                <div class="ml-4 text-white"><ins> Total</ins>: $<?php echo ($total); ?> </div>
                <br>
                <br>
                <form action="<?php echo (FRONT_ROOT) ?>/compra/generarCompra" method="POST">

                    <input type="hidden" name="idProyeccion" value="<?php echo ($idProyeccion); ?>">
                    <input type="hidden" name="cantEntradas" value="<?php echo ($_GET["cantidadEntradas"]); ?>">
                    <?php $descuento = $sala->getPrecio() * ($_GET["cantidadEntradas"]) - $total ?>
                    <input type="hidden" name="descuento" value="<?php echo ($descuento); ?>">
                    <input type="hidden" name="total" value="<?php echo ($total); ?>">

                    <div class="form-group ml-4 text-white">
                        <label><ins>Tipo de tarjeta</ins></label>
                        <select name='nombreCompania' class="form-control col-sm-7" style="border-radius: 20px" required>
                            <option value="" disabled selected>Seleccionar tarjeta</option>
                            <option value="Visa">Visa</option>
                            <option value="MasterCard">MasterCard</option>
                        </select>
                    </div>
                    <div class="form-group ml-4 text-white">
                        <label><ins>Numero de tarjeta</ins></label>
                        <input type="number" class="form-control col-sm-7" style="border-radius: 20px" name="numeroTarjeta" step=1 min=1000000000000000 max=9999999999999999 required />
                    </div>
                    <div class="form-group ml-4 text-white">
                        <label><ins>Codigo de seguridad</ins></label>
                        <input type="password" class="form-control col-sm-7" style="border-radius: 20px" name="codigoSeguridad" pattern="[0-9]{3}" maxlength="3" title="Ingrese los tres numeros que estan en el reverso de la tarjeta" required />
                    </div>
                    <div align="center">
                        <a style="border-radius: 20px" class="btn btn-danger" href="<?php echo (FRONT_ROOT); ?>/proyeccion/index">Volver</a>
                        <button style="border-radius: 20px" type="submit" class="btn btn-primary"> Finalizar </button>
                    </div>
                </form>
                <br>
                <br>

    <?php }
                                                                                                                                }
                                                                                                                            } ?>
    <?php if (isset($msg)) { ?>
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } ?>
        </div>