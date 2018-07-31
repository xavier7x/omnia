<!-- Modal Costo Envio -->
<div class="modal fade" id="modalCostoEnvio" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Costo envío</h4>
            </div>
            <form role="form" id="formModalCostoEnvio">                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="idsector_env" class="form-group control-label">Id-Sector</label>
                                <input id="idsector_env" type="text" class="form-control" disabled/>
                            </div>
                            <div class="form-group">
                                <label for="nombre_env" class="form-group control-label">Nombre</label>                                
                                <input id="nombre_env" type="text" class="form-control" disabled/>
                            </div>                            
                            <div class="form-group">
                                <label for="costo_envio_env">Costo</label>
                                <input autocomplete="off" type="number" id="costo_envio_env" class="form-control" step="0.01" min="0" max="9999999"  value="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">                
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#home">Costo envio</a></li>
</ul>
<div id="contiene_jqGrid" class="tab-content">
    <div id="home" class="row tab-pane fade in active">        
        <br>
        <form role="form" id="formCostoEnvio">             
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Cantones</label>                
                    <select class="form-control" id="idcanton_env" required></select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Zonas</label>                
                    <select class="form-control" id="idzona_env" required></select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Consultar">
                </div>
            </div>            
        </form>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridCostoEnvio"></table>
                <div id="jqGridCostoEnvioPager"></div>
            </div>
        </div>  
    </div>
</div>