<style>
    body {
        background-color: #2f353b;
    }

    .modal-content {
        background-image: url("http://localhost/movie-pass/assets/img/login.jpg");
        background-position: bottom;
    }

    .btn-success {
        background-color: #1d10d3;
        border-color: black;
    }

    .btn-success:active,
    .btn-success:focus,
    .btn-success:hover {
        background-color: #103dd3 !important;
        border-color: #103dd3 !important;
        color: white;
    }

    .btn-secondary {
        background-color: grey;
        border-color: black;
    }

    .btn-secondary:active,
    .btn-secondary:focus,
    .btn-secondary:hover {
        background-color: rgb(150, 146, 146) !important;
        border-color: rgb(150, 146, 146) !important;
        color: white;
    }
</style>
<br><br><br>
<div class="container col-sm-6">
    <header class="formularios">
        <div class="container">
            <div style="font-size:55px; color:white; font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'">LOGIN</div>
        </div>
    </header>
    <br>
    <form class="modal-content" style="border-radius: 15px; color:white; text-align: center;" action="<?php echo (FRONT_ROOT) ?>/home/index" method="POST">
        <div class="modal-body">
            <br>
            <div class="form-group" style="font-size:30px;">
                <div class="container col-sm-3" style="background: #3c4349; border-radius:20px">
                    Email
                </div>
                <div style="font-size:5px; color:transparent">      a      </div>
                <input style="border-radius: 15px; color:black" type="email" class="form-control" name="user" />
            </div>
            <br><br>
            <div class="form-group" style="font-size:30px;">
                <div class="container col-sm-5" style="background: #3c4349; border-radius:20px">
                    Contraseña           
                </div>
                <div style="font-size:5px; color:transparent">      a      </div>
                <input style="border-radius: 15px; color:black" type="password" class="form-control" name="pass">
            </div>

            <div class="actions">
                <br><br><br>

                <button style="border-radius: 15px;" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#signUp">
                    Registrar usuario
                </button>

                <button style="border-radius: 15px;" type="submit" class="btn btn-success">
                    Login
                </button>
            </div>
        </div>

        <?php if (isset($alert)) { ?>
            <div class="alert
             <?php
                                                                                                            if (isset($alert))
                                                                                                                echo 'alert-success';
                                                                                                            else
                                                                                                                echo 'alert-danger'; ?> 
                alert-dismissible fade show mt-3" role="alert">
                <strong>
                    <?php
                                                                                                            if (isset($alert))
                                                                                                                echo $alert;
                    ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
    </form>


    <!-- SIGN UP -->

    <div class="modal fade" id="signUp" tabindex="-1" role="dialog" aria-labelledby="signUp" aria-hidden="true">
        <div class="modal-dialog" role="document">

            <form class="modal-content" style="background:white; color:black; border-radius: 15px;" action="<?php echo (FRONT_ROOT) ?>/home/signUp" method="POST" style="background-color: rgba(0,0,0,80%);">

                <div class="modal-header" style="background:white; color:black; border-radius: 15px;">
                    <h5 class="modal-title">Registrar usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background:white; color:black; border-radius: 15px;">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input style="border-radius: 15px; color:black" type="text" class="form-control" name="name" maxlength="30" pattern="[A-Za-z]{1-30}" title="Solo puede ingresar letras" required />
                    </div>

                    <div class="form-group">
                        <label>Apellido</label>
                        <input style="border-radius: 15px; color:black" type="text" class="form-control" name="apellido" maxlength="30" pattern="[A-Za-z]{1-30}" title="Solo puede ingresar letras" required />
                    </div>

                    <div class="form-group">
                        <label>DNI</label>
                        <input style="border-radius: 15px; color:black" type="number" class="form-control" name="dni" step=1 min=1000000 max=99999999 required />
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input style="border-radius: 15px; color:black" type="email" class="form-control" name="email" maxlength="45" required />
                    </div>

                    <div class="form-group">
                        <label>Contraseña</label>
                        <input style="border-radius: 15px; color:black" type="password" class="form-control" name="pass" maxlength="40" required />
                    </div>

                    <div class="form-group">
                        <label>Repetir contraseña</label>
                        <input style="border-radius: 15px; color:black" type="password" class="form-control" name="passrepeated" maxlength="40" required />
                    </div>

                </div>

                <div class="modal-footer">
                    <button style="border-radius: 15px;" type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button style="border-radius: 15px;" type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

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
    <br>
</div>