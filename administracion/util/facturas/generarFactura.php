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
$idfactura = 0;
$numero_factura = "";

// Establece si se imprime con borde o no
$boder = 0;
$altoItem = 4;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idfactura']) && !empty($_POST['idfactura'])) && 
    (isset($_POST['numero_factura']) && !empty($_POST['numero_factura'])) 
){
    $usuario = $_POST['usuario'];
    $idfactura = $_POST['idfactura'];
    $numero_factura = $_POST['numero_factura'];
}

if(
    !empty($usuario) && 
    !empty($idfactura) && 
    !empty($numero_factura)
){

    $cntGenerada = 0;
        
    if(
        file_exists('../../docfac/'.$numero_factura.'.pdf')
    ){
        $cntGenerada++;
    }
    
    if( $cntGenerada == 0 ){
        
        $cabecera = array();
        $detalle = array();
        
        // Extraer los datos de cabecera
        
        $resultado = $conexion->DBConsulta("
            SELECT * 
            FROM cli_factura_cabecera
            WHERE idfactura = '".$idfactura."'
            LIMIT 1
        ");

        foreach($resultado as $fila){
            $cabecera = $fila;
        }

        // Extraer los datos del detalle

        $resultado = $conexion->DBConsulta("
            SELECT * 
            FROM cli_factura_detalle
            WHERE idfactura = '".$idfactura."'
        ");

        foreach($resultado as $fila){
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
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator($pdet_valor['empresa']);
            $pdf->SetAuthor($pdet_valor['empresa']);
            $pdf->SetTitle($pdet_valor['empresa']);
            $pdf->SetSubject($pdet_valor['empresa']);
            $pdf->SetKeywords($pdet_valor['empresa']);

            // disable header and footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $pdf->SetMargins(10, 2, 10);
            // Establece false para no crear mas paginas y el margen inferior
            $pdf->SetAutoPageBreak( false, 3 );
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
            
            // Deben sumar 190 de ancho
            
            // (Ancho,Alto,Texto,Border,Salto de linea,Alineado,Fondo,Enlace,Encaja texto,Escala texto)
            
            $pdf->MultiCell( 40, 3, $usuario.' * '.$numero_factura , $boder, 'C', 0, 1, 150, '', true, 1, false, false, 3, 'M', true);
            
            $pdf->Ln(30);
            
            $pdf->MultiCell( 70, 5,'TELÉFONO : '.$pdet_valor['telefonopedidos'], $boder, 'L', 0, 1,  50, '', true, 1, false, false, 5, 'M', true);
            
            $pdf->Ln(6);
            
            $pdf->MultiCell( 20, 5, 'Sr (es) :'                 , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 75, 5, $cabecera['nombre_fac']    , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 20, 5, 'R.U.C / C.I :'               , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 75, 5, $cabecera['num_doc_fac']   , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            $exisMovil2 = 1;
            if(!empty($cabecera['movil2_fac'])){
                $exisMovil2 = 0;
            }
            
            $pdf->MultiCell( 20, 5, 'TELÉF. / MÓVIL :'       , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 75, 5, $cabecera['movil1_fac']    , $boder, 'L', 0, $exisMovil2,  '', '', true, 1, false, false, 5, 'M', true);
            
            if(!empty($cabecera['movil2_fac'])){
                $pdf->MultiCell( 20, 5, 'TELÉF. / MÓVIL :'       , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
                $pdf->MultiCell( 75, 5, $cabecera['movil2_fac']    , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            }
            
            $pdf->MultiCell( 20, 5, 'DIRECCIÓN :'              , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell(170, 5, $cabecera['direccion_fac'] , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);
            
            
            $pdf->MultiCell( 20, 5, 'FECHA EMISIÓN :'     , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 75, 5, date('Y-m-d H:i:s')   , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 20, 5, 'EMAIL :'             , $boder, 'L', 0, 0,  '', '', true, 1, false, false, 5, 'M', true);
            $pdf->MultiCell( 75, 5, $cabecera['mail_fac'] , $boder, 'L', 0, 1,  '', '', true, 1, false, false, 5, 'M', true);

            $pdf->Ln(7);

            // Inicio Items
            
            //for( $f = 0; $f < 47; $f++ ){
            for( $f = 0; $f < count($detalle); $f++ ){
                
                $pdf->MultiCell( 17, $altoItem, $detalle[$f]['cantidad'], $boder, 'C', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell(120, $altoItem, $detalle[$f]['nombre']  , $boder, 'L', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 24, $altoItem, $detalle[$f]['precio']  , $boder, 'R', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);                
                $pdf->MultiCell( 29, $altoItem, $detalle[$f]['subtotal'], $boder, 'R', 0, 1, '', '', true, 1, false, false, $altoItem, 'M', true);
                
                /*
                $pdf->MultiCell( 17, $altoItem, ($f + 1) , $boder, 'C', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell(120, $altoItem, 'PRUEBA '.($f + 1)    , $boder, 'L', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 24, $altoItem, ($f + 1) , $boder, 'R', 0, 0, '', '', true, 1, false, false, $altoItem, 'M', true);
                $pdf->MultiCell( 29, $altoItem, '100'.($f + 1)  , $boder, 'R', 0, 1, '', '', true, 1, false, false, $altoItem, 'M', true);
                */
            }
            
            if((int)$cabecera['idmetodopago'] == 1){
                $pdf->MultiCell(16, $altoItem, $cabecera['total'] , $boder, 'R', 0, 0, 40, 266, true, 1, false, false, $altoItem, 'M', true);
            }else{
                $pdf->MultiCell(16, $altoItem, $cabecera['total'] , $boder, 'R', 0, 0, 40, 285, true, 1, false, false, $altoItem, 'M', true);
            }

            // Fin items

            $pdf->MultiCell(20, $altoItem, 'SUBTOTAL '.$pdet_valor['layoutfacimp'].'%' , $boder, 'L', 0, 0, 150, 265, true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(30, $altoItem, $cabecera['subtotal_impuesto'] , $boder, 'R', 0, 1, '',  '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(20, $altoItem, 'SUBTOTAL 0%'                  , $boder, 'L', 0, 0, 150, '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(30, $altoItem, $cabecera['subtotal_exento']   , $boder, 'R', 0, 1, '',  '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(20, $altoItem, 'DESCUENTO'                    , $boder, 'L', 0, 0, 150, '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(30, $altoItem, $cabecera['descuento']         , $boder, 'R', 0, 1, '',  '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(20, $altoItem, 'SUBTOTAL'                     , $boder, 'L', 0, 0, 150, '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(30, $altoItem, $cabecera['subtotal']          , $boder, 'R', 0, 1, '',  '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(20, $altoItem, 'IVA '.$pdet_valor['layoutfacimp'].'%'                 , $boder, 'L', 0, 0, 150, '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(30, $altoItem, $cabecera['impuesto']          , $boder, 'R', 0, 1, '',  '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(20, $altoItem, 'VALOR TOTAL'                  , $boder, 'L', 0, 0, 150, '', true, 1, false, false, $altoItem, 'M', true);
            $pdf->MultiCell(30, $altoItem, $cabecera['total']             , $boder, 'R', 0, 1, '',  '', true, 1, false, false, $altoItem, 'M', true);

            //Close and output PDF document
            $pdf->Output( $pdet_valor['rutafac'] . $numero_factura .'.pdf', 'F');

            //============================================================+
            // END OF FILE
            //============================================================+

            $respuesta->estado = 1;
            $respuesta->mensaje = "Factura # ".$idfactura." - Secuencial ".$numero_factura.", generada correctamente";
            
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "Error del sistema, la factura no tiene los datos para generar el documento";
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "La factura ya ha sido generada, se procedera a consultar nuevamente";
    }
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idfactura - numero_factura ]";
}

print_r(json_encode($respuesta));

?>