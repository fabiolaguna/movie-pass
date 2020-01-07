<style>
    body {
        background-color: #212529;
    }
</style>
<br> <br> <br> <br> 
<header class="formularios">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Registrar administrador</div>
        </div>
    </div>
</header>
<div class=container>
    <form class="modal-content" style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/usuario/signUpAdmin" method="POST">

        <div class="modal-body">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="name" maxlength="30" pattern="[A-Za-z]{1-30}" title="Solo puede ingresar letras" required />
            </div>

            <div class="form-group">
                <label>Apellido</label>
                <input type="text" class="form-control" name="apellido" maxlength="30" pattern="[A-Za-z]{1-30}" title="Solo puede ingresar letras" required />
            </div>

            <div class="form-group">
                <label>DNI</label>
                <input type="number" class="form-control" name="dni" step=1 min=1000000 max=99999999 required />
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" maxlength="45" required />
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" class="form-control" name="pass" maxlength="40" required />
            </div>

            <div class="form-group">
                <label>Repetir contraseña</label>
                <input type="password" class="form-control" name="passrepeated" maxlength="40" required />
            </div>

        </div>

        <div class="modal-footer">
            <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/home/index">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
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