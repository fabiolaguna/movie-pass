<style>
    body {
        background-color: #212529;
    }
</style>
<header class="ventas">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Totales cine</div>
        </div>
    </div>
</header>
<br> <br> <br> <br>
<div class="col-md">
    <div class="bd-example">
        <br>
        <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/entrada/index?action=consultarPorPelicula">Consultar por pelicula </a>
    </div>
</div>
<br><br>

<div class="form-group col-sm-5">
    <h4 style="color: goldenrod;"><strong>Busqueda entre fechas</strong></h4>
</div>
<form class="form-group " action="<?php echo (FRONT_ROOT) ?>/entrada/index">
    <div class="form-group col-sm-5  text-white">
        <label><strong>Primer fecha</strong></label>
        <input style="border: 2px solid goldenrod;" type="date" class="form-control" name="fecha1cine" required>
    </div>
    <div class="form-group col-sm-5 text-white">
        <label><strong>Segunda fecha</strong></label>
        <input style="border: 2px solid goldenrod;" type="date" class="form-control" name="fecha2cine" required>
    </div>
    <div class="bd-example col-sm-5">
        <button type="submit" class="btn btn-primary">Elegir</button>
    </div>
</form>
<!-- Begin Page Content -->
<?php
if (isset($_GET["fecha1cine"]) && isset($_GET["fecha2cine"])) {
?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <br>
        <br>
        <div class="card shadow mb-4" style="color:black; border: 1px solid #b38d05;">
            <div class="card-body" style="border: 1px solid #b38d05;">
                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align:center;" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Cine</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            controllers\CineController::listarCines();
                            if (isset($_SESSION["cines"])) {
                                $arrayCines = $_SESSION["cines"];
                                unset($_SESSION["cines"]);
                                if (!empty($arrayCines)) {
                                    foreach ($arrayCines as $cine) {  ?>
                                        <tr>
                                            <td> <?php echo ($cine->getNombre()); ?> </td>
                                            <td> $<?php echo (controllers\EntradaController::totalVendidoEntreFechasCine($_GET["fecha1cine"], $_GET["fecha2cine"], $cine->getIdCine()));
                                                    ?> </td>
                                        </tr>
                            <?php }
                                } else
                                    $msg = "No hay cines cargados";
                            } else
                                $msg = "No hay cines cargados"; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
<?php
} else {    ?>
    <div class="container-fluid">
        <br>
        <br>
        <div class="card shadow mb-4" style="color:black; border: 1px solid #b38d05;">
            <div class="card-body" style="border: 1px solid #b38d05;">
                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align:center;" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Cine</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            controllers\CineController::listarCines();
                            if (isset($_SESSION["cines"])) {
                                $arrayCines = $_SESSION["cines"];
                                unset($_SESSION["cines"]);
                                if (!empty($arrayCines)) {
                                    foreach ($arrayCines as $cine) {  ?>
                                        <tr>
                                            <td> <?php echo ($cine->getNombre()); ?> </td>
                                            <td> $<?php echo (controllers\EntradaController::totalVendidoCine(($cine->getIdCine())));
                                                    ?> </td>
                                        </tr>

                            <?php }
                                } else
                                    $msg = "No hay cines cargados";
                            } else
                                $msg = "No hay cines cargados"; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
<?php
} ?>

<?php if (isset($msg)) { ?>
    <div class="container">
        <div class="alert
                <?php
                echo 'alert-success'; ?> 
                    alert-dismissible fade show mt-3" role="alert">
            <strong>
                <?php
                echo $msg;
                ?>
            </strong>
        </div>
    </div>
<?php }
?>
</div>