<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

$usuario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario']))
){
    $usuario = $_POST['usuario'];
}

if(
    !empty($usuario)
){  

    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_usuario_terminos 
        WHERE usuario = '".$usuario."'
    ");

    $total = 0;

    foreach($resultado as $fila){
        $total = $fila['total'];
    }
    
    if( $total > 0 ){
        $respuesta->estado = 1;
        $respuesta->mensaje = "";
    }else{
        $insert = $conexion->DBConsulta("
            INSERT INTO cli_usuario_terminos
            (usuario, sys_create) 
            VALUES 
            ('".$usuario."', NOW())
        ");
        
        if( $insert == true ){
            $respuesta->estado = 1;
            $respuesta->mensaje = "";
        }else{
            $respuesta->estado = 1;
            $respuesta->mensaje = "Error al guardar los términos";
        }
    }    
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario ]";
}

//****************************

print_r(json_encode($respuesta));

?>