function mostrarPedidos(){
    var idbodega = $("#idbodega option:selected").val();
    var inicio = $("#fecha_inicio").val();
    var fin = $("#fecha_fin").val();
    
    $("#pedido_detalle").html('<div class="panel panel-warning"><div class="panel-body text-center bg-warning"><strong>Seleccione un pedido para visualizar el detalle</strong></div></div>');
    
    if($('#visualizar').val() == 1){
        cargarOptionPedidos( idbodega, inicio, fin );
        
        var mydata;

        $('#jqGridPedidos').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/pedidos/query.php",
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
                document.location = 'pedidos';
            },
            success: function(respuesta){
                mydata = respuesta.rows;
            },
            //complete: function(){}
        });

        $('#jqGridPedidos').jqGrid('setGridParam', {data: mydata});
        $('#jqGridPedidos').trigger('reloadGrid'); 

        $("#jqGridPedidos").jqGrid({
            datatype: 'local',
            data: mydata,
            styleUI : 'Bootstrap',
            colModel: [            
                { label: 'Gestión', name: 'btn_gestion', width: 250, align: 'left', sortable: false },
                { label: '# Pedido', name: 'idpedido', width: 100, align: 'right', key: true },
                { label: 'Generado', name: 'generado', width: 100, align: 'center'},
                { label: 'Envío', name: 'titulo_env', width: 150, align: 'left'},
                { label: 'Método pago', name: 'nombre_metodopago', width: 150, align: 'left'},
                { label: 'Total con envío', name: 'total_con_envio', width: 150, align: 'right'},
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
            pager: "#jqGridPedidosPager"
        });
    }else{        
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");        
    }
}

