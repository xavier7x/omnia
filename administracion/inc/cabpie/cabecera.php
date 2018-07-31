<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $varAcceso['nombre']; ?> | <?php echo $pdet_valor['empresa']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="google-site-verification" content="E6EbZNZHTInv3_xwF1qEXghhp9G5YUo0cjkhbfwcZK8" />
    <meta name="description" content="Administración integral del sistema de pedidos online Marketton, en donde gestionara los productos, pedidos, facturas, bodegas, etc.">
    <meta name='author' content='marketton.com'>
    <meta name='owner' content='Lcdo. Michael Jonathan Rodríguez Coello'>
    <meta name="robots" content="index, follow">
    
    <link href="images/system/favicon.ico?v=<?php echo $pdet_valor['webversion']; ?>" rel="icon" type="image/x-icon"/>
    
    <?php
        for($f=0; $f<count($varAcceso['framework']); $f++){
            switch($varAcceso['framework'][$f]){ 
                case 'jquery-ui':
                    echo '<link href="lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'bootstrap':
                    echo '<link href="lib/css/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="lib/css/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'bootstrap-datepicker':
                    echo '<link href="lib/js/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'jqgrid':
                    echo '<link href="lib/js/Guriddo_jqGrid_JS_5.1.1/css/ui.jqgrid-bootstrap.css" rel="stylesheet" type="text/css"/>';
                    break;
                case 'jquery-treeview':
                    echo '<link href="lib/js/jzaefferer-jquery-treeview/jquery.treeview.css" rel="stylesheet" type="text/css"/>';
                    break;
            }
        }
    ?>
    
    <link href="css/cabpie/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
    <link href="css/<?php echo $pagina; ?>/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
</head>
<body>
<!-- Parametros del usuario sesion -->
<input type="hidden" id="session_usuario" value="<?php echo $_SESSION['usuario']; ?>">
<!-- Parametros de la pagina -->
<input type="hidden" id="visualizar" value="<?php echo $varAcceso['visualizar']; ?>">
<input type="hidden" id="insertar" value="<?php echo $varAcceso['insertar']; ?>">
<input type="hidden" id="actualizar" value="<?php echo $varAcceso['actualizar']; ?>">
<input type="hidden" id="eliminar" value="<?php echo $varAcceso['eliminar']; ?>">
<!--------------------------------->
<!-- Parametros de la aplicacion -->
<input type="hidden" id="param_timeout" value="<?php echo $pdet_valor['timeout']; ?>">
<input type="hidden" id="param_empresa" value="<?php echo $pdet_valor['empresa']; ?>">
<input type="hidden" id="param_paginacion" value="<?php echo $pdet_valor['paginacion']; ?>">
<input type="hidden" id="param_imgproductoext" value="<?php echo $pdet_valor['imgproductoext']; ?>">
<input type="hidden" id="param_imgproductopeso" value="<?php echo $pdet_valor['imgproductopeso']; ?>">
<input type="hidden" id="param_imgproductoalto" value="<?php echo $pdet_valor['imgproductoalto']; ?>">
<input type="hidden" id="param_imgproductoancho" value="<?php echo $pdet_valor['imgproductoancho']; ?>">
<input type="hidden" id="fecha_servidor" value="<?php echo date('r') ?>">
<!-- Modal Producto -->
<div class="modal fade" id="modalProducto" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-primary">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalProductoTitle">PRODUCTO</h4>
            </div>
            <div class="modal-body" id="modalProductoBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
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
<!--------------------------------->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="<?php echo $pdet_valor['hostapp']; ?>">
                <img src="images/system/logo.png?v=<?php echo $pdet_valor['webversion']; ?>" alt="<?php echo $pdet_valor['empresa']; ?>" width="80" />
            </a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <?php
                $listaMenu = "";
                $breadcrumb = array();

                    for($f=0; $f < count($vectorMenu); $f++){
                        if( $vectorMenu[$f]['es_menu'] == 'SI' ){
                            
                            $menuAbierto = '';
                            $listaMenuInt = '<ul class="dropdown-menu">';
                            
                            // Itero sobre el mismo vector y coloco los subitems
                            for($i=0; $i < count($vectorMenu); $i++){
                                if($vectorMenu[$i]['es_menu'] == 'NO' && $vectorMenu[$i]['idpadre'] == $vectorMenu[$f]['idmenu']){
                                    // Verifico si el menu se mantiene activo
                                    if( $pagina == $vectorMenu[$i]['ventana'] ){
                                        $menuAbierto = 'class="active"';
                                        $breadcrumb[] = $vectorMenu[$f]['nombre'];
                                        $breadcrumb[] = $vectorMenu[$i]['nombre'];
                                    }
                                    
                                    $listaMenuInt .= '<li><a href="'.$vectorMenu[$i]['ventana'].'">';
                                    $listaMenuInt .= '<span class="glyphicon '.$vectorMenu[$i]['icono'].'"></span> ';
                                    $listaMenuInt .= $vectorMenu[$i]['nombre'];
                                    $listaMenuInt .= '</a></li>';
                                }
                            }
                            
                            $listaMenuInt .= '</ul>';
                            
                            $listaMenu .= '<li '.$menuAbierto.'><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                            $listaMenu .= '<span class="glyphicon '.$vectorMenu[$f]['icono'].'"></span> ';
                            $listaMenu .= $vectorMenu[$f]['nombre'].' <b class="caret"></b></a>';
                            $listaMenu .= $listaMenuInt;
                            $listaMenu .= '</li>';
                        }
                    }
                
                echo $listaMenu;
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a>Bienvenido <b><?php echo $_SESSION['nombre'] ?></b></a></li>
                <li><a href="util/system/logoutSession.php"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
            </ul>
        </div>
    </div>
</nav>    
<div class="container">
    <ul class="breadcrumb">
        <?php
        $breadcrumbText = '';
        for($f = 0; $f < count($breadcrumb); $f++){
            if(($f + 1) == count($breadcrumb)){
                $breadcrumbText .= '<li class="active">'.$breadcrumb[$f].'</li>';
            }else{
                $breadcrumbText .= '<li><a href="#">'.$breadcrumb[$f].'</a></li>';
            }
        }
        echo $breadcrumbText;
        ?>
    </ul> 