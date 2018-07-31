<!--banner-->
<div class="banner-top">
	<div class="container">
		<h3 >Carrito de compras</h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Wishlist</h4>
		<div class="clearfix"> </div>
        -->
	</div>
</div>
            <!-------------------->
<div class="check-out">	 
    <div class="container">	 
        <!--
        <div class="spec ">
            <h3>Productos</h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
        </div>
        -->
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a data-toggle="tab" href="#home">Carrito</a></li>
            <li><a data-toggle="tab" href="#menu1">Datos envío</a></li>
            <li><a data-toggle="tab" href="#menu2">Datos facturación</a></li>
        </ul>
        <div id="contiene_jqGrid" class="tab-content">
            <div id="home" class="row tab-pane fade in active">
                <div class="col-sm-12">
                    <div class="alert alert-success alert-dismissable">
                        <ul> 
                            <li>Importante, el costo de envío varía según su localidad</li>
                        </ul>
                    </div>
                </div>
                <form role="form" id="formCarrito">  
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idenvio_select" class="control-label">Envío (Para añadir un dato de envío debe dar clic en el más)</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                <select class="form-control" id="idenvio_select" required></select>
                                <span class="input-group-btn">
                                    <button type="button" id="add_envio" class="btn btn-warning"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idfacturacion_select" class="control-label">Facturación (Para añadir un dato de facturación debe dar clic en el más)</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-male fa-lg"></i></span>
                                <select class="form-control" id="idfacturacion_select" required></select>
                                <span class="input-group-btn">
                                    <button type="button" id="add_facturacion" class="btn btn-warning"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idatencion" class="control-label">Horario recepción (AÑO - MES - DÍA)</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <select class="form-control" id="idatencion" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idmetodopago" class="control-label">Método pago</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                <select class="form-control" id="idmetodopago" required></select>
                            </div>
                        </div>      
                        <div class="form-group" id="metodo_transfer"></div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="total_carrito" class="control-label">Subtotal</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control text-right" id="total_carrito" value="0.00" disabled/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="costo_envio" class="control-label">Costo envío</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control text-right" id="costo_envio" value="0.00" disabled/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="total_costo_envio" class="control-label">Total a pagar</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control text-right" id="total_costo_envio" value="0.00" disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comentario">Comentario</label>
                            <textarea maxlength="140" id="comentario" class="form-control" placeholder="Ingrese un comentario"></textarea>
                        </div>                
                        <div class="form-group">
                            <label class="control-label"></label>
                            <?php
                            if($_SESSION['tipocliente'] == 'visitante'){
                                echo '<a href="'.$pdet_valor['hostapp'].'/login/carrito" class="btn btn-warning btn-lg btn-block">Ingresar</a>';
                            }else{
                                echo '<button type="submit" id="submitFormCarrito" class="btn btn-warning btn-lg btn-block" >Generar pedido</button>';
                            }
                            ?>
                        </div>
                    </div>
                </form>   
                <div class="col-sm-12">
                    <table class="table-cart ">
                        <tr>
                            <th class="t-head head-it ">Producto</th>
                            <th class="t-head">Precio</th>
                            <th class="t-head">Cantidad</th>
                        </tr>
                        <tbody id="cuerpo_carrito">
                            <tr><td colspan="50" class="text-center">No tiene productos cargados</td></tr>
                        </tbody>
                    </table>                    
                </div>
            </div>
            <div id="menu1" class="row tab-pane fade">
                <div class="col-sm-12">
                    <div class="alert alert-success alert-dismissable">
                        <ul>
                            <li>(*) Campos obligatorios</li>
                            <li>Favor coloque a detalle su dirección para una mejor entrega</li>
                        </ul>
                    </div>
                </div>        
                <div class="col-sm-12">
                    <?php if($_SESSION['tipocliente'] == 'visitante'){ ?>
                        <div class="alert alert-warning alert-dismissable text-center">
                            Debe iniciar sesión para registrar sus datos de envío
                        </div>
                        <div class="col-sm-6 form-group">
                            <button type="reset" class="btn btn-block btn-lg btn-success regresar_carrito"><span class="glyphicon glyphicon-shopping-cart"></span> Carrito</button>
                        </div>
                        <div class="col-sm-6 form-group">
                            <a href="<?php echo $pdet_valor['hostapp']; ?>/login/carrito" class="btn btn-warning btn-lg btn-block">Ingresar</a>
                        </div>
                        
                    <?php }else{ ?>
                        <form role="form" id="formEnvio">
                            <input type="hidden" id="idenvio" value="0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="titulo_envio">Alias / Cabecera (*) [ Ejemplo: CASA ]</label>
                                        <input autocomplete="off" maxlength="40" id="titulo_envio" type="text" class="form-control" placeholder="Ingrese el alias o cabecera" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre_envio">Nombre (*)</label>
                                        <input autocomplete="off" maxlength="90" id="nombre_envio" type="text" class="form-control" placeholder="Ingrese el nombre" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="movil1_envio">Teléfono / Móvil (*)</label>
                                        <input autocomplete="off" id="movil1_envio" type="number" step="1" min="1" max="99999999999999999999" class="form-control" placeholder="Ingrese el teléfono o móvil" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="movil2_envio">Teléfono / Móvil</label>
                                        <input autocomplete="off" id="movil2_envio" type="number" step="1" min="1" max="99999999999999999999" class="form-control" placeholder="Ingrese el teléfono o móvil"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion_envio">Dirección (*)</label>
                                        <textarea maxlength="240" id="direccion_envio" class="form-control" placeholder="Ingrese la dirección" required></textarea>
                                    </div>                     
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="idprovincia_envio" class="control-label">Provincia</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                            <select class="form-control" id="idprovincia_envio" required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="idcanton_envio" class="control-label">Cantón</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                            <select class="form-control" id="idcanton_envio" required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="idzona_envio" class="control-label">Zona</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                            <select class="form-control" id="idzona_envio" required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="idsector_envio" class="control-label">Sector</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                            <select class="form-control" id="idsector_envio" required></select>
                                        </div>
                                    </div>
                                    <div class="form-group">                    
                                        <label>Estado</label>
                                        <div> 
                                            <label class="radio-inline"> 
                                                <input type="radio" name="estado_envio" value="ACTIVO" checked>ACTIVO
                                            </label> 
                                            <label class="radio-inline"> 
                                                <input type="radio" name="estado_envio" value="INACTIVO">INACTIVO
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <button type="reset" class="btn btn-block btn-lg btn-success regresar_carrito"><span class="glyphicon glyphicon-shopping-cart"></span> Carrito</button>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <button type="reset" id="limpiarFormEnvio" class="btn btn-block btn-lg btn-primary">Nuevo</button>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <button type="submit" id="submitFormEnvio" class="btn btn-block btn-lg btn-warning">Guardar</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="jqGridEnvio"></table>
                        <div id="jqGridEnvioPager"></div>
                    </div>
                </div>        
            </div>
            <div id="menu2" class="row tab-pane fade">
                <div class="col-sm-12">
                    <div class="alert alert-success alert-dismissable">
                        <ul>
                            <li>(*) Campos obligatorios</li>
                            <li>Tenga en consideración que es su responsabilidad los datos de facturación que registre</li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-12">
                    <?php if($_SESSION['tipocliente'] == 'visitante'){ ?>
                        <div class="alert alert-warning alert-dismissable text-center">
                            Debe iniciar sesión para registrar sus datos de facturación
                        </div>
                        <div class="col-sm-6 form-group">
                            <button type="reset" class="btn btn-block btn-lg btn-success regresar_carrito"><span class="glyphicon glyphicon-shopping-cart"></span> Carrito</button>
                        </div>
                        <div class="col-sm-6 form-group">
                            <a href="<?php echo $pdet_valor['hostapp']; ?>/login/carrito" class="btn btn-warning btn-lg btn-block">Ingresar</a>
                        </div>
                    <?php }else{ ?>
                        <form role="form" id="formFacturacion">
                            <input type="hidden" id="idfacturacion" value="0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="titulo_facturacion">Alias / Cabecera (*) [ Ejemplo: EMPRESA ]</label>
                                        <input autocomplete="off" maxlength="40" id="titulo_facturacion" type="text" class="form-control" placeholder="Ingrese el alias o cabecera" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre_facturacion">Nombre (*)</label>
                                        <input autocomplete="off" maxlength="90" id="nombre_facturacion" type="text" class="form-control" placeholder="Ingrese el nombre" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_facturacion">Email (*)</label>
                                        <input autocomplete="off" maxlength="90" id="mail_facturacion" type="email" class="form-control" placeholder="Ingrese el email" required/>
                                    </div> 
                                    <div class="form-group">
                                        <label for="movil1_facturacion">Teléfono / Móvil (*)</label>
                                        <input autocomplete="off" id="movil1_facturacion" type="number" step="1" min="1" max="99999999999999999999" class="form-control" placeholder="Ingrese el teléfono o móvil" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="movil2_facturacion">Teléfono / Móvil</label>
                                        <input autocomplete="off" id="movil2_facturacion" type="number" step="1" min="1" max="99999999999999999999" class="form-control" placeholder="Ingrese el teléfono o móvil"/>
                                    </div>                                             
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="num_doc_facturacion">CI / RUC (*)</label>
                                        <input autocomplete="off" maxlength="90" id="num_doc_facturacion" type="text" class="form-control" placeholder="Ingrese su CI o RUC" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion_facturacion">Dirección (*)</label>
                                        <textarea maxlength="240" id="direccion_facturacion" class="form-control" placeholder="Ingrese la dirección" required></textarea>
                                    </div>
                                    <div class="form-group">                    
                                        <label>Estado</label>
                                        <div> 
                                            <label class="radio-inline"> 
                                                <input type="radio" name="estado_facturacion" value="ACTIVO" checked>ACTIVO
                                            </label> 
                                            <label class="radio-inline"> 
                                                <input type="radio" name="estado_facturacion" value="INACTIVO">INACTIVO
                                            </label> 
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <button type="reset" class="btn btn-block btn-lg btn-success regresar_carrito"><span class="glyphicon glyphicon-shopping-cart"></span> Carrito</button>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <button type="reset" id="limpiarFormFacturacion" class="btn btn-block btn-lg btn-primary">Nuevo</button>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <button type="submit" id="submitFormFacturacion" class="btn btn-block btn-lg btn-warning">Guardar</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="jqGridFacturacion"></table>
                        <div id="jqGridFacturacionPager"></div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>