<style>
    body {
        background-color: #212529;
    }
</style>
<!-- Header -->
<header class="masthead">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Listado de proyecciones</div>
        </div>
    </div>
</header>

<br>
<br>
<form action="<?php echo (FRONT_ROOT) ?>/cine/index" method="GET">
    <div class="container-fluid">
        <br>
        <br>
        <div class="card shadow mb-3" style="color:black; border: 1px solid #1d1d1b;">
            <div class="card-header py-3" style="border: 1px solid #1d1d1b;">
                <h4 class="m-0 font-weight-bold text-primary">Proyecciones</h4>
            </div>
            <div class="card-body" style="border: 1px solid #1d1d1b;">
                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align:center;" id="dataTable" width="100%" cellspacing="0">

                        <thead>
                            <tr>
                                <th>Id Proyeccion</th>
                                <th>Cine</th>
                                <th>Sala</th>
                                <th>Pelicula</th>
                                <th>Cantidades remanentes</th>
                                <th>Cantidades vendidas</th>
                                <th>Fecha</th>
                                <th>Horario</th>
                                <th hidden></th>
                                <th hidden></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            controllers\ProyeccionController::listarProyecciones();
                            $arrayProyecciones = null;
                            if (isset($_SESSION["proyecciones"])) {
                                $arrayProyecciones = $_SESSION["proyecciones"];
                                unset($_SESSION["proyecciones"]);
                            }
                            if ($arrayProyecciones != null) {
                                $i = 0;
                                $cantProyecciones = count($arrayProyecciones);
                                while ($i < $cantProyecciones) {
                                    $sala = controllers\SalaController::readSala($arrayProyecciones[$i]->getIdSala());
                                    $cine = controllers\CineController::readCine($sala->getIdCine());
                                    $pelicula = controllers\PeliculaController::readPelicula($arrayProyecciones[$i]->getIdPelicula()); 
                                    ?>
                                    
                                   <tr>
                                        <td> <?php echo ($arrayProyecciones[$i]->getIdProyeccion()); ?> </td>
                                        <td> <?php echo ($cine->getNombre()); ?> </td>
                                        <td> <?php echo ($sala->getNombre()); ?> </td>
                                        <td> <?php echo ($pelicula->getNombrePelicula()); ?> </td>
                                        <td> <?php echo ($arrayProyecciones[$i]->getAsientosDisponibles()); ?> </td>
                                        <td> <?php echo ($arrayProyecciones[$i]->getAsientosOcupados()); ?> </td>
                                        <td> <?php echo ($arrayProyecciones[$i]->getFecha()); ?> </td>
                                        <td> <?php echo ($arrayProyecciones[$i]->getHorario()); ?> </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT); ?>/proyeccion/eliminarProyeccion?idProyeccion=<?php echo ($arrayProyecciones[$i]->getIdProyeccion()); ?>" onclick="clicked(event)">Eliminar</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="<?php echo (FRONT_ROOT); ?>/proyeccion/index?idProyeccion=<?php echo ($arrayProyecciones[$i]->getIdProyeccion()); ?>">Modificar</a>
                                        </td>
                                        <?php $i++; ?>
                                    </tr>
                            <?php }
                            } else {
                                $msg = 'No hay proyecciones cargadas';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
