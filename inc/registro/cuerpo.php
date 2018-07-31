<input type="hidden" id="pagsig" value="<?php if(isset( $_GET['p1']) && !empty($_GET['p1']) ){ echo $_GET['p1']; }else { echo 'inicio'; } ?>">
<!--banner-->
<div class="banner-top">
    <div class="container">
        <h3>Registro</h3>
        <!--
        <h4><a href="index.html">Home</a><label>/</label>Register</h4>
        <div class="clearfix"></div>
        -->
    </div>
</div>
<div class="mkt-cnt-cuerpo">
    <div class="container">
        <form role="form" id="formRegistro">        
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success alert-dismissable">
                            <strong>Requisitos para la creación del usuario y contraseña:</strong>
                            <ul> 
                                <li>Su longitud debe ser entre 5 y 15 catacteres</li>
                                <li>Solo puede contener número y letras minúsculas</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="nombre" class="control-label">Nombre</label>                        
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                <input type="text" maxlength="90" class="form-control" id="nombre"  placeholder="Ingrese su nombre" required/>
                            </div>                        
                        </div>
                        <div class="form-group">
                            <label for="mail" class="control-label">Email</label>                        
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" maxlength="90" class="form-control" id="mail"  placeholder="Ingrese su email" required/>
                            </div>                        
                        </div>
                        <div class="form-group">
                            <label for="usuario" class="control-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" maxlength="15" class="form-control" id="usuario"  placeholder="Ingrese su usuario" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contrasena" class="control-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
                                <input type="password" maxlength="15" class="form-control" id="contrasena"  placeholder="Ingrese su contraseña" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirmar_contrasena" class="control-label">Confirmar contraseña</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
                                <input type="password" maxlength="15" class="form-control" id="confirmar_contrasena"  placeholder="Confirmar su contraseña" required/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="idprovincia" class="control-label">Provincia</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                <select class="form-control" id="idprovincia" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idcanton" class="control-label">Cantón</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                <select class="form-control" id="idcanton" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idzona" class="control-label">Zona</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                <select class="form-control" id="idzona" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idsector" class="control-label">Sector</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                <select class="form-control" id="idsector" required></select>
                            </div>
                        </div>                    
                        <div class="form-group">
                            <button type="submit" id="submitFormRegistro" class="btn btn-warning btn-lg btn-block">Registrar</button>
                        </div>
                    </div>        
                </div>
            </div>
        </form>
    </div>
</div>