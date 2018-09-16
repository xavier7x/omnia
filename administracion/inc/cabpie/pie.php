        <!-- footer content -->
        <footer>
          <div class="pull-right">
          <?php echo $pdet_valor['empresa']; ?> - Derechos adquiridos por <a href="#"><?php echo $pdet_valor['empresa']; ?></a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <?php
        for($f=0; $f<count($varAcceso['framework']); $f++){
            switch($varAcceso['framework'][$f]){       
                case 'jquery':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/jquery/jquery-2.2.4.min.js"></script>';
                    break;
                case 'jquery-ui':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/jquery-ui/jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>';
                    break;
                case 'bootstrap':
                    //echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'lib/css/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/bootstrap/dist/js/bootstrap.min.js"></script>';
                    break;
                case 'bootstrap-datepicker':                    
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js"></script>';
                    break;                    
                case 'bootboxjs':                    
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/bootboxjs/bootbox.min.js"></script>';
                    break;
                case 'jqgrid':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/Guriddo_jqGrid_JS_5.1.1/js/i18n/grid.locale-es.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/Guriddo_jqGrid_JS_5.1.1/js/jquery.jqGrid.min.js"></script>';
                    break;
                case 'jquery-treeview':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/jzaefferer-jquery-treeview/jquery.treeview.js"></script>';
                    break;
                case 'highcharts':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/Highcharts-4.2.5/js/highcharts.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/lib/js/Highcharts-4.2.5/js/modules/exporting.js"></script>';
                    break;
                case 'fastclick':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/fastclick/lib/fastclick.js"></script>';
                    break;
                case 'chart-js':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/Chart.js/dist/Chart.min.js"></script>';
                    break;
                case 'gauge-js':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/gauge.js/dist/gauge.min.js"></script>';
                    break;
                case 'bootstrap-progressbar':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>';
                    break;
                case 'icheck':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/iCheck/icheck.min.js"></script>';
                    break;
                case 'skycons':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/skycons/skycons.js"></script>';
                    break;
                case 'flot':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/skycons/skycons.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/Flot/jquery.flot.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/Flot/jquery.flot.pie.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/Flot/jquery.flot.time.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/Flot/jquery.flot.stack.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/Flot/jquery.flot.resize.js"></script>';
                    /*Flot plugins*/
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>';
                    echo '<script src="'.$pdet_valor['hostapp'].'/vendors/flot.curvedlines/curvedLines.js"></script>';
                    break;
                case 'datejs':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/DateJS/build/date.js"></script>';
                    break;
                case 'jqvmap':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/jqvmap/dist/jquery.vmap.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>';
                    break;
                case 'bootstrap-daterangepicker':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/moment/min/moment.min.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>';
                    break;
                case 'bootstrap-wysiwyg':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/google-code-prettify/src/prettify.js"></script>';
                    break;
                case 'jquerytaginput':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>';
                    break;
                case 'switchery':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/switchery/dist/switchery.min.js"></script>';
                    break;
                case 'select2':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/select2/dist/js/select2.full.min.js"></script>';
                    break;
                case 'parsley':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/parsleyjs/dist/parsley.min.js"></script>';
                    break;
                case 'autosize':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'vendors/autosize/dist/autosize.min.js"></script>';
                    break;
                case 'jqueryautocomplete':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>';
                    break;
                case 'starrr':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/starrr/dist/starrr.js"></script>';
                    break;
                case 'pnotify':
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/pnotify/dist/pnotify.js"></script>';
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/pnotify/dist/pnotify.buttons.js"></script>';            
                    echo '<script type="text/javascript" language="javascript" src="'.$pdet_valor['hostapp'].'/vendors/pnotify/dist/pnotify.nonblock.js"></script>';
                    break;
            }
        }
    ?>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo $pdet_valor['hostapp']; ?>/build/js/custom.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo $pdet_valor['hostapp']; ?>/js/cabpie/funciones.js?v=<?php echo $pdet_valor['webversion']; ?>"></script>
    <script type="text/javascript" language="javascript" src="<?php echo $pdet_valor['hostapp']; ?>/js/<?php echo $pagina; ?>/funciones.js?v=<?php echo $pdet_valor['webversion']; ?>"></script>
</body>
</html>