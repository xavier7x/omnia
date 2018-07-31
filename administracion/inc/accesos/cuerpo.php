<div class="row">
	<div class="col-sm-12">
        <div class="well well-sm">(*) Primero consulte el usuario y luego guarde el permiso a los accesos</div>            
        <form role="form" class="form-horizontal" id="formQueryUser">
            <div class="form-group">
                <label for="usuarioQuery" class="col-sm-3 control-label">Usuario (*)</label>
                <div class="col-sm-9">
                    <input autocomplete="off" maxlength="10" id="usuarioQuery" type="text" class="form-control" placeholder="Ingrese el usuario"/>
                </div>
            </div>
        </form>
        <hr/>
        <form role="form" class="form-horizontal" id="formInsertUser">
            <div class="form-group">
                <label for="usuario" class="col-sm-3 control-label">Usuario</label>
                <div class="col-sm-9">
                    <input autocomplete="off" maxlength="10" id="usuario" type="text" class="form-control" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label for="nombre" class="col-sm-3 control-label">Nombre</label>
                <div class="col-sm-9">
                    <input autocomplete="off" maxlength="10" id="nombre" type="text" class="form-control" disabled/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="reset" id="limpiarFormInsertUser" class="btn btn-block btn-primary">Limpiar</button>
                    <button type="submit" id="submitFormInsertUser" class="btn btn-block btn-success">Guardar</button>
                </div> 
            </div>
        </form>
        <div id="sys_menu" class="col-sm-offset-3 col-sm-9"></div>
    </div>
</div>