function mostrarStockPorBodega(){
    var idbodega = $("#idbodega option:selected").val();
    
    limpiarFormBodegaIngreso();
    limpiarFormBodegaEgreso();
    
    if($('#visualizar').val() == 1){
        var mydata;

        $('#jqGridBodegas').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/stockproductos/stockPorBodegas.php",
            data: {
                idbodega:idbodega
            }, 
            dataType: "json",
            //beforeSend: function(){},
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = 'stockproductos';
            },
            success: function(respuesta){
                mydata = respuesta.rows;
            },
            //complete: function(){}
        });

        $('#jqGridBodegas').jqGrid('setGridParam', {data: mydata});
        $('#jqGridBodegas').trigger('reloadGrid'); 

        $("#jqGridBodegas").jqGrid({
            datatype: 'local',
            data: mydata,
            styleUI : 'Bootstrap',
            colModel: [                
                { label: 'Gestión', name: 'btn_gestion', width: 150, align: 'left', sortable: false, search:false },
                { label: 'Idproducto',  name: 'idproducto', key: true, width: 150 },
                { label: 'Nombre', name: 'nombre', width: 200 },
                { label: 'Descripción corta', name: 'descripcion_corta', width: 200},
                { label: 'SKU', name: 'sku', align: 'center', width: 200},
                { label: 'Stock', name: 'stock', align: 'right', width: 150, search:false},
                { label: 'Precio', name: 'precio', align: 'right', width: 150, search:false},
                { label: 'Costo', name: 'costo', align: 'right', width: 150, search:false},                
                { label: 'Tiene imagen', name: 'tiene_imagen', width: 150, align: 'center', search:false},
                { label: 'Estado', name: 'estado', width: 150, align: 'center', search:false},           
                { label: 'Usuario actualización', name: 'user_update', width: 200, align: 'center', search:false},
                { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center', search:false},
                { label: 'Usuario creación', name: 'user_create', width: 200, align: 'center', search:false},
                { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center', search:false},
                { name: 'nombre_seo', hidden: true },
                { name: 'descripcion_larga', hidden: true },
                { name: 'idcategoria', hidden: true },
                { name: 'idproveedor', hidden: true },
                { name: 'idmarca', hidden: true },
                { name: 'idimpuesto', hidden: true },
                { name: 'mayor_edad', hidden: true },
                { name: 'costo_anterior', hidden: true },
                { name: 'precio_anterior', hidden: true },
                { name: 'nombre_bodega', hidden: true }
            ],
            height: 'auto',
            autowidth: true, // El ancho de la tabla es el de la pagina
            shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
            viewrecords: true,
            //autoencode: false,
            rowNum: $('#param_paginacion').val(),
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            pager: "#jqGridBodegasPager"
        });


        $('#jqGridBodegas').jqGrid('filterToolbar',{
            stringResult: true,
            // No es necesario dar enter para que funque la busqueda
            searchOnEnter: false,
            searchOperators : false
        });

        // Limipar la barra de busqueda

        $('#jqGridBodegas')[0].clearToolbar();

    }else{        
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");        
    }
}

