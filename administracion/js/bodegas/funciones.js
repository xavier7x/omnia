function mostrarDatos(){
    
    if($('#visualizar').val() == 1){
        var mydata;

        $('#jqGridBodegas').jqGrid('clearGridData');

        $.ajax({
            async: false,
            type: "POST",
            url: "util/bodegas/query.php",
            //data: {}, 
            dataType: "json",
            //beforeSend: function(){},
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = 'bodegas';
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
                { name: 'idbodega', hidden: true, key: true },
                { label: 'Gestión', name: 'btn_gestion', width: 100, align: 'left', sortable: false },
                { label: 'Nombre', name: 'nombre', width: 200},
                { label: 'Descripción', name: 'descripcion', width: 200},
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
            pager: "#jqGridBodegasPager"
        });
    }else{        
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");        
    }
}

function redimensionarjqGrid(){    
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridBodegas').setGridWidth((width - 4));
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
    
    $("#formBodegas").submit(function(){
        mostrarDatos();
        return false;
    });
    
    $("#formBodegaDetalle").submit(function(){
        
        return false;
    });
    
    mostrarDatos();
    
});