function cargarOptionProvincias(){
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/provincias.php',
        dataType: 'json',
        //data: { },
        beforeSend: function(){
            $("#idprovincia").empty(); 
            $("#idcanton").empty(); 
            $("#idzona").empty(); 
            $("#idsector").empty(); 
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = $("#param_hostapp").val()+ '/registro';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idprovincia']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idprovincia").html(opcSelect); 
                
                // Cargar los cantones
                cargarOptionCantones($("#idprovincia option:selected").val());
            }
        }
    }); 
}

function cargarOptionCantones(idprovincia){
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/cantones.php',
        dataType: 'json',
        data: {
            idprovincia:idprovincia
        },
        beforeSend: function(){
            $("#idcanton").empty(); 
            $("#idzona").empty(); 
            $("#idsector").empty(); 
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = $("#param_hostapp").val()+ '/registro';
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
                
                $("#idcanton").html(opcSelect); 
                
                // Cargar las zonas
                cargarOptionZonas($("#idcanton option:selected").val());
            }
        }
    }); 
}

function cargarOptionZonas(idcanton){
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/zonas.php',
        dataType: 'json',
        data: {
            idcanton:idcanton
        },
        beforeSend: function(){
            $("#idzona").empty(); 
            $("#idsector").empty(); 
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = $("#param_hostapp").val()+ '/registro';
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
                
                $("#idzona").html(opcSelect); 
                
                // Cargar los sectores
                cargarOptionSectores($("#idzona option:selected").val());
            }
        }
    }); 
}

function cargarOptionSectores(idzona){
    $.ajax({
        async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wslocalidades/sectores.php',
        dataType: 'json',
        data: {
            idzona:idzona
        },
        beforeSend: function(){
            $("#idsector").empty(); 
        },
        error: function (request, status, error) { 
            console.log(request.responseText);
            document.location = $("#param_hostapp").val()+ '/registro';
        },
        success: function(respuesta){
            var opciones = respuesta.resultado;
            if(opciones.length > 0){
                // Crear los option
                //console.log(respuesta);
                var opcSelect = '';
                
                for(var f = 0; f < opciones.length; f++){
                    opcSelect += '<option ';
                    opcSelect += ' value="'+opciones[f]['idsector']+'">';
                    opcSelect += opciones[f]['nombre'];
                    opcSelect += '</option>';
                }
                
                $("#idsector").html(opcSelect); 
            }
        }
    }); 
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {    
    
    cargarOptionProvincias();
    
    $("#idprovincia").on('change keyup', function () {
        // Ojo que se esta ejecutando 2 veces
        cargarOptionCantones($("#idprovincia option:selected").val());
    });
    
    $("#idcanton").on('change keyup', function () {
        cargarOptionZonas($("#idcanton option:selected").val());
    });
    
    $("#idzona").on('change keyup', function () {
        cargarOptionSectores($("#idzona option:selected").val());
    });
    /*
    $('#idzona').change(function(e) {
        console.log(2);
        cargarOptionSectores($("#idzona option:selected").val());
    });
    */
    $("#formRegistro").submit(function(){
        var nombre = $("#nombre").val();
        var mail = $("#mail").val();
        var usuario = $("#usuario").val();
        var contrasena = $("#contrasena").val();
        var confirmar_contrasena =  $("#confirmar_contrasena").val();
        var idprovincia = $("#idprovincia option:selected").val();
        var idcanton = $("#idcanton option:selected").val();
        var idzona = $("#idzona option:selected").val();
        var idsector = $("#idsector option:selected").val();
        var pagsig = $("#pagsig").val();
        
        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/registro/insert.php',
            data: {
                nombre: nombre,
                mail: mail,
                usuario: usuario,
                contrasena: contrasena,
                confirmar_contrasena: confirmar_contrasena,
                idprovincia: idprovincia,
                idcanton: idcanton,
                idzona: idzona,
                idsector: idsector
            }, 
            dataType: "json",
            beforeSend: function(){
                $("#submitFormRegistro").html('<i class="fa fa-circle-o-notch fa-spin"></i> Consultando');
                $('#submitFormRegistro').prop("disabled", true);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val()+ '/registro';
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:
                        $('#myModalSuccessTitle').html("Bienvenido a "+$("#param_empresa").val());
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        
                        $('#myModalSuccess').modal("show"); 
                        setInterval(function(){ document.location = $("#param_hostapp").val()+ '/' + pagsig; }, 3000);
                        break;
                    case 2:
                        var errores = respuesta.errores;
                        var erroresText = '';
                        
                        if(parseInt(respuesta.error) == 1){
                            
                            for(var f=0; f < errores.length; f++){
                                erroresText += errores[f] + '<br>';
                            }
                                
                        }else{
                            erroresText = respuesta.mensaje;     
                        }
                        
                        $("#confirmar_contrasena").val('');
                        $("#contrasena").val('');
                        
                        $('#myModalWarningBody').html('<div class="text-left">'+erroresText+'</div>');
                        $('#myModalWarning').modal("show"); 
                        
                        $('#submitFormRegistro').prop("disabled", false);
                        $("#submitFormRegistro").html('Registrar'); 
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val()+ '/registro';
                        break;
                }
            },
            //complete: function(){ }
        });
        
        return false;
    });    
    
});