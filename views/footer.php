    <script>
        function clicked(e) {
            if (!confirm('Estas seguro?')) e.preventDefault();
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo (ASSETS_PATH) ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo (ASSETS_PATH) ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="<?php echo (ASSETS_PATH) ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="<?php echo (ASSETS_PATH) ?>/js/jqBootstrapValidation.js"></script>
    <script src="<?php echo (ASSETS_PATH) ?>/js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="<?php echo (ASSETS_PATH) ?>/js/agency.min.js"></script>
    <script src="<?php echo (ASSETS_PATH) ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo (ASSETS_PATH) ?>/vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo (ASSETS_PATH) ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo (ASSETS_PATH) ?>/js/demo/datatables-demo.js"></script>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <?php
                    if (!isset($_SESSION["loggedRole"])) {
                    ?>
                        <span style="color:white;" class="copyright">Copyright &copy; MoviePass</span>
                        <?php
                    } else {
                        if (isset($_GET["cantidadEntradas"])) { ?>
                            <span style="color:white;" class="copyright">Copyright &copy; MoviePass</span>
                        <?php unset($_GET["cantidadEntradas"]); } else {
                            if($_SERVER["REQUEST_URI"] == "/movie-pass/entrada/index")
                            {
                                ?>
                                <span style="color:white;" class="copyright">Copyright &copy; MoviePass</span>
                                <?php
                            }else{
                                if($_SESSION["loggedRole"]=='admin'){
                                    if($_SERVER["REQUEST_URI"] == "/movie-pass/home/index"){?>
                                        <span style="color:black;" class="copyright">Copyright &copy; MoviePass</span>
                                <?php } else{
                                     ?><span style="color:white;" class="copyright">Copyright &copy; MoviePass</span>
                                <?php }
                                }
                                else { ?>
                                <span style="color:black;" class="copyright">Copyright &copy; MoviePass</span>
                        <?php   } 
                            } 
                        }
                        ?>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </footer>
    </body>

    </html>