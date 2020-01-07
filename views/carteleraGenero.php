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
        <a style="border-radius: 15px;" class="btn btn-primary" href="<?php echo (FRONT_ROOT); ?>/proyeccion/index?action=consultarPeliculasFecha">Cartelera a partir de una fecha </a>
        <a style="border-radius: 15px;" class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index?action=consultarPeliculasGenero">Cartelera por genero </a>
        <a style="border-radius: 15px;" class="btn btn-primary" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index?action=consultarPeliculasFechaYGenero">Cartelera por fecha y genero </a>
    </div>
</div>
<br><br>
<form class="form-group" action="<?php echo (FRONT_ROOT) ?>/proyeccion/index">
    <div class="form-group col-sm-5  text-white">
        <select style="border-radius: 15px; border: 2px solid goldenrod; color:black" class="form-control" name="genre" required>
            <option value="" disabled selected>Seleccionar genero</option>
            <option value="Acción">Acción</option>
            <option value="Aventura">Aventura</option>
            <option value="Animación">Animación</option>
            <option value="Comedia">Comedia</option>
            <option value="Crimen">Crimen</option>
            <option value="Documental">Documental</option>
            <option value="Drama">Drama</option>
            <option value="Familia">Familia</option>
            <option value="Fantasía">Fantasía</option>
            <option value="Historia">Historia</option>
            <option value="Terror">Terror</option>
            <option value="Música">Música</option>
            <option value="Misterio">Misterio</option>
            <option value="Romance">Romance</option>
            <option value="Ciencia ficción">Ciencia ficción</option>
            <option value="Película de TV">Película de TV</option>
            <option value="Suspense">Suspenso</option>
            <option value="Bélica">Bélica</option>
            <option value="Western">Western</option>
        </select>
    </div>
    <div class="form-group col-sm-5  text-white">
        <button style="border-radius: 15px;" type="submit" class="btn btn-primary">
            Enviar
        </button>
    </div>
</form>
<?php
if (isset($_SESSION["proyeccionGenero"])) {
    $cartelera = $_SESSION["proyeccionGenero"];
    unset($_SESSION["proyeccionGenero"]);
    if (!empty($cartelera)) { ?>
        <section id="portfolio">
            <div class="container">
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
                                <img style="border-radius: 20px;" class="img-fluid" src="https://image.tmdb.org/t/p/w500/<?php echo ($cartelera[$i]["imagen"]); ?>"></a>
                            </a>
                            <div class="portfolio-caption" style="border-radius: 5px; background: transparent; color:black">
                                <h4><?php echo ($cartelera[$i]["titulo"]); ?></h4>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } else {
            $msg = "No hay peliculas en cartelera pertenecientes a este género";
        }
    }
    if (isset($msg)) { ?>
    <div class="container">
        <br>
        <div class="alert alert-primary alert-dismissible fade show mt-3 text-center" style="font-size:20px; border-radius:15px" role="alert">
            <strong>
                <?php
                    echo $msg;
                    ?>
            </strong>
        </div>
    </div>
<?php } ?>
</div>