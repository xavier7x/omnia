function mostrarDatos() {
    if ($('#visualizar').val() == 1) {
        var mydata;

        $('#jqGridParametros').jqGrid('clearGridData');
        $.ajax({
            async: false,
            type: "POST",
            url: "util/parametros/query.php",
            //data: {}, 
            dataType: "json",
            //beforeSend: function(){},
            error: function(request, status, error) {
                console.log(request.responseText);
                //document.location = 'Parametros';
            },
            success: function(respuesta) {
                mydata = respuesta.rows;
            },
            //complete: function(){}
        });

        $('#jqGridParametros').jqGrid('setGridParam', { data: mydata });
        $('#jqGridParametros').trigger('reloadGrid');

        $("#jqGridParametros").jqGrid({
            datatype: 'local',
            data: mydata,
            styleUI: 'Bootstrap',
            colModel: [
                { label: 'Gestión', name: 'btn_gestion', width: 100, align: 'left', sortable: false, search: false },
                { label: 'IdParametro', name: 'idparametro', key: true, width: 150 },
                { label: 'Nombre', name: 'nombre', width: 200 },
                { label: 'Descripción', name: 'descripcion', width: 200 },
                { label: 'Valor', name: 'valor', align: 'center', width: 100, search: false },
                { label: 'Fecha Creado', name: 'sys_create', align: 'center', width: 150, search: false },
                { label: 'Fecha Actualizado', name: 'sys_update', align: 'center', width: 150, search: false },
                { label: 'Creado por', name: 'user_create', align: 'center', width: 150, search: false },
                { label: 'Actualizado por', name: 'user_update', align: 'center', width: 200, search: false }
            ],
            height: 'auto',
            autowidth: true, // El ancho de la tabla es el de la pagina
            shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
            viewrecords: true,
            //autoencode: false,
            rowNum: $('#param_paginacion').val(),
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            pager: "#jqGridParametrosPager"
        });


        $('#jqGridParametros').jqGrid('filterToolbar', {
            stringResult: true,
            // No es necesario dar enter para que funque la busqueda
            searchOnEnter: false,
            searchOperators: false
        });

        // Limipar la barra de busqueda

        $('#jqGridParametros')[0].clearToolbar();

    } else {
        $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
        $('#myModalWarning').modal("show");
    }
}

function limpiarFormParametrosDetalle() {
    $('#formParametroDetalle').trigger("reset");
    $('#idparametro').val('');
    $('#idparametro').removeAttr("disabled");
    $('#nombre').val('');
    $('#descripcion').val('');
    $('#valor').val('');
    $('#accion').text('insertar');
}

function redimensionarjqGrid() {
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridParametros').setGridWidth((width - 4));
}

