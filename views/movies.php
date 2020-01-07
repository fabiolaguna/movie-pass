<br>
<div class="container">
<form class="form-inline" action="<?php echo (FRONT_ROOT) ?>/pelicula/index" method="GET">
    <div class="form-group mb-4">

        <select name='action' class="form-control ml-3">
            <option value="" disabled selected>Seleccionar consulta</option>
            <option value="consultarTodasPeliculas">Consultar todas las peliculas</option>
            <option value="consultarPeliculasFecha">Consultar peliculas por fecha</option>
            <option value="consultarPeliculasGenero">Consultar peliculas por genero</option>
            <option value="consultarPeliculasFechaYGenero">Consultar peliculas por fecha y genero</option>
        </select>
        <button type="submit" class="btn btn-dark ml-3">Elegir</button>
    </div>
</form>
</div>