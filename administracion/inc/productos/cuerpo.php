<ul class="nav nav-tabs nav-justified">
    <li role="presentation" class="active"><a role="tab" id="visualizar-tab" data-toggle="tab" href="#home" aria-expanded="true">Visualización</a></li>
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
                    (*) Campos obligatorios
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
                                <label for="nombre">Nombre (*)</label>
                                <input autocomplete="off" maxlength="90" type="text" id="nombre" class="form-control"  placeholder="Ingrese un nombre" required/>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre SEO (*)</label>
                                <input autocomplete="off" maxlength="90" type="text" id="nombre_seo" class="form-control"  placeholder="Ingrese un nombre de posicionamiento" required/>
                            </div>
                            <div class="form-group">                    
                                <label>Estado (*)</label>
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
                                <label>Mayor edad (*)</label>
                                <div> 
                                    <label class="radio-inline"> 
                                        <input type="radio" name="mayor_edad" value="NO" checked>NO
                                    </label> 
                                    <label class="radio-inline"> 
                                        <input type="radio" name="mayor_edad" value="SI">SI
                                    </label> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">                        
                            <div class="form-group">
                                <label for="sku">SKU [Código único de artículo] (*)</label>
                                <input autocomplete="off" maxlength="90" type="text" id="sku" class="form-control"  placeholder="Ingrese un SKU" required/>
                            </div>
                            <div class="form-group">
                                <label for="costo">Costo (*)</label>
                                <input autocomplete="off" type="number" id="costo" class="form-control" step="0.01" min="0.01" max="999999999999"  value="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="costo_anterior">Costo anterior</label>
                                <input type="number" id="costo_anterior" class="form-control" value="0" disabled>
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio (*)</label>
                                <input autocomplete="off" type="number" id="precio" class="form-control" step="0.01" min="0.01" max="999999999999"  value="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="precio_impuesto">Precio con impuesto</label>
                                <input type="number" id="precio_impuesto" class="form-control" value="0" disabled>
                            </div>
                            <div class="form-group">
                                <label for="precio_anterior">Precio anterior</label>
                                <input type="number" id="precio_anterior" class="form-control" value="0" disabled>
                            </div>
                            <div class="form-group">
                                <label for="idimpuesto">Impuesto (*)</label>
                                <select class="form-control" id="idimpuesto" required></select>
                            </div>
                        </div>
                        <div class="col-sm-6">                        
                            <div class="form-group">
                                <label for="idproveedor">Proveedor (*)</label>
                                <select class="form-control" id="idproveedor" required></select>
                            </div>
                            <div class="form-group">
                                <label for="idcategoria">Subcategoria (*)</label>
                                <select class="form-control" id="idsubcategoria" required></select>
                            </div>                      
                            <div class="form-group">
                                <label for="idmarca">Marca (*)</label>
                                <select class="form-control" id="idmarca" required></select>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_corta">Descripción corta (*)</label>
                                <textarea maxlength="90" id="descripcion_corta" class="form-control" placeholder="Ingrese una descripción corta" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_larga">Descripción larga</label>
                                <textarea maxlength="290" id="descripcion_larga" class="form-control" placeholder="Ingrese una descripción larga"></textarea>
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