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
        SELECT a.*, b.costo_envio
        FROM cli_datos_envio AS a
        INNER JOIN sectores AS b ON (a.idsector = b.idsector)
        WHERE a.estado = 'ACTIVO'
        AND a.usuario = '".$usuario."'
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idenvio'] = $fila['idenvio'];    
        $respuesta->resultado[$cont]['nombre'] = $fila['titulo'] . " - COSTO ENVÍO ( $ " . $fila['costo_envio'] . " )";
        $respuesta->resultado[$cont]['costo_envio'] = $fila['costo_envio'];
        //$respuesta->resultado[$cont]['nombre'] = $fila['titulo'] . " - COSTO ENVÍO ( $ " . $fila['costo_envio'] . " - 100 % = $ 0 )";

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>