    <?php
    $peliculaCartelera = $_SESSION["peliculaCartelera"];
    unset($_SESSION["peliculaCartelera"]); //Si se hace el unset, despues no se puede volver atras 

    $funcionesAgotadas = true;
    foreach ($peliculaCartelera["proyecciones"] as $proye) {
        if ($proye->getAsientosDisponibles() > 0)
            $funcionesAgotadas = false;
    }

    $categorias = $peliculaCartelera["generos"];
    $cantCategorias = count($categorias);
    $nombresCategorias = "";
    for ($j = 0; $j < $cantCategorias - 1; $j++)
        $nombresCategorias = $nombresCategorias . $categorias[$j] . ", ";
    $nombresCategorias = $nombresCategorias . $categorias[$cantCategorias - 1];


    if (!$funcionesAgotadas) { ?>
        <style>
            header.pelicula {
                text-align: center;
                color: #fed136;
                background-image: url("https://image.tmdb.org/t/p/original/<?php echo ($peliculaCartelera["poster"]); ?>");
                background-repeat: no-repeat;
                background-attachment: scroll;
                background-position: center;
                background-size: cover;
            }

            header.pelicula .intro-text {
                padding-top: 100px;
                padding-bottom: 100px;
            }

            header.pelicula .intro-text .intro-heading {
                font-size: 50px;
                font-weight: 700;
                line-height: 50px;
                margin-bottom: 25px;
                font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            }

            @media (min-width: 768px) {
                header.pelicula .intro-text {
                    padding-top: 200px;
                    padding-bottom: 140px;
                }

                header.pelicula .intro-text .intro-heading {
                    font-size: 70px;
                    font-style: italic;
                    font-weight: 500;
                    line-height: 75px;
                    margin-top: 20px;
                    margin-bottom: 205px;
                    font-family: 'Droid Serif', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
                }
            }
        </style>
        <div style="background:#212529">
            <br><br><br><br><br>
        </div>
        <header class="pelicula">
            <div class="container">
                <div class="intro-text">
                    <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
                </div>
            </div>
        </header>
        <br><br>
        <div id="portfolio">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <img style="border-radius: 20px; height: 400px; width: 90%;" class="img-fluid" src="https://image.tmdb.org/t/p/w500/<?php echo ($peliculaCartelera["imagen"]); ?>">
                    </div>
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <div class="col-lg-12 text-center">
                            <h4 style="color: black; background:goldenrod; border-radius: 20px" class="section-heading text-uppercase">Descripción</h4>
                            <br>
                        </div>
                        <div class="portfolio-caption" style="border-radius: 5px; background: transparent; color:black; border: 2px solid #212529; border-radius: 20px">
                            <?php echo ($peliculaCartelera["descripcion"]); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <div class="col-lg-12 text-center">
                            <h4 style="color: black; background:goldenrod; border-radius: 20px" class="section-heading text-uppercase">Genero/s</h4>
                            <br>
                        </div>
                        <div class="portfolio-caption" style="border-radius: 20px; background: transparent; color:black; border: 2px solid #212529;">
                            <?php echo ($nombresCategorias); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <?php
                        $cant = count($peliculaCartelera["salas"]);
                        for ($i = 0; $i < $cant; $i++) { ?>
                        <div class="col-md-4 col-sm-6 portfolio-item">
                            <div class="portfolio-caption">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 portfolio-item">
                            <div class="portfolio-caption" style="background: transparent; color:black;">

                                <?php
                                        $cine = controllers\CineController::readCine($peliculaCartelera["salas"][$i]->getIdCine());
                                        $showCine = false;

                                        for ($j = 0; $j < $i; $j++) {
                                            if ($cine->getIdCine() == $peliculaCartelera["salas"][$j]->getIdCine()) { //Para que no se muestre repetido
                                                $showCine = true;
                                            }
                                        }
                                        if (!$showCine)
                                            echo ($cine->getNombre() . ", " . $cine->getDireccion()->getProvincia() . ", " . $cine->getDireccion()->getCiudad() . ", " . $cine->getDireccion()->getCalle() . " " . $cine->getDireccion()->getAltura()); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 portfolio-item">
                            <div class="portfolio-caption" style="background: transparent; color:black;">
                                <?php
                                        $cantProyeccion = count($peliculaCartelera["proyecciones"]);
                                        for ($j = 0; $j < $cantProyeccion; $j++) {
                                            if ($peliculaCartelera["salas"][$i]->getIdSala() == $peliculaCartelera["proyecciones"][$j]->getIdSala() && $peliculaCartelera["proyecciones"][$j]->getAsientosDisponibles() > 0) {
                                                $fechaHorario = $peliculaCartelera["proyecciones"][$j]->getFecha() . ", " . $peliculaCartelera["proyecciones"][$j]->getHorario();
                                                echo ($fechaHorario); ?>
                                        <a class="btn btn-primary btn-sm" style="border-radius: 20px;" href="<?php echo (FRONT_ROOT); ?>/compra/index?idProyeccion=<?php echo ($peliculaCartelera["proyecciones"][$j]->getIdProyeccion()); ?>">Comprar entrada </a>
                                        <br><br>
                                            <?php }
                                        } ?>
                            </div>
                        </div>  
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } else {
        $msg = "Funciones agotadas";
    } ?>
    <div class="container">
        <?php if (isset($msg)) { ?>
            <div class="alert
             <?php
                    echo 'alert-success'; ?> 
                alert-dismissible fade show mt-3" role="alert">
                <strong>
                    <?php
                        echo $msg;
                        ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
    </div>