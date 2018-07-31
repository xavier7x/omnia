<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$resultado = $conexion->DBConsulta("
    SELECT * 
    FROM marcas 
    ORDER BY nombre
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->resultado[$cont]['idmarca'] = $fila['idmarca'];    
    $respuesta->resultado[$cont]['nombre'] = $fila['nombre'] ." [ ".$fila['descripcion']." ] ". " - ( " . $fila['estado'] . " )";

    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>