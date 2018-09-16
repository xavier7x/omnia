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
                case 'bootstrap':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">'; 
                    break; 
                case 'jquery-ui':
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>';
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
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>';
                    break;
                case 'nprogress':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/nprogress/nprogress.css" rel="stylesheet"/>';
                    break;
                case 'icheck':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>';
                    break;
                case 'bootstrap-progressbar':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet"/>';
                    break;
                case 'jqvmap':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>';
                    break;
                case 'bootstrap-daterangepicker':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet"/>';
                    break;
                case 'bootstrap-wysiwyg':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet"/>';
                    break;
                case 'select2':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>';
                    break;
                case 'switchery':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/switchery/dist/switchery.min.css" rel="stylesheet"/>';
                    break;
                case 'starrr':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/starrr/dist/starrr.css" rel="stylesheet"/>';
                    break;
                case 'pnotify':
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/pnotify/dist/pnotify.css" rel="stylesheet"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet"/>';
                    echo '<link href="'.$pdet_valor['hostapp'].'/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet"/>';
                    break;
            }
        }
    ?>
    
    <!-- Custom Theme Style -->
    <link href="<?php echo $pdet_valor['hostapp']; ?>/build/css/custom.min.css" rel="stylesheet"/>
    <link href="<?php echo $pdet_valor['hostapp']; ?>/css/cabpie/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $pdet_valor['hostapp']; ?>/css/<?php echo $pagina; ?>/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
</head>
<body class="nav-md">
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
<!--inicio cuerpo completo nuevo admin-->
<div class="container body">
    <div class="main_container">
        <!--menu left-->
        <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                    <a class="site_title" href="<?php echo $pdet_valor['hostapp']; ?>">
                        <i class="fa fa-cloud"></i> <span><?php echo $pdet_valor['empresa']; ?></span>
                    </a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="images/img.jpg" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Bienvenido,</span>
                        <h2><?php echo $_SESSION['nombre'] ?></h2>
                    </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                    <h3>General</h3>
                    <ul class="nav side-menu">
                            <?php
                            $listaMenu = "";
                            $breadcrumb = array();

                                for($f=0; $f < count($vectorMenu); $f++){
                                    if( $vectorMenu[$f]['es_menu'] == 'SI' ){
                                        
                                        $menuAbierto = '';
                                        $listaMenuInt = '<ul class="nav child_menu">';
                                        
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
                                        
                                        $listaMenu .= '<li '.$menuAbierto.'><a><i class="glyphicon '.$vectorMenu[$f]['icono'].'"></i> ';
                                        $listaMenu .= $vectorMenu[$f]['nombre'].'';
                                        $listaMenu .= '<span class="fa fa-chevron-down"></span></a>';
                                        $listaMenu .= $listaMenuInt;
                                        $listaMenu .= '</li>';
                                    }
                                    
                                }

                            echo $listaMenu;
                            ?>
                        </ul>
                        <!--Ejemplo multinivel no aplicado en el for-->
                        <!--<li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="#level1_1">Level One</a>
                                <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu"><a href="level2.html">Level Two</a>
                                    </li>
                                    <li><a href="#level2_1">Level Two</a>
                                    </li>
                                    <li><a href="#level2_2">Level Two</a>
                                    </li>
                                </ul>
                                </li>
                                <li><a href="#level1_2">Level One</a>
                                </li>
                            </ul>
                        </li>-->                  
                        </ul>
                    </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings" href="<?php echo $pdet_valor['hostapp']; ?>/parametros">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Salir" href="<?php echo $pdet_valor['hostapp']; ?>/util/system/logoutSession.php">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
        </div>
        <!-- fin menu left -->
        <!-- inicio nuevo top navigation -->
        <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="images/img.jpg" alt=""><?php echo $_SESSION['nombre'] ?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <li><a href="<?php echo $pdet_valor['hostapp'].'/cuenta' ?>"> Perfil</a></li>
                            <li>
                            <a href="javascript:;">
                                <span class="badge bg-red pull-right">50%</span>
                                <span>Settings</span>
                            </a>
                            </li>
                            <li><a href="javascript:;">Help</a></li>
                            <li><a href="<?php echo $pdet_valor['hostapp']; ?>/util/system/logoutSession.php"><i class="fa fa-sign-out pull-right"></i> Salir</a></li>
                        </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-green">6</span>
                        </a>
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                            <li>
                            <a>
                                <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                <span>
                                <span>John Smith</span>
                                <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                            </li>
                            <li>
                            <a>
                                <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                <span>
                                <span>John Smith</span>
                                <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                            </li>
                            <li>
                            <a>
                                <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                <span>
                                <span>John Smith</span>
                                <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                            </li>
                            <li>
                            <a>
                                <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                                <span>
                                <span>John Smith</span>
                                <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                            </li>
                            <li>
                            <div class="text-center">
                                <a>
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                            </li>
                        </ul>
                        </li>
                    </ul>
                    </nav>
                </div>
                </div>
        <!-- end nuevo top navigation -->
    </div>

</nav>
<div class="right_col" role="main">
          <!-- top tiles -->
    <div class="page-title">
        <div class="title_left">
            <h3>
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
            </h3> 
        </div>
        <div class="title_right">
            <!--<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                    </span>
                </div>
            </div>-->
        </div>
    </div>
    <div class="clearfix"></div>