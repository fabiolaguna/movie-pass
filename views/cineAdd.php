<style>
    body { background-color: #212529; }
</style>
<br> <br> <br> <br> 
<header class="formularios">
    <div class="container">
        <div class="intro-text">
            <!--<div class="intro-lead-in">Bienvenido a MoviePass!</div> -->
            <div class="intro-heading">Registrar cine</div>
        </div>
    </div>
</header>
<div class="container">
    <form class="modal-content"  style="border: 2px solid goldenrod; color:black;" action="<?php echo (FRONT_ROOT) ?>/cine/agregarCine" method="POST">
        <div class="modal-body">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="name" maxlength="40" pattern="[A-Za-z]{1-40}" title="Solo puede ingresar letras" required />
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label>Provincia</label>
                    <?php
                        use controllers\ProvinciaController as ProvinciaController;
                        $provincias = new ProvinciaController();
                        $nombres = $provincias->getNombresProvincia();
                        $cantidad = count($nombres);
                    ?>
                    <select class="form-control" name="provincia" required>
                        <option value="" disabled selected>Seleccionar provincia</option>
                        <?php
                        for ($i = 0; $i < $cantidad; $i++){
                            ?><option value="<?php echo ($nombres[$i]); ?>"><?php echo ($nombres[$i]); } ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" class="form-control" name="ciudad" maxlength="40" pattern="[A-Za-z]{1-40}" title="Solo puede ingresar letras" required />
                </div>
                <div class="form-group">
                    <label>Calle</label>
                    <input type="text" class="form-control" name="calle" maxlength="40" pattern="[A-Za-z]{1-40}" title="Solo puede ingresar letras" required />
                </div>
                <div class="form-group">
                    <label>Altura</label>
                    <input type="number" class="form-control" name="altura" min=1 max=100000 required />
                </div>
            </div>

            <div class="form-group">
                <label>Precio Entrada</label>
                <input type="number" class="form-control" name="precio" min=1 step=1 max=1000 required />
            </div>

        </div>

        <div class="modal-footer">
            <a class="btn btn-danger btn-sm" href="<?php echo (FRONT_ROOT) ?>/cine/index">Cancelar</a>
            <button type="submit" class="btn btn-primary">Agregar cine</button>
        </div>
    </form>
</div>