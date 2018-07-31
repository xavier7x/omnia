/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    $("#consulta-producto").submit(function(){
        //console.log(encodeURI($("#search_products").val()));
        var datoUrl = encodeURI($("#search_products_text").val());
        //var datoUrl = encodeURIComponent($("#search_products").val());
        document.location = $("#param_hostapp").val() + '/buscar/'+datoUrl;
        
        return false;
    });

    var validateEmail = function(elementValue) {
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailPattern.test(elementValue);
    }
    
    
    var is_valid;
    $('#email_suscribe').keyup(function() {
        var value = $(this).val();
        var valid = validateEmail(value);
        if (!valid) {
            $(this).css('color', 'red');
        } else {
            is_valid = true;
            $(this).css('color', '#000');
        }
    
    });

    $("#clickSuscribe").click(function(){
        var email_suscrito = $("#email_suscribe").val();
        if(email_suscrito!='' && is_valid == true){
            $.ajax({
                type: "POST",
                url: "util/inicio/insert.php",
                dataType: 'json',
                data: {
                    email_suscrito:email_suscrito 
                },
                beforeSend: function(){
                    $('#myModalWarningBody').html('Te estamos agregando a nuestro listado');
                    $('#myModalWarning').modal("show"); 
                },
                error: function (request, status, error) { 
                    console.log(request.responseText);
                    document.location = 'inicio';
                },
                success: function(respuesta){
                    //console.log(respuesta);
                    switch (respuesta.estado){
                        case 1:
                            $('#myModalWarning').modal("hide");
                            $('#myModalSuccessBody').html(respuesta.mensaje);
                            $('#myModalSuccess').modal("show");
                            break;
                        case 2:
                            $('#myModalWarningBody').html(respuesta.mensaje);
                            $('#myModalWarning').modal("show");
                            break;                    
                        default:
                             $('#myModalWarning').modal("hide");
                            alert('Se ha producido un error');
                            document.location = 'inicio';
                            break;
                    } 
                },
                complete: function(){
                    //$('#submitFormProductoDetalle').prop("disabled", false);
                    //$("#submitFormProductoDetalle").html('Guardar');
                }
            });
        }else{
            $('#myModalWarningBody').html('Ingrese un correo valido');
            $('#myModalWarning').modal("show");
        }
    });
});