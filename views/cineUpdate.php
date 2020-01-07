<style>
    body { background-color: #1d1d1b; }
</style>
<br> <br> <br> <br> 
<header class="formularios">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Ingresar valores a modificar</div>
        </div>
    </div>
</header>
<?php
controllers\CineController::listarCines();
if (empty(($_SESSION["cines"]))) {
    $msg = "No hay cines cargados para modificar.";
} else {
    $cines = $_SESSION["cines"];
    ?>
    <div class="container">
        <form class="modal-content" style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/cine/modificarCine" method="POST">

            <div class="modal-body">
                <?php
                    $idCine = $_GET['idCine'];
                    unset($_GET['idCine']);
                    $cine = controllers\CineController::readCine($idCine);
                    $direccion = $cine->getDireccion();
                    ?>
                <p><strong>Id de cine a modificar:</strong> <?php echo ($cine->getIdCine() . "<br>Nombre: " . $cine->getNombre() . "<br>Capacidad: " . $cine->getCapacidadTotal() . "<br>Precio de entrada: " . $cine->getPrecioEntrada() . "<br>Provincia: " . $direccion->getProvincia() . "<br>Ciudad: " . $direccion->getCiudad() . "<br>Calle: " . $direccion->getCalle()  . "<br>Altura: " . $direccion->getAltura());  ?></p>

                <div class="form-group">
                    <input type="hidden" class="form-control" name="idCine" value="<?php echo ($idCine); ?>">
                </div>


                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" maxlength="40" pattern="[A-Za-z]{1-40}" title="Solo puede ingresar letras">
                </div>

                <div class="form-group">
                    <label>Precio de entrada</label>
                    <input type="number" class="form-control" name="precio" min=1 step=1 max=1000>
                </div>

                <div class="form-group">
                    <div class="form-group">
                        <label>Provincia</label>
                        <input type="hidden" class="form-control" name="provincia">
                        <?php
                            $provincias = new controllers\ProvinciaController();
                            $nombres = $provincias->getNombresProvincia();
                            $cantidad = count($nombres);
                            ?>
                        <select class="form-control" name="provincia">
                            <option value="" disabled selected>Seleccionar provincia</option>
                            <?php
                                for ($i = 0; $i < $cantidad; $i++) {
                                    ?><option value="<?php echo ($nombres[$i]); ?>"> <?php echo ($nombres[$i]);
                                                                                            } ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" maxlength="40">
                    </div>

                    <div class="form-group">
                        <label>Calle</label>
                        <input type="text" class="form-control" name="calle" maxlength="40">
                    </div>

                    <div class="form-group">
                        <label>Altura</label>
                        <input type="number" class="form-control" name="altura" min=1 max=100000>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/cine/index">Cancelar</a>
                <button type="submit" class="btn btn-primary">Modificar</button>
            </div>
        </form>
    <?php } ?>
    </div>