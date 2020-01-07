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
        <a class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/entrada/index?action=consultarPorFechaCine">Consultar rango fecha por cine </a>
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