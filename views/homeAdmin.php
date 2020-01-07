<!-- Header -->
<header class="masthead">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Bienvenido a MoviePass!</div>
        </div>
    </div>
</header>

<br>
<br>
<div class="container wow fadeInUp">

    <h2 class="sub-heading text-center">Peliculas now playing</h2>
    <br>
    <div class="tab-content row justify-content-center">

        <div role="tabpanel" class="col-lg-9 tab-pane fade show active" id="nowplaying">
            <br>
            <?php
            $peliculaController = new controllers\PeliculaController();
            $arrayPelicula = $peliculaController->apiToList();
            foreach ($arrayPelicula as $pelicula) { ?>
                <div class="row schedule-item">
                    <div class="col-sm-2"> 
                        <strong> Fecha de estreno: </strong>
                        <br>
                        <date> <?php echo ($pelicula->getFechaEstreno()); ?> </date>
                    </div>
                    <div class="col-md">
                        <h4> <?php echo ($pelicula->getNombrePelicula()); ?> </h4>
                        <p> <?php echo ($pelicula->getDescripcion()); ?> </p>
                    </div>
                    <div class="col-sm-2 text-right">
                        <a class="btn btn-primary btn-sm" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index?idPelicula=<?php echo ($pelicula->getIdPelicula()) ?>">Agregar a proyección</a>
                    </div>
                </div>
                <br>
            <?php }  ?>
            <br>
        </div>

    </div>

</div>