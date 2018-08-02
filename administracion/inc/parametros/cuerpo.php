<ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#home">Visualización</a></li>
    <li><a data-toggle="tab" href="#menu1">Gestión</a></li>
</ul>
<div id="contiene_jqGrid" class="tab-content">
    <div id="home" class="row tab-pane fade in active">        
        <br>       
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="jqGridParametros"></table>
                <div id="jqGridParametrosPager"></div>
            </div>
        </div>  
    </div>
    <div id="menu1" class="row tab-pane fade">
        <br>
        <div class="col-sm-12">
            <div class="well well-sm">
            (*) Campos obligatorios
            </div>
            <form role="form" id="formParametroDetalle">
                <div class="row">  
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idparametro">IdParametro</label>
                            <input autocomplete="off" type="text" id="idparametro" placeholder="Ingrese un parametro" class="form-control" value="">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre (*)</label>
                            <input autocomplete="off" maxlength="90" type="text" id="nombre" class="form-control"  placeholder="Ingrese un nombre" required/>
                        </div>
                        
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="descripcion">Descripción (*)</label>
                            <input autocomplete="off" maxlength="90" type="text" id="descripcion" class="form-control"  placeholder="Ingrese descripcion" required/>
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor (*)</label>
                            <input autocomplete="off" maxlength="90" type="text" id="valor" class="form-control"  placeholder="Ingrese un valor" required/>
                        </div> 
                        
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-sm-6 form-group">
                        <button type="reset" id="limpiarFormParametrosDetalle" class="btn btn-block btn-info">Limpiar</button>
                    </div>
                    <div class="col-sm-6 form-group">
                        <button type="submit" id="submitFormParametrosDetalle" class="btn btn-block btn-success">Guardar</button>
                    </div>
                    <div id="accion" class ="hidden"></div>
                </div>
            </form>
        </div>
    </div>
</div>