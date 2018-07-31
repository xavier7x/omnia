<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->cabecera = array();
$respuesta->detalles = array();

$idfactura = "";

if(
    (isset($_POST['idfactura']) && !empty($_POST['idfactura']))
){
    $idfactura = $_POST['idfactura'];
}

if(
    !empty($idfactura)
){  

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_factura_cabecera 
        WHERE idfactura = '".$idfactura."'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $fila['numero_factura'] = $fila['establecimiento'].'-'.$fila['punto_emision'].'-'.$fila['secuencial'];
        $respuesta->cabecera = $fila;        
    }
    
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_factura_detalle 
        WHERE idfactura = '".$idfactura."'
    ");

    foreach($resultado as $fila){
        $respuesta->detalles[] = $fila;        
    }
}

//****************************

print_r(json_encode($respuesta));

?>