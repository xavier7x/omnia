<ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#home">Visualización</a></li>
    <li><a data-toggle="tab" href="#menu1">Gestión</a></li>
</ul>
<div id="contiene_jqGrid" class="tab-content">
    <div id="home" class="row tab-pane fade in active">        
        <br>
        <form role="form" id="formBodegas">  
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="submit" id="submitFormBodegas" class="btn btn-primary btn-block" value="Consultar">
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
    <div id="menu1" class="row tab-pane fade">
        <br>
        <form role="form" id="formBodegaDetalle">  
            <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control" id="idpedido" required></select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="submit" id="submitFormBodegaDetalle" class="btn btn-primary btn-block" value="Consultar">
                </div>
            </div>            
        </form>
        <div class="col-sm-12">
        </div>
    </div>
</div>