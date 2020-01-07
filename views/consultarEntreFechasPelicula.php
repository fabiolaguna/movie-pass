<style>
    body {
        background-color: #212529;
    }
</style>
<header class="ventas">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Totales por pelicula entre fechas</div>
        </div>
    </div>
</header>
<br> <br> <br>
<div class="col-md">
    <div class="bd-example">
        <br>
        <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/entrada/index">Consultar por cine </a>
        <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/entrada/index?action=consultarPorPelicula">Consultar por pelicula </a>
    </div>
</div>
<br>
<br>
<form class="form-group" action="<?php echo (FRONT_ROOT) ?>/entrada/index">
    <div class="form-group col-sm-5  text-white">
        <label><strong>Primer fecha</strong></label>
        <input style="border: 2px solid goldenrod;" type="date" class="form-control" name="fecha1" required>
    </div>
    <div class="form-group col-sm-5  text-white">
        <label><strong>Segunda fecha</strong></label>
        <input style="border: 2px solid goldenrod;" type="date" class="form-control" name="fecha2" required>
    </div>
    <div class="bd-example col-sm-5 ">
        <button type="submit" class="btn btn-primary">Elegir</button>
    </div>
</form>
<?php
if (isset($_GET["fecha1"]) && isset($_GET["fecha2"])) {
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
                                <th>Pelicula</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $arrayMovies = controllers\PeliculaController::apiToListStatic();
                                foreach ($arrayMovies as $movie) {  ?>
                                <tr>
                                    <td> <?php echo ($movie->getNombrePelicula()); ?> </td>
                                    <td> $<?php echo (controllers\EntradaController::totalVendidoEntreFechasPelicula($_GET["fecha1"], $_GET["fecha2"], $movie->getIdPelicula()));
                                                    ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
<?php
}
?>
</div>