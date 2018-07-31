/*
window.fbAsyncInit = function() {
    FB.init({
      appId      : 'marketton',
      xfbml      : true,
      version    : 'v2.7'
    });
  };
*/
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    $('#btn_reset_contrasena').click(function(){
        $('#formOlvidoContrasena').trigger("reset");
        $('#modalOlvidoContrasena').modal("show"); 
    });
    
    $("#formOlvidoContrasena").submit(function(){
        var mail = $('#mail').val();
        
        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/login/recuperar.php',
            data: {
                mail: mail
            }, 
            dataType: "json",
            beforeSend: function(){
                $('#modalOlvidoContrasena').modal("hide"); 
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val() + '/login';
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:
                        $('#myModalSuccessTitle').html("Recuperación de contraseña");
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        
                        $('#myModalSuccess').modal("show"); 
                        break;
                    case 2:
                        $('#myModalWarningBody').html(respuesta.mensaje);
                        $('#myModalWarning').modal("show"); 
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val() + '/login';
                        break;
                }
            },
            //complete: function(){ }
        });
        
        return false;
    });
    
    $("#formLogin").submit(function(){
        var usuario = $('#usuario').val().toLowerCase();
        var contrasena = $('#contrasena').val();
        var pagsig = $("#pagsig").val();
        
        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/login/login.php',
            data: {
                usuario: usuario,
                contrasena: contrasena
            }, 
            dataType: "json",
            beforeSend: function(){
                $("#submitFormLogin").val('Consultando');
                $('#submitFormLogin').prop("disabled", true);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val() + '/login';
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:
                        $('#myModalSuccessTitle').html("Bienvenido a "+$("#param_empresa").val());
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        
                        $('#myModalSuccess').modal("show"); 
                        
                        // Para repertir por N milisegundos
                        setInterval(function(){ document.location = $("#param_hostapp").val() + '/' + pagsig; }, 2000);
                        break;
                    case 2:
                        $('#myModalWarningBody').html(respuesta.mensaje);
                        $('#myModalWarning').modal("show"); 
                        
                        $('#submitFormLogin').prop("disabled", false);
                        $("#submitFormLogin").val('Ingresar'); 
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val() + '/login';
                        break;
                }
            },
            complete: function(){
                $('#submitFormLogin').prop("disabled", false);
                $("#submitFormLogin").val('Ingresar'); 
            }
        });
        
        return false;
    });
    
});