function mostrarIngresosPorPorductos(){
    var idbodega = $("#idbodega_ing").val();
    var idproducto = $("#idproducto_ing").val();

    if(
        parseInt(idbodega) > 0 || 
        parseInt(idproducto) > 0 
    ){
        if($('#visualizar').val() == 1){
            var mydata;

            $('#jqGridBodegasIngreso').jqGrid('clearGridData');

            $.ajax({
                async: false,
                type: "POST",
                url: "util/stockproductos/queryIngresos.php",
                data: {
                    idbodega:idbodega,
                    idproducto:idproducto
                }, 
                dataType: "json",
                //beforeSend: function(){},
                error: function (request, status, error) {
                    console.log(request.responseText);
                    document.location = 'stockproductos';
                },
                success: function(respuesta){
                    mydata = respuesta.rows;
                },
                //complete: function(){}
            });

            $('#jqGridBodegasIngreso').jqGrid('setGridParam', {data: mydata});
            $('#jqGridBodegasIngreso').trigger('reloadGrid'); 

            $("#jqGridBodegasIngreso").jqGrid({
                datatype: 'local',
                data: mydata,
                styleUI : 'Bootstrap',
                colModel: [
                    { label: 'Motivo ingreso',  name: 'nombre_motivo', width: 200 },
                    { label: 'Comentario',  name: 'comentario', width: 200 },
                    { label: 'Cantidad', name: 'cantidad', align: 'right', width: 150},
                    { label: 'Stock anterior', name: 'stock_anterior', align: 'right', width: 150},
                    { label: 'Stock nuevo', name: 'stock_nuevo', align: 'right', width: 150},
                    { label: 'Costo', name: 'costo', align: 'right', width: 150},
                    { label: 'Costo total', name: 'costo_total', align: 'right', width: 150},
                    { label: 'Precio', name: 'precio', align: 'right', width: 150},
                    { label: 'Precio total', name: 'precio_total', align: 'right', width: 150},    
                    { label: 'Margen', name: 'margen', align: 'right', width: 150},
                    { label: 'Margen total', name: 'margen_total', align: 'right', width: 150},
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
                pager: "#jqGridBodegasIngresoPager"
            });

        }else{        
            $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
            $('#myModalWarning').modal("show");        
        }
    }else{
        $('#myModalWarningBody').html('Debe seleccionar un producto');
        $('#myModalWarning').modal("show");
    }
}

function mostrarEgresosPorPorductos(){
    var idbodega = $("#idbodega_egr").val();
    var idproducto = $("#idproducto_egr").val();

    if(
        parseInt(idbodega) > 0 || 
        parseInt(idproducto) > 0 
    ){
        if($('#visualizar').val() == 1){
            var mydata;

            $('#jqGridBodegasEgreso').jqGrid('clearGridData');

            $.ajax({
                async: false,
                type: "POST",
                url: "util/stockproductos/queryEgresos.php",
                data: {
                    idbodega:idbodega,
                    idproducto:idproducto
                }, 
                dataType: "json",
                //beforeSend: function(){},
                error: function (request, status, error) {
                    console.log(request.responseText);
                    document.location = 'stockproductos';
                },
                success: function(respuesta){
                    mydata = respuesta.rows;
                },
                //complete: function(){}
            });

            $('#jqGridBodegasEgreso').jqGrid('setGridParam', {data: mydata});
            $('#jqGridBodegasEgreso').trigger('reloadGrid'); 

            $("#jqGridBodegasEgreso").jqGrid({
                datatype: 'local',
                data: mydata,
                styleUI : 'Bootstrap',
                colModel: [
                    { label: 'Motivo egreso',  name: 'nombre_motivo', width: 200 },
                    { label: 'Comentario',  name: 'comentario', width: 200 },
                    { label: 'Cantidad', name: 'cantidad', align: 'right', width: 150},
                    { label: 'Stock anterior', name: 'stock_anterior', align: 'right', width: 150},
                    { label: 'Stock nuevo', name: 'stock_nuevo', align: 'right', width: 150},
                    { label: 'Costo', name: 'costo', align: 'right', width: 150},
                    { label: 'Costo total', name: 'costo_total', align: 'right', width: 150},
                    { label: 'Precio', name: 'precio', align: 'right', width: 150},
                    { label: 'Precio total', name: 'precio_total', align: 'right', width: 150},    
                    { label: 'Margen', name: 'margen', align: 'right', width: 150},
                    { label: 'Margen total', name: 'margen_total', align: 'right', width: 150},
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
                pager: "#jqGridBodegasEgresoPager"
            });

        }else{        
            $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
            $('#myModalWarning').modal("show");        
        }
    }else{
        $('#myModalWarningBody').html('Debe seleccionar un producto');
        $('#myModalWarning').modal("show");
    }
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
            document.location = 'stockproductos';
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

function cargarOptionMotivosIngreso(){
    $.ajax({
        //async: false,
        type: "POST",
        url: "util/stockproductos/optionMotivosIngreso.php",
        dataType: 'json',
        //data: { },
        beforeSend: function(){
            $("#idmotivoing").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'stockproductos';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idmotivoing']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idmotivoing").html(opcSelect); 
            }
        }
    }); 
}

function cargarOptionMotivosEgreso(){
    $.ajax({
        //async: false,
        type: "POST",
        url: "util/stockproductos/optionMotivosEgreso.php",
        dataType: 'json',
        //data: { },
        beforeSend: function(){
            $("#idmotivoegr").empty();
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = 'stockproductos';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idmotivoegr']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idmotivoegr").html(opcSelect); 
            }
        }
    }); 
}

function limpiarFormBodegaIngreso(){
    $('#formBodegaIngreso').trigger("reset");
    $('#tiene_imagen_ing').val("NO");
    
    d = new Date();
    $('#imagen_producto_ing').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
    
    $('#cnt_jqGridBodegasIngreso').html('<div class="table-responsive"><table id="jqGridBodegasIngreso"></table><div id="jqGridBodegasIngresoPager"></div></div>'); 
}

function limpiarFormBodegaEgreso(){
    $('#formBodegaEgreso').trigger("reset");
    $('#tiene_imagen_egr').val("NO");
    
    d = new Date();
    $('#imagen_producto_egr').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
    
    $('#cnt_jqGridBodegasEgreso').html('<div class="table-responsive"><table id="jqGridBodegasEgreso"></table><div id="jqGridBodegasEgresoPager"></div></div>'); 
}

function redimensionarjqGrid(){    
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridBodegas').setGridWidth((width - 4));
    $('#jqGridBodegasIngreso').setGridWidth((width - 4));
    $('#jqGridBodegasEgreso').setGridWidth((width - 4));
}

/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    cargarOptionDatosBodegasPorUsuario();
    cargarOptionMotivosIngreso();
    cargarOptionMotivosEgreso();
    
    $(window).on("resize", function () {
        redimensionarjqGrid();
    });
    
    // Select all tabs
    $('.nav-tabs a').on('click', function (event) {
        redimensionarjqGrid();
    });  
    
    $('#limpiarFormBodegaIngreso').click(function(){
        limpiarFormBodegaIngreso();
    });
    
    $('#limpiarFormBodegaEgreso').click(function(){
        limpiarFormBodegaEgreso();
    });
    
    $('#queryFormBodegaEgreso').click(function(){
        mostrarEgresosPorPorductos();
    });
    
    $('#queryFormBodegaIngreso').click(function(){
        mostrarIngresosPorPorductos();
    });
    
    $("#jqGridBodegas").on("click",'.gestion_add',function(event){
        var datos = $("#jqGridBodegas").jqGrid('getRowData', this.dataset.idproducto);
        var idproducto = this.dataset.idproducto;
        var idbodega = this.dataset.idbodega;
        var nombre_producto = this.dataset.nombre_producto;
        var nombre_bodega = this.dataset.nombre_bodega;
        var tiene_imagen = this.dataset.tiene_imagen;
        //console.log(datos);
        $("#idproducto_ing").val(idproducto);
        $("#idbodega_ing").val(idbodega);
        $("#nombre_producto_ing").val(nombre_producto);
        $("#nombre_bodega_ing").val(nombre_bodega);
        $("#tiene_imagen_ing").val(datos['tiene_imagen']);
        
        $('#imagen_producto_ing').removeAttr( "src" );
        d = new Date();
        
        if(datos['tiene_imagen'] == 'SI'){
            $('#imagen_producto_ing').attr('src',"../images/productos/"+datos['idproducto']+"/320x320/"+datos['nombre_seo']+".png?v="+d.getTime());
        }else{
            $('#imagen_producto_ing').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
        }
        
        $('.nav-tabs a[href="#menu1Now"]').tab('show');
        
        limpiarFormBodegaEgreso();
        
        setTimeout(function(){
            mostrarIngresosPorPorductos();
        }, 1000); 
        
    });
    
    $("#jqGridBodegas").on("click",'.gestion_delete',function(event){
        var datos = $("#jqGridBodegas").jqGrid('getRowData', this.dataset.idproducto);
        var idproducto = this.dataset.idproducto;
        var idbodega = this.dataset.idbodega;
        var nombre_producto = this.dataset.nombre_producto;
        var nombre_bodega = this.dataset.nombre_bodega;
        var tiene_imagen = this.dataset.tiene_imagen;
        //console.log(datos);
        $("#idproducto_egr").val(idproducto);
        $("#idbodega_egr").val(idbodega);
        $("#nombre_producto_egr").val(nombre_producto);
        $("#nombre_bodega_egr").val(nombre_bodega);
        $("#tiene_imagen_egr").val(datos['tiene_imagen']);
        
        $('#imagen_producto_egr').removeAttr( "src" );
        d = new Date();
        
        if(datos['tiene_imagen'] == 'SI'){
            $('#imagen_producto_egr').attr('src',"../images/productos/"+datos['idproducto']+"/320x320/"+datos['nombre_seo']+".png?v="+d.getTime());
        }else{
            $('#imagen_producto_egr').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
        }
        
        $('.nav-tabs a[href="#menu2"]').tab('show');
        
        limpiarFormBodegaIngreso();
        
        setTimeout(function(){
            mostrarEgresosPorPorductos();
        }, 1000); 
        
    });
    
    $("#formBodegas").submit(function(){
        mostrarStockPorBodega();
        return false;
    });
    
    $("#formBodegaIngreso").submit(function(){
        var usuario = $("#session_usuario").val();
        var idbodega = $("#idbodega_ing").val();
        var idproducto = $("#idproducto_ing").val();
        var idmotivoing = $("#idmotivoing option:selected").val();
        var cantidad = $("#cantidad_ing").val();        
        var comentario = $("#comentario_ing").val();
        var tiene_imagen = $("#tiene_imagen_ing").val();

        if(
            parseInt(idbodega) > 0 &&
            parseInt(idproducto) > 0
        ){
            if($('#insertar').val() == 1){
                if( tiene_imagen == 'SI' ){
                    $.ajax({
                        type: "POST",
                        url: "util/stockproductos/insertIngreso.php",
                        dataType: 'json',
                        data: {
                            usuario:usuario,
                            idbodega:idbodega,
                            idproducto:idproducto,
                            idmotivoing:idmotivoing,
                            cantidad:cantidad,
                            comentario:comentario
                        },
                        beforeSend: function(){
                            $("#submitFormBodegaIngreso").html('Insertando');
                            $('#submitFormBodegaIngreso').prop("disabled", true);
                        },
                        error: function (request, status, error) { 
                            console.log(request.responseText);
                            document.location = 'stockproductos';
                        },
                        success: function(respuesta){
                            switch (respuesta.estado){
                                case 1:
                                    $('#myModalSuccessBody').html(respuesta.mensaje);
                                    $('#myModalSuccess').modal("show");

                                    $('.nav-tabs a[href="#home"]').tab('show');
                                    mostrarStockPorBodega();
                                    break;
                                case 2:
                                    $('#myModalWarningBody').html(respuesta.mensaje);
                                    $('#myModalWarning').modal("show");

                                    break;                    
                                default:
                                    alert('Se ha producido un error');
                                    document.location = 'stockproductos';
                                    break;
                            } 
                        },
                        complete: function(){
                            $('#submitFormBodegaIngreso').prop("disabled", false);
                            $("#submitFormBodegaIngreso").html('Guardar');
                        }
                    }); 
                }else{
                    $('#myModalWarningBody').html('El producto debe tener una imagen para gestionar stock');
                    $('#myModalWarning').modal("show");
                }
            }else{
                $('#myModalWarningBody').html('Usted no tiene privilegios para insertar');
                $('#myModalWarning').modal("show");  
            }
        }else{
            $('#myModalWarningBody').html('Debe seleccionar un producto');
            $('#myModalWarning').modal("show");
        }
        
        return false;
    });
    
    $("#formBodegaEgreso").submit(function(){
        var usuario = $("#session_usuario").val();
        var idbodega = $("#idbodega_egr").val();
        var idproducto = $("#idproducto_egr").val();
        var idmotivoegr = $("#idmotivoegr option:selected").val();
        var cantidad = $("#cantidad_egr").val();        
        var comentario = $("#comentario_egr").val();
        var tiene_imagen = $("#tiene_imagen_egr").val();
        
        if(
            parseInt(idbodega) > 0 &&
            parseInt(idproducto) > 0
        ){
            if($('#insertar').val() == 1){
                if( tiene_imagen == 'SI' ){
                    
                    $.ajax({
                        type: "POST",
                        url: "util/stockproductos/insertEgreso.php",
                        dataType: 'json',
                        data: {
                            usuario:usuario,
                            idbodega:idbodega,
                            idproducto:idproducto,
                            idmotivoegr:idmotivoegr,
                            cantidad:cantidad,
                            comentario:comentario
                        },
                        beforeSend: function(){
                            $("#submitFormBodegaEgreso").html('Insertando');
                            $('#submitFormBodegaEgreso').prop("disabled", true);
                        },
                        error: function (request, status, error) { 
                            console.log(request.responseText);
                            document.location = 'stockproductos';
                        },
                        success: function(respuesta){
                            switch (respuesta.estado){
                                case 1:
                                    $('#myModalSuccessBody').html(respuesta.mensaje);
                                    $('#myModalSuccess').modal("show");

                                    $('.nav-tabs a[href="#home"]').tab('show');
                                    mostrarStockPorBodega();
                                    break;
                                case 2:
                                    $('#myModalWarningBody').html(respuesta.mensaje);
                                    $('#myModalWarning').modal("show");

                                    break;                    
                                default:
                                    alert('Se ha producido un error');
                                    document.location = 'stockproductos';
                                    break;
                            } 
                        },
                        complete: function(){
                            $('#submitFormBodegaEgreso').prop("disabled", false);
                            $("#submitFormBodegaEgreso").html('Guardar');
                        }
                    });
                    
                }else{
                    $('#myModalWarningBody').html('El producto debe tener una imagen para gestionar stock');
                    $('#myModalWarning').modal("show");
                }
            }else{
                $('#myModalWarningBody').html('Usted no tiene privilegios para insertar');
                $('#myModalWarning').modal("show");  
            }
        }else{
            $('#myModalWarningBody').html('Debe seleccionar un producto');
            $('#myModalWarning').modal("show");
        }
        return false;
    });
    
});