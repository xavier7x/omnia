/*function tutorialCompra() {
    introJs().setOptions({
        steps: [{
                intro: "Hoy vamos a ver como realizar tu primera compra!"
            },
            {
                element: '.table-cart',
                intro: "Este es tu listado de productos."
            },
            {
                element: '#idenvio_select',
                intro: "La primera vez que realizas tu compra tendras que ingresar una o dos direcciones para la entrega del producto",
                position: 'right'
            },
            {
                element: '#add_envio',
                intro: 'Haz click aqui para ingresar tu dirección.',
                position: 'bottom'
            },
            {
                element: '#datosEnvioStep',
                intro: "Aqui ingresa todos los datos que te solicita la pantalla. No te rindas estamos cerca.",
                position: 'left'
            },
            {
                element: '#datosFacturacionStep',
                intro: 'Aqui llena tus datos de facturacion.',
                position: 'right'
            },
            {
                element: '#submitFormFacturacion',
                intro: 'No olvides guardar todos tus Datos.',
                position: 'right'
            },
            {
                element: '#idsector_envio',
                intro: 'Si no deseas cancelar el valor del envio elige la opcion sitio acordado un asesor se contactara contigo para elegir el lugar.',
            }

        ]
    }).onchange(function() {
        if (this._currentStep === 4) {
            $('#add_envio').trigger('click');
            return false;
        }
        if (this._currentStep === 5) {
            $('#datosFacturacionStepClick').trigger('click');
            return false;
        }
        if (this._currentStep === 7) {
            $('#datosEnvioStepClick').trigger('click');
            return false;
        }
        console.log(this, arguments, 'onchange');
    }).onexit(function() {
        console.log(this, arguments, 'onexit');
    }).oncomplete(function() {
        console.log(this, arguments, 'oncomplete');
    }).start();
}*/


function tutorialCompra() {
	//if($('a.introjs-donebutton:visible').length==0) {
		    var intro = introJs();
			intro.setOptions({
				showStepNumbers: false,
				tooltipClass: 'introjs-tooltip-fb',
				exitOnOverlayClick: false,
				showProgress: true,
				steps: [
                    {
                        intro: "Hoy vamos a ver como realizar tu primera compra!"
                    },
                    {
                        element: '.table-cart',
                        intro: "Este es tu listado de productos."
                    },
                    {
                        element: '#idenvio_select',
                        intro: "La primera vez que realizas tu compra tendras que ingresar una o dos direcciones para la entrega del producto",
                        position: 'right'
                    },
                    {
                        element: '#add_envio',
                        intro: 'Haz click aqui para ingresar tu dirección.',
                        position: 'bottom'
                    },
                    {
                        element: '#datosEnvioStep',
                        intro: "Aqui ingresa todos los datos que te solicita la pantalla. No te rindas estamos cerca.",
                        position: 'left'
                    },
                    {
                        element: '#datosFacturacionStep',
                        intro: 'Aqui llena tus datos de facturacion.',
                        position: 'right'
                    },
                    {
                        element: '#submitFormFacturacion',
                        intro: 'No olvides guardar todos tus Datos.',
                        position: 'right'
                    },
                    {
                        element: '#idsector_envio',
                        intro: 'Si no deseas cancelar el valor del envio elige la opcion sitio acordado un asesor se contactara contigo para elegir el lugar.',
                        position: 'right'
                    }
						]
			});
			intro.onafterchange(function(targetElement) {
                if (this._currentStep === 4) {
                    $('#add_envio').trigger('click');
                    return false;
                }
                if (this._currentStep === 5) {
                    $('#datosFacturacionStepClick').trigger('click');
                    return false;
                }
                if (this._currentStep === 7) {
                    $('#datosEnvioStepClick').trigger('click');
                    return false;
                }
				/*if(this._currentStep == 1){
					//$('.introjs-tooltip-fb').css('top', '-80px').delay( 800 );
					overlay = document.getElementsByClassName("introjs-tooltip-fb");
					for(i=0; i<overlay.length; i++) {
						setTimeout(function(){overlay[0].style.top = '-80px';}, 350);
						//overlay[i].style.right = '10px';
					}
				}*/
			});
			intro.onbeforechange(function(targetElement) {
				/*if(this._currentStep == 1){
					var li_social = $("#li-Social").css("display");
					if(li_social == "none"){
						intro.goToStep(0);
					}
				}*/				
			});
			intro.start();
		//actionButtonCampIntroJs_Done();
		/*$("#social_fb").click(function(event){
			intro.exit();
		});*/
    //}
}

function mostrarCarrito() {
    var usuario = $("#session_usuario").val();
    var idsector = $("#session_idsector").val();

    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/carrito/carrito.php',
        dataType: 'json',
        data: {
            usuario: usuario,
            idsector: idsector
        },
        beforeSend: function() {
            $("#total_carrito").val('0.00');
            $("#total_costo_envio").val('0.00');
            $("#cuerpo_carrito").html('<tr><td colspan="50" class="text-center"><i class="fa fa-spinner fa-spin" style="font-size:60px;color:#FAB005;margin-top:50px;margin-bottom:50px;"></i></td></tr>');
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            switch (respuesta.estado) {
                case 1:
                    $("#total_carrito").val(respuesta.total);
                    var filas = respuesta.rows;
                    var cuerpo = '';

                    if (filas.length > 0) {
                        for (var f = 0; f < filas.length; f++) {
                            var claseFila = '';

                            if (
                                filas[f]['estado'] != 'ACTIVO' ||
                                parseInt(filas[f]['stock']) == 0 ||
                                parseInt(filas[f]['cantidad']) > parseInt(filas[f]['stock'])
                            ) {
                                claseFila = 'danger';
                            }

                            //*****************************************

                            cuerpo += '<tr class="cross">';
                            cuerpo += '<td class="ring-in t-data">';
                            cuerpo += '<a href="' + $("#param_hostapp").val() + '/productos/' + filas[f]['nombre_seo'] + '" class="at-in">';
                            cuerpo += '<img src="' + filas[f]['img_pro'] + '" height="100" width="100" class="img-responsive" alt="' + filas[f]['nombre'] + '">';
                            cuerpo += '</a>';
                            cuerpo += '<div class="sed">';
                            cuerpo += '<h5>' + filas[f]['nombre'] + '</h5>';
                            cuerpo += '</div>';
                            cuerpo += '<div class="clearfix"></div>';
                            cuerpo += '<div class="close-producto" data-idproducto="' + filas[f]['idproducto'] + '"><i class="fa fa-times" aria-hidden="true"></i></div>';
                            cuerpo += '</td>';
                            cuerpo += '<td class="t-data">$' + filas[f]['precio'] + '</td>';
                            cuerpo += '<td class="t-data"><div class="quantity"> ';
                            cuerpo += '<div class="quantity-select">  ';
                            cuerpo += '<div class="entry value-minus" data-idproducto="' + filas[f]['idproducto'] + '">&nbsp;</div>';
                            cuerpo += '<div class="entry value"><span class="span-1">' + filas[f]['cantidad'] + '</span></div>';
                            cuerpo += '<div class="entry value-plus active" data-idproducto="' + filas[f]['idproducto'] + '">&nbsp;</div>';
                            cuerpo += '</div>';
                            cuerpo += '</div>';
                            cuerpo += '</td>';
                            cuerpo += '</tr>';
                        }
                    } else {
                        cuerpo += '<tr><td colspan="50" class="text-center">No tiene productos cargados</td></tr>';
                    }

                    $("#cuerpo_carrito").html(cuerpo);
                    calcularTotal();

                    break;
                case 2:
                    alert(respuesta.mensaje);

                    $("#cuerpo_carrito").html('<tr><td colspan="50" class="text-center">No tiene productos cargados</td></tr>');
                    break;
                default:
                    alert('Se ha producido un error');
                    document.location = $("#param_hostapp").val() + '/carrito';
                    break;
            }
        }
    });
}

