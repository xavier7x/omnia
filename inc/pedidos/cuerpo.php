<!--banner-->
<div class="banner-top">
    <div class="container">
        <h3>Pedidos</h3>
        <!--
        <h4><a href="index.html">Home</a><label>/</label>Register</h4>
        <div class="clearfix"></div>
        -->
    </div>
</div>
<div class="mkt-cnt-cuerpo">
    <div class="container">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a data-toggle="tab" href="#home">Pedidos</a></li>
            <li><a data-toggle="tab" href="#menu1">Detalles pedido</a></li>
        </ul>
        <div id="contiene_jqGrid" class="tab-content">
            <div id="home" class="row tab-pane fade in active">        
                <br>
                <form role="form" id="formPedidos">  
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="submit" id="submitFormPedidos" class="btn btn-success btn-block" value="Consultar">
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
                            <input type="submit" id="submitFormPedidoDetalle" class="btn btn-success btn-block" value="Consultar">
                        </div>
                    </div>            
                </form>
                <div id="pedido_detalle" class="col-sm-12">
                    <div class="alert alert-success alert-dismissable text-center">
                        <strong>Seleccione un pedido para visualizar el detalle</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>