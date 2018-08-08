<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">    
    <!-- Title
    Entre 10 y 70 carateres y sera lo que se mostrara en google
    -->
    <title><?php echo ucfirst(strtolower($varAcceso['nombre'])); ?> | <?php echo $pdet_valor['empresa']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="google-site-verification" content="E6EbZNZHTInv3_xwF1qEXghhp9G5YUo0cjkhbfwcZK8" />
    <meta name="msvalidate.01" content="B8933CDE0C76736CF865DAC3EE3CB460" />
    <meta name="keywords" content="<?php echo $varAcceso['meta_keywords']; ?>">
    <link rel="alternate" href="<?php echo $pdet_valor['hostapp']?>" hreflang="es-ec">
    <!-- Description
    La descripcion que se vera en el motor de busqueda, entre 70 y 160 caracteres
    -->    
    <meta name="description" content="<?php echo $varAcceso['meta_description']; ?>">
    <meta name='author' content='marketton.com'>
    <meta name='owner' content='Lcdo. Michael Jonathan Rodríguez Coello'>
    <meta name="robots" content="index, follow">
    
    <!--meta etiquetas para que facebook reconozca las imagenes y titulos-->
    <?php
    if(count($globalProducto) > 0 && file_exists('images/productos/'.$globalProducto['idproducto'].'/320x320/'.$globalProducto['nombre_seo'].'.png')){
        $imgProShared = $pdet_valor['hostapp'].'/images/productos/'.$globalProducto['idproducto'].'/320x320/'.$globalProducto['nombre_seo'].'.png?v='.$pdet_valor['webversion'];                        
    }
    ?>
    
    <!--smarttlook para grabar a los visitantes-->
    <script type="text/javascript">
    window.smartlook||(function(d) {
    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
    c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
    })(document);
    smartlook('init', '023df0bcbef0b7bb46df8d9182905219792a9d8d');
    </script>
    <!--fin smartlook-->
    <!-- integracion facebook sdk javascript-->
    <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId            : '868889376642502',
              autoLogAppEvents : true,
              xfbml            : true,
              version          : 'v3.0'
            });
          };
        
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "https://connect.facebook.net/en_ES/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
    </script>
    <!--sdk de facebook-->
    
    
    <!-- fin integracion facebook sdk-->
    <meta property="fb:app_id" content="868889376642502" />
    <meta property="og:title" content="<?php echo $varAcceso['meta_description']; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $pdet_valor['hostapp'].$_SERVER["REQUEST_URI"]; ?>"/>
    <meta property="og:image" content="<?php echo $imgProShared ?>" />
    <meta property="og:description" content="<?php echo $varAcceso['meta_description']; ?>" />
    <!-- fin metaetiquetas de facebook-->
    
    <!--meta etiquetas para que google reconozca las imagenes y titulos-->
        <meta itemprop="name" content="<?php echo $varAcceso['meta_description']; ?>">
        <meta itemprop="description" content="<?php echo $varAcceso['meta_description']; ?>">
        <meta itemprop="image" content="<?php echo $imgProShared ?>">
    <!-- fin de metaetiquetas google-->
    
    
    <link href="<?php echo $pdet_valor['hostapp']; ?>/images/system/favicon.ico?v=<?php echo $pdet_valor['webversion']; ?>" rel="icon" type="image/x-icon"/>
    
    <?php
        for($f=0; $f<count($varAcceso['framework']); $f++){
            switch($varAcceso['framework'][$f]){ 
                case 'jquery-ui':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'bootstrap':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/css/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/css/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'bootstrap-datepicker':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'jqgrid':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/Guriddo_jqGrid_JS_5.1.1/css/ui.jqgrid-bootstrap.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'jquery-treeview':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jzaefferer-jquery-treeview/jquery.treeview.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'font-awesome':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'jstarbox':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jStarbox-master/css/jstarbox.css" rel="stylesheet" type="text/css"/>';
                    break;
            }
        }
    ?>
    
    
    <link href="<?php echo $pdet_valor['hostapp']; ?>/css/<?php echo $varAcceso['ventana']; ?>/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $pdet_valor['hostapp']; ?>/css/cabpie/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
    
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
    