function anadirProductos(idproducto, cantidad) {
    // Enviar a guardar al carrido de compras
    var usuario = $("#session_usuario").val();
    var idsector = $("#session_idsector").val();

    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsproductos/addproduct.php',
        data: {
            usuario: usuario,
            idproducto: idproducto,
            cantidad: cantidad,
            idsector: idsector,
            proceso: 'no-sumar'
        },
        dataType: "json",
        beforeSend: function() {},
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            switch (respuesta.estado) {
                case 1:
                    $("#mkt-total-productos").html(respuesta.total);
                    $("#total_carrito").val(respuesta.total_carrito);
                    calcularTotal();
                    break;
                case 2:
                    alert(respuesta.mensaje);
                    break;
                default:
                    alert('Se ha producido un error');
                    document.location = $("#param_hostapp").val() + '/carrito';
                    break;
            }
        },
        //complete: function(){ }
    });
}

function cargarOptionMetodosPago() {
    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/carrito/metodosPago.php',
        dataType: 'json',
        //data: { },
        beforeSend: function() {
            $("#idmetodopago").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' data-minimo_compra="' + opciones[f]['minimo_compra'] + '" ';
                    opcSelect += ' data-maximo_compra="' + opciones[f]['maximo_compra'] + '" ';
                    opcSelect += ' value="' + opciones[f]['idmetodopago'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idmetodopago").html(opcSelect);
            }
        }
    });
}

function cargarOptionBodegaAtencion() {
    var idsector = $("#session_idsector").val();

    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/carrito/bodegaAtencion.php',
        dataType: 'json',
        data: {
            idsector: idsector
        },
        beforeSend: function() {
            $("#idatencion").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idatencion'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idatencion").html(opcSelect);
            }
        }
    });
}

function cargarOptionDatosFacturacion() {
    var usuario = $("#session_usuario").val();

    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/carrito/datosFacturacion.php',
        dataType: 'json',
        data: {
            usuario: usuario
        },
        beforeSend: function() {
            $("#idfacturacion_select").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idfacturacion'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idfacturacion_select").html(opcSelect);
            }
        }
    });
}

function cargarOptionDatosEnvio() {
    var usuario = $("#session_usuario").val();

    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/carrito/datosEnvio.php',
        dataType: 'json',
        data: {
            usuario: usuario
        },
        beforeSend: function() {
            $("#idenvio_select").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idenvio'] + '" ';
                    opcSelect += ' data-costo_envio="' + opciones[f]['costo_envio'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idenvio_select").html(opcSelect);

                calcularTotal();
            }
        }
    });
}

function mostrarDatosEnvio() {
    var usuario = $("#session_usuario").val();
    var mydata;

    $('#jqGridEnvio').jqGrid('clearGridData');

    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsclientes/datosenvio.php',
        data: {
            usuario: usuario
        },
        dataType: "json",
        //beforeSend: function(){},
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            mydata = respuesta.rows;
        },
        //complete: function(){}
    });

    $('#jqGridEnvio').jqGrid('setGridParam', { data: mydata });
    $('#jqGridEnvio').trigger('reloadGrid');

    $("#jqGridEnvio").jqGrid({
        datatype: 'local',
        data: mydata,
        styleUI: 'Bootstrap',
        colModel: [
            { name: 'idenvio', hidden: true, key: true },
            { label: 'Gestión', name: 'btn_gestion', width: 150, align: 'left', sortable: false },
            { label: 'Alias', name: 'titulo', width: 200 },
            { label: 'Nombre', name: 'nombre', width: 200 },
            { label: 'Dirección', name: 'direccion', width: 200 },
            { label: 'Estado', name: 'estado', width: 150, align: 'center' },
            { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center' },
            { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center' },
            { name: 'movil1', hidden: true },
            { name: 'movil2', hidden: true },
            { name: 'idprovincia', hidden: true },
            { name: 'idcanton', hidden: true },
            { name: 'idzona', hidden: true },
            { name: 'idsector', hidden: true }
        ],
        height: 'auto',
        autowidth: true, // El ancho de la tabla es el de la pagina
        shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
        viewrecords: true,
        //autoencode: false,
        rowNum: $('#param_paginacion').val(),
        rownumbers: true, // show row numbers
        rownumWidth: 35, // the width of the row numbers columns
        pager: "#jqGridEnvioPager"
    });

}

function limpiarFormEnvio() {
    $('#formEnvio').trigger("reset");
    $("#idenvio").val(0);
    cargarOptionProvincias(0, 0, 0, 0);
}

function redimensionarjqGrid() {
    var width = $('#contiene_jqGrid').width();
    //console.log(width);
    $('#jqGridEnvio').setGridWidth((width - 4));
    $('#jqGridFacturacion').setGridWidth((width - 4));
}

function cargarOptionProvincias(idprovincia, idcanton, idzona, idsector) {
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/provincias.php',
        dataType: 'json',
        //data: { },
        beforeSend: function() {
            $("#idprovincia_envio").empty();
            $("#idcanton_envio").empty();
            $("#idzona_envio").empty();
            $("#idsector_envio").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idprovincia'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idprovincia_envio").html(opcSelect);

                if (parseInt(idprovincia) != 0) {
                    $('#idprovincia_envio option[value="' + idprovincia + '"]').prop('selected', true);
                }

                // Cargar los cantones
                cargarOptionCantones($("#idprovincia_envio option:selected").val(), idcanton, idzona, idsector);
            }
        }
    });
}

function cargarOptionCantones(idprovincia, idcanton, idzona, idsector) {
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/cantones.php',
        dataType: 'json',
        data: {
            idprovincia: idprovincia
        },
        beforeSend: function() {
            $("#idcanton_envio").empty();
            $("#idzona_envio").empty();
            $("#idsector_envio").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idcanton'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idcanton_envio").html(opcSelect);

                if (parseInt(idcanton) != 0) {
                    $('#idcanton_envio option[value="' + idcanton + '"]').prop('selected', true);
                }

                // Cargar las zonas
                cargarOptionZonas($("#idcanton_envio option:selected").val(), idzona, idsector);

            }
        }
    });
}

function cargarOptionZonas(idcanton, idzona, idsector) {
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/zonas.php',
        dataType: 'json',
        data: {
            idcanton: idcanton
        },
        beforeSend: function() {
            $("#idzona_envio").empty();
            $("#idsector_envio").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idzona'] + '">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }

                $("#idzona_envio").html(opcSelect);

                if (parseInt(idzona) != 0) {
                    $('#idzona_envio option[value="' + idzona + '"]').prop('selected', true);
                }

                // Cargar los sectores
                cargarOptionSectores($("#idzona_envio option:selected").val(), idsector);
            }
        }
    });
}

function cargarOptionSectores(idzona, idsector) {
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/sectores.php',
        dataType: 'json',
        data: {
            idzona: idzona
        },
        beforeSend: function() {
            $("#idsector_envio").empty();
        },
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var opciones = respuesta.resultado;
            if (opciones.length > 0) {
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';

                for (var f = 0; f < opciones.length; f++) {
                    opcSelect += '<option ';
                    opcSelect += ' value="' + opciones[f]['idsector'] + '">';
                    opcSelect += opciones[f]['nombre'] + ' - COSTO ENVÍO ( $' + opciones[f]['costo_envio'] + ' )';
                    opcSelect += '</option>';
                }

                $("#idsector_envio").html(opcSelect);

                if (parseInt(idsector) != 0) {
                    $('#idsector_envio option[value="' + idsector + '"]').prop('selected', true);
                }
                //console.log(idsector);
            }
        }
    });
}

function mostrarDatosFacturacion() {
    var usuario = $("#session_usuario").val();
    var mydata;

    $('#jqGridFacturacion').jqGrid('clearGridData');

    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsclientes/datosfacturacion.php',
        data: {
            usuario: usuario
        },
        dataType: "json",
        //beforeSend: function(){},
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            mydata = respuesta.rows;
        },
        //complete: function(){}
    });

    $('#jqGridFacturacion').jqGrid('setGridParam', { data: mydata });
    $('#jqGridFacturacion').trigger('reloadGrid');

    $("#jqGridFacturacion").jqGrid({
        datatype: 'local',
        data: mydata,
        styleUI: 'Bootstrap',
        colModel: [
            { name: 'idfacturacion', hidden: true, key: true },
            { label: 'Gestión', name: 'btn_gestion', width: 150, align: 'left', sortable: false },
            { label: 'Alias', name: 'titulo', width: 200 },
            { label: 'Nombre', name: 'nombre', width: 200 },
            { label: 'CI / RUC', name: 'num_doc', width: 200 },
            { label: 'Dirección', name: 'direccion', width: 200 },
            { label: 'Estado', name: 'estado', width: 150, align: 'center' },
            { label: 'Fecha actualización', name: 'sys_update', width: 200, align: 'center' },
            { label: 'Fecha creación', name: 'sys_create', width: 200, align: 'center' },
            { name: 'movil1', hidden: true },
            { name: 'movil2', hidden: true },
            { name: 'mail', hidden: true }
        ],
        height: 'auto',
        autowidth: true, // El ancho de la tabla es el de la pagina
        shrinkToFit: false, // El ancho de la columna es el que tiene parametrizado
        viewrecords: true,
        //autoencode: false,
        rowNum: $('#param_paginacion').val(),
        rownumbers: true, // show row numbers
        rownumWidth: 35, // the width of the row numbers columns
        pager: "#jqGridFacturacionPager"
    });

}

