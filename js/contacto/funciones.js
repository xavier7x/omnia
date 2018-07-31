/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    //Horizontal Tab
    $('#parentHorizontalTab').easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion
        width: 'auto', //auto or any width like 600px
        fit: true, // 100% fit in a container
        tabidentify: 'hor_1', // The tab groups identifier
        activate: function(event) { // Callback function if tab is switched
            var $tab = $(this);
            var $info = $('#nested-tabInfo');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });
    
    $("#formContacto").submit(function(){
        var nombre = $("#nombre").val();   
        var email = $("#email").val();   
        var mensaje = $("#mensaje").val();   
        
        // Nuevo
        $.ajax({
            type: "POST",
            url: $("#param_hostapp").val() + '/util/contacto/formulario.php',
            data: {
                nombre:nombre,
                email:email,
                mensaje:mensaje
            },
            dataType: "json",
            beforeSend: function(){
                $("#submitFormContacto").val('Enviando');
                $('#submitFormContacto').prop("disabled", true);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val() + '/contacto';
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:                                
                        $('#myModalSuccessTitle').html('Formulario de contacto');
                        $('#myModalSuccessBody').html(respuesta.mensaje);
                        $('#myModalSuccess').modal("show");    

                        setTimeout(function(){
                            document.location = $("#param_hostapp").val() + '/inicio';
                        }, 3000);

                        break;
                    case 2:

                        $('#myModalWarningTitle').html('Advertencia');
                        $('#myModalWarningBody').html(respuesta.mensaje);
                        $('#myModalWarning').modal("show");

                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val() + '/contacto';
                        break;
                } 
            },
            complete: function(){
                $('#submitFormContacto').prop("disabled", false);
                $("#submitFormContacto").val('Enviar');                    
            }
        });
        
        return false;
    });
});