</head>
<body>
    <!--messenger en mi web funciona con sdk de facebook instanciado en la cabecera del sitio-->
    <!-- Load Facebook SDK for JavaScript -->
    <!-- url documentation: https://developers.facebook.com/docs/messenger-platform/reference/web-plugins/#customer_chat -->
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/es_LA/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Your customer chat code -->
    <div class="fb-customerchat"
    attribution=setup_tool
    page_id="859854860811105"
    theme_color="#0084ff"
    logged_in_greeting="¡Hola! como podemos ayudarte?"
    logged_out_greeting="¡Hola! como podemos ayudarte?"
    greeting_dialog_display=fade>
    </div>
    <!--end messenger-->
    <!-- Parametros de sesion de la aplicacion -->
    <input type="hidden" id="session_usuario" value="<?php echo $_SESSION['usuario']; ?>">
    <input type="hidden" id="session_tipocliente" value="<?php echo $_SESSION['tipocliente']; ?>">
    <input type="hidden" id="session_idsector" value="<?php echo $_SESSION['idsector']; ?>">
    <!-- Parametros de la aplicacion -->
    <input type="hidden" id="param_hostapp" value="<?php echo $pdet_valor['hostapp']; ?>">
    <input type="hidden" id="param_totalvistaproductos" value="<?php echo $pdet_valor['totalvistaproductos']; ?>">
    <input type="hidden" id="param_timeout" value="<?php echo $pdet_valor['timeout']; ?>">
    <input type="hidden" id="param_paginacion" value="<?php echo $pdet_valor['paginacion']; ?>">
    <input type="hidden" id="param_webversion" value="<?php echo $pdet_valor['webversion']; ?>">
    <input type="hidden" id="param_empresa" value="<?php echo $pdet_valor['empresa']; ?>">
    <input type="hidden" id="param_pagostranscuenta" value="<?php echo $pdet_valor['pagostranscuenta']; ?>">
    <input type="hidden" id="param_pagostransdocumento" value="<?php echo $pdet_valor['pagostransdocumento']; ?>">
    <input type="hidden" id="param_pagostransentidad" value="<?php echo $pdet_valor['pagostransentidad']; ?>">
    <input type="hidden" id="param_pagostransmail" value="<?php echo $pdet_valor['pagostransmail']; ?>">
    <input type="hidden" id="param_pagostransnombre" value="<?php echo $pdet_valor['pagostransnombre']; ?>">
    <input type="hidden" id="param_pagostranstipocuenta" value="<?php echo $pdet_valor['pagostranstipocuenta']; ?>">
    <!-- Parametros del servidor de la aplicacion -->
    <input type="hidden" id="fecha_servidor" value="<?php echo date('r') ?>">
<!-- Modal Peligro -->
<div class="modal fade" id="myModalDanger" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalDangerTitle">Peligro</h4>
            </div>
            <div class="modal-body text-center" id="myModalDangerBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Advertencia -->
<div class="modal fade" id="myModalWarning" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalWarningTitle">Advertencia</h4>
            </div>
            <div class="modal-body text-center" id="myModalWarningBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Exito -->