function limpiarFormFacturacion() {
    $('#formFacturacion').trigger("reset");
    $("#idfacturacion").val(0);
}

function mostrarPrevFacturacion() {
    var usuario = $("#session_usuario").val();

    $.ajax({
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsclientes/prevfacturacion.php',
        data: {
            usuario: usuario
        },
        dataType: "json",
        //beforeSend: function(){},
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var filas = respuesta.rows;

            if (filas.length > 0) {
                $("#titulo_envio").val(filas[0]['titulo']);
                $("#nombre_envio").val(filas[0]['nombre']);
                $("#movil1_envio").val(filas[0]['movil1']);
                $("#movil2_envio").val(filas[0]['movil2']);
                $("#direccion_envio").val(filas[0]['direccion']);
            }
        },
        //complete: function(){}
    });
}

function mostrarPrevEnvio() {
    var usuario = $("#session_usuario").val();

    $.ajax({
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsclientes/prevenvio.php',
        data: {
            usuario: usuario
        },
        dataType: "json",
        //beforeSend: function(){},
        error: function(request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/carrito';
        },
        success: function(respuesta) {
            var filas = respuesta.rows;

            if (filas.length > 0) {
                $("#titulo_facturacion").val(filas[0]['titulo']);
                $("#nombre_facturacion").val(filas[0]['nombre']);
                $("#mail_facturacion").val(filas[0]['mail']);
                $("#movil1_facturacion").val(filas[0]['movil1']);
                $("#movil2_facturacion").val(filas[0]['movil2']);
                $("#direccion_facturacion").val(filas[0]['direccion']);
            }
        },
        //complete: function(){}
    });
}

