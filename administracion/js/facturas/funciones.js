function mostrarFacturas(){
    var idbodega = $("#idbodega option:selected").val();
    var inicio = $("#fecha_inicio").val();
    var fin = $("#fecha_fin").val();
    
    $("#factura_detalle").html('<div class="panel panel-warning"><div class="panel-body text-center bg-warning"><strong>Seleccione una factura para visualizar el detalle</strong></div></div>');
    
    if($('#visualizar').val() == 1){
        cargarOptionFacturas( idbodega, inicio, fin );
        
        var mydata;

        $('#jqGridFacturas').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/facturas/query.php",
            data: {
                idbodega:idbodega,
                inicio:inicio,
                fin:fin,
                actualizar:$("#actualizar").val()
            }, 
            dataType: "json",
            //beforeSend: function(){},
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = 'facturas';
            },
            success: function(respuesta){
                mydata = respuesta.rows;
            },
            //complete: function(){}
        });

        $('#jqGridFacturas').jqGrid('setGridParam', {data: mydata});
        $('#jqGridFacturas').trigger('reloadGrid'); 

        $("#jqGridFacturas").jqGrid({
            datatype: 'local',
            data: mydata,
            styleUI : 'Bootstrap',
            colModel: [            
                { label: 'Gestión', name: 'btn_gestion', width: 200, align: 'left', sortable: false },
                { label: 'Secuencial', name: 'numero_factura', width: 200, align: 'center'},
                { label: '# Factura', name: 'idfactura', width: 100, align: 'right', key: true },
                { label: '# Pedido', name: 'idpedido', width: 100, align: 'right'},
                { label: 'Generada', name: 'generada', width: 100, align: 'center'},
                { label: 'Método pago', name: 'nombre_metodopago', width: 150, align: 'left'},
                { label: 'Total', name: 'total', width: 150, align: 'right'},
                { label: 'Estado interno', name: 'estado_interno', width: 150, align: 'center'},
                { label: 'Inicio', name: 'inicio', width: 150, align: 'center'},
                { label: 'Fin', name: 'fin', width: 150, align: 'center'},
                { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center'},
                { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center'},
            ],
            height: 'auto',
            autowidth: true, // El ancho de la tabla es el de la pagina
            shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
            viewrecords: true,
            //autoencode: false,
            rowNum: $('#param_paginacion').val(),
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            pager: "#jqGridFacturasPager"
        });
    }else{        
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");        
    }
}

function cargarOptionFacturas( idbodega, inicio, fin ){
    $.ajax({
        type: "POST",
        url: "util/facturas/optionFacturas.php",
        dataType: 'json',
        data: {
            idbodega:idbodega,
            inicio:inicio,
            fin:fin
        },
        beforeSend: function(){
            $("#idfactura").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'facturas';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idfactura']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idfactura").html(opcSelect);
            }
        }
    }); 
}

function cargarOptionDatosBodegasPorUsuario(){
    var usuario = $("#session_usuario").val();
    
    $.ajax({
        //async: false,
        type: "POST",
        url: "util/wsusuarios/optionBodegasPorUsuario.php",
        dataType: 'json',
        data: {
            usuario:usuario
        },
        beforeSend: function(){
            $("#idbodega").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'facturas';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idbodega']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idbodega").html(opcSelect); 
            }
        }
    }); 
}

