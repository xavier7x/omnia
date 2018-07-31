function cargaMenu(){
    $.ajax({
        async: false,
        type: "POST",
        url: "util/accesos/queryMenu.php",
        data: {
            
        }, 
        dataType: "json",
        beforeSend: function(){
            $("#sys_menu").empty();
        },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = 'accesos';
        },
        success: function(respuesta){   
            var menu = respuesta.rows;
            var sys_menu = '<ul id="myMenuPermisos">';
            
            for(f=0 ; f < menu.length;  f++){
                sys_menu += '<li class="open li_menu_sys">';
                sys_menu += '<input type="checkbox" id="menu_'+(f+1)+'" value="'+menu[f]['idmenu']+'" class="menu_sys">';
                sys_menu += '<label for="menu_'+(f+1)+'">'+menu[f]['nombre']+'</label>';
                sys_menu += '<ul>';
                var submenu = menu[f]['submenu'];
                
                for(i=0; i < submenu.length; i++){
                    sys_menu += '<li class="li_menu_item_sys">';
                    sys_menu += '<input type="checkbox" id="menu_'+(f+1)+'_'+(i+1)+'" value="'+submenu[i]['idmenu']+'"  class="menu_item_sys">';
                    sys_menu += '<label for="menu_'+(f+1)+'_'+(i+1)+'">'+submenu[i]['nombre']+'</label>';  
                    sys_menu += '<ul>';
                    
                    sys_menu += '<li class="li_menu_item_accesos_sys">';
                    sys_menu += '<input type="checkbox" id="menu_'+(f+1)+'_'+(i+1)+'_1" value="visualizar" class="menu_item_accesos_sys">';
                    sys_menu += '<label for="menu_'+(f+1)+'_'+(i+1)+'_1">Visualizar</label>'; 
                    sys_menu += '</li>';
                    
                    sys_menu += '<li class="li_menu_item_accesos_sys">';
                    sys_menu += '<input type="checkbox" id="menu_'+(f+1)+'_'+(i+1)+'_2" value="insertar" class="menu_item_accesos_sys">';
                    sys_menu += '<label for="menu_'+(f+1)+'_'+(i+1)+'_2">Insertar</label>'; 
                    sys_menu += '</li>';
                    
                    sys_menu += '<li class="li_menu_item_accesos_sys">';
                    sys_menu += '<input type="checkbox" id="menu_'+(f+1)+'_'+(i+1)+'_3" value="actualizar" class="menu_item_accesos_sys">';
                    sys_menu += '<label for="menu_'+(f+1)+'_'+(i+1)+'_3">Actualizar</label>'; 
                    sys_menu += '</li>';
                    
                    sys_menu += '<li class="li_menu_item_accesos_sys">';
                    sys_menu += '<input type="checkbox" id="menu_'+(f+1)+'_'+(i+1)+'_4" value="eliminar" class="menu_item_accesos_sys">';
                    sys_menu += '<label for="menu_'+(f+1)+'_'+(i+1)+'_4">Eliminar</label>'; 
                    sys_menu += '</li>';
                    
                    sys_menu += '</ul>';
                    sys_menu += '</li>';
                }
                
                sys_menu += '</ul>';
                sys_menu += '</li>';
            }
            
            sys_menu += '</ul>';
            $("#sys_menu").html(sys_menu);
        },
        complete: function(){
            chekear();
            
            $("#myMenuPermisos").treeview({
                animated:"normal",
                collapsed: true,
                unique: false,
                persist: "location"
            });
        }        
    }); 
}

// Apparently click is better chan change? Cuz IE?
function chekear(){
    
    $('input[type="checkbox"]').change(function(e) {
        var checked = $(this).prop("checked"),
        container = $(this).parent(),
        siblings = container.siblings();

        container.find('input[type="checkbox"]').prop({
        indeterminate: false,
        checked: checked
    });

    function checkSiblings(el) {
        var parent = el.parent().parent(),
        all = true;

        el.siblings().each(function() {
            return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
        });

        if (all && checked) {
            parent.children('input[type="checkbox"]').prop({
                indeterminate: false,
                checked: checked
            });
            checkSiblings(parent);
        } else if (all && !checked) {
            parent.children('input[type="checkbox"]').prop("checked", checked);
            parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
            checkSiblings(parent);
        } else {
            el.parents("li").children('input[type="checkbox"]').prop({
                indeterminate: true,
                checked: true
            });
        }
    }

    checkSiblings(container);
    });
}


