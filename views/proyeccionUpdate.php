<style>
    body {
        background-color: #1d1d1b;
    }
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
<div class="container">
    <form class="modal-content" style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/proyeccion/modificarProyeccion" method="POST">

        <div class="modal-body">
            <?php
            $idProyeccion = $_GET['idProyeccion'];
            unset($_GET['idProyeccion']);
            $proyeccion = controllers\ProyeccionController::buscarProyeccion($idProyeccion);
            $sala = controllers\SalaController::readSala($proyeccion->getIdSala()) ?>
            <p><strong>Id de proyeccion a modificar:</strong> <?php echo ($proyeccion->getIdProyeccion() . "<br>Id sala: " . $proyeccion->getIdSala() . "<br>Id cine: " . $sala->getIdCine() . "<br>Id Pelicula: " . $proyeccion->getIdPelicula() . "<br>Cantidades remanentes: " . $proyeccion->getAsientosDisponibles() . "<br>Cantidades vendidas: " . $proyeccion->getAsientosOcupados() . "<br>Fecha: " . $proyeccion->getFecha() . "<br>Horario: " . $proyeccion->getHorario());  ?></p>

            <?php
            $salaController = new controllers\SalaController();
            $salas = $salaController->listarSalas();

            $peliculaController = new controllers\PeliculaController();
            $arrayPelicula = $peliculaController->apiToList();
            ?>

            <div class="form-group">
                <input type="hidden" class="form-control" name="idProyeccion" value="<?php echo ($idProyeccion); ?>">
            </div>

            <div class="form-group">
                <br><label>Id de la sala</label>
                <input type="hidden" class="form-control" name="idSala">
                <select class="form-control" name="idSala" id="idSala">
                    <option value="" disabled selected>Seleccionar sala</option>
                    <?php foreach ($salas as $sala) {
                        $cine = controllers\CineController::readCine($sala->getIdCine()) ?>
                        <option value=<?php echo ($sala->getIdSala()); ?>> <?php echo ($sala->getIdSala() . ", " . $sala->getNombre() . ", " . $sala->getCapacidadButacas() . " butacas - (" . $cine->getNombre() . ", " . $cine->getDireccion()->getProvincia() . ", " . $cine->getDireccion()->getCiudad() . ", " . $cine->getDireccion()->getCalle() . " " . $cine->getDireccion()->getAltura() . ")");
                                                                            } ?> </option>
                </select>
            </div>

            <div class="form-group">
                <label>Id de la pelicula</label>
                <input type="hidden" class="form-control" name="idPelicula">
                <select class="form-control" name="idPelicula" id="idPelicula">
                    <option value="" disabled selected>Seleccionar pelicula</option>
                    <?php foreach ($arrayPelicula as $peli) { ?>
                        <option value=<?php echo ($peli->getIdPelicula()); ?>> <?php echo ($peli->getIdPelicula() . " - (" . $peli->getNombrePelicula() . ")");
                                                                                } ?> </option>
                </select>
            </div>

            <div class="form-group">
                <label>Cantidades remanentes</label>
                <input type="number" class="form-control" min=0 name="asientosDisponibles">
            </div>

            <div class="form-group">
                <label>Fecha</label>
                <input type="date" class="form-control" name="fecha" min=<?php
                                                                            $fecha = date('Y-m-d');
                                                                            $nuevafecha = strtotime('+1 day', strtotime($fecha));
                                                                            $nuevafecha = date('Y-m-d', $nuevafecha);
                                                                            echo ($nuevafecha); ?> max="2030-01-01">
            </div>

            <div class="form-group">
                <label>Horario</label>
                <input name="horario" type="time">
            </div>

        </div>
        <div class="modal-footer">
            <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index">Cancelar</a>
            <button type="submit" class="btn btn-primary">Modificar</button>
        </div>
    </form>
</div>
