function mostrarPrevUsuario(){
    var usuario = $("#session_usuario").val();
    
    $.ajax({
        type: "POST",
        url: $("#param_hostapp").val() + '/util/cuenta/query.php',
        data: {
            usuario:usuario
        }, 
        dataType: "json",
        //beforeSend: function(){},
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val() + '/cuenta';
        },
        success: function(respuesta){
            var filas = respuesta.rows;
            
            if(filas.length > 0){
                $("#usuario").val(filas[0]['usuario']);
                $("#nombre").val(filas[0]['nombre']);
                $("#mail").val(filas[0]['mail']);
            }
        },
        //complete: function(){}
    });
}

/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    mostrarPrevUsuario();
    
    $("#cambiar_contrasena").on('change keyup', function () {
        var cambiar_contrasena = $("#cambiar_contrasena").is( ":checked" ) ? 1 : 0;
        console.log(cambiar_contrasena);
        
        if(cambiar_contrasena == 1){
            // Desbloquear
            $('#anterior_contrasena').prop("disabled", false);
            $('#nueva_contrasena').prop("disabled", false);
            $('#confirmar_contrasena').prop("disabled", false);
            
            $('#anterior_contrasena').prop("required", true);
            $('#nueva_contrasena').prop("required", true);
            $('#confirmar_contrasena').prop("required", true);
        }else{
            // Bloquear
            $('#anterior_contrasena').prop("required", false);
            $('#nueva_contrasena').prop("required", false);
            $('#confirmar_contrasena').prop("required", false);
            
            $('#anterior_contrasena').prop("disabled", true);
            $('#nueva_contrasena').prop("disabled", true);
            $('#confirmar_contrasena').prop("disabled", true);
        }
    });
    
    $("#formUpdateUsuario").submit(function(){
        var usuario = $("#usuario").val();
        var nombre = $("#nombre").val();
        var mail = $("#mail").val();  
        var cambiar_contrasena = $("#cambiar_contrasena").is( ":checked" ) ? 1 : 0;
        var anterior_contrasena = $("#anterior_contrasena").val();
        var nueva_contrasena = $("#nueva_contrasena").val();
        var confirmar_contrasena =  $("#confirmar_contrasena").val();
        
        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/cuenta/update.php',
            data: {
                usuario: usuario,
                nombre: nombre,
                mail: mail,
                cambiar_contrasena: cambiar_contrasena,
                anterior_contrasena: anterior_contrasena,
                nueva_contrasena: nueva_contrasena,
                confirmar_contrasena: confirmar_contrasena
            }, 
            dataType: "json",
            beforeSend: function(){
                $("#submitFormUpdateUsuario").html('<i class="fa fa-circle-o-notch fa-spin"></i> Actualizando');
                $('#submitFormUpdateUsuario').prop("disabled", true);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val() + '/cuenta';
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:
                        $('#myModalSuccessTitle').html("Gestión usuario");
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        
                        $('#myModalSuccess').modal("show"); 
                        setInterval(function(){ document.location = $("#param_hostapp").val() + '/inicio'; }, 2000);
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
                        
                        $("#anterior_contrasena").val('');
                        $("#nueva_contrasena").val('');
                        $("#confirmar_contrasena").val('');
                        
                        $('#myModalWarningTitle').html("Gestión usuario");
                        $('#myModalWarningBody').html('<div class="text-left">'+erroresText+'</div>');
                        $('#myModalWarning').modal("show"); 
                        
                        $('#submitFormUpdateUsuario').prop("disabled", false);
                        $("#submitFormUpdateUsuario").html('Actualizar'); 
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val() + '/cuenta';
                        break;
                }
            },
            //complete: function(){ }
        });
        
        return false;
    });
});