function accesoUsuario(usuario){
    
    $.ajax({
        type: "POST",
        url: "util/accesos/queryMenuByUsuario.php",
        data: {
            usuario:usuario
        }, 
        dataType: "json",
        beforeSend: function(){
            $('input[type="checkbox"]').each(function() {
                $(this).prop({checked: false}); 
                $(this).prop({indeterminate: false}); 
            });
        },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = 'accesos';
        },
        success: function(respuesta){      
            var menu = respuesta.menu;
            
            if(menu.length > 0){      
                
                // Por cada menu_sys debo chekearlo
                                
                $('.li_menu_sys').each(function(){
                    var idmenu = $('.menu_sys', this).val();
                    //console.log($('.menu_sys', this).val());
                    
                    for(var f=0; f < menu.length; f++){
                        
                        if(
                            idmenu == menu[f]['idmenu'] &&
                            menu[f]['es_menu'] == 'SI'
                        ){
                            $('.menu_sys', this).prop({checked: true});
                            
                            $('.li_menu_item_sys', this).each(function(){
                                var menu_item = $('.menu_item_sys', this).val();
                                //console.log($('.menu_item_sys', this).val());
                            
                                for(var i=0; i < menu.length; i++){
                                    
                                    if(
                                        menu_item == menu[i]['idmenu'] &&
                                        menu[i]['es_menu'] == 'NO'
                                    ){
                                        $('.menu_item_sys', this).prop({checked: true});
                                        
                                        $('.li_menu_item_accesos_sys', this).each(function(){
                                            //console.log($('.menu_item_accesos_sys', this).val());  
                                            var menu_item_accesos = $('.menu_item_accesos_sys', this).val();
                                            
                                            if(
                                                ( menu_item_accesos == 'visualizar' &&
                                                menu[i]['visualizar'] == 1 ) || 
                                                ( menu_item_accesos == 'insertar' &&
                                                menu[i]['insertar'] == 1 ) || 
                                                ( menu_item_accesos == 'actualizar' &&
                                                menu[i]['actualizar'] == 1 ) || 
                                                ( menu_item_accesos == 'eliminar' &&
                                                menu[i]['eliminar'] == 1 )
                                            ){
                                                $('.menu_item_accesos_sys', this).prop({checked: true});
                                            }
                                        }); 
                                    }
                                }                                
                            }); 
                        }                        
                    }
                    
                });
                
            }else{
                
                $('#myModalWarningBody').html('El usuario no tiene permisos asignados');
                $('#myModalWarning').modal("show"); 
                
                $('input[type="checkbox"]').each(function() {
                    $(this).prop({checked: false}); 
                    $(this).prop({indeterminate: false}); 
                });
            }
        },
        //complete: function(){ } 
    }); 
}

