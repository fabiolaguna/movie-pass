<style>
    body {
        background-color: #212529;
    }
</style>
<header class="ventas">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Totales por pelicula</div>
        </div>
    </div>
</header>
<br> <br> <br>
<div class="col-md">
    <div class="bd-example">
        <br>
        <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/entrada/index">Consultar por cine </a>
        <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/entrada/index?action=consultarPorFechaPelicula">Consultar rango fecha por pelicula </a>
    </div>
</div>
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
                                <td> $<?php echo (controllers\EntradaController::totalVendidoPelicula($movie->getIdPelicula()));
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