<ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#home">Facturas</a></li>
    <li><a data-toggle="tab" href="#menu1Now">Detalles factura</a></li>
</ul>
<div id="contiene_jqGrid" class="tab-content">
    <div id="home" class="row tab-pane fade in active">        
        <br>
        <form role="form" id="formFacturas"> 
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Bodega</label>                
                    <select class="form-control" id="idbodega" required></select>
                </div>
            </div>   
            <div class="col-sm-6">    
                <div class="form-group">
                    <label>Fecha [ Inicio - Fin ]</label>
                    <div class="input-group input-daterange" id="fecha_inifin">
                        <input type="text" class="form-control" id="fecha_inicio" value="<?php echo date('Y-m-d', strtotime('-2 day')) ?>" readonly>
                        <span class="input-group-addon">a</span>
                        <input type="text" class="form-control" id="fecha_fin" value="<?php echo date('Y-m-d', strtotime('+5 day')) ?>" readonly>
                    </div>
                </div>
            </div> 
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="submit" id="submitFormFacturas" class="btn btn-primary btn-block" value="Consultar">
                </div>
            </div>            
        </form>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridFacturas"></table>
                <div id="jqGridFacturasPager"></div>
            </div>
        </div>  
    </div>
    <div id="menu1Now" class="row tab-pane fade">
        <br>
        <form role="form" id="formFacturaDetalle">  
            <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control" id="idfactura" required></select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="submit" id="submitFormFacturaDetalle" class="btn btn-primary btn-block" value="Consultar">
                </div>
            </div>            
        </form>
        <div id="factura_detalle" class="col-sm-12">
            <div class="panel panel-warning">
                <div class="panel-body text-center bg-warning">
                    <strong>Seleccione una factura para visualizar el detalle</strong>
                </div>
            </div>
        </div>
    </div>
</div>