function limpiarFormInsertUser(){
    $('#formInsertUser').trigger("reset");
    
    $( "#usuarioQuery" ).val("");
    $( "#usuario" ).val('');
    $( "#nombre" ).val('');
    
    $('input[type="checkbox"]').each(function() {
        $(this).prop({checked: false}); 
        $(this).prop({indeterminate: false}); 
    });
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    cargaMenu();
    
    $('#limpiarFormInsertUser').click(function(){
        $( "#usuarioQuery" ).val("");
        $("#alert_exito_servicio").hide();
        limpiarFormInsertUser();
    });    
    
    $( "#usuarioQuery" ).autocomplete({
        minLength: 1,
        dataType: 'json',
        source: "util/accesos/autocomplete.php",
        select: function( event, ui ) {
            
            if($('#visualizar').val() == 1){
                $( "#usuarioQuery" ).val( ui.item.usuario );
                $( "#nombre" ).val( ui.item.nombre );
                $( "#usuario" ).val( ui.item.usuario );
                accesoUsuario(ui.item.usuario);
            }else{
                $('#myModalWarningBody').html('Usted no tiene privilegios para visualizar');
                $('#myModalWarning').modal("show"); 
            }
            
            return false;
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ){
        return $( '<li>' )
        .append(  '<div class="list_autocomplete">' + item.usuario + ' - ' + item.nombre + '</div>' )
        .appendTo( ul );
    };
    
    $("#formQueryUser").submit(function(){
        return false;
    });
    
    $("#formInsertUser").submit(function(){
        
        if($('#actualizar').val() == 1){
            
            var usuario = $( "#usuario" ).val();
            var session_usuario = $('#session_usuario').val();

            if(usuario != ''){
                var menuChekeado = [];
                // Por cada menu_sys debo chekearlo
                var existeMenu = false;

                $('.li_menu_sys').each(function(){
                    existeMenu = true;

                    var idmenu = $('.menu_sys', this).val();
                    //console.log($('.menu_sys', this).val());            
                    var checkeado = $('.menu_sys', this).is( ":checked" ) || $('.menu_sys', this).prop("indeterminate") ? 1 : 0;

                    if(checkeado == 1){

                        menuChekeado.push({
                            "idmenu":idmenu,
                            "visualizar":0,
                            "insertar":0,
                            "actualizar":0,
                            "eliminar":0
                        });

                        $('.li_menu_item_sys', this).each(function(){
                            var menu_item = $('.menu_item_sys', this).val();
                            //console.log($('.menu_item_sys', this).val());
                            var checkeado_item = $('.menu_item_sys', this).is( ":checked" ) || $('.menu_item_sys', this).prop("indeterminate") ? 1 : 0;

                            if(checkeado_item == 1){
                                var visualizar = 0;
                                var insertar = 0;
                                var actualizar = 0;
                                var eliminar = 0;

                                $('.li_menu_item_accesos_sys', this).each(function(){
                                    //console.log($('.menu_item_accesos_sys', this).val());  
                                    var menu_item_accesos = $('.menu_item_accesos_sys', this).val();
                                    var checkeado_item_accesos = $('.menu_item_accesos_sys', this).is( ":checked" ) || $('.menu_item_accesos_sys', this).prop("indeterminate") ? 1 : 0;

                                    if( menu_item_accesos == 'visualizar' &&
                                        checkeado_item_accesos == 1 
                                    ){
                                        visualizar = 1;
                                    }else if( menu_item_accesos == 'insertar' &&
                                        checkeado_item_accesos == 1 
                                    ){
                                        insertar = 1;
                                    }else if( menu_item_accesos == 'actualizar' &&
                                        checkeado_item_accesos == 1 
                                    ){
                                        actualizar = 1;
                                    }else if( menu_item_accesos == 'eliminar' &&
                                        checkeado_item_accesos == 1 
                                    ){
                                        eliminar = 1;
                                    }
                                }); 

                                menuChekeado.push({
                                    "idmenu":menu_item,
                                    "visualizar":visualizar,
                                    "insertar":insertar,
                                    "actualizar":actualizar,
                                    "eliminar":eliminar
                                });
                            }
                        }); 

                    }
                });

                //console.log(menuChekeado);
                if(existeMenu == true){
                    $.ajax({
                        //async: false,
                        type: "POST",
                        url: "util/accesos/insert.php",
                        data: {
                            usuario:usuario,
                            session_usuario:session_usuario,
                            menuChekeado:menuChekeado
                        }, 
                        dataType: "json",
                        beforeSend: function(){
                            $("#submitFormInsertUser").html('Actualizando');
                            $('#submitFormInsertUser').prop("disabled", true);
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            document.location = 'accesos';
                        },
                        success: function(respuesta){
                            switch (respuesta.estado){
                                case 1:
                                    $('#myModalSuccessBody').html(respuesta.mensaje);
                                    $('#myModalSuccess').modal("show");                        
                                    limpiarFormInsertUser();
                                    break;
                                case 2:
                                    $('#myModalWarningBody').html(respuesta.mensaje);
                                    $('#myModalWarning').modal("show"); 
                                    break;                    
                                default:
                                    alert('Se ha producido un error');
                                    document.location = 'accesos';
                                    break;
                            } 
                        },
                        complete: function(){
                            $('#submitFormInsertUser').prop("disabled", false);
                            $("#submitFormInsertUser").html('Guardar');  
                        }
                    });
                }else{

                    $('#myModalWarningBody').html('No se cargo el menú, recarge la página');
                    $('#myModalWarning').modal("show"); 

                }
            }else{

                $('#myModalWarningBody').html('Debe seleccionar un usuario');
                $('#myModalWarning').modal("show"); 

            }
        }else{
            
            $('#myModalWarningBody').html('Usted no tiene privilegios para actualizar');
            $('#myModalWarning').modal("show"); 
            
        }
        return false;
    });
});