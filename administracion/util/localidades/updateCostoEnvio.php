<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

$usuario= "";
$idsector = 0;
$costo_envio = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) &&
    (isset($_POST['idsector']) && !empty($_POST['idsector'])) &&
    ( isset($_POST['costo_envio']) )
){
    $usuario = $_POST['usuario'];
    $idsector = $_POST['idsector'];
    $costo_envio  = $_POST['costo_envio'];
}

if(
    !empty($usuario) && 
    !empty($idsector)
){
    
    $resultado = $conexion->DBConsulta("
        UPDATE sectores SET 
        costo_envio = '".$costo_envio."',
        user_update = '".$usuario."',
        sys_update = NOW() 
        WHERE idsector = '".$idsector."'
    ");

    if($resultado == true){
        $respuesta->estado = 1;
        $respuesta->mensaje = "Registro actualizado con éxito";
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Error al realizar la actualización";
    }
    
}else{    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idsector - costo_envio ]";
}

print_r(json_encode($respuesta));

?>