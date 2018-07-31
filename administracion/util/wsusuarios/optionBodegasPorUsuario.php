<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

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
        SELECT a.*
        FROM bodegas AS a
        INNER JOIN sys_usuario_bodegas AS b ON (a.idbodega = b.idbodega)
        WHERE b.usuario = '".$usuario."'
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idbodega'] = $fila['idbodega'];    
        $respuesta->resultado[$cont]['nombre'] = $fila['idbodega'] ." - ". $fila['nombre'] . " -  ( " . $fila['estado'] . " )";

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>