function cargarOptionPedidos( idbodega, inicio, fin ){
    $.ajax({
        type: "POST",
        url: "util/pedidos/optionPedidos.php",
        dataType: 'json',
        data: {
            idbodega:idbodega,
            inicio:inicio,
            fin:fin
        },
        beforeSend: function(){
            $("#idpedido").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'pedidos';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idpedido']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idpedido").html(opcSelect);
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
            document.location = 'pedidos';
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

function mostrarPedidoDetalles(){
    var idpedido = $("#idpedido option:selected").val();
    //console.log(idpedido);
    $.ajax({
        type: "POST",
        url: "util/pedidos/pedidoDetalle.php",
        data: {
            idpedido:idpedido
        }, 
        dataType: "json",
        beforeSend: function(){
            $("#pedido_detalle").html('<div class="col-sm-12 text-center"><i class="fa fa-spinner fa-spin" style="font-size:60px;color:#0071BC;margin-top:50px;margin-bottom:50px;"></i></div>');
        },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = 'pedidos';
        },
        success: function(respuesta){
            var cabecera = respuesta.cabecera;
            var detalles = respuesta.detalles;
            
            if(
                detalles.length > 0
            ){
                var cuerpo_pedido = '<div class="panel panel-primary">';
                cuerpo_pedido += '<div class="panel-heading text-center">Pedido # '+cabecera['idpedido']+'</div>';
                cuerpo_pedido += '<div class="panel-body">';
                cuerpo_pedido += '<div class="row">';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Horario:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['inicio']+' - '+cabecera['fin'].substring(11)+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Usuario:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['usuario']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Estado:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['estado_interno']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Método pago:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['nombre_metodopago']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Total:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-right">';
                cuerpo_pedido += '<span>'+cabecera['total']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Costo envío:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-right">';
                cuerpo_pedido += '<span>'+cabecera['costo_envio']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Total con envío:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-right">';
                cuerpo_pedido += '<span>'+cabecera['total_con_envio']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong></strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span></span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                if(cabecera['comentario'] != null){
                    cuerpo_pedido += '<div class="col-sm-12">';
                    cuerpo_pedido += '<div class="row">';
                    cuerpo_pedido += '<div class="col-sm-3 text-left">';
                    cuerpo_pedido += '<strong>Comentario:</strong>';
                    cuerpo_pedido += '</div>';
                    cuerpo_pedido += '<div class="col-sm-9 text-left">';
                    cuerpo_pedido += '<span>'+cabecera['comentario']+'</span>';
                    cuerpo_pedido += '</div>';
                    cuerpo_pedido += '</div>';
                    cuerpo_pedido += '</div>'; 
                }
                
                cuerpo_pedido += '<div class="col-sm-12 text-center form-group">';
                cuerpo_pedido += '<hr>';
                cuerpo_pedido += '<strong>Datos envío</strong>';              
                cuerpo_pedido += '</div>';
                /*
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Alias:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-9 text-left">';
                cuerpo_pedido += '<span>'+cabecera['titulo_env']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                */
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Nombre:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-9 text-left">';
                cuerpo_pedido += '<span>'+cabecera['nombre_env']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Dirección:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-9 text-left">';
                cuerpo_pedido += '<span>'+cabecera['direccion_env']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Teléfono / Móvil:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['movil1_env']+'</span>';
                cuerpo_pedido += '</div>';
                if(cabecera['movil2_env'] != null){
                    cuerpo_pedido += '<div class="col-sm-3 text-left">';
                    cuerpo_pedido += '<strong>Teléfono / Móvil:</strong>';
                    cuerpo_pedido += '</div>';
                    cuerpo_pedido += '<div class="col-sm-3 text-left">';
                    cuerpo_pedido += '<span>'+cabecera['movil2_env']+'</span>';
                    cuerpo_pedido += '</div>';
                }
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Provincia:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['provincia_nom']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Cantón:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['canton_nom']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Zona:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['zona_nom']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Sector:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['sector_nom']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                
                cuerpo_pedido += '<div class="col-sm-12 text-center form-group">';
                cuerpo_pedido += '<hr>';
                cuerpo_pedido += '<strong>Datos facturación</strong>';
                cuerpo_pedido += '</div>';
                /*
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Alias:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-9 text-left">';
                cuerpo_pedido += '<span>'+cabecera['titulo_fac']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                */
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Nombre:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-9 text-left">';
                cuerpo_pedido += '<span>'+cabecera['nombre_fac']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Dirección:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-9 text-left">';
                cuerpo_pedido += '<span>'+cabecera['direccion_fac']+'</span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>'; 
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>CI / RUC:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['num_doc_fac']+'</span>';
                cuerpo_pedido += '</div>';                
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Email:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['mail_fac']+'</span>';
                cuerpo_pedido += '</div>';                
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Teléfono / Móvil:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['movil1_fac']+'</span>';
                cuerpo_pedido += '</div>';
                if(cabecera['movil2_fac'] != null){
                    cuerpo_pedido += '<div class="col-sm-3 text-left">';
                    cuerpo_pedido += '<strong>Teléfono / Móvil:</strong>';
                    cuerpo_pedido += '</div>';
                    cuerpo_pedido += '<div class="col-sm-3 text-left">';
                    cuerpo_pedido += '<span>'+cabecera['movil2_fac']+'</span>';
                    cuerpo_pedido += '</div>';
                }
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<hr>';
                cuerpo_pedido += '<div class="table-responsive">';
                cuerpo_pedido += '<table class="table table-bordered table-striped table-hover">';
                cuerpo_pedido += '<thead>';
                cuerpo_pedido += '<tr>';
                cuerpo_pedido += '<th>#</th>';
                cuerpo_pedido += '<th>Producto</th>';
                cuerpo_pedido += '<th>Cantidad</th>';
                cuerpo_pedido += '<th>Precio</th>';
                cuerpo_pedido += '<th>Impuesto</th>';
                cuerpo_pedido += '<th>Subtotal</th>';
                cuerpo_pedido += '<th>Total</th>';
                cuerpo_pedido += '</tr>';
                cuerpo_pedido += '</thead>';
                cuerpo_pedido += '<tbody>';
                
                for(var f=0; f < detalles.length; f++){
                    cuerpo_pedido += '<tr>';
                    cuerpo_pedido += '<td class="text-right">'+(f + 1)+'</td>';
                    cuerpo_pedido += '<td class="text-left"><a class="info_producto" data-idproducto="'+detalles[f]['idproducto']+'"><span class="glyphicon glyphicon-chevron-right"></span> '+detalles[f]['nombre']+'</a></td>';
                    cuerpo_pedido += '<td class="text-center">'+detalles[f]['cantidad']+'</td>';
                    cuerpo_pedido += '<td class="text-right">'+detalles[f]['precio']+'</td>';
                    cuerpo_pedido += '<td class="text-right">'+detalles[f]['valor_impuesto']+'</td>';
                    cuerpo_pedido += '<td class="text-right">'+detalles[f]['subtotal']+'</td>';
                    cuerpo_pedido += '<td class="text-right">'+detalles[f]['total']+'</td>';
                    cuerpo_pedido += '</tr>';
                }
                
                cuerpo_pedido += '</tbody>';
                cuerpo_pedido += '</table>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                $("#pedido_detalle").html(cuerpo_pedido); 
            }else{
                $("#pedido_detalle").html('<div class="panel panel-danger"><div class="panel-body text-center bg-danger"><strong>Sin datos del pedido</strong></div></div>');  
            }
        },
        //complete: function(){}
    });
}

function redimensionarjqGrid(){    
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridPedidos').setGridWidth((width - 4));
}

function mostrarSecuencialFactura(idbodega, idpedido, estado_interno, sig_estado_interno){
    $.ajax({
        async: false,
        type: "POST",
        url: "util/pedidos/secuencialFactura.php",
        dataType: 'json',
        data: {
            idbodega:idbodega,
            idpedido:idpedido,
            estado_interno:estado_interno
        },
        //beforeSend: function(){ },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'pedidos';
        },
        success: function(respuesta){            
            switch (respuesta.estado){
                case 1:
                    bootbox.confirm({
                        title: 'Confirmar # factura',
                        message: respuesta.establecimiento +'-'+ respuesta.punto_emision +'-'+ respuesta.secuencial ,
                        backdrop: true,
                        buttons: {
                            'cancel': {
                                label: 'Cancelar',
                                className: 'btn-default pull-right'
                            },
                            'confirm': {
                                label: 'Confirmar',
                                className: 'btn-success pull-left'
                            }
                        },
                        callback: function(result) {
                            if (result) {
                                bootbox.hideAll();
                                pedidoFacturar(respuesta.establecimiento, respuesta.punto_emision, respuesta.secuencial, idbodega, idpedido, estado_interno, sig_estado_interno);
                            }
                        }
                    });
                    
                    break;
                case 2:
                    $('#myModalWarningBody').html(respuesta.mensaje);
                    $('#myModalWarning').modal("show");
                    
                    mostrarPedidos();
                    break;                    
                default:
                    alert('Se ha producido un error');
                    document.location = 'pedidos';
                    break;
            }
        }
    }); 
}

function pedidoFacturar(establecimiento, punto_emision, secuencial, idbodega, idpedido, estado_interno, sig_estado_interno){
    var usuario = $("#session_usuario").val();
    //alert(establecimiento +' - '+punto_emision +' - '+secuencial +' - '+ idpedido +' - '+ estado_interno +' - '+ sig_estado_interno);
    $.ajax({
        async: false,
        type: "POST",
        url: "util/pedidos/facturar.php",
        dataType: 'json',
        data: {
            usuario:usuario,
            establecimiento:establecimiento,
            punto_emision:punto_emision,
            secuencial:secuencial,
            idbodega:idbodega,
            idpedido:idpedido,
            estado_interno:estado_interno,
            sig_estado_interno:sig_estado_interno
        },
        //beforeSend: function(){ },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'pedidos';
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
                    document.location = 'pedidos';
                    break;
            }
        },
        complete: function(){
            mostrarPedidos();
        }
    }); 
}

