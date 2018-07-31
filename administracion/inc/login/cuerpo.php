<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración | <?php echo $pdet_valor['empresa']; ?></title>    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="google-site-verification" content="E6EbZNZHTInv3_xwF1qEXghhp9G5YUo0cjkhbfwcZK8" />
    <meta name="keywords" content="administracion del sistema marketton, administracion del proceso de los pedidos">
    <meta name="description" content="Administración integral del sistema de pedidos online Marketton, en donde gestionara los productos, pedidos, facturas, bodegas, etc.">
    <meta name='author' content='marketton.com'>
    <meta name='owner' content='Lcdo. Michael Jonathan Rodríguez Coello'>
    <meta name="robots" content="index, follow">
    
    <link href="images/system/favicon.ico?v=<?php echo $pdet_valor['webversion']; ?>" rel="icon" type="image/x-icon"/>
    <link href="lib/css/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="lib/css/bootstrap-3.3.6-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/login/style.css?v=<?php echo $pdet_valor['webversion']; ?>" rel="stylesheet" type="text/css"/>
</head>
<body>
<!-- Parametros de la aplicacion -->    
<input type="hidden" id="param_empresa" value="<?php echo $pdet_valor['empresa']; ?>">
    
<!-- Modal -->
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
<!-- Modal -->
<div class="modal fade" id="myModalSuccess" role="dialog">
    <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalSuccessTitle">Éxito</h4>
            </div>
            <div class="modal-body text-center" id="myModalSuccessBody"></div>
        </div>
    </div>
</div>
    
    <div class="container">
        
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="page-header text-center">
                        <h1>Acceso al sistema</h1>
                    </div>
                    <div class="alert alert-info text-center">
                        <blink>
                            Bienvenido al sistema administrador de <b><?php echo $pdet_valor['empresa']; ?></b>.
                        </blink>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Ingrese sus datos para acceder al Sistema <b class="pull-right"></b>
                        </div>
                        <div class="panel-body">
                        <form role="form" id="formLogin" class="form-horizontal">
                            <div class="form-group">
                                <label for="usuario" class="col-sm-2 control-label">Usuario</label>
                                <div class="col-sm-10">
                                    <input autocomplete="off" type="text" id="usuario" class="form-control" placeholder="Ingrese su nombre de usuario" required/>            
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contrasena" class="col-sm-2 control-label">Contraseña</label>
                                <div class="col-sm-10">
                                    <input autocomplete="off" type="password" id="contrasena" class="form-control" placeholder="Ingrese su contraseña" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10 text-right">
                                    <button type="submit" id="submitFormLogin" class="btn btn-info">Acceder</button>
                                </div>
                            </div>
                        </form>						  
                        </div>
                    </div>
                </div>
                <div class="col-sm-3"></div>
                </div>
            </div>
        </div>
        <footer class="text-center">
			<hr/>
			<img src="images/system/logo.png?v=<?php echo $pdet_valor['webversion']; ?>" alt="<?php echo $pdet_valor['empresa']; ?>" width="200" />
	    </footer>
    </div>

<script type="text/javascript" language="javascript" src="lib/js/jquery/jquery-2.2.4.min.js"></script>
<script type="text/javascript" language="javascript" src="lib/css/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="js/login/funciones.js?v=<?php echo $pdet_valor['webversion']; ?>"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-84967671-1', 'auto');
  ga('send', 'pageview');
</script>
</body>
</html>