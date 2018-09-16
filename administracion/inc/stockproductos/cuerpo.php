<ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#home">Visualización</a></li>
    <li><a data-toggle="tab" href="#menu1Now">Ingresos</a></li>
    <li><a data-toggle="tab" href="#menu2">Egresos</a></li>
    <li><a data-toggle="tab" href="#menu3">Mínimos y máximos</a></li>
</ul>
<div id="contiene_jqGrid" class="tab-content">
    <div id="home" class="row tab-pane fade in active">        
        <br>
        <form role="form" id="formBodegas">
            <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control" id="idbodega" required></select>
                </div>
            </div>   
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="submit" id="submitFormBodegas" class="btn btn-primary btn-block" value="Consultar bodega">
                </div>
            </div>            
        </form>        
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridBodegas"></table>
                <div id="jqGridBodegasPager"></div>
            </div>
        </div>  
    </div>
    <div id="menu1Now" class="row tab-pane fade">
        <br>
        <div class="col-sm-12">            
            <div class="well well-sm">
                (*) Campos obligatorios
            </div>
            <form role="form" id="formBodegaIngreso">
                <input type="hidden" id="tiene_imagen_ing" value="NO">
                <div class="row"> 
                    <div class="col-sm-6 text-center">
                        <div class="form-group">
                            <img src="../images/productos/0/320x320/error.png" id="imagen_producto_ing" class="img-thumbnail" alt="Imagen producto" height="<?php echo $pdet_valor['imgproductoalto']; ?>" width="<?php echo $pdet_valor['imgproductoancho']; ?>">     
                        </div>  
                    </div> 
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="idbodega_ing">Idbodega</label>
                            <input type="number" id="idbodega_ing" class="form-control" value="0" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nombre_bodega_ing">Nombre bodega</label>
                            <input type="text" id="nombre_bodega_ing" class="form-control" value="" disabled/>
                        </div>
                        <div class="form-group">
                            <label for="idproducto_ing">Idproducto</label>
                            <input type="number" id="idproducto_ing" class="form-control" value="0" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nombre_producto_ing">Nombre producto</label>
                            <input type="text" id="nombre_producto_ing" class="form-control" value="" disabled/>
                        </div>
                    </div>
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="idmotivoing">Motivo ingreso (*)</label>
                            <select class="form-control" id="idmotivoing" required></select>
                        </div> 
                    </div>
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="cantidad_ing">Cantidad (*)</label>
                            <input type="number" id="cantidad_ing" class="form-control" min="1" value="1" required>
                        </div> 
                    </div>
                    <div class="col-sm-12"> 
                        <div class="form-group">
                            <label for="comentario_ing">Comentario</label>
                            <textarea maxlength="140" id="comentario_ing" class="form-control" placeholder="Comentario"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">                     
                    <div class="col-sm-4 form-group">
                        <button type="button" id="queryFormBodegaIngreso" class="btn btn-block btn-primary">Consultar</button>
                    </div>
                    <div class="col-sm-4 form-group">
                        <button type="reset" id="limpiarFormBodegaIngreso" class="btn btn-block btn-info">Limpiar</button>
                    </div>
                    <div class="col-sm-4 form-group">
                        <button type="submit" id="submitFormBodegaIngreso" class="btn btn-block btn-success">Guardar</button>
                    </div>
                </div>
            </form>
        </div>                
        <div id="cnt_jqGridBodegasIngreso" class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridBodegasIngreso"></table>
                <div id="jqGridBodegasIngresoPager"></div>
            </div>
        </div>  
    </div>
    <div id="menu2" class="row tab-pane fade">
        <br>
        <div class="col-sm-12">            
            <div class="well well-sm">
                (*) Campos obligatorios
            </div>
            <form role="form" id="formBodegaEgreso">
                <input type="hidden" id="tiene_imagen_egr" value="NO">
                <div class="row"> 
                    <div class="col-sm-6 text-center">
                        <div class="form-group">
                            <img src="../images/productos/0/320x320/error.png" id="imagen_producto_egr" class="img-thumbnail" alt="Imagen producto" height="<?php echo $pdet_valor['imgproductoalto']; ?>" width="<?php echo $pdet_valor['imgproductoancho']; ?>">     
                        </div>  
                    </div> 
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="idbodega_egr">Idbodega</label>
                            <input type="number" id="idbodega_egr" class="form-control" value="0" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nombre_bodega_egr">Nombre bodega</label>
                            <input type="text" id="nombre_bodega_egr" class="form-control" value="" disabled/>
                        </div>
                        <div class="form-group">
                            <label for="idproducto_egr">Idproducto</label>
                            <input type="number" id="idproducto_egr" class="form-control" value="0" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nombre_producto_egr">Nombre producto</label>
                            <input type="text" id="nombre_producto_egr" class="form-control" value="" disabled/>
                        </div>
                    </div>
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="idmotivoegr">Motivo egreso (*)</label>
                            <select class="form-control" id="idmotivoegr" required></select>
                        </div> 
                    </div>
                    <div class="col-sm-6">                        
                        <div class="form-group">
                            <label for="cantidad_egr">Cantidad (*)</label>
                            <input type="number" id="cantidad_egr" class="form-control" min="1" value="1" required>
                        </div> 
                    </div>
                    <div class="col-sm-12"> 
                        <div class="form-group">
                            <label for="comentario_egr">Comentario</label>
                            <textarea maxlength="140" id="comentario_egr" class="form-control" placeholder="Comentario"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group">
                        <button type="button" id="queryFormBodegaEgreso" class="btn btn-block btn-primary">Consultar</button>
                    </div>
                    <div class="col-sm-4 form-group">
                        <button type="reset" id="limpiarFormBodegaEgreso" class="btn btn-block btn-info">Limpiar</button>
                    </div>
                    <div class="col-sm-4 form-group">
                        <button type="submit" id="submitFormBodegaEgreso" class="btn btn-block btn-success">Guardar</button>
                    </div>
                </div>
            </form>
        </div>                
        <div id="cnt_jqGridBodegasEgreso" class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridBodegasEgreso"></table>
                <div id="jqGridBodegasEgresoPager"></div>
            </div>
        </div>  
    </div>
    <div id="menu3" class="row tab-pane fade">
        <br>
        <div class="col-sm-12">            
            <div class="well well-sm">
                (*) Campos obligatorios
            </div>
        </div> 
    </div> 
</div>