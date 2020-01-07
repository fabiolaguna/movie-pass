<!--<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <form class="login-form">
            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#about">
                Sobre MoviePass
            </button>
        </form>
        <form class="login-form">
            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#contact">
                Contactenos
            </button>
        </form>
    </div>
</nav>-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <a class="navbar-brand js-scroll-trigger" href="<?php echo (FRONT_ROOT) ?>/home/index">MoviePass</a>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" href="#about">
                    Sobre MoviePass
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" href="#contact">
                    Contactenos
                </a>
            </li>
        </ul>
    </div>
</nav>
<div class="modal fade" id="about" tabindex="-1" role="dialog" aria-labelledby="about" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background:white; color:black; border-radius: 15px;">
            <div class="modal-header">
                <h4 class="modal-title" id="aboutTitle">Sobre MoviePass</h4>
            </div>
            <div class="modal-body">
                <p>
                    Somos una empresa dedicada a la organizacion y venta de entradas de cine, que abarca un amplio repertorio de cines
                    ubicados a lo largo de toda la Argentina. Ofrecemos los mejores descuentos y beneficios, ademas de un excelente
                    servicio de compra de entradas a traves de nuestro sitio web para su mayor comodidad.
                </p>
            </div>
            <div class="modal-footer">
                <button style="border-radius: 15px;" type="button" class="btn btn-danger" data-dismiss="modal">Volver</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="contact" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background:white; color:black; border-radius: 15px;">
            <div class="modal-header">
                <h4 class="modal-title" id="aboutTitle">Contactenos</h4>
            </div>
            <div class="modal-body">
                <p>
                    Federico Alesandro. <strong>Email:</strong> fede.alesandro@gmail.com<br /><br />
                    Fabio Laguna. <strong>Email:</strong> fabiolaguna.94@gmail.com<br /><br />
                </p>
            </div>
            <div class="modal-footer">
                <button style="border-radius: 15px;" type="button" class="btn btn-danger" data-dismiss="modal">Volver</button>
            </div>
        </div>
    </div>
</div>