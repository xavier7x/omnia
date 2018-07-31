<div class="banner-top">
    <div class="container">
        <h3>Mi perfil</h3>
        <!--
        <h4><a href="index.html">Home</a><label>/</label>Register</h4>
        <div class="clearfix"></div>
        -->
    </div>
</div>
<div class="mkt-cnt-cuerpo">
    <div class="container">
        <form role="form" id="formUpdateUsuario"> 
            <div class="col-sm-12">
                <div class="alert alert-success alert-dismissable">
                    <strong>Requisitos para la actualización de la contraseña:</strong>
                    <ul> 
                        <li>Su longitud debe ser entre 5 y 15 catacteres</li>
                        <li>Solo puede contener número y letras minúsculas</li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">                    
                        <div class="form-group">
                            <label for="usuario" class="control-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" maxlength="15" class="form-control" id="usuario" disabled/>
                            </div>
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
                        <div class="checkbox">
                            <label for="cambiar_contrasena">
                                <input type="checkbox" id="cambiar_contrasena"> <strong>Cambiar contraseña</strong>
                            </label>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="anterior_contrasena" class="control-label">Anterior contraseña</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
                                <input type="password" maxlength="15" class="form-control" id="anterior_contrasena"  placeholder="Ingrese su anterior contraseña" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nueva_contrasena" class="control-label">Nueva contraseña</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
                                <input type="password" maxlength="15" class="form-control" id="nueva_contrasena"  placeholder="Ingrese su nueva contraseña" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirmar_contrasena" class="control-label">Confirmar nueva contraseña</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
                                <input type="password" maxlength="15" class="form-control" id="confirmar_contrasena"  placeholder="Confirmar su nueva contraseña" disabled/>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>  
            <div class="col-sm-12">
                <div class="form-group">
                    <button type="submit" id="submitFormUpdateUsuario" class="btn btn-warning btn-lg btn-block">Actualizar</button>
                </div>
            </div>           
        </form>
    </div>
</div>