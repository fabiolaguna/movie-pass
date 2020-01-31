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
            <div class="intro-heading">Administración de salas</div>
        </div>
    </div>
</header>
<br>
<div class="container">
    <form action="<?php echo (FRONT_ROOT) ?>/sala/index" method="GET">

        <div class="col-md">
            <div class="bd-example">
                <br>
                <br>
                <a class="btn btn-primary" href="<?php echo (FRONT_ROOT); ?>/sala/index?action=agregarSala">Agregar sala</a>
                <a class="btn btn-danger" href="<?php echo (FRONT_ROOT) ?>/cine/index">Volver</a>
            </div>
        </div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <br>
            <br>
            <div class="card shadow mb-4" style="color:black; border: 1px solid #1d1d1b;">
                <div class="card-header py-3" style="border: 1px solid #1d1d1b;">
                    <h4 class="m-0 font-weight-bold text-primary">Listado de salas</h4>
                </div>
                <div class="card-body" style="border: 1px solid #1d1d1b;">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="text-align:center;" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><strong>Id de la sala</strong></th>
                                    <th><strong>Id del cine</strong></th>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Precio de entrada</strong></th>
                                    <th><strong>Capacidad de butacas</strong></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $salaController = new controllers\salaController();
                                $arraySalas = $salaController->listarSalas();
                                if (!empty($arraySalas)) {
                                    $i = 0;
                                    $cantSalas = count($arraySalas);
                                    while ($i < $cantSalas) { ?>
                                        <tr>
                                            <td> <?php echo ($arraySalas[$i]->getIdSala()); ?> </td>
                                            <td> <?php echo ($arraySalas[$i]->getIdCine()); ?> </td>
                                            <td> <?php echo ($arraySalas[$i]->getNombre()); ?> </td>
                                            <td> <?php echo "$" . ($arraySalas[$i]->getPrecio()); ?> </td>
                                            <td> <?php echo ($arraySalas[$i]->getCapacidadButacas()); ?> </td>
                                            <td>
                                                <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT); ?>/sala/eliminarSala?idSala=<?php echo ($arraySalas[$i]->getIdSala()); ?>" onclick="clicked(event)">Eliminar</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="<?php echo (FRONT_ROOT); ?>/sala/index?idSala=<?php echo ($arraySalas[$i]->getIdSala()); ?>&action=modificarSala">Modificar</a>
                                            </td>
                                            <?php $i++; ?>
                                        </tr>
                                <?php }
                                } else {
                                    $msg = 'No hay salas cargadas';
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
        </div>
    <?php } ?>                            
</div>