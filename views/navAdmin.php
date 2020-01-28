<!--
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand mb-0 h1" href="<?php echo (FRONT_ROOT) ?>/home/index">MoviePass</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/home/index">Home</a>
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/pelicula/index">Peliculas</a>
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/cine/index">Administrar cines</a>
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index">Proyecciones</a>
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/entrada/index">Consultar ventas</a>
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/usuario/index">Agregar administrador</a>
        <a class="navbar-brand" href="<?php echo (FRONT_ROOT) ?>/home/logout">Logout</a>
    </div>
</nav>
-->

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <a class="navbar-brand js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/home/index">MoviePass</a>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/home/index">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/cine/index">Cines</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/proyeccion/index">Proyecciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/entrada/index">Ventas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/usuario/index">Agregar admin</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/usuario/index?action=5">Mi cuenta</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/home/logout">Logout <?php echo(" ");?><small><?php echo("(".$_SESSION["loggedEmail"].")"); ?></small></a>
            </li>
        </ul>
    </div>
</nav>