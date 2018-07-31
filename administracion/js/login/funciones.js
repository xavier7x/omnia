$(document).ready(function(){
    
    $("#formLogin").submit(function(){     
        var usuario = $("#usuario").val().toLowerCase();
        var contrasena = $("#contrasena").val();
        
        //alert(usuario + "***" + contrasena);
        $.ajax({
            //async: false,
            type: "POST",
            url: "util/login/login.php",
            data: {
                usuario:usuario,
                contrasena:contrasena
            },
            dataType: 'json',
            beforeSend: function(){
                $("#submitFormLogin").html('Consultando');
                $('#submitFormLogin').prop("disabled", true);
            },
            error: function (request, status, error) { 
                console.log(request.responseText);
                document.location = '';
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:
                        $('#myModalSuccessTitle').html("Bienvenido a "+$("#param_empresa").val());
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        
                        $('#myModalSuccess').modal("show");
                        // Para repertir por N milisegundos
                        setInterval(function(){ document.location = ''; }, 2000);
                        break;
                    case 2:
                        $('#myModalWarningBody').html(respuesta.mensaje);
                        $('#myModalWarning').modal("show"); 
                        
                        $('#submitFormLogin').prop("disabled", false);
                        $("#submitFormLogin").html('Acceder');   
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = '';
                        break;
                } 
            },
            //complete: function(){ }
        });
        
        return false;        		
	});
    
});