<div class="modal fade" id="myModalSuccess" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalSuccessTitle">Éxito</h4>
            </div>
            <div class="modal-body text-center" id="myModalSuccessBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!---------- HEADER ------------>
    <a href="<?php echo $pdet_valor['hostapp']; ?>/alimentos-y-bebidas/bebidas-hidratantes">
        <img src="<?php echo $pdet_valor['hostapp']; ?>/images/system/ofertas.png?v=<?php echo $pdet_valor['webversion']; ?>" class="img-head" alt="Ofertas">
    </a>
    <div class="header">
        <div class="container">
            <div class="logo">
                <h1>
                    <a href="<?php echo $pdet_valor['hostapp']; ?>">
                        <img src="<?php echo $pdet_valor['hostapp']; ?>/images/system/logo.png?v=<?php echo $pdet_valor['webversion']; ?>" width="240" height="90" class="img-responsive" alt="<?php echo $pdet_valor['empresa']; ?>">
                        <span>Supermercado online a domicilio</span>
                    </a>
                </h1>
            </div>
            <div class="head-t">
                <ul class="card">                
                    <?php if( $_SESSION['tipocliente'] != 'visitante' ){ ?>                    
                    <li><a href="<?php echo $pdet_valor['hostapp']; ?>/pedidos" ><i class="fa fa-file-text-o" aria-hidden="true"></i><?php echo $menuSys['pedidos']['nombre']; ?></a></li>
                    <li><a href="<?php echo $pdet_valor['hostapp']; ?>/cuenta" ><i class="fa fa-user-circle-o" aria-hidden="true"></i><?php echo $menuSys['cuenta']['nombre']; ?></a></li>
                    <li><a href="<?php echo $pdet_valor['hostapp']; ?>/util/system/logoutSession.php" ><i class="fa fa-sign-out" aria-hidden="true"></i>Cerrar sesión</a></li>
                    <?php }else{ ?>
                    <li><a href="<?php echo $pdet_valor['hostapp']; ?>/registro" ><i class="fa fa-user" aria-hidden="true"></i><?php echo $menuSys['registro']['nombre']; ?></a></li>   
                    <li><a href="<?php echo $pdet_valor['hostapp']; ?>/login" ><i class="fa fa-sign-in" aria-hidden="true"></i><?php echo $menuSys['login']['nombre']; ?></a></li>             
                    <?php } ?>
                    <li><a href="<?php echo $pdet_valor['hostapp']; ?>/carrito" ><i class="fa fa-shopping-cart" aria-hidden="true"></i><?php echo $menuSys['carrito']['nombre']; ?></a></li>  
                </ul>	
            </div>
            <div class="header-ri">
                <ul class="social-top">
                    <li><a rel="nofollow" href="https://www.facebook.com/markettonec" target="_blank" class="icon facebook"><i class="fa fa-facebook" aria-hidden="true"></i><span></span></a></li>
                    <li><a rel="nofollow" href="https://twitter.com/markettonec" target="_blank" class="icon twitter"><i class="fa fa-twitter" aria-hidden="true"></i><span></span></a></li>
                    <li><a rel="nofollow" href="https://www.instagram.com/markettonec/" target="_blank" class="icon instagram"><i class="fa fa-instagram" aria-hidden="true"></i><span></span></a></li>
                    <li class="mkt-cnt-whatsapp"><a rel="nofollow" href="intent://send/<?php echo $pdet_valor['whatsapp']; ?>#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" target="_blank" class="icon whatsapp"><i class="fa fa-whatsapp" aria-hidden="true"></i><span></span></a></li>
                </ul>	
            </div>
            <div class="nav-top">
                <nav class="navbar navbar-default">
                <div class="navbar-header nav_2">
                    <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> 
                <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
                    <ul class="nav navbar-nav">
                        <li class="<?php echo ( $varAcceso['ventana'] == 'inicio' ? "active" : "" ); ?>"><a href="<?php echo $pdet_valor['hostapp']; ?>" class="hyper"><span><?php echo $menuSys['inicio']['nombre']; ?></span></a></li>
                        <?php
                        
                        for($f=0; $f < count($vectorMenu); $f++){
                            $menuCatAct = ''; 
                            $menuCatInt = '';                            
                            $subDiv = (int)ceil(count($vectorMenu[$f]['subcategorias']) / 3);                            
                            
                            // Validar si el menu seleccionado
                            if( 
                                count($globalCategoria) > 0 && 
                                $vectorMenu[$f]['nombre_seo'] == $globalCategoria['nombre_seo'] 
                            ){
                                $menuCatAct = 'active'; 
                            }
                            
                            $cntCatInt = 1;
                            $flaSubAbierta = false;
                            $columnasAbi = 0;
                            
                            for($i=0; $i < count($vectorMenu[$f]['subcategorias']); $i++){
                                
                                // Si esta cerrada abrirla
                                if($flaSubAbierta == false){
                                    $menuCatInt .= '<div class="col-sm-3">';
                                    $menuCatInt .= '<ul class="multi-column-dropdown">';
                                    
                                    $columnasAbi++;
                                    $flaSubAbierta = true;
                                }
                                
                                // Escribir el item
                                $menuCatInt .= '<li><a href="'.$pdet_valor['hostapp'].'/'.$vectorMenu[$f]['nombre_seo'].'/'.$vectorMenu[$f]['subcategorias'][$i]['nombre_seo'].'">';
                                $menuCatInt .= '<i class="fa fa-angle-right" aria-hidden="true">';
                                $menuCatInt .= '</i>'.$vectorMenu[$f]['subcategorias'][$i]['nombre'].'</a></li>';
                                
                                // si esta abierta y solo contine un item o ya tiene el tamaño cerrarlo
                                if(
                                    $flaSubAbierta == true && (                                    
                                        ( $i + 1 ) == count($vectorMenu[$f]['subcategorias']) || 
                                        $cntCatInt == $subDiv
                                    )
                                ){
                                    $menuCatInt .= '</ul>';
                                    $menuCatInt .= '</div>';
                                    
                                    $cntCatInt = 1;
                                    $flaSubAbierta = false;
                                }else{
                                    $cntCatInt++;
                                }
                                
                                // Añadir las columnas que faltaron de 3
                                if( ( $i + 1 ) == count($vectorMenu[$f]['subcategorias']) ){
                                    for( $c=$columnasAbi ; $c < 3; $c++ ){
                                        $menuCatInt .= '<div class="col-sm-3"></div>';
                                    }
                                }
                                
                            }
                            
                            if( !empty($menuCatInt) ){
                                $menuCatInt .= '<div class="col-sm-3 w3l">';
                                $menuCatInt .= '<a href="'.$pdet_valor['hostapp'].'/'.$vectorMenu[$f]['nombre_seo'].'"><img src="'.$pdet_valor['hostapp'].'/images/categorias/'.$vectorMenu[$f]['nombre_seo'].'.png?v='.$pdet_valor['webversion'].'" class="img-responsive" alt="'.$vectorMenu[$f]['nombre'].'"></a>';
                                $menuCatInt .= '</div>';
                            }
                            
                            $menuCat = '<li class="dropdown '.$menuCatAct.'">';
                            $menuCat .= '<a href="#" class="dropdown-toggle  hyper" data-toggle="dropdown"><span>';
                            $menuCat .= ucfirst(strtolower($vectorMenu[$f]['nombre']));
                            $menuCat .= '<b class="caret"></b></span></a>';
                            $menuCat .= '<ul class="dropdown-menu multi">';
                            $menuCat .= '<div class="row">';
                            $menuCat .= $menuCatInt;
                            $menuCat .= '<div class="clearfix"></div>';
                            $menuCat .= '</div>';
                            $menuCat .= '</ul>';
                            $menuCat .= '</li>';
                            
                            // Imprimir la categoria solo si tiene subcategorias                            
                            if( !empty($menuCatInt) ){
                                echo $menuCat;
                            }
                                                        
                        } 
                        
                        ?>
                        <li class="<?php echo ( $varAcceso['ventana'] == 'contacto' ? "active" : "" ); ?>"><a href="<?php echo $pdet_valor['hostapp']; ?>/contacto" class="hyper"><span><?php echo $menuSys['contacto']['nombre']; ?></span></a></li>
                    </ul>
                </div>
                </nav>
                <div class="cart">
                    <a href="<?php echo $pdet_valor['hostapp']; ?>/carrito" >
                        <span class="fa fa-shopping-cart my-cart-icon"><span id="mkt-total-productos" class="badge badge-notify my-cart-badge">
                            <?php
                            $resultadoCabTotal = $conexion->DBConsulta("
                                SELECT IFNULL(SUM(cantidad),0) AS total
                                FROM cli_carrito_detalle 
                                WHERE usuario = '".$_SESSION['usuario']."'
                            ");

                            foreach($resultadoCabTotal as $filaCabTotal){
                                echo $filaCabTotal['total'];
                            }
                            ?>
                        </span></span>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>			
    </div>    
<!---------- END HEADER ------------>