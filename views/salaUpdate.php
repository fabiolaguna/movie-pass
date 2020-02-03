<style>
    body {
        background-color: #1d1d1b;
    }
</style>
<br> <br> <br> <br>
<?php
controllers\CineController::listarCines();
if (!empty(($_SESSION["cines"]))) {
    $cines = $_SESSION["cines"];
    unset($_SESSION["cines"]);
    ?>
    <header class="formularios">
        <div class="container">
            <div class="intro-text">
                <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
                <div class="intro-heading">Ingresar valores a modificar</div>
            </div>
        </div>
    </header>
    <div class="container">
        <form class="modal-content" style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/sala/modificarSala" method="POST">

            <div class="modal-body">
                <?php

                    controllers\CineController::listarCines();
                    $cines = $_SESSION["cines"];
                    unset($_SESSION["cines"]);
                    $salaController = new controllers\SalaController();
                    $salas = $salaController->listarSalas();
                    $idSala = $_GET['idSala'];
                    unset($_GET['idSala']);
                    $sala = controllers\SalaController::readSala($idSala);
                    ?>
                <p><strong>Id de sala a modificar:</strong> <?php echo ($sala->getIdSala() . "<br>Id cine: " . $sala->getIdCine() . "<br>Nombre de la sala: " . $sala->getNombre() . "<br>Precio: $" . $sala->getPrecio() . "<br>Capacidad de butacas: " . $sala->getCapacidadButacas());  ?></p>

                <div class="form-group">
                    <input type="hidden" class="form-control" name="idSala" value="<?php echo ($idSala); ?>">
                </div>

                <div class="form-group">
                    <label>Id del cine</label>
                    <input type="hidden" class="form-control" name="idCine">
                    <select class="form-control" name="idCine" id="idCine">
                        <option value="" disabled selected>Seleccionar cine</option>
                        <?php foreach ($cines as $cine) { ?>
                            <option value=<?php echo ($cine->getIdCine()); ?>> <?php echo ($cine->getIdCine() . " - (" . $cine->getNombre() . ", " . $cine->getDireccion()->getProvincia() . ", " . $cine->getDireccion()->getCiudad() . ", " . $cine->getDireccion()->getCalle() . " " . $cine->getDireccion()->getAltura() . ")");
                                                                                    } ?> </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" maxlength="40" pattern="[A-Za-z]{1-40}" title="Solo puede ingresar letras" />
                </div>

                <div class="form-group">
                    <label>Capacidad de butacas</label>
                    <input type="number" class="form-control" name="capacidadButacas" min=1 max=2000 />
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/sala/index">Cancelar</a>
                <button type="submit" class="btn btn-primary">Modificar</button>
            </div>

        </form>
    <?php } else
        $msg = "No se puede realizar esta accion porque no se encuentran cines cargados."; ?>

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
        <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/sala/index">Volver</a>

    <?php } ?>
    </div>