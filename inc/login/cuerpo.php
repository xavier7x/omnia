<input type="hidden" id="pagsig" value="<?php if(isset( $_GET['p1']) && !empty($_GET['p1']) ){ echo $_GET['p1']; }else { echo 'inicio'; } ?>">
<!-- Modal Olvido Contraseña -->
<div class="modal fade" id="modalOlvidoContrasena" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recuperar contraseña</h4>
            </div>
            <form role="form" id="formOlvidoContrasena">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mail" class="control-label">Para recuperar sus datos favor ingrese su email</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input type="email" maxlength="90" class="form-control" id="mail"  placeholder="Ingrese su email" required/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <input class="btn btn-warning" type="submit" value="Recuperar">                    
                </div>
            </form>
        </div>
    </div>
</div>
<div class="banner-top">
	<div class="container">
		<h3>Ingreso al sistema</h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Login</h4>
		<div class="clearfix"></div>
        -->
	</div>
</div>
<!--login-->
<div class="login">
    <div class="main-agileits">
        <div class="form-w3agile">
            <h3>Iniciar sesión</h3>
            <form role="form" id="formLogin">
                <div class="key">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <input type="text" id="usuario" maxlength="90" placeholder="Usuario / email@ejemplo.com" required>
                    <div class="clearfix"></div>
                </div>
                <div class="key">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" id="contrasena" maxlength="15" placeholder="Contraseña" required>
                    <div class="clearfix"></div>
                </div>
                <input type="submit" id="submitFormLogin" value="Ingresar">
            </form>
        </div>
        <div class="forg">
            <button type="button" id="btn_reset_contrasena" class="forg-left">¿Olvidó su contraseña?</button>
            <?php 
            if(isset( $_GET['p1']) && !empty($_GET['p1']) ){
                echo '<a href="'.$pdet_valor['hostapp'].'/registro/'.$_GET['p1'].'" class="forg-right">Registro</a>';
            }else{
                echo '<a href="'.$pdet_valor['hostapp'].'/registro" class="forg-right">Registro</a>';
            }
            ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>