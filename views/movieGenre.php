<div class="container">
    <form action="<?php echo (FRONT_ROOT) ?>/pelicula/index">
        <select class="form-control" name="genre" required>
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
        <button type="submit" class="btn btn-dark">
            Enviar
        </button>
    </form>
    <?php if (isset($_SESSION["movieGenre"])) { ?>
        <main class="p-5">
            <div class="container">

                <h2 class="mb-5">Listado de peliculas por categoria</h2>
                <table border="1" class="table" style="text-align:center;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Titulo</th>
                            <th>Puntaje</th>
                            <th>Descripcion</th>
                            <th>Fecha de estreno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $arrayMovies = $_SESSION["movieGenre"];
                            unset($_SESSION["movieGenre"]);
                            $i = 0;
                            $cantPeliculas = count($arrayMovies);
                            while ($i < $cantPeliculas) {  ?>
                            <tr>
                                <td> <?php echo ($arrayMovies[$i]['title']); ?> </td>
                                <td> <?php echo ($arrayMovies[$i]['vote_average']); ?> </td>
                                <td> <?php echo ($arrayMovies[$i]['overview']); ?> </td>
                                <td> <?php echo ($arrayMovies[$i]['release_date']); ?> </td>
                                <?php
                                        $i++;
                                        ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    <?php } ?>
</div>