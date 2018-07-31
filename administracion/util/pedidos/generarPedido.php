<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

// Extraer los parametros

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM sys_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************

$usuario = "";
$idpedido = 0;

// Establece si se imprime con borde o no
$boder = 0;
$altoItem = 5;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idpedido']) && !empty($_POST['idpedido'])) 
){
    $usuario = $_POST['usuario'];
    $idpedido = $_POST['idpedido']; 
}

if(
    !empty($usuario) && 
    !empty($idpedido) 
){

    $cntGenerado = 0;
        
    if(
        file_exists('../../docped/'.$idpedido.'.pdf')
    ){
        $cntGenerado++;
    }
    
    if( $cntGenerado == 0 ){
        
        $cabecera = array();
        $detalle = array();
        
        // Extraer los datos de cabecera
        
        $resultado = $conexion->DBConsulta("
            SELECT * 
            FROM cli_pedido_cabecera
            WHERE idpedido = '".$idpedido."'
            LIMIT 1
        ");

        foreach($resultado as $fila){
            $cabecera = $fila;
        }

        // Extraer los datos del detalle

        $resultado = $conexion->DBConsulta("
            SELECT * 
            FROM cli_pedido_detalle
            WHERE idpedido = '".$idpedido."'
        ");

        foreach($resultado as $fila){
            
            $fila['sku'] = $fila['idproducto'];
            
            $resultadoInt = $conexion->DBConsulta("
                SELECT sku 
                FROM productos
                WHERE idproducto = '".$fila['idproducto']."'
                LIMIT 1
            ");

            foreach($resultadoInt as $filaInt){
                $fila['sku'] = $filaInt['sku'];
            }
            
            $detalle[] = $fila;
        }
        
        //***************************************
        
        if(
            count($cabecera) > 0 && 
            count($detalle) > 0
        ){

            // Detalle de los metodos de la libreria http://www.rinconastur.com/php/php90.php
            
            // Include the main TCPDF library (search for installation path).
            require_once('../../lib/php/TCPDF-master/tcpdf.php');


            // create new PDF document
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', true);

            // set document information
            $pdf->SetCreator($pdet_valor['empresa']);
            $pdf->SetAuthor($pdet_valor['empresa']);
            $pdf->SetTitle($pdet_valor['empresa']);
            $pdf->SetSubject($pdet_valor['empresa']);
            $pdf->SetKeywords($pdet_valor['empresa']);

            // disable header and footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $pdf->SetMargins(10, 10, 10 );
            // Establece true para crear mas paginas y el margen inferior
            $pdf->SetAutoPageBreak( true, 10 );
            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
                require_once(dirname(__FILE__).'/lang/spa.php');
                $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            $pdf->SetFont('helvetica', '', 10);

            // add a page
            $pdf->AddPage();
            /*
                1.- $w -> float ancho de la celda
                2.- $h -> minimo de alto de la celda
                3.- $txt -> texto
                4.- $border -> indica si va borde o no [ 0 1 ]
                5.- $align -> alineacion [ L C R J ]
                6.- $fill -> boolean indica si va pintado o no el fondo de la celda
                7.- $ln -> si esta uno realiza un salto de linea
                8.- $x -> posicion de ancho
                9.- $y -> posicion de alto
                10.- $reseth -> si esta true establece el alto de la ultima celda
                11.- $stretch -> 1 fuerza a mantener el alto y ancho al texto
                12.- $ishtml -> si es texto html debe ir true
                13.- $autopadding -> (boolean) ajusta automaticamente el padding
                14.- $maxh -> (float) maximo de algo
                15.- $valign -> alineacion vertical
                16.- $fitcell -> si es true encaja todo el texto dentro de la celda
            */
            
            // Deben sumar 180 de ancho
            
            
            $pdf->Image('../../images/system/logo.png',10,3,30,10,'PNG');
            
            // (Ancho,Alto,Texto,Border,Salto de linea,Alineado,Fondo,Enlace,Encaja texto,Escala texto)
            
            $pdf->MultiCell(180, 5, 'Pedido # '.$idpedido , $boder, 'C', 0, 1, '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->Ln(10);
            
            $pdf->MultiCell( 20, 5, 'USUARIO :' , $boder,           'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $usuario , $boder,              'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);            
            $pdf->MultiCell( 20, 5, 'GENERADO :' , $boder,          'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, date('Y-m-d H:i:s') , $boder,   'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'HORARIO :' , $boder,           'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, substr($cabecera['inicio'], 0, -3).' - '.substr(substr($cabecera['fin'], 0, -3), 11) , $boder,              'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true); 
                        
            $pdf->MultiCell( 20, 5, 'COSTO ENVIO :' , $boder,          'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['costo_envio'] , $boder,  'R', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'TOTAL :' , $boder,           'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['total'] , $boder,  'R', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);            
            $pdf->MultiCell( 20, 5, 'TOTAL ENVIO :' , $boder,     'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['total_con_envio'] , $boder,   'R', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            if(!empty($cabecera['comentario'])){
                $pdf->MultiCell( 20, 5, 'COMENTARIO :'             , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
                $pdf->MultiCell(160, 5, $cabecera['comentario']    , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            }
            
            $pdf->Ln(5);
            //********************************************************************************************
            
            $pdf->MultiCell(180, 5, 'DATOS ENVIO' , 1, 'C', 0, 1, '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'NOMBRE :'                 , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell(160, 5, $cabecera['nombre_env']    , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $exisMovil2Fac = 1;
            if(!empty($cabecera['movil2_env'])){
                $exisMovil2Fac = 0;
            }
            
            $pdf->MultiCell( 20, 5, 'TELÉF. / MÓVIL :'       , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['movil1_env']    , $boder, 'L', 0, $exisMovil2Fac,  '', '', true, 1, false, false, 5, 'M', true);
            
            if(!empty($cabecera['movil2_env'])){
                $pdf->MultiCell( 20, 5, 'TELÉF. / MÓVIL :'       , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
                $pdf->MultiCell( 70, 5, $cabecera['movil2_env']    , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            }
            
            $pdf->MultiCell( 20, 5, 'DIRECCIÓN :'              , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell(160, 5, $cabecera['direccion_env'] , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'PROVINCIA :'                 , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['provincia_nom']    , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 20, 5, 'CANTON :'               , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['canton_nom']   , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'ZONA :'                 , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['zona_nom']    , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 20, 5, 'SECTOR :'               , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['sector_nom']   , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->Ln(5);
            //********************************************************************************************
            
            $pdf->MultiCell(180, 5, 'DATOS FACTURACION' , 1, 'C', 0, 1, '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'NOMBRE :'                 , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['nombre_fac']    , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 20, 5, 'RUC / CI :'               , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['num_doc_fac']   , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $exisMovil2Fac = 1;
            if(!empty($cabecera['movil2_fac'])){
                $exisMovil2Fac = 0;
            }
            
            $pdf->MultiCell( 20, 5, 'TELÉF. / MÓVIL :'       , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['movil1_fac']    , $boder, 'L', 0, $exisMovil2Fac,  '', '', true, 1, false, false, 5, 'M', true);
            
            if(!empty($cabecera['movil2_fac'])){
                $pdf->MultiCell( 20, 5, 'TELÉF. / MÓVIL :'       , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
                $pdf->MultiCell( 70, 5, $cabecera['movil2_fac']    , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            }
            
            $pdf->MultiCell( 20, 5, 'DIRECCIÓN :'              , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell(160, 5, $cabecera['direccion_fac'] , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $pdf->MultiCell( 20, 5, 'EMAIL :' , $boder,             'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 70, 5, $cabecera['mail_fac'] , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);

            $pdf->Ln(10);
            
            $pdf->MultiCell( 15, 5, '#' , 1, 'C', 0, 0, '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 30, 5, 'SKU' , 1, 'C', 0, 0, '', '', true, 1, false, false, 5, 'M', true);            
            $pdf->MultiCell( 80, 5, 'NOMBRE'   , 1, 'C', 0, 0, '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 25, 5, 'CANTIDAD' , 1, 'C', 0, 0, '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 30, 5, 'SUBTOTAL' , 1, 'C', 0, 1, '', '', true, 1, false, false, 5, 'M', true);

            // Inicio Items
            $cntPaginas = 0;
            $cntItemsPagina = 1;
            $limitePaginaInicial = 30;
            $limitePaginas = 50;
            
            //for( $f = 0; $f < 200; $f++ ){
            for( $f = 0; $f < count($detalle); $f++ ){
                
                $pdf->MultiCell( 15, $altoItem, ($f + 1) , $boder, 'C', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 30, $altoItem, $detalle[$f]['sku'] , $boder, 'C', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 80, $altoItem, $detalle[$f]['nombre']   , $boder, 'L', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 25, $altoItem, $detalle[$f]['cantidad'] , $boder, 'C', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 30, $altoItem, $detalle[$f]['subtotal'] , $boder, 'R', 0, 1, '', '', true, 1, false, false, $altoItem, 'M', true);
                
                /*
                $pdf->MultiCell( 15, $altoItem, ($f + 1) , $boder, 'C', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 30, $altoItem, 'PRUEBA '.($f + 1)    , $boder, 'L', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 80, $altoItem, 'PRUEBA '.($f + 1)    , $boder, 'L', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 25, $altoItem, 'PRUEBA '.($f + 1)    , $boder, 'L', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 30, $altoItem, '100'.($f + 1)  , $boder, 'R', 0, 1, '', '', true, 1, false, false, $altoItem, 'M', true);
                */
                
                if($cntPaginas == 0){
                    if(($f + 1) == $limitePaginaInicial){
                        $pdf->AddPage();
                        $cntPaginas++;
                    }
                }else{
                    if($cntItemsPagina == $limitePaginas){
                        $cntItemsPagina = 1;
                        $pdf->AddPage();
                    }else{
                        $cntItemsPagina++;
                    }
                }
                
            }
            
            //Close and output PDF document
            $pdf->Output( $pdet_valor['rutaped'] . $idpedido .'.pdf', 'F');

            //============================================================+
            // END OF FILE
            //============================================================+

            $respuesta->estado = 1;
            $respuesta->mensaje = "Pedido # ".$idpedido.", generado correctamente";
            
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "Error del sistema, el pedido no tiene los datos para generar el documento";
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "El pedido ya ha sido generado, se procedera a consultar nuevamente";
    }
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idpedido ]";
}

print_r(json_encode($respuesta));

?>