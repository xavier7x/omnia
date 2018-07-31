function ajax_download(url, data) {
    var $iframe,
        iframe_doc,
        iframe_html;
    //  Comentar el display none para ver el error style="display: none"
    if (($iframe = $('#download_iframe')).length === 0) {
        $iframe = $('<iframe id="download_iframe"' +
                    ' style="display: none" src="about:blank"></iframe>'
                   ).appendTo("body");
    }

    iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;
    if (iframe_doc.document) {
        iframe_doc = iframe_doc.document;
    }
    
    iframe_html = '<html><head></head><body><form method="POST" action="' + url +'">' 
    
    //console.log(data);

    Object.keys(data).forEach(function(key){        
        if(data[key]!= ''){
            if(typeof data[key] !== 'object'){
                //hidden
                //console.log(data[key]);
                iframe_html += '<input type="hidden" name="'+key+'" value="'+data[key]+'">';                
            }else{                
                Object.keys(data[key]).forEach(function(key2){
                    //console.log(key);
                    //console.log(data[key][key2]);
                    iframe_html += "<input type='hidden' name='"+key+"' value='"+data[key][key2]+"'>";
                });
            }            
        }
    });
    
    iframe_html += '</form></body></html>';
    //console.log(iframe_html);
    iframe_doc.open();
    iframe_doc.write(iframe_html);
    $(iframe_doc).find('form').submit();
}

function mostrarDetalleProducto(idproducto, pagina){
    
     $.ajax({
        async: false,
        type: "POST",
        url: "util/wsproductos/detalleProducto.php",
        data: {
            idproducto: idproducto
        }, 
        dataType: "json",
        //beforeSend: function(){ },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = pagina;
        },
        success: function(respuesta){
            switch (respuesta.estado){
                case 1:
                    var d = new Date();
                    var resultado = respuesta.producto;
                    
                    if(resultado.length > 0){
                        var cuerpo = '<div class="text-center">';

                        if(resultado[0]['tiene_imagen'] == 'SI'){
                            cuerpo += '<img src="../images/productos/'+resultado[0]['idproducto']+'/320x320/'+resultado[0]['nombre_seo']+'.png?v='+d.getTime()+'" class="img-thumbnail"  width="'+$('#param_imgproductoancho').val()+'" height="'+$('#param_imgproductoalto').val()+'" alt="Imagen producto">';  
                        }else{
                            cuerpo += '<img src="../images/productos/0/320x320/error.png?v='+$("#param_webversion").val()+'" class="img-thumbnail"  width="'+$('#param_imgproductoancho').val()+'" height="'+$('#param_imgproductoalto').val()+'" alt="Imagen producto">';  
                        }

                        cuerpo += '</div>';

                        cuerpo += '<div class="text-left">'; 
                        cuerpo += '<div><strong>'+resultado[0]['nombre']+'</strong></div>'; 
                        cuerpo += '<div><span>$ '+resultado[0]['precio']+'</span></div>';                        
                        
                        if(resultado[0]['sku'] != '' && resultado[0]['sku'] != null){
                            cuerpo += '<div><strong>SKU: </strong>'+resultado[0]['sku']+'</div>'; 
                        }
                        
                        cuerpo += '<div><strong>Solo mayor de edad: </strong>'+resultado[0]['mayor_edad']+'</div>'; 
                        
                        cuerpo += '<div><strong>Descripción:</strong></div>'; 
                        cuerpo += '<div>'+resultado[0]['descripcion_corta']+'</div>';
                        
                        if(resultado[0]['descripcion_larga'] != '' && resultado[0]['descripcion_larga'] !== null){
                            cuerpo += '<div><strong>Detalle:</strong></div>'; 
                            cuerpo += '<div>'+resultado[0]['descripcion_larga']+'</div>';
                        }
                        
                        cuerpo += '</div>'; 

                        $("#modalProductoBody").html(cuerpo);
                        $('#modalProducto').modal("show"); 
                    }else{
                        $('#myModalWarningBody').html('Se ha producido un error');
                        $('#myModalWarning').modal("show"); 
                    }
                    
                    break;
                case 2:
                    $('#myModalWarningBody').html(respuesta.mensaje);
                    $('#myModalWarning').modal("show"); 
                    break;                    
                default:
                    alert('Se ha producido un error');
                    document.location = pagina;
                    break;
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
    var param_timeout = $("#param_timeout").val();
    
    var myVar = setInterval(logoutSession, (parseInt(param_timeout) * 60000));
    
    function logoutSession() {
        document.location = 'util/system/logoutSession.php';
    }
    
    if(typeof Highcharts !== 'undefined'){
        
        Highcharts.setOptions({
            lang: {
                loading: 'Cargando...',
                months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                weekdays: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                shortMonths: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                exportButtonTitle: "Exportar",
                printButtonTitle: "Imprimir"
            }
        });
        
    }
    
});