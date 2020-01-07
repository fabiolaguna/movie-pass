<style>
    body {
        background-color: #212529;
    }
</style>

<!-- Header -->
<header class="cineAdmin">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Administración de cines</div>
        </div>
    </div>
</header>

<form action="<?php echo (FRONT_ROOT) ?>/cine/index" method="GET">
    <div class="col-md">
        <div class="bd-example">
            <br>
            <br>
            <a class="btn btn-primary" href="<?php echo (FRONT_ROOT); ?>/cine/index?action=agregarCine">Agregar cine </a>
            <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/sala/index"> Administrar salas </a>
        </div>
    </div>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <br>
        <br>
        <!-- DataTales Example -->
        <div class="card shadow mb-4" style="color:black; border: 1px solid #1d1d1b;">
            <div class="card-header py-3" style="border: 1px solid #1d1d1b;">
                <h4 class="m-0 font-weight-bold text-primary">Listado de cines</h4>
            </div>
            <div class="card-body" style="border: 1px solid #1d1d1b;">
                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align:center;" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><strong>Id del cine</strong></th>
                                <th><strong>Nombre</strong></th>
                                <th><strong>Capacidad</strong></th>
                                <th><strong>Cantidad de salas</strong></th>
                                <th><strong>Precio de entrada</strong></th>
                                <th><strong>Provincia</strong></th>
                                <th><strong>Ciudad</strong></th>
                                <th><strong>Calle</strong></th>
                                <th><strong>Altura</strong></th>
                                <th hidden></th>
                                <th hidden></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            controllers\CineController::listarCines();
                            $salaController = new controllers\SalaController();
                            $arraySalas = null;
                            $arraySalas = $salaController->listarSalas();
                            if ($arraySalas != null)
                                $cantSalas = count($arraySalas);

                            $arrayCines = null;
                            if (isset($_SESSION["cines"])) {
                                $arrayCines = $_SESSION["cines"];
                                unset($_SESSION["cines"]);
                            }
                            if ($arrayCines != null) {
                                $i = 0;
                                $cantCines = count($arrayCines);
                                while ($i < $cantCines) {
                                    $cantidad = 0;
                                    if ($arraySalas != null) {
                                        for ($j = 0; $j < $cantSalas; $j++) {
                                            if ($arrayCines[$i]->getIdCine() == $arraySalas[$j]->getIdCine())
                                                $cantidad++;
                                        }
                                    } ?>
                                    <tr>
                                        <td> <?php echo ($arrayCines[$i]->getIdCine()); ?> </td>
                                        <td> <?php echo ($arrayCines[$i]->getNombre()); ?> </td>
                                        <td> <?php echo ($arrayCines[$i]->getCapacidadTotal()); ?> </td>
                                        <td> <?php echo $cantidad; ?> </td>
                                        <td> <?php echo "$" . ($arrayCines[$i]->getPrecioEntrada()); ?> </td>
                                        <td> <?php
                                                        $direccion = $arrayCines[$i]->getDireccion();
                                                        echo ($direccion->getProvincia()); ?> </td>
                                        <td> <?php
                                                        $direccion = $arrayCines[$i]->getDireccion();
                                                        echo ($direccion->getCiudad()); ?> </td>
                                        <td> <?php
                                                        $direccion = $arrayCines[$i]->getDireccion();
                                                        echo ($direccion->getCalle()); ?> </td>
                                        <td> <?php
                                                        $direccion = $arrayCines[$i]->getDireccion();
                                                        echo ($direccion->getAltura()); ?> </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT); ?>/cine/eliminarCine?idCine=<?php echo ($arrayCines[$i]->getIdCine()); ?>" onclick="clicked(event)">Eliminar</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="<?php echo (FRONT_ROOT); ?>/cine/index?idCine=<?php echo ($arrayCines[$i]->getIdCine()); ?>&action=modificarCine">Modificar</a>
                                        </td>
                                        <?php $i++; ?>
                                    </tr>
                            <?php }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</form>
