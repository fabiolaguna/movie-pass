<?php
$proyeccionController = new controllers\ProyeccionController();
$cartelera = $proyeccionController->proyeccionesToCartelera();
?>
<style>
    body {
        background: linear-gradient(to bottom, black 30%, white);
    }
</style>
<!-- Header -->
<header class="masthead">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Bienvenido a MoviePass!</div>
        </div>
    </div>
</header>
<br><br>
<div class="col-md">
    <div class="bd-example">
        <a style="border-radius: 15px;" class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index?action=consultarPeliculasFecha">Cartelera a partir de una fecha </a>
        <a style="border-radius: 15px;" class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index?action=consultarPeliculasGenero">Cartelera por genero </a>
        <a style="border-radius: 15px;" class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index?action=consultarPeliculasFechaYGenero">Cartelera por fecha y genero </a>
    </div>
</div>
<div id="portfolio">
    <div class="container">
        <br><br><br>
        <?php if (!empty($cartelera)) { ?>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 style="color: white;" class="section-heading text-uppercase">Cartelera</h2>
                    <br>
                </div>
            </div>
            <div class="row">
                <?php
                    $cant = count($cartelera);
                    for ($i = 0; $i < $cant; $i++) { ?>
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a class="portfolio-link" href="<?php echo (FRONT_ROOT) ?>/proyeccion/proyeccionDePelicula?idPelicula=<?php echo ($cartelera[$i]["idPelicula"]) ?>">
                            <img style="border-radius: 20px; height: 500px; width: 120%;" class="img-fluid" src="https://image.tmdb.org/t/p/w500/<?php echo ($cartelera[$i]["imagen"]); ?>">
                        </a>
                        <div class="portfolio-caption" style="border-radius: 5px; background: transparent; color:black">
                            <h4><?php echo ($cartelera[$i]["titulo"]); ?></h4>
                        </div>
                    </div>
                <?php } ?>
            <?php } else {
                $msg = "La cartelera esta vacia por el momento, disculpe las molestias";
            } ?>
            </div>
    </div>
    <div class="container">
    <?php if (isset($msg)) { ?>
        <div class="alert alert-primary alert-dismissible fade show mt-3 text-center" style="font-size:20px; border-radius:15px" role="alert">
            <strong>
                <?php
                    if (isset($msg))
                        echo $msg;
                    ?>
            </strong>
        </div>
    <?php } ?>
    </div>
</div>