function confirmPedidoCancelar( idbodega, idpedido, estado_interno, sig_estado_interno){
    bootbox.confirm({
        title: 'Cancelar pedido # '+idpedido,
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
                pedidoCancelar( idbodega, idpedido, estado_interno, sig_estado_interno);
            }
        }
    });
}

function pedidoCancelar( idbodega, idpedido, estado_interno, sig_estado_interno){
    var usuario = $("#session_usuario").val();
    var comentario = $("#comentario_cancelar").val();
    //console.log(comentario);
    if(comentario != ''){
        $.ajax({
            async: false,
            type: "POST",
            url: "util/pedidos/cancelar.php",
            dataType: 'json',
            data: {
                usuario:usuario,
                comentario:comentario,
                idbodega:idbodega,
                idpedido:idpedido,
                estado_interno:estado_interno,
                sig_estado_interno:sig_estado_interno
            },
            //beforeSend: function(){ },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = 'pedidos';
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
                        document.location = 'pedidos';
                        break;
                }
            },
            complete: function(){
                mostrarPedidos();
            }
        }); 
    }else{
        $('#myModalWarningBody').html("Debe ingresar un comentario para cancelar un pedido");
        $('#myModalWarning').modal("show");
    }
}

function generarPedido(idpedido){
    var usuario = $("#session_usuario").val();
    
    $.ajax({
        async: false,
        type: "POST",
        url: "util/pedidos/generarPedido.php",
        dataType: 'json',
        data: {
            usuario:usuario,
            idpedido:idpedido
        },
        //beforeSend: function(){ },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'pedidos';
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
                    document.location = 'pedidos';
                    break;
            }
        },
        complete: function(){
            mostrarPedidos();
        }
    }); 
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
    
    $("#pedido_detalle").on("click",'.info_producto',function(event){ 
        var idproducto = this.dataset.idproducto;
        console.log(idproducto);
        mostrarDetalleProducto(idproducto, 'pedidos');
    });
    
    $("#jqGridPedidos").on("click",'.gestion_select',function(event){
        var idpedido =  this.dataset.idpedido;
        $('.nav-tabs a[href="#menu1Now"]').tab('show');
        
        $('#idpedido option[value="'+idpedido+'"]').prop('selected', true);        
        mostrarPedidoDetalles();
    });
    
    $("#jqGridPedidos").on("click",'.gestion_update',function(event){
        var idbodega =  this.dataset.idbodega;
        var idpedido =  this.dataset.idpedido;
        var estado_interno =  this.dataset.estado_interno;
        var sig_estado_interno =  this.dataset.sig_estado_interno;
        //console.log(idbodega +' - '+ idpedido +' - '+ estado_interno +' - '+ sig_estado_interno);
        
        if($('#actualizar').val() == 1){
            
            if(estado_interno == 'CREADO'){
                // Abrir el dialogo para confirmar el secuencial
                mostrarSecuencialFactura(idbodega, idpedido, estado_interno, sig_estado_interno);
            }
            
        }else{
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show");  
        }
        
    });
    
    $("#jqGridPedidos").on("click",'.gestion_print',function(event){
        var idpedido =  this.dataset.idpedido;
        //console.log(idpedido);
        
        if($('#actualizar').val() == 1){
        
            bootbox.confirm({
                title: 'Generar pedido # '+idpedido,
                message: '¿ Está seguro que desea generar el pedido # '+idpedido+' ?',
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
                        generarPedido(idpedido);
                    }
                }
            });
        
        }else{
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show");  
        }
    });
    
    $("#jqGridPedidos").on("click",'.gestion_delete',function(event){
        var idbodega =  this.dataset.idbodega;
        var idpedido =  this.dataset.idpedido;
        var estado_interno =  this.dataset.estado_interno;
        var sig_estado_interno =  this.dataset.sig_estado_interno;
        //console.log(idbodega +' - '+ idpedido +' - '+ estado_interno +' - '+ sig_estado_interno);
        
        if($('#actualizar').val() == 1){
            // Abrir el dialogo para ingresar un comentario
            confirmPedidoCancelar( idbodega, idpedido, estado_interno, sig_estado_interno);           
        }else{
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show");  
        }
        
    });
    
    $("#formPedidos").submit(function(){
        mostrarPedidos();
        return false;
    });
    
    $("#formPedidoDetalle").submit(function(){
        mostrarPedidoDetalles();
        return false;
    });
    
});