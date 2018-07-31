function mostrarDatos(){
    
    if($('#visualizar').val() == 1){
        var mydata;

        $('#jqGridProductos').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/productos/query.php",
            //data: {}, 
            dataType: "json",
            //beforeSend: function(){},
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = 'productos';
            },
            success: function(respuesta){
                mydata = respuesta.rows;
            },
            //complete: function(){}
        });

        $('#jqGridProductos').jqGrid('setGridParam', {data: mydata});
        $('#jqGridProductos').trigger('reloadGrid'); 
        
        $("#jqGridProductos").jqGrid({
            datatype: 'local',
            data: mydata,
            styleUI : 'Bootstrap',
            colModel: [                
                { label: 'Gestión', name: 'btn_gestion', width: 100, align: 'left', sortable: false, search:false },
                { label: 'Idproducto',  name: 'idproducto', key: true, width: 150 },
                { label: 'Nombre', name: 'nombre', width: 200 },
                { label: 'Descripción corta', name: 'descripcion_corta', width: 200},
                { label: 'Tiene IMP', name: 'tiene_imp', align: 'center', width: 100, search:false},
                { label: 'Precio', name: 'precio', align: 'right', width: 150, search:false},
                { label: 'Precio IMP', name: 'precio_impuesto', align: 'right', width: 150, search:false},
                { label: 'Costo', name: 'costo', align: 'right', width: 150, search:false},
                { label: 'SKU', name: 'sku', align: 'center', width: 200},
                { label: 'Tiene imagen', name: 'tiene_imagen', width: 150, align: 'center', search:false},
                { label: 'Estado', name: 'estado', width: 150, align: 'center', search:false},           
                { label: 'Usuario actualización', name: 'user_update', width: 200, align: 'center', search:false},
                { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center', search:false},
                { label: 'Usuario creación', name: 'user_create', width: 200, align: 'center', search:false},
                { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center', search:false},
                { name: 'nombre_seo', hidden: true },
                { name: 'descripcion_larga', hidden: true },
                { name: 'idsubcategoria', hidden: true },
                { name: 'idproveedor', hidden: true },
                { name: 'idmarca', hidden: true },
                { name: 'idimpuesto', hidden: true },
                { name: 'mayor_edad', hidden: true },
                { name: 'costo_anterior', hidden: true },
                { name: 'precio_anterior', hidden: true }
            ],
            height: 'auto',
            autowidth: true, // El ancho de la tabla es el de la pagina
            shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
            viewrecords: true,
            //autoencode: false,
            rowNum: $('#param_paginacion').val(),
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            pager: "#jqGridProductosPager"
        });
        
        
        $('#jqGridProductos').jqGrid('filterToolbar',{
            stringResult: true,
            // No es necesario dar enter para que funque la busqueda
            searchOnEnter: false,
            searchOperators : false
        });
        
        // Limipar la barra de busqueda
        
        $('#jqGridProductos')[0].clearToolbar();
        
    }else{        
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");        
    }
}

function limpiarFormProductoDetalle(){
    $('#formProductoDetalle').trigger("reset");
    $("#idproducto").val('0');
    $("#costo_anterior").val('0');
    $("#precio_anterior").val('0');
    d = new Date();
    $('#imagen_producto').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
}

function redimensionarjqGrid(){
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridProductos').setGridWidth((width - 4));
}

function cargarOptionSubcategorias(){
    
    if($('#visualizar').val() == 1){
        $.ajax({
            type: "POST",
            url: "util/productos/optionSubcategorias.php",
            dataType: 'json',
            //data: { },
            beforeSend: function(){
                $("#idsubcategoria").empty();
            },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = 'productos';
            },
            success: function(respuesta){
                var opciones = respuesta.resultado;
                if(opciones.length > 0){
                    // Crear los option
                    //console.log(respuesta);
                    var opcSelect = '';

                    for(var f = 0; f < opciones.length; f++){
                        opcSelect += '<option ';
                        opcSelect += ' value="'+opciones[f]['idsubcategoria']+'">';
                        opcSelect += opciones[f]['nombre'];
                        opcSelect += '</option>';
                    }

                    $("#idsubcategoria").html(opcSelect); 
                }
            }
        }); 
    }
}

