<ul class="nav nav-tabs nav-justified">
    <li role="presentation"class="active"><a role="tab" id="visualizar-tab" data-toggle="tab" href="#home" aria-expanded="true">Visualización</a></li>
    <li role="presentation" class=""><a role="tab" id="gestionar-tab" data-toggle="tab" href="#menu1Now" aria-expanded="false">Gestión</a></li>
</ul>
<div id="contiene_jqGrid">
    <div class="tab-content">
        <div role="tabpanel" aria-labelledby="visualizar-tab" id="home" class="row tab-pane fade in active">        
            <br>
            <form role="form" id="formProductos">  
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="submit" id="submitFormProductos" class="btn btn-primary btn-block" value="Consultar">
                    </div>
                </div>            
            </form>        
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="jqGridProductos"></table>
                    <div id="jqGridProductosPager"></div>
                </div>
            </div>  
        </div>
        <div role="tabpanel" aria-labelledby="gestionar-tab" id="menu1Now" class="row tab-pane fade">
            <br>
            <div class="col-sm-12">
                <div class="well well-sm">
                    <strong>Requisitos para la imagen de los productos:</strong>
                    <ul> 
                        <li>Formato (<?php echo $pdet_valor['imgproductoext']; ?>)</li>
                        <li>Peso (<?php echo $pdet_valor['imgproductopeso']; ?> KB) como máximo</li>
                        <li>Alto (<?php echo $pdet_valor['imgproductoalto']; ?> PX)</li>
                        <li>Ancho (<?php echo $pdet_valor['imgproductoancho']; ?> PX)</li>                    
                    </ul>
                </div>
                <form role="form" id="formProductoDetalle">
                    <div class="row"> 
                        <div class="col-sm-6 text-center">
                            <div class="form-group">
                                <img src="../images/productos/0/320x320/error.png" id="imagen_producto" class="img-thumbnail" alt="Imagen producto" height="<?php echo $pdet_valor['imgproductoalto']; ?>" width="<?php echo $pdet_valor['imgproductoancho']; ?>">
                            </div>  
                        </div> 
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="idproducto">Idproducto</label>
                                <input autocomplete="off" type="number" id="idproducto" class="form-control" value="0" disabled>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input autocomplete="off" maxlength="90" type="text" id="nombre" class="form-control" disabled/>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre SEO</label>
                                <input autocomplete="off" maxlength="90" type="text" id="nombre_seo" class="form-control" disabled/>
                            </div>
                            <div class="form-group">                    
                                <label>Estado</label>
                                <div> 
                                    <label class="radio-inline"> 
                                        <input type="radio" name="estado" value="ACTIVO" checked>ACTIVO
                                    </label> 
                                    <label class="radio-inline"> 
                                        <input type="radio" name="estado" value="INACTIVO">INACTIVO
                                    </label> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="imagen_item">Imagen (*)</label>
                                <input type="file" id="imagen_item" accept="image/png" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-sm-6 form-group">
                            <button type="reset" id="limpiarFormProductoDetalle" class="btn btn-block btn-info">Limpiar</button>
                        </div>
                        <div class="col-sm-6 form-group">
                            <button type="submit" id="submitFormProductoDetalle" class="btn btn-block btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>