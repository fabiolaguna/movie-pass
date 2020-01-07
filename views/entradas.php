<style>
    body {
        background: linear-gradient(to bottom, grey 30%, black);
    }
</style>
<div class="container">
    <br>
    <header class="formularios">
        <div class="container">
            <div class="intro-text">
                <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
                <div class="intro-heading">Entradas</div>
            </div>
        </div>
    </header>
    <br>
    <?php
    $entradaController = new controllers\EntradaController();
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "pelicula")
            $valoresAMostrar = $entradaController->ordenarPorPelicula();
        if ($_GET["action"] == "fecha")
            $valoresAMostrar = $entradaController->ordenarPorFecha();
    } else
        $valoresAMostrar = $entradaController->mostrarEntradasEnVista();
    if (!empty($valoresAMostrar)) { ?>
        <div class="container-fluid">
            <div class="card shadow mb-3" style="color:black; border-radius:20px">
                <div class="card-body" style="border-radius:20px">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="text-align:center;" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Número de entrada</th>
                                    <th>Cine</th>
                                    <th>Sala</th>
                                    <th>Pelicula</th>
                                    <th>Precio</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>QR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $cant = count($valoresAMostrar);
                                for ($i = 0; $i < $cant; $i++) {
                                ?>
                                    <tr>
                                        <td> <?php echo ($valoresAMostrar[$i]["idEntrada"]); ?> </td>
                                        <td> <?php echo ($valoresAMostrar[$i]["cine"]); ?> </td>
                                        <td> <?php echo ($valoresAMostrar[$i]["sala"]); ?> </td>
                                        <td> <?php echo ($valoresAMostrar[$i]["pelicula"]); ?> </td>
                                        <td> <?php echo (round($valoresAMostrar[$i]["precio"] * 100) / 100); ?> </td>
                                        <td> <?php echo ($valoresAMostrar[$i]["fecha"]); ?> </td>
                                        <td> <?php echo ($valoresAMostrar[$i]["horario"]); ?> </td>
                                        <td> <?php echo ($valoresAMostrar[$i]["qr"]); ?> </td>
                                    </tr>
                            <?php
                                            }
                                        } else
                                            $msg = "No posee ninguna entrada actualmente";
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="container">
    <?php if (isset($msg)) { ?>
        <br>
        <div class="alert alert-primary alert-dismissible fade show mt-3 text-center" style="border-radius:30px" role="alert">
            <h3 class="alert-heading"><?php echo $msg;  ?>...</h3>
            <hr>
            <p style="font-size:20px;">Vuelva al Home, seleccione una pelicula de la cartelera y ¡compre una entrada!</p>
        </div>
        <br>
        <br>
    <?php } ?>
</div>
<br>
<br>
<br>