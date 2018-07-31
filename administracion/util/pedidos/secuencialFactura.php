<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->establecimiento = "";
$respuesta->punto_emision = "";
$respuesta->secuencial = "";
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

$idbodega = 0;
$idpedido = 0;
$estado_interno = "";

if(
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['idpedido']) && !empty($_POST['idpedido'])) && 
    (isset($_POST['estado_interno']) && !empty($_POST['estado_interno']))     
){
    $idbodega = $_POST['idbodega'];
    $idpedido = $_POST['idpedido'];
    $estado_interno = $_POST['estado_interno'];
}

if(
    !empty($idbodega) && 
    !empty($idpedido) && 
    !empty($estado_interno)  
){
    
    // Validar que el estado sea igual, caso contrario ya sufrio cambios
    $camTotal = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_pedido_cabecera
        WHERE idpedido = '".$idpedido."'
        AND estado_interno = '".$estado_interno."'
    ");

    foreach($resultado as $fila){
        $camTotal = $fila['total'];
    }
    
    if($camTotal == 1){
        
        // Extraer el numero de facturacion y sumarle uno, ademas de añadir los ceros
        $cntSec = 0;
        $resultado = $conexion->DBConsulta("
            SELECT establecimiento, punto_emision, 
            LPAD( (secuencial + 1), ".$pdet_valor['longsecuencial'].", '0') AS secuencial_new
            FROM bodega_cajas
            WHERE idbodega = '".$idbodega."'
            LIMIT 1
        ");

        foreach($resultado as $fila){
            $respuesta->establecimiento = $fila['establecimiento'];
            $respuesta->punto_emision = $fila['punto_emision'];
            $respuesta->secuencial = $fila['secuencial_new'];
            $cntSec++;
        }
        
        if($cntSec == 1){
            $respuesta->estado = 1;
            $respuesta->mensaje = "";
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "No se obtuvo el secuencial";
        }
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "El pedido sufrio cambios en su estado, se procedera a consultar nuevamente";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ idbodega - idpedido - estado_interno ]";
}

print_r(json_encode($respuesta));

?>