function mostrarFacturaDetalles(){
    var idfactura = $("#idfactura option:selected").val();
    //console.log(idfactura);
    $.ajax({
        type: "POST",
        url: "util/facturas/facturaDetalle.php",
        data: {
            idfactura:idfactura
        }, 
        dataType: "json",
        beforeSend: function(){
            $("#factura_detalle").html('<div class="col-sm-12 text-center"><i class="fa fa-spinner fa-spin" style="font-size:60px;color:#0071BC;margin-top:50px;margin-bottom:50px;"></i></div>');
        },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = 'facturas';
        },
        success: function(respuesta){
            var cabecera = respuesta.cabecera;
            var detalles = respuesta.detalles;
            
            if(
                detalles.length > 0
            ){
                var cuerpo_factura = '<div class="panel panel-primary">';
                cuerpo_factura += '<div class="panel-heading text-center">Secuencial factura [ '+cabecera['numero_factura']+' ]</div>';
                cuerpo_factura += '<div class="panel-body">';
                cuerpo_factura += '<div class="row">';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Horario:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['inicio']+' - '+cabecera['fin'].substring(11)+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Usuario:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['usuario']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Estado:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['estado_interno']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Método pago:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['nombre_metodopago']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Total:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-right">';
                cuerpo_factura += '<span>'+cabecera['total']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong></strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-right">';
                cuerpo_factura += '<span></span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                if(cabecera['comentario'] != null){
                    cuerpo_factura += '<div class="col-sm-12">';
                    cuerpo_factura += '<div class="row">';
                    cuerpo_factura += '<div class="col-sm-3 text-left">';
                    cuerpo_factura += '<strong>Comentario:</strong>';
                    cuerpo_factura += '</div>';
                    cuerpo_factura += '<div class="col-sm-9 text-left">';
                    cuerpo_factura += '<span>'+cabecera['comentario']+'</span>';
                    cuerpo_factura += '</div>';
                    cuerpo_factura += '</div>';
                    cuerpo_factura += '</div>'; 
                }
                
                cuerpo_factura += '<div class="col-sm-12 text-center form-group">';
                cuerpo_factura += '<hr>';
                cuerpo_factura += '<strong>Datos envío</strong>';              
                cuerpo_factura += '</div>';
                /*
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Alias:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-9 text-left">';
                cuerpo_factura += '<span>'+cabecera['titulo_env']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                */
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Nombre:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-9 text-left">';
                cuerpo_factura += '<span>'+cabecera['nombre_env']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Dirección:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-9 text-left">';
                cuerpo_factura += '<span>'+cabecera['direccion_env']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Teléfono / Móvil:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['movil1_env']+'</span>';
                cuerpo_factura += '</div>';
                if(cabecera['movil2_env'] != null){
                    cuerpo_factura += '<div class="col-sm-3 text-left">';
                    cuerpo_factura += '<strong>Teléfono / Móvil:</strong>';
                    cuerpo_factura += '</div>';
                    cuerpo_factura += '<div class="col-sm-3 text-left">';
                    cuerpo_factura += '<span>'+cabecera['movil2_env']+'</span>';
                    cuerpo_factura += '</div>';
                }
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Provincia:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['provincia_nom']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Cantón:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['canton_nom']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Zona:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['zona_nom']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Sector:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['sector_nom']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                
                cuerpo_factura += '<div class="col-sm-12 text-center form-group">';
                cuerpo_factura += '<hr>';
                cuerpo_factura += '<strong>Datos facturación</strong>';
                cuerpo_factura += '</div>';
                /*
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Alias:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-9 text-left">';
                cuerpo_factura += '<span>'+cabecera['titulo_fac']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                */
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Nombre:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-9 text-left">';
                cuerpo_factura += '<span>'+cabecera['nombre_fac']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Dirección:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-9 text-left">';
                cuerpo_factura += '<span>'+cabecera['direccion_fac']+'</span>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>'; 
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>CI / RUC:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['num_doc_fac']+'</span>';
                cuerpo_factura += '</div>';                
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Email:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['mail_fac']+'</span>';
                cuerpo_factura += '</div>';                
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<div class="row">';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<strong>Teléfono / Móvil:</strong>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '<div class="col-sm-3 text-left">';
                cuerpo_factura += '<span>'+cabecera['movil1_fac']+'</span>';
                cuerpo_factura += '</div>';
                if(cabecera['movil2_fac'] != null){
                    cuerpo_factura += '<div class="col-sm-3 text-left">';
                    cuerpo_factura += '<strong>Teléfono / Móvil:</strong>';
                    cuerpo_factura += '</div>';
                    cuerpo_factura += '<div class="col-sm-3 text-left">';
                    cuerpo_factura += '<span>'+cabecera['movil2_fac']+'</span>';
                    cuerpo_factura += '</div>';
                }
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '<div class="col-sm-12">';
                cuerpo_factura += '<hr>';
                cuerpo_factura += '<div class="table-responsive">';
                cuerpo_factura += '<table class="table table-bordered table-striped table-hover">';
                cuerpo_factura += '<thead>';
                cuerpo_factura += '<tr>';
                cuerpo_factura += '<th>#</th>';
                cuerpo_factura += '<th>Producto</th>';
                cuerpo_factura += '<th>Cantidad</th>';
                cuerpo_factura += '<th>Precio</th>';
                cuerpo_factura += '<th>Impuesto</th>';
                cuerpo_factura += '<th>Subtotal</th>';
                cuerpo_factura += '<th>Total</th>';
                cuerpo_factura += '</tr>';
                cuerpo_factura += '</thead>';
                cuerpo_factura += '<tbody>';
                
                for(var f=0; f < detalles.length; f++){
                    cuerpo_factura += '<tr>';
                    cuerpo_factura += '<td class="text-right">'+(f + 1)+'</td>';
                    cuerpo_factura += '<td class="text-left"><a class="info_producto" data-idproducto="'+detalles[f]['idproducto']+'"><span class="glyphicon glyphicon-chevron-right"></span> '+detalles[f]['nombre']+'</a></td>';
                    cuerpo_factura += '<td class="text-center">'+detalles[f]['cantidad']+'</td>';
                    cuerpo_factura += '<td class="text-right">'+detalles[f]['precio']+'</td>';
                    cuerpo_factura += '<td class="text-right">'+detalles[f]['valor_impuesto']+'</td>';
                    cuerpo_factura += '<td class="text-right">'+detalles[f]['subtotal']+'</td>';
                    cuerpo_factura += '<td class="text-right">'+detalles[f]['total']+'</td>';
                    cuerpo_factura += '</tr>';
                }
                
                cuerpo_factura += '</tbody>';
                cuerpo_factura += '</table>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                cuerpo_factura += '</div>';
                
                $("#factura_detalle").html(cuerpo_factura); 
            }else{
                $("#factura_detalle").html('<div class="panel panel-danger"><div class="panel-body text-center bg-danger"><strong>Sin datos de la factura</strong></div></div>');  
            }
        },
        //complete: function(){}
    });
}

