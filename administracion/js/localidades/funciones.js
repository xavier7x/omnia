function mostrarCostoEnvio(){
    var idzona = $("#idzona_env option:selected").val();
    
    if($('#visualizar').val() == 1){
        
        var mydata;

        $('#jqGridCostoEnvio').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/localidades/queryLocalidades.php",
            data: {
                idzona:idzona
            }, 
            dataType: "json",
            //beforeSend: function(){},
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = 'localidades';
            },
            success: function(respuesta){
                mydata = respuesta.rows;
            },
            //complete: function(){}
        });

        $('#jqGridCostoEnvio').jqGrid('setGridParam', {data: mydata});
        $('#jqGridCostoEnvio').trigger('reloadGrid'); 

        $("#jqGridCostoEnvio").jqGrid({
            datatype: 'local',
            data: mydata,
            styleUI : 'Bootstrap',
            colModel: [      
                { name: 'idsector', hidden: true, key: true },
                { label: 'Gestión', name: 'btn_gestion', width: 100, align: 'left', sortable: false },                
                { label: 'Nombre', name: 'nombre', width: 200 },                           
                { label: 'Costo envío', name: 'costo_envio', width: 150, align: 'center'}, 
                { label: 'Estado', name: 'estado', width: 150, align: 'center'},
                { label: 'Usuario actualización', name: 'user_update', width: 200, align: 'center'},
                { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center'},
                { label: 'Usuario creación', name: 'user_create', width: 200, align: 'center'},
                { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center'}
            ],
            height: 'auto',
            autowidth: true, // El ancho de la tabla es el de la pagina
            shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
            viewrecords: true,
            //autoencode: false,
            rowNum: $('#param_paginacion').val(),
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            pager: "#jqGridCostoEnvioPager"
        });
    }else{        
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");        
    }
}

function cargarOptionCantones(idprovincia){
    $.ajax({
        async: false,
        type: "POST",
        url: "util/localidades/optionCantones.php",
        dataType: 'json',
        data: {
            idprovincia: idprovincia
        },
        beforeSend: function(){
            $("#idcanton_env").empty();
            $("#idzona_env").empty(); 
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'localidades';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idcanton']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idcanton_env").html(opcSelect);
                
                // Cargar las zonas
                cargarOptionZonas($("#idcanton_env option:selected").val());
            }
        }
    }); 
}

function cargarOptionZonas(idcanton){
    $.ajax({
        async: false,
        type: "POST",
        url: "util/localidades/optionZonas.php",
        dataType: 'json',
        data: {
            idcanton:idcanton
        },
        beforeSend: function(){
            $("#idzona_env").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'localidades';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idzona']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idzona_env").html(opcSelect);
            }
        }
    }); 
}

function redimensionarjqGrid(){    
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridCostoEnvio').setGridWidth((width - 4));
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    $(window).on("resize", function () {
        redimensionarjqGrid();
    });
    
    // Select all tabs
    $('.nav-tabs a').on('click', function (event) {
        redimensionarjqGrid();
    });
    
    cargarOptionCantones(1);
    
    $("#jqGridCostoEnvio").on("click",'.gestion_update',function(event){ 
        var datos = $("#jqGridCostoEnvio").jqGrid('getRowData', this.dataset.idsector);
        //console.log(datos);
        $('#idsector_env').val(datos['idsector']);
        $('#nombre_env').val(datos['nombre']);
        $('#costo_envio_env').val(datos['costo_envio']);
        
        $('#modalCostoEnvio').modal("show");
    });
    
    $("#idcanton_env").on('change keyup', function () {
        cargarOptionZonas($("#idcanton_env option:selected").val());
    });
    
    $("#formModalCostoEnvio").submit(function(){
        var usuario = $("#session_usuario").val();
        var idsector = $("#idsector_env").val();
        var costo_envio = $("#costo_envio_env").val();
        $('#modalCostoEnvio').modal("hide");
        //alert(idsector + ' - ' + costo_envio);
        
        if(parseInt(idsector) > 0){
            
            if(parseInt(costo_envio) >= 0){
                
                if($('#actualizar').val() == 1){
                    
                    $.ajax({
                        type: "POST",
                        url: "util/localidades/updateCostoEnvio.php",
                        dataType: 'json',
                        data: {
                            usuario:usuario,
                            idsector:idsector,
                            costo_envio:costo_envio
                        },
                        //beforeSend: function(){ },
                        error: function (request, status, error) { 
                            console.log(request.responseText);
                            document.location = 'localidades';
                        },
                        success: function(respuesta){
                            switch (respuesta.estado){
                                case 1:
                                    $('#myModalSuccessBody').html(respuesta.mensaje);
                                    $('#myModalSuccess').modal("show");
                                    
                                    mostrarCostoEnvio();
                                    break;
                                case 2:
                                    $('#myModalWarningBody').html(respuesta.mensaje);
                                    $('#myModalWarning').modal("show");

                                    break;                    
                                default:
                                    alert('Se ha producido un error');
                                    document.location = 'localidades';
                                    break;
                            } 
                        },
                        //complete: function(){ }
                    }); 
                    
                }else{
                    $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
                    $('#myModalWarning').modal("show");  
                }
                
            }else{
                $('#myModalWarningBody').html('Debe colocar un costo de envío');
                $('#myModalWarning').modal("show");
            }
            
        }else{
            $('#myModalWarningBody').html('Debe seleccionar un sector');
            $('#myModalWarning').modal("show"); 
        }
        
        return false;
    }); 
    
    $("#formCostoEnvio").submit(function(){
        //var idcanton = $("#idcanton_env option:selected").val();
        //var idzona = $("#idzona_env option:selected").val();
        //alert(idcanton + ' - ' + idzona);        
        mostrarCostoEnvio();
        return false;
    }); 
    
});