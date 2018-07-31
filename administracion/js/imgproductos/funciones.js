function mostrarDatos(){
    
    if($('#visualizar').val() == 1){
        var mydata;

        $('#jqGridProductos').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/imgproductos/query.php",
            //data: {}, 
            dataType: "json",
            //beforeSend: function(){},
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = 'imgproductos';
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
                { label: 'Tiene imagen', name: 'tiene_imagen', width: 150, align: 'center', search:false},
                { label: 'Estado', name: 'estado', width: 150, align: 'center', search:false},           
                { label: 'Usuario actualización', name: 'user_update', width: 200, align: 'center', search:false},
                { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center', search:false},
                { label: 'Usuario creación', name: 'user_create', width: 200, align: 'center', search:false},
                { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center', search:false},
                { name: 'nombre_seo', hidden: true },
                { name: 'descripcion_larga', hidden: true },
                { name: 'sku', hidden: true },
                { name: 'idcategoria', hidden: true },
                { name: 'idmarca', hidden: true },
                { name: 'idimpuesto', hidden: true },
                { name: 'precio', hidden: true },
                { name: 'costo', hidden: true },
                { name: 'mayor_edad', hidden: true }
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
    $("#imagen_item").val('');
    ancho = 0;
    alto = 0;
    d = new Date();
    $('#imagen_producto').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
}

function redimensionarjqGrid(){
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridProductos').setGridWidth((width - 4));
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    var _URL = window.URL || window.webkitURL;
    var alto = 0;
    var ancho = 0;
    
    $("#imagen_item").change(function (e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                //alert(this.width + " " + this.height);
                ancho = this.width;
                alto = this.height;
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    
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
    
    $("#jqGridProductos").on("click",'.gestion_update',function(event){ 
        var datos = $("#jqGridProductos").jqGrid('getRowData', this.dataset.idproducto);
        //console.log(datos);
        $('#idproducto').val(datos['idproducto']);
        $('#nombre').val(datos['nombre']);
        $('#nombre_seo').val(datos['nombre_seo']);
        $("input:radio[name=estado][value='"+datos['estado']+"']").prop('checked',true);
        
        $('#imagen_producto').removeAttr( "src" );
        d = new Date();
        
        if(datos['tiene_imagen'] == 'SI'){
            $('#imagen_producto').attr('src',"../images/productos/"+datos['idproducto']+"/320x320/"+datos['nombre_seo']+".png?v="+d.getTime());
        }else{
            $('#imagen_producto').attr('src',"../images/productos/0/320x320/error.png?v="+d.getTime());
        }
        
        // Select tab by name
        $('.nav-tabs a[href="#menu1"]').tab('show');
        
    });
    
    $("#formProductos").submit(function(){
        mostrarDatos();
        limpiarFormProductoDetalle();
        return false;
    });
    
    $("#formProductoDetalle").submit(function(){
        var usuario = $("#session_usuario").val();
        var idproducto = $("#idproducto").val();
        var nombre_seo = $("#nombre_seo").val();
        var imagen_item = $("#imagen_item")[0].files[0];
        var imagen_itemVal = $("#imagen_item").val().toLowerCase();
        var imagen_itemSize = imagen_item.size;
        var mensaje = "";
        var validacion = false;        
        
        var param_imgproductoext = $("#param_imgproductoext").val();
        var param_imgproductopeso = $("#param_imgproductopeso").val();
        var param_imgproductoalto = $("#param_imgproductoalto").val();
        var param_imgproductoancho = $("#param_imgproductoancho").val();
        
        //console.log(imagen_item);
        //console.log(alto + ' - ' + ancho);
        //console.log(imagen_itemSize);
        //console.log( parseInt(param_imgproductopeso) * 1024 );
        
        if(
            imagen_itemVal.lastIndexOf('png') == -1 || 
            imagen_itemSize >= ( parseInt(param_imgproductopeso) * 1024 ) // EN KB
        ){ 
            mensaje = 'Solo puede cargar imagen a los productos de formato ('+param_imgproductoext+') y de tamaño ('+param_imgproductopeso+' KB) como máximo';
            $("#imagen_item").val('');
            alto = 0;
            ancho = 0;
        }else{
            if(
                alto != parseInt(param_imgproductoalto) || 
                ancho != parseInt(param_imgproductoancho)
              ){
                mensaje = 'Solo puede cargar imagen a los productos de alto ('+param_imgproductoalto+' PX) y de ancho ('+param_imgproductoancho+' PX)';
                $("#imagen_item").val('');
                alto = 0;
            ancho = 0;
            }else{
                validacion = true;
            }
        }
        
        if(validacion == true){
            if(parseInt(idproducto) != 0){
                // Actualizacion
                if($('#actualizar').val() == 1){
                    
                    var formData = new FormData();
                    formData.append('usuario',usuario);
                    formData.append('idproducto',idproducto);
                    formData.append('nombre_seo',nombre_seo);
                    formData.append('imagen_item',imagen_item);

                    $.ajax({
                        type: "POST",
                        url: "util/imgproductos/update.php",
                        data: formData, 
                        dataType: "json",
                        cache: false,
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        beforeSend: function(){
                            $("#submitFormProductoDetalle").html('Actualizando');
                            $('#submitFormProductoDetalle').prop("disabled", true);
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            document.location = 'imgproductos';
                        },
                        success: function(respuesta){
                            switch (respuesta.estado){
                                case 1:
                                    limpiarFormProductoDetalle();                                                      
                                    $('.nav-tabs a[href="#home"]').tab('show');
                                    $('#myModalSuccessBody').html(respuesta.mensaje);
                                    $('#myModalSuccess').modal("show");
                                    mostrarDatos();
                                    break;
                                case 2:
                                    $('#myModalWarningBody').html(respuesta.mensaje);
                                    $('#myModalWarning').modal("show"); 
                                    break;                    
                                default:
                                    alert('Se ha producido un error');
                                    document.location = 'imgproductos';
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

            }else{
                $('#myModalWarningBody').html('Debe seleccionar un producto para cargar su imagen');
                $('#myModalWarning').modal("show"); 
            }
        }else{
            $('#myModalWarningBody').html(mensaje);
            $('#myModalWarning').modal("show");
        }
        
        return false;
    });
});