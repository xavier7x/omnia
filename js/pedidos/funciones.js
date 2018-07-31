function cargarOptionPedidos(){
    var usuario = $("#session_usuario").val();
    
    $.ajax({
        type: "POST",
        url: $("#param_hostapp").val() + '/util/pedidos/optionPedidos.php',
        dataType: 'json',
        data: {
            usuario:usuario
        },
        beforeSend: function(){
            $("#idpedido").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/pedidos';
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

function mostrarPedidos(){
    var usuario = $("#session_usuario").val();
    var mydata;

    $('#jqGridPedidos').jqGrid('clearGridData');

    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsclientes/pedidos.php',
        data: {
            usuario:usuario
        }, 
        dataType: "json",
        //beforeSend: function(){},
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/pedidos';
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
            { label: 'Gestión', name: 'btn_gestion', width: 100, align: 'left', sortable: false },
            { label: '# Pedido', name: 'idpedido', width: 100, align: 'right', key: true },
            { label: 'Envío', name: 'titulo_env', width: 150, align: 'left'},
            { label: 'Método pago', name: 'nombre_metodopago', width: 150, align: 'left'},
            { label: 'Total con envío', name: 'total_con_envio', width: 150, align: 'right'},
            { label: 'Estado', name: 'estado', width: 150, align: 'center'},
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
        
}

function mostrarPedidoDetalles(){
    var idpedido = $("#idpedido option:selected").val();
    //console.log(idpedido);
    $.ajax({
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsclientes/pedidoDetalle.php',
        data: {
            idpedido:idpedido
        }, 
        dataType: "json",
        beforeSend: function(){
            $("#pedido_detalle").html('<div class="col-sm-12 text-center"><i class="fa fa-spinner fa-spin" style="font-size:60px;color:#0071BC;margin-top:50px;margin-bottom:50px;"></i></div>');
        },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/pedidos';
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
                cuerpo_pedido += '<strong></strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span></span>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '</div>';
                
                cuerpo_pedido += '<div class="col-sm-12">';
                cuerpo_pedido += '<div class="row">';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<strong>Estado:</strong>';
                cuerpo_pedido += '</div>';
                cuerpo_pedido += '<div class="col-sm-3 text-left">';
                cuerpo_pedido += '<span>'+cabecera['estado']+'</span>';
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
                    cuerpo_pedido += '<td class="text-left"><a href="'+$("#param_hostapp").val()+'/productos/'+detalles[f]['nombre_seo']+'"><span class="glyphicon glyphicon-chevron-right"></span> '+detalles[f]['nombre']+'</a></td>';
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
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    cargarOptionPedidos();
    mostrarPedidos();
    
    $(window).on("resize", function () {
        redimensionarjqGrid();
    });
    
    // Select all tabs
    $('.nav-tabs a').on('click', function (event) {
        redimensionarjqGrid();
    }); 
    
    $("#jqGridPedidos").on("click",'.gestion_select',function(event){
        var idpedido =  this.dataset.idpedido;
        $('.nav-tabs a[href="#menu1"]').tab('show');
        
        $('#idpedido option[value="'+idpedido+'"]').prop('selected', true);        
        mostrarPedidoDetalles();
    });
    
    $("#formPedidos").submit(function(){
        cargarOptionPedidos();
        mostrarPedidos();
        return false;
    });
    
    $("#formPedidoDetalle").submit(function(){
        mostrarPedidoDetalles();
        return false;
    });
    
});