function generarFactura(idfactura, numero_factura){
    var usuario = $("#session_usuario").val();
    
    $.ajax({
        async: false,
        type: "POST",
        url: "util/facturas/generarFactura.php",
        dataType: 'json',
        data: {
            usuario:usuario,
            idfactura:idfactura,
            numero_factura:numero_factura
        },
        //beforeSend: function(){ },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'facturas';
        },
        success: function(respuesta){
            switch (respuesta.estado){
                case 1:
                    $('#myModalSuccessBody').html(respuesta.mensaje);
                    $('#myModalSuccess').modal("show");
                    break;
                case 2:
                    $('#myModalWarningBody').html(respuesta.mensaje);
                    $('#myModalWarning').modal("show");
                    break;                    
                default:
                    alert('Se ha producido un error');
                    document.location = 'facturas';
                    break;
            }
        },
        complete: function(){
            mostrarFacturas();
        }
    }); 
}

function confirmFacturaCancelar( idbodega, idfactura, estado_interno, sig_estado_interno){
    bootbox.confirm({
        title: 'Cancelar factura # '+idfactura,
        message: '<label>Comentario</label><textarea maxlength="290" id="comentario_cancelar" class="form-control" placeholder="Ingrese un comentario"></textarea>',
        backdrop: true,
        buttons: {
            'cancel': {
                label: 'Cerrar',
                className: 'btn-default pull-right'
            },
            'confirm': {
                label: 'Cancelar',
                className: 'btn-danger pull-left'
            }
        },
        callback: function(result) {
            if (result) {
                bootbox.hideAll();
                facturaCancelar( idbodega, idfactura, estado_interno, sig_estado_interno);
            }
        }
    });
}

function facturaCancelar( idbodega, idfactura, estado_interno, sig_estado_interno){
    var usuario = $("#session_usuario").val();
    var comentario = $("#comentario_cancelar").val();
    //console.log(comentario);
    if(comentario != ''){
        $.ajax({
            async: false,
            type: "POST",
            url: "util/facturas/cancelar.php",
            dataType: 'json',
            data: {
                usuario:usuario,
                comentario:comentario,
                idbodega:idbodega,
                idfactura:idfactura,
                estado_interno:estado_interno,
                sig_estado_interno:sig_estado_interno
            },
            //beforeSend: function(){ },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = 'facturas';
            },
            success: function(respuesta){          
                switch (respuesta.estado){
                    case 1:
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        $('#myModalSuccess').modal("show");
                        break;
                    case 2:
                        $('#myModalWarningBody').html(respuesta.mensaje);
                        $('#myModalWarning').modal("show");
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = 'facturas';
                        break;
                }
            },
            complete: function(){
                mostrarFacturas();
            }
        }); 
    }else{
        $('#myModalWarningBody').html("Debe ingresar un comentario para cancelar una factura");
        $('#myModalWarning').modal("show");
    }
}

function facturaCerrar( idbodega, idfactura, estado_interno, sig_estado_interno){
    var usuario = $("#session_usuario").val();
    var comentario = $("#comentario_cerrar").val();
    //console.log(comentario);
    
    $.ajax({
        async: false,
        type: "POST",
        url: "util/facturas/cerrar.php",
        dataType: 'json',
        data: {
            usuario:usuario,
            comentario:comentario,
            idbodega:idbodega,
            idfactura:idfactura,
            estado_interno:estado_interno,
            sig_estado_interno:sig_estado_interno
        },
        //beforeSend: function(){ },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'facturas';
        },
        success: function(respuesta){          
            switch (respuesta.estado){
                case 1:
                    $('#myModalSuccessBody').html(respuesta.mensaje);
                    $('#myModalSuccess').modal("show");
                    break;
                case 2:
                    $('#myModalWarningBody').html(respuesta.mensaje);
                    $('#myModalWarning').modal("show");
                    break;                    
                default:
                    alert('Se ha producido un error');
                    document.location = 'facturas';
                    break;
            }
        },
        complete: function(){
            mostrarFacturas();
        }
    }); 
}