function cargarOptionMarcas(){
    
    if($('#visualizar').val() == 1){
        $.ajax({
            type: "POST",
            url: "util/productos/optionMarcas.php",
            dataType: 'json',
            //data: { },
            beforeSend: function(){
                $("#idmarca").empty();
            },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = 'productos';
            },
            success: function(respuesta){
                var opciones = respuesta.resultado;
                if(opciones.length > 0){
                    // Crear los option
                    //console.log(respuesta);
                    var opcSelect = '';

                    for(var f = 0; f < opciones.length; f++){
                        opcSelect += '<option ';
                        opcSelect += ' value="'+opciones[f]['idmarca']+'">';
                        opcSelect += opciones[f]['nombre'];
                        opcSelect += '</option>';
                    }

                    $("#idmarca").html(opcSelect); 
                }
            }
        }); 
    }
}

function cargarOptionImpuestos(){
    
    if($('#visualizar').val() == 1){
        $.ajax({
            type: "POST",
            url: "util/productos/optionImpuestos.php",
            dataType: 'json',
            //data: { },
            beforeSend: function(){
                $("#idimpuesto").empty();
            },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = 'productos';
            },
            success: function(respuesta){
                var opciones = respuesta.resultado;
                if(opciones.length > 0){
                    // Crear los option
                    //console.log(respuesta);
                    var opcSelect = '';

                    for(var f = 0; f < opciones.length; f++){
                        opcSelect += '<option ';
                        opcSelect += ' value="'+opciones[f]['idimpuesto']+'" ';
                        opcSelect += ' data-valor="'+opciones[f]['valor']+'">';
                        opcSelect += opciones[f]['nombre'];
                        opcSelect += '</option>';
                    }

                    $("#idimpuesto").html(opcSelect); 
                }
            }
        }); 
    }
}

function cargarOptionProveedores(){
    
    if($('#visualizar').val() == 1){
        $.ajax({
            type: "POST",
            url: "util/productos/optionProveedores.php",
            dataType: 'json',
            //data: { },
            beforeSend: function(){
                $("#idproveedor").empty();
            },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = 'productos';
            },
            success: function(respuesta){
                var opciones = respuesta.resultado;
                if(opciones.length > 0){
                    // Crear los option
                    //console.log(respuesta);
                    var opcSelect = '';

                    for(var f = 0; f < opciones.length; f++){
                        opcSelect += '<option ';
                        opcSelect += ' value="'+opciones[f]['idproveedor']+'">';
                        opcSelect += opciones[f]['nombre'];
                        opcSelect += '</option>';
                    }

                    $("#idproveedor").html(opcSelect); 
                }
            }
        }); 
    }
}

function calculoImp(){
    
    var precio = $("#precio").val();
    var impuesto = $("#idimpuesto option:selected").data('valor');
    var precio_imp = precio;

    if(parseInt(impuesto) > 0){
        var sub_pre = (precio * impuesto) / 100;
        //console.log(sub_pre);
        precio_imp = parseFloat(precio) + parseFloat(sub_pre);
        //console.log(precio_imp);
    }

    $("#precio_impuesto").val(precio_imp);

    //console.log(valor);
}