function getCleanedString(cadena) {
    // Definimos los caracteres que queremos eliminar
    var specialChars = "¡!@#$^%*()+=\\[]\"'{}|´:<>¿?,.";
    // Los eliminamos todos
    for (var i = 0; i < specialChars.length; i++) {
        cadena = cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
    }
    // Lo queremos devolver limpio en minusculas
    cadena = cadena.toLowerCase();
    // Quitamos espacios y los sustituimos por _ porque nos gusta mas asi
    cadena = cadena.replace(/ /g, "-");
    // Quitamos acentos y "ñ". Fijate en que va sin comillas el primer parametro
    cadena = cadena.replace(/[áäàâ]/gi, "a");
    cadena = cadena.replace(/[éëèê]/gi, "e");
    cadena = cadena.replace(/[íïìî]/gi, "i");
    cadena = cadena.replace(/[óöòô]/gi, "o");
    cadena = cadena.replace(/[úüùû]/gi, "u");

    cadena = cadena.replace(/ñ/gi, "n");
    cadena = cadena.replace(/&/gi, "y");
    cadena = cadena.replace(/_/gi, "-");
    cadena = cadena.replace(/\//gi, "-");
    return cadena;
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {

    mostrarDatos();

    $(window).on("resize", function() {
        redimensionarjqGrid();
    });

    // Select all tabs
    $('.nav-tabs a').on('click', function(event) {
        redimensionarjqGrid();
    });

    $('#limpiarFormParametrosDetalle').click(function() {
        limpiarFormParametrosDetalle();
    });



    $("#jqGridParametros").on("click", '.gestion_update', function(event) {
        var usuario = $("#session_usuario").val();
        var datos = $("#jqGridParametros").jqGrid('getRowData', this.dataset.idparametro);
        $('#idparametro').val(datos['idparametro']);
        $('#idparametro').attr("disabled", "disabled");
        $('#nombre').val(datos['nombre']);
        $('#descripcion').val(datos['descripcion']);
        $('#valor').val(datos['valor']);
        $('#accion').text('actualizar');
        // Select tab by name
        $('.nav-tabs a[href="#menu1"]').tab('show');

    });

    /*$("#formParametros").submit(function(){
        mostrarDatos();
        limpiarFormParametrosDetalle();
        return false;
    });*/

    $("#formParametroDetalle").submit(function() {
        var usuario = $("#session_usuario").val();
        var idparametro = $("#idparametro").val();
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var valor = $("#valor").val();
        var accion = $('#accion').text();
        if (accion == 'actualizar') {
            //actualizar
            if ($('#actualizar').val() == 1) {
                $.ajax({
                    type: "POST",
                    url: "util/parametros/update.php",
                    dataType: 'json',
                    data: {
                        usuario: usuario,
                        idparametro: idparametro,
                        nombre: nombre,
                        descripcion: descripcion,
                        valor: valor
                    },
                    beforeSend: function() {
                        $("#submitFormParametrosDetalle").html('Actualizando');
                        $('#submitFormParametrosDetalle').prop("disabled", true);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        document.location = 'parametros';
                    },
                    success: function(respuesta) {
                        switch (respuesta.estado) {
                            case 1:
                                $('#myModalSuccessBody').html(respuesta.mensaje);
                                $('#myModalSuccess').modal("show");

                                limpiarFormParametrosDetalle();
                                $('.nav-tabs a[href="#home"]').tab('show');
                                mostrarDatos();
                                break;
                            case 2:
                                $('#myModalWarningBody').html(respuesta.mensaje);
                                $('#myModalWarning').modal("show");

                                break;
                            default:
                                alert('Se ha producido un error');
                                document.location = 'parametros';
                                break;
                        }
                    },
                    complete: function() {
                        $('#submitFormParametrosDetalle').prop("disabled", false);
                        $("#submitFormParametrosDetalle").html('Guardar');
                    }
                });
            } else {
                $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
                $('#myModalWarning').modal("show");
            }
            //fin actualizar

        } else {
            // Insercion
            if ($('#insertar').val() == 1) {
                $.ajax({
                    type: "POST",
                    url: "util/parametros/insert.php",
                    dataType: 'json',
                    data: {
                        usuario: usuario,
                        idparametro: idparametro,
                        nombre: nombre,
                        descripcion: descripcion,
                        valor: valor
                    },
                    beforeSend: function() {
                        $("#submitFormParametrosDetalle").html('Insertando');
                        $('#submitFormParametrosDetalle').prop("disabled", true);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        document.location = 'parametros';
                    },
                    success: function(respuesta) {
                        switch (respuesta.estado) {
                            case 1:
                                $('#myModalSuccessBody').html(respuesta.mensaje);
                                $('#myModalSuccess').modal("show");

                                limpiarFormParametrosDetalle();
                                $('.nav-tabs a[href="#home"]').tab('show');
                                mostrarDatos();
                                break;
                            case 2:
                                $('#myModalWarningBody').html(respuesta.mensaje);
                                $('#myModalWarning').modal("show");

                                break;
                            default:
                                alert('Se ha producido un error');
                                document.location = 'parametros';
                                break;
                        }
                    },
                    complete: function() {
                        $('#submitFormParametrosDetalle').prop("disabled", false);
                        $("#submitFormParametrosDetalle").html('Guardar');
                    }
                });
            } else {
                $('#myModalWarningBody').html('Usted no tiene privilegios para insertar');
                $('#myModalWarning').modal("show");
            }
        }

        return false;
    });

});