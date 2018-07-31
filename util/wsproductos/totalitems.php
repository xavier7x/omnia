<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();

$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";
$respuesta->total = 0;

// Extraer los parametros
/*
$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM cli_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}
*/
//****************************

$usuario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) 
){

    $usuario = $_POST['usuario'];
}

if(
    !empty($usuario) 
){ 

    // Devolver el total de items que tenemos en el carrito
    
    $resultado = $conexion->DBConsulta("
        SELECT IFNULL(SUM(cantidad),0) AS total
        FROM cli_carrito_detalle 
        WHERE usuario = '".$usuario."'
    ");
    
    foreach($resultado as $fila){
        $respuesta->total = $fila['total'];
    }
    
    $respuesta->estado = 1;
    $respuesta->mensaje = "";
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario ]";
}

print_r(json_encode($respuesta));

?>