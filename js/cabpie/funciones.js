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

function hideURLbar(){ 
    window.scrollTo(0,1); 
}

function goToCartIcon(idproducto, cantidad, image, usuario, buttonProducto){
    var $cartIcon = $(".my-cart-icon");
    var $image = $('<img width="30px" height="30px" src="' + image+ '"/>').css({"position": "absolute", "z-index": "999"});
    //console.log($image);
    $(buttonProducto).before($image);
    var position = $cartIcon.position();
    //console.log(position);
    
    $image.animate({
        top: position.top,
        left: position.left
    }, 500 , "linear", function() {
        $image.remove();
    });
    
    var idsector = $("#session_idsector").val();
    
    $.ajax({
        //async: false,
        type: "POST",
        url: $("#param_hostapp").val() + '/util/wsproductos/addproduct.php',
        data: {
            usuario: usuario,
            idproducto: idproducto,
            cantidad: cantidad,
            idsector: idsector
        }, 
        dataType: "json",
        //beforeSend: function(){ },
        error: function (request, status, error) {
            console.log(request.responseText);
            document.location = $("#param_hostapp").val();
        },
        success: function(respuesta){
            switch (respuesta.estado){
                case 1:
                    $("#mkt-total-productos").html(respuesta.total);

                    break;
                case 2:
                    alert(respuesta.mensaje);
                    //$('#myModalWarningBody').html(respuesta.mensaje);
                    //$('#myModalWarning').modal("show"); 
                    break;                    
                default:
                    alert('Se ha producido un error');
                    document.location = $("#param_hostapp").val();
                    break;
            }
        },
        //complete: function(){ }
    });        
  
}

function verificarMobile(){
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    
    if( !isMobile.any() ){
        $( ".mkt-cnt-whatsapp" ).hide();
    };
    
    //if( isMobile.iOS() ) alert('iOS');
}
/***********************************************************/
/*                                                         */
/*                   DOCUMENTO - READY                     */
/*                                                         */
/***********************************************************/
$(document).ready(function() {
    
    setTimeout(hideURLbar, 0);
    
    verificarMobile();
    
    $(".scroll").click(function(event){
        event.preventDefault();
        $('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
    });
    
    $().UItoTop({ easingType: 'easeOutQuart' });   
    
    jQuery('.starbox').each(function() {
        var starbox = jQuery(this);
            starbox.starbox({
            average: starbox.attr('data-start-value'),
            changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
            ghosting: starbox.hasClass('ghosting'),
            autoUpdateAverage: starbox.hasClass('autoupdate'),
            buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
            stars: starbox.attr('data-star-count') || 5
            }).bind('starbox-value-changed', function(event, value) {
            if(starbox.hasClass('random')) {
            var val = Math.random();
            starbox.next().text(' '+val);
            return val;
            } 
        })
    });

    $('.my-cart-btn').on('click', function (event) {
        var idproducto = this.dataset.idproducto; 
        var cantidad = this.dataset.cantidad;
        var image = this.dataset.image;
        var usuario = $("#session_usuario").val();
        
        goToCartIcon(idproducto, cantidad, image, usuario, $(this));
    });
    
    $('.mtk-abre-modal').on('click', function (event) {
        var idproducto = this.dataset.idproducto;
        var usuario = $("#session_usuario").val();
        
        $.ajax({
            //async: false,
            type: "POST",
            url: $("#param_hostapp").val() + '/util/wsproductos/detalleProducto.php',
            data: {
                usuario:usuario,
                idproducto: idproducto
            }, 
            dataType: "json",
            //beforeSend: function(){ },
            error: function (request, status, error) {
                console.log(request.responseText);
                document.location = $("#param_hostapp").val();
            },
            success: function(respuesta){
                switch (respuesta.estado){
                    case 1:
                        

                        break;
                    case 2:
                        alert(respuesta.mensaje);
                        break;                    
                    default:
                        alert('Se ha producido un error');
                        document.location = $("#param_hostapp").val();
                        break;
                }
            },
            //complete: function(){}
        });
    });
    
});