function calcularTotal() {
    if ($('#idenvio_select > option').length > 0) {
        var total_carrito = $("#total_carrito").val();
        var costo_envio = $("#idenvio_select option:selected").data('costo_envio');
        $("#costo_envio").val(costo_envio);
        $("#total_costo_envio").val((parseFloat(total_carrito) + parseFloat(costo_envio)).toFixed(2));
        //console.log(costo_envio);
    } else {
        var total_carrito = $("#total_carrito").val();
        $("#total_costo_envio").val(parseFloat(total_carrito).toFixed(2));
    }
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {

    //******************************************************

    $("#idenvio").val(0);
    $("#idfacturacion").val(0);

    var contadorEnvio = 0;
    var contadorFacturacion = 0;

    //******************************************************

    cargarOptionMetodosPago();
    cargarOptionBodegaAtencion();
    cargarOptionDatosFacturacion();
    cargarOptionDatosEnvio();
    mostrarCarrito();
    tutorialCompra();

    $(window).on("resize", function() {
        redimensionarjqGrid();
    });

    // Select all tabs
    $('.nav-tabs a').on('click', function(event) {
        redimensionarjqGrid();
        //console.log($(this).attr('href'));        
        if (
            $(this).attr('href') == '#menu1' &&
            contadorEnvio == 0 &&
            $("#session_tipocliente").val() != 'visitante'
        ) {
            setTimeout(function() {
                mostrarDatosEnvio();
                mostrarPrevFacturacion();
                cargarOptionProvincias(0, 0, 0, 0);

                $("#idprovincia_envio").on('change keyup', function() {
                    // Ojo que se esta ejecutando 2 veces
                    cargarOptionCantones($("#idprovincia_envio option:selected").val(), 0, 0, 0);
                });

                $("#idcanton_envio").on('change keyup', function() {
                    cargarOptionZonas($("#idcanton_envio option:selected").val(), 0, 0);
                });

                $("#idzona_envio").on('change keyup', function() {
                    cargarOptionSectores($("#idzona_envio option:selected").val(), 0);
                });

            }, 1000);

            contadorEnvio++;
        } else if (
            $(this).attr('href') == '#menu1' &&
            $("#session_tipocliente").val() != 'visitante'
        ) {
            mostrarPrevFacturacion();
        }

        if (
            $(this).attr('href') == '#menu2' &&
            contadorFacturacion == 0 &&
            $("#session_tipocliente").val() != 'visitante'
        ) {
            setTimeout(function() {
                mostrarDatosFacturacion();
                mostrarPrevEnvio();
            }, 1000);

            contadorFacturacion++;
        } else if (
            $(this).attr('href') == '#menu2' &&
            $("#session_tipocliente").val() != 'visitante'
        ) {
            mostrarPrevEnvio();
        }

    });

    //**************************************    

    $("#jqGridEnvio").on("click", '.gestion_update', function(event) {
        var datos = $("#jqGridEnvio").jqGrid('getRowData', this.dataset.idenvio);
        //console.log(datos);
        $("#idenvio").val(datos['idenvio']);
        $("#titulo_envio").val(datos['titulo']);
        $("#nombre_envio").val(datos['nombre']);
        $("#movil1_envio").val(datos['movil1']);
        $("#movil2_envio").val(datos['movil2']);
        $("#direccion_envio").val(datos['direccion']);
        $("input:radio[name=estado_envio][value='" + datos['estado'] + "']").prop('checked', true);

        cargarOptionProvincias(
            datos['idprovincia'],
            datos['idcanton'],
            datos['idzona'],
            datos['idsector']
        );

    });

    $("#jqGridFacturacion").on("click", '.gestion_update', function(event) {
        var datos = $("#jqGridFacturacion").jqGrid('getRowData', this.dataset.idfacturacion);
        //console.log(datos);
        $("#idfacturacion").val(datos['idfacturacion']);
        $("#titulo_facturacion").val(datos['titulo']);
        $("#nombre_facturacion").val(datos['nombre']);
        $("#movil1_facturacion").val(datos['movil1']);
        $("#movil2_facturacion").val(datos['movil2']);
        $("#mail_facturacion").val(datos['mail']);
        $("#num_doc_facturacion").val(datos['num_doc']);
        $("#direccion_facturacion").val(datos['direccion']);
        $("input:radio[name=estado_facturacion][value='" + datos['estado'] + "']").prop('checked', true);

    });

    //**************************************

    $("#idenvio_select").on('change keyup', function() {
        calcularTotal();
    });

    $("#idmetodopago").on('change keyup', function() {
        var idmetodopago = $("#idmetodopago option:selected").val();
        $("#metodo_transfer").html('');

        if (parseInt(idmetodopago) == 3) {
            var cuerpoMetodo = '<div class="panel panel-warning"><div class="panel-body text-left bg-warning">';
            cuerpoMetodo += '<strong>Para su pago con transferencia bancaria, debe enviar un correo con el comprobante al siguiente email ( ' + $("#param_pagostransmail").val() + ' ) con el número de su pedido, caso contrario su pedido no sera despachado.</strong><br><br>';
            cuerpoMetodo += '<strong>ENTIDAD : </strong>' + $("#param_pagostransentidad").val() + '<br>';
            cuerpoMetodo += '<strong>TIPO CUENTA : </strong>' + $("#param_pagostranstipocuenta").val() + '<br>';
            cuerpoMetodo += '<strong># CUENTA  : </strong>' + $("#param_pagostranscuenta").val() + '<br>';
            cuerpoMetodo += '<strong>RUC / CI : </strong>' + $("#param_pagostransdocumento").val() + '<br>';
            cuerpoMetodo += '<strong>NOMBRE : </strong>' + $("#param_pagostransnombre").val() + '';
            cuerpoMetodo += '</div></div>';

            $("#metodo_transfer").html(cuerpoMetodo);
        }
    });

    //**************************************
    $("#cuerpo_carrito").on("click", '.value-plus', function(event) {
        var idproducto = $(this).data('idproducto');
        var divUpd = $(this).parent().find('.value');
        var cantidad = parseInt(divUpd.text(), 10) + 1;
        divUpd.text(cantidad);
        anadirProductos(idproducto, cantidad);
    });

    $("#cuerpo_carrito").on("click", '.value-minus', function(event) {
        var idproducto = $(this).data('idproducto');
        var divUpd = $(this).parent().find('.value');
        var cantidad = parseInt(divUpd.text(), 10) - 1;
        if (cantidad >= 1) {
            divUpd.text(cantidad);
            anadirProductos(idproducto, cantidad);
        }
    });

    $("#cuerpo_carrito").on("click", '.close-producto', function(event) {
        var usuario = $("#session_usuario").val();
        var idsector = $("#session_idsector").val();
        var idproducto = $(this).data('idproducto');
        var cantidad = $(this).parent().parent().find('.value').text();

        $(this).parent().fadeOut('slow', function(c) {
            $(this).parent().remove();
        });

        //alert(cantidad);

        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/wsproductos/deleteproduct.php',
            data: {
                usuario: usuario,
                idproducto: idproducto,
                cantidad: cantidad,
                idsector: idsector
            },
            dataType: "json",
            //beforeSend: function(){},
            error: function(request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val() + '/carrito';
            },
            success: function(respuesta) {
                switch (respuesta.estado) {
                    case 1:
                        $("#mkt-total-productos").html(respuesta.total);
                        $("#total_carrito").val(respuesta.total_carrito);
                        calcularTotal();
                        break;
                    case 2:
                        alert(respuesta.mensaje);
                        break;
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val() + '/carrito';
                        break;
                }
            },
            //complete: function(){}
        });

    });

    //**************************************

    $('.regresar_carrito').click(function() {
        //$('.nav-tabs a[href="#menu2"]').tab('show');
        $('.nav-tabs a[href="#home"]').trigger("click");
    });

    $('#add_envio').click(function() {
        //$('.nav-tabs a[href="#menu1"]').tab('show');
        $('.nav-tabs a[href="#menu1"]').trigger("click");
    });

    $('#add_facturacion').click(function() {
        //$('.nav-tabs a[href="#menu2"]').tab('show');
        $('.nav-tabs a[href="#menu2"]').trigger("click");
    });

    $('#limpiarFormEnvio').click(function() {
        limpiarFormEnvio();
    });

    $('#limpiarFormFacturacion').click(function() {
        limpiarFormFacturacion();
    });

    $("#formEnvio").submit(function() {
        var usuario = $("#session_usuario").val();
        var idenvio = $("#idenvio").val();
        var titulo = $("#titulo_envio").val();
        var nombre = $("#nombre_envio").val();
        var movil1 = $("#movil1_envio").val();
        var movil2 = $("#movil2_envio").val();
        var direccion = $("#direccion_envio").val();
        var estado = $('input:radio[name=estado_envio]:checked').val();
        var idprovincia = $("#idprovincia_envio option:selected").val();
        var idcanton = $("#idcanton_envio option:selected").val();
        var idzona = $("#idzona_envio option:selected").val();
        var idsector = $("#idsector_envio option:selected").val();

        if (parseInt(idenvio) != 0) {
            // Actualizar
            $.ajax({
                type: "POST",
                url: $("#param_hostapp").val() + '/util/carrito/updateEnvio.php',
                data: {
                    usuario: usuario,
                    idenvio: idenvio,
                    titulo: titulo,
                    nombre: nombre,
                    movil1: movil1,
                    movil2: movil2,
                    direccion: direccion,
                    estado: estado,
                    idprovincia: idprovincia,
                    idcanton: idcanton,
                    idzona: idzona,
                    idsector: idsector
                },
                dataType: "json",
                beforeSend: function() {
                    $("#submitFormEnvio").html('<i class="fa fa-circle-o-notch fa-spin"></i> Actualizando');
                    $('#submitFormEnvio').prop("disabled", true);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    document.location = $("#param_hostapp").val() + '/carrito';
                },
                success: function(respuesta) {
                    switch (respuesta.estado) {
                        case 1:
                            $('#myModalSuccessTitle').html('Gestión datos envio');
                            $('#myModalSuccessBody').html(respuesta.mensaje);
                            $('#myModalSuccess').modal("show");

                            setTimeout(function() {
                                $('#myModalSuccess').modal("hide");
                                //console.log('Cerrado');
                            }, 5000);

                            cargarOptionDatosEnvio();
                            mostrarDatosEnvio();
                            limpiarFormEnvio();
                            break;
                        case 2:
                            $('#myModalWarningTitle').html('Advertencia');
                            $('#myModalWarningBody').html(respuesta.mensaje);
                            $('#myModalWarning').modal("show");
                            break;
                        default:
                            alert('Se ha producido un error');
                            document.location = $("#param_hostapp").val() + '/carrito';
                            break;
                    }
                },
                complete: function() {
                    $('#submitFormEnvio').prop("disabled", false);
                    $("#submitFormEnvio").html('Guardar');
                }
            });
        } else {
            // Nuevo
            $.ajax({
                type: "POST",
                url: $("#param_hostapp").val() + '/util/carrito/insertEnvio.php',
                data: {
                    usuario: usuario,
                    titulo: titulo,
                    nombre: nombre,
                    movil1: movil1,
                    movil2: movil2,
                    direccion: direccion,
                    estado: estado,
                    idprovincia: idprovincia,
                    idcanton: idcanton,
                    idzona: idzona,
                    idsector: idsector
                },
                dataType: "json",
                beforeSend: function() {
                    $("#submitFormEnvio").html('<i class="fa fa-circle-o-notch fa-spin"></i> Registrando');
                    $('#submitFormEnvio').prop("disabled", true);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    document.location = $("#param_hostapp").val() + '/carrito';
                },
                success: function(respuesta) {
                    switch (respuesta.estado) {
                        case 1:
                            $('#myModalSuccessTitle').html('Gestión datos envio');
                            $('#myModalSuccessBody').html(respuesta.mensaje);
                            $('#myModalSuccess').modal("show");

                            setTimeout(function() {
                                $('#myModalSuccess').modal("hide");
                            }, 5000);

                            cargarOptionDatosEnvio();
                            mostrarDatosEnvio();
                            limpiarFormEnvio();
                            break;
                        case 2:
                            $('#myModalWarningTitle').html('Advertencia');
                            $('#myModalWarningBody').html(respuesta.mensaje);
                            $('#myModalWarning').modal("show");
                            break;
                        default:
                            alert('Se ha producido un error');
                            document.location = $("#param_hostapp").val() + '/carrito';
                            break;
                    }
                },
                complete: function() {
                    $('#submitFormEnvio').prop("disabled", false);
                    $("#submitFormEnvio").html('Guardar');
                }
            });
        }

        return false;
    });

    $("#formFacturacion").submit(function() {
        var usuario = $("#session_usuario").val();
        var idfacturacion = $("#idfacturacion").val();
        var titulo = $("#titulo_facturacion").val();
        var nombre = $("#nombre_facturacion").val();
        var movil1 = $("#movil1_facturacion").val();
        var movil2 = $("#movil2_facturacion").val();
        var mail = $("#mail_facturacion").val();
        var num_doc = $("#num_doc_facturacion").val();
        var direccion = $("#direccion_facturacion").val();
        var estado = $('input:radio[name=estado_facturacion]:checked').val();

        if (parseInt(idfacturacion) != 0) {
            // Actualizar
            $.ajax({
                type: "POST",
                url: $("#param_hostapp").val() + '/util/carrito/updateFacturacion.php',
                data: {
                    usuario: usuario,
                    idfacturacion: idfacturacion,
                    titulo: titulo,
                    nombre: nombre,
                    movil1: movil1,
                    movil2: movil2,
                    direccion: direccion,
                    estado: estado,
                    mail: mail,
                    num_doc: num_doc
                },
                dataType: "json",
                beforeSend: function() {
                    $("#submitFormFacturacion").html('<i class="fa fa-circle-o-notch fa-spin"></i> Actualizando');
                    $('#submitFormFacturacion').prop("disabled", true);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    document.location = $("#param_hostapp").val() + '/carrito';
                },
                success: function(respuesta) {
                    switch (respuesta.estado) {
                        case 1:
                            $('#myModalSuccessTitle').html('Gestión datos facturación');
                            $('#myModalSuccessBody').html(respuesta.mensaje);
                            $('#myModalSuccess').modal("show");

                            setTimeout(function() {
                                $('#myModalSuccess').modal("hide");
                                //console.log('Cerrado');
                            }, 5000);

                            cargarOptionDatosFacturacion();
                            mostrarDatosFacturacion();
                            limpiarFormFacturacion();
                            break;
                        case 2:
                            $('#myModalWarningTitle').html('Advertencia');
                            $('#myModalWarningBody').html(respuesta.mensaje);
                            $('#myModalWarning').modal("show");
                            break;
                        default:
                            alert('Se ha producido un error');
                            document.location = $("#param_hostapp").val() + '/carrito';
                            break;
                    }
                },
                complete: function() {
                    $('#submitFormFacturacion').prop("disabled", false);
                    $("#submitFormFacturacion").html('Guardar');
                }
            });
        } else {
            // Nuevo
            $.ajax({
                type: "POST",
                url: $("#param_hostapp").val() + '/util/carrito/insertFacturacion.php',
                data: {
                    usuario: usuario,
                    titulo: titulo,
                    nombre: nombre,
                    movil1: movil1,
                    movil2: movil2,
                    direccion: direccion,
                    estado: estado,
                    mail: mail,
                    num_doc: num_doc
                },
                dataType: "json",
                beforeSend: function() {
                    $("#submitFormFacturacion").html('<i class="fa fa-circle-o-notch fa-spin"></i> Registrando');
                    $('#submitFormFacturacion').prop("disabled", true);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                    document.location = $("#param_hostapp").val() + '/carrito';
                },
                success: function(respuesta) {
                    switch (respuesta.estado) {
                        case 1:
                            $('#myModalSuccessTitle').html('Gestión datos facturación');
                            $('#myModalSuccessBody').html(respuesta.mensaje);
                            $('#myModalSuccess').modal("show");

                            setTimeout(function() {
                                $('#myModalSuccess').modal("hide");
                            }, 5000);

                            cargarOptionDatosFacturacion();
                            mostrarDatosFacturacion();
                            limpiarFormFacturacion();
                            break;
                        case 2:
                            $('#myModalWarningTitle').html('Advertencia');
                            $('#myModalWarningBody').html(respuesta.mensaje);
                            $('#myModalWarning').modal("show");
                            break;
                        default:
                            alert('Se ha producido un error');
                            document.location = $("#param_hostapp").val() + '/carrito';
                            break;
                    }
                },
                complete: function() {
                    $('#submitFormFacturacion').prop("disabled", false);
                    $("#submitFormFacturacion").html('Guardar');
                }
            });
        }

        return false;
    });

    $("#formCarrito").submit(function() {
        var usuario = $("#session_usuario").val();
        var total_carrito = $("#total_carrito").val();
        var tipocliente = $("#session_tipocliente").val();
        var comentario = $("#comentario").val();
        var idfacturacion = $("#idfacturacion_select option:selected").val();
        var idenvio = $("#idenvio_select option:selected").val();
        var idatencion = $("#idatencion option:selected").val();
        var idmetodopago = $("#idmetodopago option:selected").val();
        var minimo_compra = $("#idmetodopago option:selected").data('minimo_compra');
        var maximo_compra = $("#idmetodopago option:selected").data('maximo_compra');

        if (tipocliente != 'visitante') {
            if (
                parseInt(total_carrito) >= parseInt(minimo_compra) &&
                parseInt(total_carrito) <= parseInt(maximo_compra)
            ) {
                // Nuevo
                $.ajax({
                    type: "POST",
                    url: $("#param_hostapp").val() + '/util/carrito/crearpedido.php',
                    data: {
                        usuario: usuario,
                        comentario: comentario,
                        idfacturacion: idfacturacion,
                        idenvio: idenvio,
                        idatencion: idatencion,
                        idmetodopago: idmetodopago
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#submitFormCarrito").html('<i class="fa fa-circle-o-notch fa-spin"></i> Generando pedido');
                        $('#submitFormCarrito').prop("disabled", true);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        document.location = $("#param_hostapp").val() + '/carrito';
                    },
                    success: function(respuesta) {
                        switch (respuesta.estado) {
                            case 1:
                                $('#myModalSuccessTitle').html('Gestión pedido');
                                $('#myModalSuccessBody').html(respuesta.mensaje);
                                $('#myModalSuccess').modal("show");

                                setTimeout(function() {
                                    document.location = $("#param_hostapp").val() + '/pedidos';
                                }, 1000);

                                break;
                            case 2:
                                var msnError = respuesta.mensaje;

                                switch (respuesta.error) {
                                    case 1:
                                        cargarOptionBodegaAtencion();
                                        break;
                                    case 2:
                                        var filas = respuesta.carrito_detalle;

                                        msnError += '<hr>';
                                        msnError += '<div class="table-responsive">';
                                        msnError += '<table class="table table-bordered table-striped table-hover">';
                                        msnError += '<thead>';
                                        msnError += '<tr>';
                                        msnError += '<th>Producto</th>';
                                        msnError += '<th>Estado</th>';
                                        msnError += '<th>Stock</th>';
                                        msnError += '<th>Cantidad</th>';
                                        msnError += '</tr>';
                                        msnError += '</thead>';
                                        msnError += '<tbody>';

                                        for (var f = 0; f < filas.length; f++) {
                                            if (
                                                parseInt(filas[f]['valido']) == 0
                                            ) {
                                                msnError += '<tr>';
                                                msnError += '<td class="text-left">' + filas[f]['nombre'] + '</td>';
                                                msnError += '<td class="text-center">' + filas[f]['estado'] + '</td>';
                                                msnError += '<td class="text-right">' + filas[f]['stock'] + '</td>';
                                                msnError += '<td class="text-right">' + filas[f]['cantidad'] + '</td>';
                                                msnError += '</tr>';
                                            }
                                        }

                                        msnError += '</tbody>';
                                        msnError += '</table>';
                                        msnError += '</div>';

                                        mostrarCarrito();
                                        break;
                                    case 3:
                                        msnError += '<div class="row">';
                                        msnError += '<form role="form" id="aceptarCondiciones">';
                                        msnError += '<div class="col-sm-12 terminos text-left">';

                                        msnError += '<h4>LEA ESTOS TÉRMINOS Y CONDICIONES DETENIDAMENTE.</h4>';
                                        msnError += '<h6>(Actualizado el día 05 de Julio de 2016)</h6>';
                                        msnError += '<br>';
                                        msnError += '<p>¡Bienvenido a www.marketton.com! Estos Términos de uso describen los términos y las condiciones aplicables a su acceso y uso de los sitios web www.marketton.com (en lo sucesivo, toda la información del "Sitio"). Este documento es un acuerdo legalmente vinculante entre usted como usuario del sitio (en lo sucesivo, referido como "usted", "su" o "Usuario") y la entidad www.marketton.com que figura en la cláusula 2.1 a continuación (en lo sucesivo, referido como "nosotros", "nuestro" o "www.marketton.com").</p>';
                                        msnError += '<br>';

                                        msnError += '<ul>';
                                        msnError += '<strong>Aplicación y aceptación de los términos</strong>';

                                        msnError += '<li>1.1 El uso de los Sitios y los servicios, el software y los productos de www.marketton.com (en lo sucesivo y de forma conjunta, los "Servicios") está sujeto a los términos y las condiciones contenidos en este documento, así como en la Política de privacidad y de cookies, la Política de listado de productos y cualquier otra normativa y política de los Sitios que pueda publicar www.marketton.com puntualmente y que no impliquen algún pago de cantidad alguna por parte del Usuario. En lo sucesivo, el presente documento y dichas otras normativas y políticas de los Sitios se denominan, conjuntamente, los "Términos". Al acceder a los Sitios o al utilizar los Servicios, usted se compromete a aceptar y someterse a estos Términos de uso. Por favor, no utilice los Servicios o los Sitios si no acepta todos los Términos.</li>';

                                        msnError += '<li>1.2 Usted no puede utilizar los Servicios ni aceptar las Términos si (a) no tiene la edad legal para formalizar un acuerdo vinculante con www.marketton.com, o (b) no está autorizado a recibir Servicios en virtud de la legislación de Ecuador u otros países o regiones, incluido el país o región en el que resida o desde el cual utiliza los Servicios.</li>';

                                        msnError += '<li>1.3 Usted reconoce y acepta que www.marketton.com podrá modificar cualquiera de los Términos en cualquier momento mediante la publicación de la modificación correspondiente y los Términos actualizados en los Sitios. Al continuar utilizando los Servicios o los Sitios, usted acepta que los Términos modificados le son de aplicación.</li>';

                                        msnError += '<li>1.4 Si usted accede a los Servicios desde Latinoamérica, quedará vinculado por la presente versión en español de los Términos de uso.</li>';

                                        msnError += '<li>1.5 Es posible que para cualquier Servicio tenga que firmar un acuerdo independiente, ya sea en línea o no, con www.marketton.com o nuestra filial (en lo sucesivo, los "Acuerdos adicionales"). En caso de conflicto o incoherencia entre los Términos y un Acuerdo adicional, únicamente prevalecerá el Acuerdo adicional sobre los Términos respecto al Servicio de que se trate.</li>';

                                        msnError += '<li>1.6 Los Términos solo podrán ser modificados por escrito por un representante autorizado de www.marketton.com</li>';

                                        msnError += '</ul>';
                                        msnError += '<ul>';
                                        msnError += '<strong>Prestación de Servicios</strong>';

                                        msnError += '<li>2.1 Si usted es un usuario registrado de los Sitios y está registrado o es residente en Ecuador, la entidad contratante es www.marketton.com. Si se ha registrado en una jurisdicción fuera de Ecuador, su acuerdo es de carácter temporal y no obliga a www.marketton.com a cumplir con ninguna exigencia, y usted acepta que esta le facture por la parte de los Servicios que le preste.</li>';

                                        msnError += '<li>2.2 Usted debe registrarse como miembro en los Sitios con el fin de acceder y utilizar algunos Servicios. Además, www.marketton.com se reserva el derecho, sin previo aviso, de restringir el acceso o la utilización de ciertos Servicios (o cualquier característica de los Servicios) a Usuarios de pago o sujetos a otras condiciones que www.marketton.com pueda imponer a su entera discreción.</li>';

                                        msnError += '<li>2.3 Los Servicios (o cualquier característica en los Servicios) pueden variar en función de las diferentes regiones. No se ofrece ninguna garantía o declaración de que siempre estará disponible para los Usuarios un Servicio o una característica o función particular del mismo, o el mismo tipo y alcance de los mismos. www.marketton.com puede, a nuestra entera discreción, limitar, negar o crear diferentes niveles de acceso para utilizar cualquiera de los Servicios (o cualquier característica de los mismos) con respecto a los diferentes Usuarios. En cualquier caso, los Servicios consistirán en todo momento, al menos, en la puesta a disposición de los usuarios de una plataforma en la que estos pueden comprar a Marketton S.A.. Servicios adicionales pueden llegar a complementar la anterior definición y estar sujetos a distintos términos y condiciones.</li>';

                                        msnError += '<li>2.4 www.marketton.com puede poner en marcha, cambiar, actualizar, imponer condiciones a, suspender o detener la prestación de cualquiera de los Servicios (o cualquier característica de los Servicios) sin previo aviso, excepto que, en el caso de un Servicio de pago, tal cambio no afecte negativamente en gran medida al disfrute de dicho Servicio por parte de los Usuarios de pago.</li>';

                                        msnError += '<li>2.5 Algunos Servicios pueden proporcionarlos filiales de www.marketton.com en nombre de www.marketton.com.</li>';

                                        msnError += '</ul>';
                                        msnError += '<ul>';
                                        msnError += '<strong>Usuarios en general</strong>';

                                        msnError += '<li>3.1 Como condición para el acceso y uso de los Sitios o Servicios, usted acepta que cumplirá con toda la legislación y las normativas aplicables al usar los Sitios o Servicios.</li>';

                                        msnError += '<li>3.2 Usted se compromete a utilizar los Sitios o Servicios únicamente para sus propios fines privados e internos. Acepta que (a) no copiará, reproducirá, descargará, volverá a publicar, venderá, distribuirá o revenderá ningún Servicio ni ninguna información, texto, imágenes, gráficos, clips de vídeo, sonido, directorios, archivos, bases de datos o listados, etc., que estén disponibles en o a través de los Sitios (en lo sucesivo, el "Contenido del Sitio"), y (b) no copiará, reproducirá, descargará, compilará o utilizará de otro modo cualquier contenido del Sitio con el fin de emprender una actividad comercial que compita con www.marketton.com , ni explotará comercialmente los contenidos del Sitio. Está prohibida la recuperación sistemática del Contenido del Sitio de los Sitios para crear o compilar, directa o indirectamente, una recopilación, compilación, base de datos o directorio (ya sea a través de robots, arañas, dispositivos automáticos o procesos manuales) sin el permiso por escrito de www.marketton.com. Está prohibido el uso de cualquier contenido o materiales de los Sitios para cualquier propósito que no esté expresamente permitido en los Términos.</li>';

                                        msnError += '<li>3.3 Debe leer la Política de privacidad y de cookies de www.marketton.com que regula la protección y el uso de la información personal sobre los Usuarios que esté en posesión de www.marketton.com Usted puede consultar los términos de la Política de privacidad y de cookies.</li>';

                                        msnError += '<li>3.4 Los Usuarios podrán acceder a contenidos, productos o servicios ofrecidos por www.marketton.com a través de hipervínculos (en forma de enlaces de texto, anuncios, canales o de otro tipo), interfaces API o de otra manera en los Sitios web de dichos terceros. Se le recomienda leer los términos y condiciones o políticas de privacidad de dichos sitios web antes de usar los Sitios. Usted reconoce que www.marketton.com no tiene control sobre los sitios web de dichos terceros, no realiza un seguimiento de ellos y no será responsable ni se hace cargo de ninguno de dichos sitios web, ni de ningún contenido, producto o servicio disponible en dichos sitios web.</li>';

                                        msnError += '<li>3.5 Usted acuerda no emprender ninguna acción para socavar la integridad de los sistemas informáticos o redes de www.marketton.com o de ningún otro Usuario, ni obtener acceso no autorizado a dichos sistemas informáticos o redes.</li>';

                                        msnError += '<li>3.6 Usted acepta que no adoptará ninguna medida que pueda afectar a la integridad del sistema de comentarios de www.marketton.com, como por ejemplo dejando comentarios positivos de sí mismo mediante el uso de Identificaciones de otros Miembros o a través de terceros, o dejando comentarios negativos sin fundamento de otro Usuario.</li>';

                                        msnError += '</ul>';
                                        msnError += '<ul>';
                                        msnError += '<strong>Cuentas de los Miembros</strong>';

                                        msnError += '<li>4.1 Usted debe estar registrado en los Sitios para acceder o utilizar algunos de los Servicios (un Usuario registrado también es referido en lo sucesivo como "Miembro"). Excepto si tiene la aprobación de www.marketton.com, un Usuario solo podrá registrar una cuenta de miembro en los Sitios. www.marketton.com puede cancelar o suspender la cuenta de miembro de un Usuario si tiene razones para sospechar que el Usuario se ha registrado o controla dos o más cuentas de miembro al mismo tiempo. Además, www.marketton.com podrá rechazar la solicitud de registro de un Usuario por cualquier motivo.</li>';

                                        msnError += '<li>4.2 Una vez registrado en los Sitios, www.marketton.com asignará una cuenta y proporcionará, para cada Usuario registrado, una identificación de miembro y una contraseña (esta última la escogerá el usuario durante su registro). Una cuenta puede tener una cuenta de correo electrónico en Internet con espacio de almacenamiento limitado para que el Miembro pueda enviar o recibir mensajes de correo electrónico.</li>';

                                        msnError += '<li>4.3 El conjunto de Identificación de Miembro y contraseña es único para cada cuenta. El Miembro será el único responsable de mantener la confidencialidad y seguridad de su Identificación de Miembro y contraseña, así como de todas las actividades que se lleven a cabo en la cuenta. Ningún Miembro puede compartir, ceder o permitir el uso de su cuenta de Miembro, identificación o contraseña a otra persona fuera de la propia entidad empresarial de dicho Miembro. El Miembro acuerda notificar a www.marketton.com inmediatamente si tiene conocimiento de cualquier uso no autorizado de su contraseña o su cuenta, o de cualquier otra infracción de la seguridad de su cuenta.</li>';

                                        msnError += '<li>4.4 El Miembro acepta que todas las actividades que se lleven a cabo a través de su cuenta (incluidos, entre otros, publicar cualquier información de la compañía o producto, pulsar para aceptar los Acuerdos o normas adicionales, suscribir o hacer cualquier pago por cualquier servicio, enviar mensajes de correo electrónico con la cuenta de correo electrónico o enviar SMS) se considera que han sido autorizadas por dicho Miembro.</li>';

                                        msnError += '<li>4.5 El Miembro reconoce que el uso compartido de su cuenta con otras personas o permitir que múltiples usuarios fuera de su entidad empresarial utilicen su cuenta (en lo sucesivo y conjuntamente, el "uso múltiple") puede causar un daño irreparable a www.marketton.com o a otros Usuarios de los Sitios. El Miembro eximirá a www.marketton.com, nuestras filiales, directores, empleados, agentes y representantes de cualquier pérdida o daño (incluida, entre otras, la lucro cesante) que puedan sufrir como consecuencia del uso múltiple de su cuenta. El Miembro también acepta que, en caso de un uso múltiple de su cuenta o de no poder mantener la seguridad de la misma, www.marketton.com no será responsable de ninguna pérdida o daños derivados de tal infracción y tendrá derecho a suspender o cancelar la cuenta del Miembro sin responsabilidad en relación con el mismo si cualquiera de las circunstancias anteriores tienen lugar.</li>';

                                        msnError += '</ul>';
                                        msnError += '<ul>';
                                        msnError += '<strong>Responsabilidades de los Miembros</strong>';

                                        msnError += '<li>5.1 El Miembro reconoce, garantiza y acepta que (a) tiene plenas facultades para aceptar los Términos, conceder la licencia y autorización y llevar a cabo las obligaciones que figuran a continuación; (b) utiliza los Sitios y Servicios únicamente con fines comerciales; y (c) la dirección que ha proporcionado durante el registro es el centro de actividad principal de su entidad empresarial. A los efectos de esta disposición, una sucursal o una oficina de enlace no serán consideradas como entidades independientes y su centro de actividad principal será su sede central.</li>';

                                        msnError += '<li>5.2 El Miembro estará obligado a proporcionar información o material acerca de su entidad, empresa o productos y servicios como parte del proceso de registro en los Sitios o para el uso de cualquier Servicio o de la cuenta de miembro. El Miembro reconoce, garantiza y acepta que (a) dicha información y material, se presenten durante el proceso de registro o posteriormente durante el uso de los Sitios o el Servicio, son verdaderos, precisos, actualizados y completos, y (b) mantendrá y corregirá con prontitud toda la información y los materiales para que sigan siendo verdaderos, precisos, actualizados y completos.</li>';

                                        msnError += '<li>5.3 Al convertirse en Miembro, usted autoriza la inclusión de su información de contacto en nuestra Base de datos de compradores y autoriza a www.marketton.com y a nuestras filiales a compartir la información de contacto con otros Usuarios, cuando así resulte necesario para la prestación de los Servicios, o a utilizar de otra manera su información personal de acuerdo con lo dispuesto en la Política de privacidad y de cookies.</li>';


                                        msnError += '<li>5.4 El Miembro reconoce, garantiza y acepta que (a) será el único responsable de obtener todas las licencias y permisos necesarios de terceros con respecto a cualquier Contenido de Usuario que envíe, publique o muestre; (b) cualquier Contenido de Usuario que envíe, publique o muestre no infringe ni vulnera ninguno de los derechos de autor, patentes, marcas comerciales, nombres comerciales, secretos comerciales o cualquier otro derecho personal o de propiedad de terceros (en lo sucesivo, los "Derechos de terceros"); (c) tiene las facultades para vender, comercializar, distribuir, exportar u ofrecer para la venta, negociar, distribuir o exportar los productos o servicios descritos en el Contenido de Usuario y dicha venta, comercio, distribución o exportación u oferta no vulnera ningún Derecho de terceros; y (d) usted y sus filiales no están sujetos a ninguna restricción de comercio, sanción ni cualquier otra restricción legal impuesta por cualquier Estado, organización internacional o jurisidicción.</li>';


                                        msnError += '<li>5.5 Además, el Miembro reconoce, garantiza y acepta que el Contenido de Usuario enviado, publicado o mostrado:';
                                        msnError += '<ul>';
                                        msnError += '<li>a) será verdadero, preciso, completo y legal;</li>';
                                        msnError += '<li>b) no será falso, equívoco o engañoso;</li>';
                                        msnError += '<li>c) no contendrá información que sea difamatoria, calumniosa, amenazante u hostigante, obscena, ofensiva, sexualmente explícita o perjudicial para los menores de edad;</li>';
                                        msnError += '<li>d) no contendrá información que sea discriminatoria o promueva la discriminación en función de raza, sexo, religión, nacionalidad, discapacidad, orientación sexual o edad;</li>';
                                        msnError += '<li>e) no vulnerará la Política de listado de productos, otros Términos ni ningún Acuerdo adicional aplicable;</li>';
                                        msnError += '<li>f) no vulnerará ninguna ley o reglamento (incluidos, entre otros, los que rigen el control de las exportaciones/importaciones, la protección del consumidor, la competencia desleal o la publicidad engañosa aplicables), ni promover ninguna actividad que pueda vulnerar las leyes y reglamentos aplicables;</li>';
                                        msnError += '<li>g) no contendrá ningún enlace directo o indirecto a ningún otro Sitio web que incluya cualquier contenido que pueda vulnerar los Términos.</li>';
                                        msnError += '</ul>';
                                        msnError += '</li>';

                                        msnError += '<li>5.6 El Miembro además reconoce, garantiza y acepta que deberá:';
                                        msnError += '<ul>';
                                        msnError += '<li>a) llevar a cabo sus actividades en los Sitios de conformidad con las leyes y reglamentos aplicables;</li>';
                                        msnError += '<li>b) llevar a cabo sus transacciones comerciales con otros usuarios de los Sitios de buena fe;</li>';
                                        msnError += '<li>c) llevar a cabo sus actividades de conformidad con los Términos y cualquier Acuerdo adicional aplicable;</li>';
                                        msnError += '<li>d) no utilizar los Servicios o los Sitios para estafar a ninguna persona o entidad (incluidos, entre otros, la venta de artículos robados o el uso de tarjetas de crédito o débito robadas);</li>';
                                        msnError += '<li>e) no hacerse pasar por otra persona o entidad, ni falseará su identidad o afiliación con ninguna persona o entidad;</li>';
                                        msnError += '<li>f) no enviar correos basura ni hará phishing;</li>';
                                        msnError += '<li>g) no involucrarse en ninguna otra actividad ilegal (incluidas, entre otras, las que podrían constituir un delito, dar lugar a responsabilidad civil, etc.) ni alentar o instigar ninguna actividad ilegal;</li>';
                                        msnError += '<li>h) no involucrarse en intentos de copiar, reproducir, explotar o expropiar los diversos directorios, bases de datos y listados propiedad de www.marketton.com;</li>';
                                        msnError += '<li>i) no utilizar ningún tipo de virus informático u otros dispositivos y códigos destructivos que tengan el efecto de dañar, interferir, interceptar o expropiar ningún sistema de software o hardware, o datos o información personal;</li>';
                                        msnError += '<li>j) no suponer ningún plan de socavar la integridad de los datos, los sistemas o las redes utilizados por www.marketton.com o de cualquier usuario de los Sitios o de obtener acceso no autorizado a dichos datos, sistemas o redes;</li>';
                                        msnError += '<li>k) no participar en ninguna actividad que de otro modo pueda crear cualquier tipo de responsabilidad a www.marketton.com o a nuestras filiales.</li>';
                                        msnError += '</ul>';
                                        msnError += '</li>';

                                        msnError += '<li>5.7 El Miembro no puede utilizar los Servicios ni la cuenta de miembro para participar en actividades que sean idénticas o similares a la actividad comercial en el sector del comercio electrónico de www.marketton.com.</li>';

                                        msnError += '<li>5.8 Si el Miembro proporciona una persona de contacto empresarial, el Miembro reconoce, garantiza y acepta que ha obtenido todas las autorizaciones, aprobaciones y dispensas necesarias de sus socios empresariales y asociados para (a) actuar como su persona de contacto empresarial; (b) enviar y publicar sus datos e información de contacto, cartas de referencia y comentarios en su nombre; y (c) que terceras personas puedan ponerse en contacto con dicha persona de contacto empresarial para respaldar las afirmaciones o declaraciones hechas sobre usted. Asimismo, garantiza y acepta que todas las cartas de referencia y los comentarios son verdaderos y exactos, y que terceros pueden comunicarse con las personas de contacto empresarial sin necesidad de obtener su consentimiento.</li>';

                                        msnError += '<li>5.9 El Miembro se compromete a proporcionar toda la información, los materiales y la aprobación necesarios, y a prestar toda la asistencia razonable y la cooperación necesaria para que www.marketton.com pueda prestar los Servicios, evaluar si el Miembro ha incumplido los Términos o gestionar cualquier queja contra el Miembro. Si el incumplimiento de lo anterior por parte del Miembros da lugar al retraso, la suspensión o la resolución en la prestación de cualquier Servicio, www.marketton.com no estará obligado a extender el período de servicio pertinente ni será responsable ante ninguna pérdida o daño derivados de dicha demora, suspensión o resolución.</li>';

                                        msnError += '<li>5.10 El Miembro reconoce y acepta que www.marketton.com no estará obligado a vigilar de forma activa ni ejercer ningún tipo de control editorial sobre el contenido de cualquier mensaje u otro material o información creado, obtenido o accesible a través del Servicio o los Sitios. www.marketton.com no avala, verifica o certifica de otro modo el contenido de cualquier comentario u otro material o información proporcionado por los Miembros. El Miembro es el único responsable del contenido de sus comunicaciones y puede ser considerado legalmente responsable o culpable por el contenido de sus comentarios u otro material o información.';

                                        msnError += '<li>5.11 El Miembro reconoce y acepta que los Servicios solo pueden utilizarse por parte de empresas y sus representantes para su uso comercial y no para consumidores individuales o para uso personal.</li>';

                                        msnError += '<li>5.12 El Miembro reconoce y acepta que el Miembro es el único responsable del cumplimiento de la legislación y los reglamentos aplicables en sus respectivas jurisdicciones, a fin de garantizar que el uso del Sitio y los Servicios se lleva a cabo de conformidad con los mismos.</li>';

                                        msnError += '</ul>';
                                        msnError += '<ul>';
                                        msnError += '<strong>Infracciones de los Miembros</strong>';

                                        msnError += '<li>6.1 www.marketton.com se reserva el derecho, a su entera discreción, de eliminar, modificar o rechazar cualquier Contenido de Usuario que usted envíe, publique o muestre en los Sitios que creamos razonablemente que es ilegal, infringe los Términos, podría suponer una responsabilidad a www.marketton.com o a nuestras filiales, o se considere de otro modo inadecuado en opinión de www.marketton.com.</li>';

                                        msnError += '<li>6.2 Si un Miembro infringe cualquiera de los Términos, o si www.marketton.com tiene motivos razonables para creer que un Miembro ha incumplido alguno de los Términos, www.marketton.com tendrá derecho a: tomar cualquier medida disciplinaria que considere necesaria, incluyendo sin limitación: (i) suspender o cancelar la cuenta del mismo y todas y cada una de las cuentas de usuario que se determine que guardan relación con aquella por www.marketton.com a su discreción; (ii) restringir, rebajar la categoría de, suspender o cancelar su suscripción a, acceso a, o uso actual o futuro de cualquier Servicio; (iii) eliminar cualquier listado de productos u otro Contenido de Usuario que el Miembro haya enviado, publicado o mostrado, así como imponer restricciones sobre el número de listados de productos que el Miembro puede publicar o mostrar; (iv) imponer otras restricciones en el uso que el Miembro haga de cualquier característica o función de cualquier Servicio que www.marketton.com considere adecuadas a su sola discreción; y (v) cualesquiera otras medidas correctoras, disciplinarias o sanciones que www.marketton.com pueda considerar necesarias o adecuadas a su entera discreción.</li>';

                                        msnError += '<li>6.3 Sin limitar la generalidad de lo dispuesto en los Términos, se considera que un Miembro incumple los Términos en cualquiera de las siguientes circunstancias:';
                                        msnError += '<ul>';
                                        msnError += '<li>a) tras la queja o reclamación de terceros, si www.marketton.com tiene motivos razonables para creer que tal Miembro no ha cumplido voluntaria o materialmente con el acuerdo con dicho tercero, incluidos, entre otros, si el Miembro no ha entregado ningún artículo solicitado por tal tercero después de la recepción del precio de compra o cuando los productos que ha enviado el Miembro no cumplen materialmente con los términos y las descripciones detallados en su acuerdo con dicho tercero;</li>';
                                        msnError += '<li>b) si www.marketton.com tiene motivos razonables para sospechar que dicho Miembro ha utilizado una tarjeta de crédito robada u otra información falsa o engañosa en cualquier transacción con una contraparte;</li>';
                                        msnError += '<li>c) si www.marketton.com tiene motivos razonables para sospechar que cualquier información proporcionada por el Miembro no está actualizada o completa, o es falsa, inexacta o engañosa; o</li>';
                                        msnError += '<li>d) si www.marketton.com cree que las acciones del Miembro pueden provocar una pérdida financiera o responsabilidad legal de www.marketton.com, nuestras filiales u otros Usuarios.</li>';
                                        msnError += '</ul>';
                                        msnError += '</li>';

                                        msnError += '</ul>';

                                        msnError += '</div>';
                                        msnError += '<div class="col-sm-12">';
                                        msnError += '<div class="checkbox"><label><input type="checkbox" required>Aceptar términos y condiciones</label></div>';
                                        msnError += '<button type="submit" class="btn btn-warning">Guardar</button></form>';
                                        msnError += '</div>';
                                        msnError += '</div>';
                                        break;
                                }

                                $('#myModalWarningTitle').html('Términos y condiciones');
                                $('#myModalWarningBody').html(msnError);
                                $('#myModalWarning').modal("show");

                                break;
                            default:
                                alert('Se ha producido un error');
                                document.location = $("#param_hostapp").val() + '/carrito';
                                break;
                        }
                    },
                    complete: function() {
                        $('#submitFormCarrito').prop("disabled", false);
                        $("#submitFormCarrito").html('Generar pedido');
                    }
                });
            } else {
                $('#myModalWarningTitle').html('Advertencia');
                $('#myModalWarningBody').html('Estimado para el método de pago seleccionado la compra <strong>(subtotal)</strong> debe estar entre [ $ ' + minimo_compra + ' - $ ' + maximo_compra + ' ]');
                $('#myModalWarning').modal("show");
            }
        } else {
            $('#myModalDangerTitle').html("Iniciar sesión");
            $('#myModalDangerBody').html("Estimado usuario debe iniciar sesión para generar su pedido");
            $('#myModalDanger').modal("show");
        }


        return false;
    });

    $("#myModalWarningBody").on("submit", '#aceptarCondiciones', function(event) {
        var usuario = $("#session_usuario").val();

        //alert(usuario);
        $('#myModalWarning').modal("hide");

        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/carrito/terminos.php',
            data: {
                usuario: usuario
            },
            dataType: "json",
            //beforeSend: function(){ },
            error: function(request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val() + '/carrito';
            },
            success: function(respuesta) {
                switch (respuesta.estado) {
                    case 1:
                        $('#formCarrito').trigger("submit");

                        break;
                    case 2:

                        $('#myModalWarningTitle').html('Advertencia');
                        $('#myModalWarningBody').html(respuesta.error);
                        $('#myModalWarning').modal("show");

                        break;
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val() + '/carrito';
                        break;
                }
            },
            //complete: function(){ }
        });

        return false;
    });
});