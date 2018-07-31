<ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#home">Pedidos</a></li>
    <li><a data-toggle="tab" href="#menu1">Detalles pedido</a></li>
</ul>
<div id="contiene_jqGrid" class="tab-content">
    <div id="home" class="row tab-pane fade in active">        
        <br>
        <form role="form" id="formPedidos"> 
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
                    <input type="submit" id="submitFormPedidos" class="btn btn-primary btn-block" value="Consultar">
                </div>
            </div>            
        </form>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridPedidos"></table>
                <div id="jqGridPedidosPager"></div>
            </div>
        </div>  
    </div>
    <div id="menu1" class="row tab-pane fade">
        <br>
        <form role="form" id="formPedidoDetalle">  
            <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control" id="idpedido" required></select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="submit" id="submitFormPedidoDetalle" class="btn btn-primary btn-block" value="Consultar">
                </div>
            </div>            
        </form>
        <div id="pedido_detalle" class="col-sm-12">
            <div class="panel panel-warning">
                <div class="panel-body text-center bg-warning">
                    <strong>Seleccione un pedido para visualizar el detalle</strong>
                </div>
            </div>
        </div>
    </div>
</div>