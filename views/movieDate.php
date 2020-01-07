<div class="container">
    <form action="<?php echo (FRONT_ROOT) ?>/pelicula/index">
        <input type="date" class="form-control" name="date" required>
        <button type="submit" class="btn btn-dark">
            Enviar
        </button>
    </form>
    <?php if (isset($_SESSION["movieDate"])) { ?>
        <main class="p-5">
            <div class="container">

                <h2 class="mb-5">Listado de peliculas por fecha</h2>
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
                            $arrayMovies = $_SESSION["movieDate"];
                            unset($_SESSION["movieDate"]);
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