function redimensionarjqGrid(){    
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridFacturas').setGridWidth((width - 4));
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    cargarOptionDatosBodegasPorUsuario();
    
    $(window).on("resize", function () {
        redimensionarjqGrid();
    });
    
    // Select all tabs
    $('.nav-tabs a').on('click', function (event) {
        redimensionarjqGrid();
    });
    
    $('#fecha_inifin').datepicker({
        autoclose:true
        //startDate: new Date($("#fecha_servidor").val())
    });
    
    $("#jqGridFacturas").on("click",'.gestion_update',function(event){
        var idbodega =  this.dataset.idbodega;
        var idfactura =  this.dataset.idfactura;
        var estado_interno =  this.dataset.estado_interno;
        var sig_estado_interno =  this.dataset.sig_estado_interno;
        //console.log(idbodega +' - '+ idfactura +' - '+ estado_interno +' - '+ sig_estado_interno);
        
        if($('#actualizar').val() == 1){
            
            if(estado_interno == 'CREADA'){
                // Cuando el ya el transportita trae el dinero
                bootbox.confirm({
                    title: 'Cerrar factura # '+idfactura,
                    message: '<label>Comentario</label><textarea maxlength="290" id="comentario_cerrar" class="form-control" placeholder="Ingrese un comentario"></textarea>',
                    backdrop: true,
                    buttons: {
                        'cancel': {
                            label: 'Cancelar',
                            className: 'btn-default pull-right'
                        },
                        'confirm': {
                            label: 'Cerrar factura',
                            className: 'btn-success pull-left'
                        }
                    },
                    callback: function(result) {
                        if (result) {
                            bootbox.hideAll();
                            facturaCerrar(idbodega, idfactura, estado_interno, sig_estado_interno);
                        }
                    }
                });
                
            }
            
        }else{
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show");  
        }
        
    });
    
    $("#jqGridFacturas").on("click",'.gestion_print',function(event){
        var idfactura =  this.dataset.idfactura;
        var numero_factura =  this.dataset.numero_factura;
        //console.log(idfactura + '*' + numero_factura);
        
        if($('#actualizar').val() == 1){
        
            bootbox.confirm({
                title: 'Generar factura # '+idfactura,
                message: '¿ Está seguro que desea generar el secuencial '+numero_factura+' ?',
                backdrop: true,
                buttons: {
                    'cancel': {
                        label: 'Cerrar',
                        className: 'btn-default pull-right'
                    },
                    'confirm': {
                        label: 'Generar',
                        className: 'btn-success pull-left'
                    }
                },
                callback: function(result) {
                    if (result) {
                        bootbox.hideAll();
                        generarFactura(idfactura, numero_factura);
                    }
                }
            });
        
        }else{
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show");  
        }
    });
    
    $("#jqGridFacturas").on("click",'.gestion_delete',function(event){
        var idbodega =  this.dataset.idbodega;
        var idfactura =  this.dataset.idfactura;
        var estado_interno =  this.dataset.estado_interno;
        var sig_estado_interno =  this.dataset.sig_estado_interno;
        //console.log(idbodega +' - '+ idfactura +' - '+ estado_interno +' - '+ sig_estado_interno);
        
        if($('#actualizar').val() == 1){
            // Abrir el dialogo para ingresar un comentario
            confirmFacturaCancelar( idbodega, idfactura, estado_interno, sig_estado_interno);           
        }else{
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show");  
        }
        
    });
    
    $("#factura_detalle").on("click",'.info_producto',function(event){ 
        var idproducto = this.dataset.idproducto;
        //console.log(idproducto);
        if(parseInt(idproducto) > 0){
            mostrarDetalleProducto(idproducto, 'facturas');
        }else{
            $('#myModalWarningBody').html('El servicio no tiene imagen');
            $('#myModalWarning').modal("show");
        }
    });
    
    $("#jqGridFacturas").on("click",'.gestion_select',function(event){
        var idfactura =  this.dataset.idfactura;
        $('.nav-tabs a[href="#menu1Now"]').tab('show');
        
        $('#idfactura option[value="'+idfactura+'"]').prop('selected', true);        
        mostrarFacturaDetalles();
    });
    
    $("#formFacturas").submit(function(){
        mostrarFacturas();
        return false;
    });
    
    $("#formFacturaDetalle").submit(function(){
        mostrarFacturaDetalles();
        return false;
    });
    
});