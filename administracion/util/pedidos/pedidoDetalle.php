<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->cabecera = array();
$respuesta->detalles = array();

$idpedido = "";

if(
    (isset($_POST['idpedido']) && !empty($_POST['idpedido']))
){
    $idpedido = $_POST['idpedido'];
}

if(
    !empty($idpedido)
){  

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_pedido_cabecera 
        WHERE idpedido = '".$idpedido."'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $respuesta->cabecera = $fila;        
    }
    
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_pedido_detalle 
        WHERE idpedido = '".$idpedido."'
    ");

    foreach($resultado as $fila){
        $respuesta->detalles[] = $fila;        
    }
}

//****************************

print_r(json_encode($respuesta));

?>