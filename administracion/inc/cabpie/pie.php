</div>
<footer class="text-center">
    <hr/>
    <img src="images/system/logo.png?v=<?php echo $pdet_valor['webversion']; ?>" alt="<?php echo $pdet_valor['empresa']; ?>" width="200" />
</footer>

<?php
    for($f=0; $f<count($varAcceso['framework']); $f++){
        switch($varAcceso['framework'][$f]){       
            case 'jquery':
                echo '<script type="text/javascript" language="javascript" src="lib/js/jquery/jquery-2.2.4.min.js"></script>';
                break;
            case 'jquery-ui':
                echo '<script type="text/javascript" language="javascript" src="lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>';
                break;
            case 'bootstrap':
                echo '<script type="text/javascript" language="javascript" src="lib/css/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>';
                break;
            case 'bootstrap-datepicker':                    
                echo '<script type="text/javascript" language="javascript" src="lib/js/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>';
                echo '<script type="text/javascript" language="javascript" src="lib/js/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js"></script>';
                break;                    
            case 'bootboxjs':                    
                echo '<script type="text/javascript" language="javascript" src="lib/js/bootboxjs/bootbox.min.js"></script>';
                break;
            case 'jqgrid':
                echo '<script type="text/javascript" language="javascript" src="lib/js/Guriddo_jqGrid_JS_5.1.1/js/i18n/grid.locale-es.js"></script>';
                echo '<script type="text/javascript" language="javascript" src="lib/js/Guriddo_jqGrid_JS_5.1.1/js/jquery.jqGrid.min.js"></script>';
                break;
            case 'jquery-treeview':
                echo '<script type="text/javascript" language="javascript" src="lib/js/jzaefferer-jquery-treeview/jquery.treeview.js"></script>';
                break;
            case 'highcharts':
                echo '<script type="text/javascript" language="javascript" src="lib/js/Highcharts-4.2.5/js/highcharts.js"></script>';
                echo '<script type="text/javascript" language="javascript" src="lib/js/Highcharts-4.2.5/js/modules/exporting.js"></script>';
                break;
        }
    }
?>

<script type="text/javascript" language="javascript" src="js/cabpie/funciones.js?v=<?php echo $pdet_valor['webversion']; ?>"></script>
<script type="text/javascript" language="javascript" src="js/<?php echo $pagina; ?>/funciones.js?v=<?php echo $pdet_valor['webversion']; ?>"></script>
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