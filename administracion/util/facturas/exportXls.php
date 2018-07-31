<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");
require '../../lib/php/phpExcel/PHPExcel.php';

$conexion = new DBManager();
$conexion->DBConectar();

$headings = array("N°","IDPEDIDO","USUARIO","TOTAL","FECHA ACTUALIZACIÓN","FECHA CREACION");

$titulo = "REPORTE SOLICITUD USUARIOS";

//-------------------------     Creando clase PHPExcel

$objPHPExcel = new PHPExcel(); 
$objPHPExcel->getActiveSheet()->setTitle($titulo);

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM cli_factura_cabecera
");

$rowNumber = 3;
$cntLinea = 1;

foreach($resultado as $fila){    
    $col = 'A';
    
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($col.$rowNumber,$cntLinea,PHPExcel_Cell_DataType::TYPE_STRING);
    $col++;
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($col.$rowNumber,$fila['idpedido'],PHPExcel_Cell_DataType::TYPE_STRING);
    $col++;
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($col.$rowNumber,$fila['usuario'],PHPExcel_Cell_DataType::TYPE_STRING);
    $col++;
    
    //*****
    $objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber, $fila['total']);
    $objPHPExcel->getActiveSheet()->getStyle($col.$rowNumber)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    $col++;
    //*****
    
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($col.$rowNumber,$fila['sys_update'],PHPExcel_Cell_DataType::TYPE_STRING);
    $col++;
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($col.$rowNumber,$fila['sys_create'],PHPExcel_Cell_DataType::TYPE_STRING);
    $col++;
    
    $rowNumber++;
    $cntLinea++;
}

// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',$titulo);

$t = 'A';
for ($n=0; $n<count($headings)-1; $n++) {
    ++$t . PHP_EOL;
}

$rowNumber2 = 2; 
$col = 'A'; 
foreach($headings as $heading) { 
   $objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber2,$heading); 
   $col++; 
}
// Se combinan las celdas A1 hasta (N)1, para colocar ahí el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:'.$t.'1');

//-------------------------     Parametros de estilo

$objPHPExcel->getActiveSheet()->freezePane('A3'); 

$estiloTituloReporte = array(
        'font' => array(
            'name'      => 'Arial',
            'bold'      => true,
            'size' =>16,
                'color'     => array(
                    'rgb' => 'ffffff'
                )
        ),
        'fill' => array(
            'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
            'color'	=> array('rgb' => '000000')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_NONE                    
            )
        ), 
        'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation'   => 0,
                'wrap'          => TRUE
        )
    );

    $estiloTituloColumnas = array(
        'font' => array(
            'name'      => 'Arial',
            'size'      =>      12,
            'color'     => array(
                'rgb' => '000000'
            )
        ),
        'borders' => array(
            'allborders'     => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                'color' => array(
                    'rgb' => '000000'
                )
            )
        ),
        'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
        ));

    $estiloInformacion = new PHPExcel_Style();
    $estiloInformacion->applyFromArray(
        array(
            'font' => array(
            'name'      => 'Arial',               
            'color'     => array(
                'rgb' => '000000'
            )
        ),
        'borders' => array(
            'allborders'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN ,
                'color' => array(
                    'rgb' => '000000'
                )
            )
        )
    ));

//-------------------------------------------------------       Aplicando estilo

    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$t.'1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.$t.'2')->applyFromArray($estiloTituloColumnas);	
    
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, 'A3:'.$t.($rowNumber-1));    

    for($i = 'A'; $i <= $t; $i++){
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(true);
    }

//-------------------------------------------------------

ob_start();
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);   

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');

$objWriter->save('php://output');

exit;
ob_end_clean();

?>