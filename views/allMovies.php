<div class="container">

    <h2 class="mb-5">Listado de peliculas</h2>
    <table border="1" class="table" style="text-align:center;">
        <thead class="thead-dark">
            <tr>
                <th>Titulo</th>
                <th>Categoria/s</th>
                <th>Descripcion</th>
                <th>Fecha de estreno</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arrayMovies = $_SESSION["allmovies"];
            unset($_SESSION["allmovies"]);
            $i = 0;
            $cantPeliculas = count($arrayMovies['results']);

            use controllers\CategoriaController as Categoria;

            while ($i < $cantPeliculas) {  ?>
                <tr>
                    <!--FIJARSE DE HACERLO CON API TO LIST-->
                    <td> <?php echo ($arrayMovies['results'][$i]['title']); ?> </td>
                    <td> <?php
                                $categorias = Categoria::idToGenreName($arrayMovies['results'][$i]['genre_ids']);
                                $cantCategorias = count($categorias);
                                $nombresCategorias = "";
                                for ($j = 0; $j < $cantCategorias - 1; $j++)
                                    $nombresCategorias = $nombresCategorias . $categorias[$j] . ", ";
                                $nombresCategorias = $nombresCategorias . $categorias[$cantCategorias - 1];
                                echo ($nombresCategorias);
                                ?> </td>
                    <td> <?php echo ($arrayMovies['results'][$i]['overview']); ?> </td>
                    <td> <?php echo ($arrayMovies['results'][$i]['release_date']); ?> </td>
                    <?php
                        $i++;
                        ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>