function getCleanedString(cadena){
   // Definimos los caracteres que queremos eliminar
   var specialChars = "¡!@#$^%*()+=\\[]\"'{}|´:<>¿?,.";

   // Los eliminamos todos
   for (var i = 0; i < specialChars.length; i++) {
       cadena= cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
   }   

   // Lo queremos devolver limpio en minusculas
   cadena = cadena.toLowerCase();

   // Quitamos espacios y los sustituimos por _ porque nos gusta mas asi
   cadena = cadena.replace(/ /g,"-");

   // Quitamos acentos y "ñ". Fijate en que va sin comillas el primer parametro
    
   cadena = cadena.replace(/[áäàâ]/gi,"a");
   cadena = cadena.replace(/[éëèê]/gi,"e");
   cadena = cadena.replace(/[íïìî]/gi,"i");
   cadena = cadena.replace(/[óöòô]/gi,"o");
   cadena = cadena.replace(/[úüùû]/gi,"u");
    
   cadena = cadena.replace(/ñ/gi,"n");
   cadena = cadena.replace(/&/gi,"y");
    cadena = cadena.replace(/_/gi,"-");
    cadena = cadena.replace(/\//gi,"-");
    /*
   cadena = cadena.replace(/à/gi,"a");
   cadena = cadena.replace(/è/gi,"e");
   cadena = cadena.replace(/ì/gi,"i");
   cadena = cadena.replace(/ò/gi,"o");
   cadena = cadena.replace(/ù/gi,"u");
    
   cadena = cadena.replace(/â/gi,"a");
   cadena = cadena.replace(/ê/gi,"e");
   cadena = cadena.replace(/î/gi,"i");
   cadena = cadena.replace(/ô/gi,"o");
   cadena = cadena.replace(/û/gi,"u");
    */
   return cadena;
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    cargarOptionSubcategorias();
    cargarOptionMarcas();
    cargarOptionImpuestos();
    cargarOptionProveedores();
    mostrarDatos();
    
    $(window).on("resize", function () {
        redimensionarjqGrid();
    });
    
    // Select all tabs
    $('.nav-tabs a').on('click', function (event) {
        redimensionarjqGrid();
    });
    
    $('#limpiarFormProductoDetalle').click(function(){
        limpiarFormProductoDetalle();
    });
    
    $("#idimpuesto").on('change keyup', function () {
        calculoImp();
    });
    
    $("#precio").on('change keyup', function () {
        calculoImp();
    });
    
    $("#nombre, #nombre_seo").on('change keyup', function () {
        var cadena = $("#nombre").val();
        cadena = getCleanedString(cadena);
        $("#nombre_seo").val(cadena);
    });
    
    $("#jqGridProductos").on("click",'.gestion_update',function(event){ 
        var datos = $("#jqGridProductos").jqGrid('getRowData', this.dataset.idproducto);
        //console.log(datos);
        $('#idproducto').val(datos['idproducto']);
        $('#nombre').val(datos['nombre']);
        $('#nombre_seo').val(datos['nombre_seo']);
        $('#sku').val(datos['sku']);
        $('#costo').val(datos['costo']);
        $('#precio').val(datos['precio']);
        $('#precio_anterior').val((datos['precio_anterior'] == '' ? '0' : datos['precio_anterior']));
        $('#costo_anterior').val((datos['costo_anterior'] == '' ? '0' : datos['costo_anterior']));
        $("input:radio[name=estado][value='"+datos['estado']+"']").prop('checked',true);        
        $("input:radio[name=mayor_edad][value='"+datos['mayor_edad']+"']").prop('checked',true);        
        $('#idimpuesto option[value="'+datos['idimpuesto']+'"]').prop('selected', true);
        $('#idsubcategoria option[value="'+datos['idsubcategoria']+'"]').prop('selected', true);
        $('#idproveedor option[value="'+datos['idproveedor']+'"]').prop('selected', true);
        $('#idmarca option[value="'+datos['idmarca']+'"]').prop('selected', true);
        $('#descripcion_corta').val(datos['descripcion_corta']);
        $('#descripcion_larga').val(datos['descripcion_larga']);
        
        $('#imagen_producto').removeAttr( "src" );
        d = new Date();
        
        if(datos['tiene_imagen'] == 'SI'){
            $('#imagen_producto').attr('src',"../images/productos/"+datos['idproducto']+"/320x320/"+datos['nombre_seo']+".png?v="+d.getTime());
        }else{
            $('#imagen_producto').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
        }
        
        // Select tab by name
        $('.nav-tabs a[href="#menu1"]').tab('show');
        calculoImp();
        
    });
    
    $("#formProductos").submit(function(){
        mostrarDatos();
        limpiarFormProductoDetalle();
        return false;
    });
    
    $("#formProductoDetalle").submit(function(){
        var usuario = $("#session_usuario").val();
        var idproducto = $("#idproducto").val();
        var nombre = $("#nombre").val();
        var nombre_seo = $("#nombre_seo").val();
        var sku = $("#sku").val();
        var costo = $("#costo").val();
        var precio = $("#precio").val();
        var estado = $('input:radio[name=estado]:checked').val();
        var mayor_edad = $('input:radio[name=mayor_edad]:checked').val();
        var idimpuesto = $("#idimpuesto option:selected").val();
        var idsubcategoria = $("#idsubcategoria option:selected").val();
        var idproveedor = $("#idproveedor option:selected").val();
        var idmarca = $("#idmarca option:selected").val();
        var descripcion_corta = $("#descripcion_corta").val();
        var descripcion_larga = $("#descripcion_larga").val();
        
        if(
            parseFloat(costo) > parseFloat("0.001") && 
            parseFloat(precio) > parseFloat("0.001")
        ){
            if(
                parseFloat(costo) < parseFloat(precio)
            ){
                if(parseInt(idproducto) == 0){
                    // Insercion
                    if($('#insertar').val() == 1){
                        $.ajax({
                            type: "POST",
                            url: "util/productos/insert.php",
                            dataType: 'json',
                            data: {
                                usuario:usuario,
                                nombre:nombre,
                                nombre_seo:nombre_seo,
                                sku:sku,
                                costo:costo,
                                precio:precio,
                                estado:estado,
                                mayor_edad:mayor_edad,
                                idimpuesto:idimpuesto,
                                idsubcategoria:idsubcategoria,
                                idproveedor:idproveedor,
                                idmarca:idmarca,
                                descripcion_corta:descripcion_corta,
                                descripcion_larga:descripcion_larga
                            },
                            beforeSend: function(){
                                $("#submitFormProductoDetalle").html('Insertando');
                                $('#submitFormProductoDetalle').prop("disabled", true);
                            },
                            error: function (request, status, error) { 
                                console.log(request.responseText);
                                document.location = 'productos';
                            },
                            success: function(respuesta){
                                switch (respuesta.estado){
                                    case 1:
                                        $('#myModalSuccessBody').html(respuesta.mensaje);
                                        $('#myModalSuccess').modal("show");
                                        
                                        limpiarFormProductoDetalle();
                                        $('.nav-tabs a[href="#home"]').tab('show');
                                        mostrarDatos();
                                        break;
                                    case 2:
                                        $('#myModalWarningBody').html(respuesta.mensaje);
                                        $('#myModalWarning').modal("show");
                                        
                                        break;                    
                                    default:
                                        alert('Se ha producido un error');
                                        document.location = 'productos';
                                        break;
                                } 
                            },
                            complete: function(){
                                $('#submitFormProductoDetalle').prop("disabled", false);
                                $("#submitFormProductoDetalle").html('Guardar');
                            }
                        }); 
                    }else{
                        $('#myModalWarningBody').html('Usted no tiene privilegios para insertar');
                        $('#myModalWarning').modal("show");  
                    }
                }else{
                    // Actualizacion
                    if($('#actualizar').val() == 1){
                        $.ajax({
                            type: "POST",
                            url: "util/productos/update.php",
                            dataType: 'json',
                            data: {
                                usuario:usuario,
                                idproducto:idproducto,
                                nombre:nombre,
                                nombre_seo:nombre_seo,
                                sku:sku,
                                costo:costo,
                                precio:precio,
                                estado:estado,
                                mayor_edad:mayor_edad,
                                idimpuesto:idimpuesto,
                                idsubcategoria:idsubcategoria,
                                idproveedor:idproveedor,
                                idmarca:idmarca,
                                descripcion_corta:descripcion_corta,
                                descripcion_larga:descripcion_larga
                            },
                            beforeSend: function(){
                                $("#submitFormProductoDetalle").html('Actualizando');
                                $('#submitFormProductoDetalle').prop("disabled", true);
                            },
                            error: function (request, status, error) { 
                                console.log(request.responseText);
                                document.location = 'productos';
                            },
                            success: function(respuesta){
                                switch (respuesta.estado){
                                    case 1:
                                        $('#myModalSuccessBody').html(respuesta.mensaje);
                                        $('#myModalSuccess').modal("show");
                                        
                                        limpiarFormProductoDetalle();
                                        $('.nav-tabs a[href="#home"]').tab('show');
                                        mostrarDatos();
                                        break;
                                    case 2:
                                        $('#myModalWarningBody').html(respuesta.mensaje);
                                        $('#myModalWarning').modal("show");
                                        
                                        break;                    
                                    default:
                                        alert('Se ha producido un error');
                                        document.location = 'productos';
                                        break;
                                } 
                            },
                            complete: function(){
                                $('#submitFormProductoDetalle').prop("disabled", false);
                                $("#submitFormProductoDetalle").html('Guardar');
                            }
                        }); 
                    }else{
                        $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
                        $('#myModalWarning').modal("show");  
                    }
                }
            }else{
                $('#myModalWarningBody').html('Recuerde que el costo debe ser menor al precio');
                $('#myModalWarning').modal("show");  
            }
        }else{
            $('#myModalWarningBody').html('Recuerde que el costo y el precio deben ser mayores a cero');
            $('#myModalWarning').modal("show");   
        }
        
        return false;
    });
    
});