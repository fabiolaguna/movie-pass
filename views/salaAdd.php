<style>
    body {
        background-color: #212529;
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
                <div class="intro-heading">Registrar sala</div>
            </div>
        </div>
    </header>
    <div class="container">
        <form class="modal-content" style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/sala/agregarSala" method="POST">
            <div class="modal-body">

                <div class="form-group">
                    <label>Id del cine</label>
                    <select class="form-control" name="idCine" id="idCine" required>
                        <?php foreach ($cines as $cine) { ?>
                            <option value=<?php echo ($cine->getIdCine()); ?>> <?php echo ($cine->getIdCine() . " - (" . $cine->getNombre() . ", " . $cine->getDireccion()->getProvincia() . ", " . $cine->getDireccion()->getCiudad() . ", " . $cine->getDireccion()->getCalle() . " " . $cine->getDireccion()->getAltura() . ")");
                                                                                    } ?> </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="name" maxlength="40" pattern="[A-Za-z]{1-40}" title="Solo puede ingresar letras" required />
                </div>

                <div class="form-group">
                    <label>Capacidad de butacas</label>
                    <input type="number" class="form-control" name="capacidad" min=1 max=2000 required />
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/sala/index">Cancelar</a>
                <button type="submit" class="btn btn-primary">Agregar sala</button>
            </div>
        </form>
    </div>
<?php } else
    $msg = "No se puede realizar esta accion porque no se encuentran cines cargados."; ?>
<div class="container">
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
        </div>
        <div class="modal-footer" style="border: 2px solid #212529;">
        <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/sala/index">Volver</a>
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br>
    <?php } ?>
</div>