<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$resultado = $conexion->DBConsulta("
    SELECT * 
    FROM impuestos 
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->resultado[$cont]['idimpuesto'] = $fila['idimpuesto'];    
    $respuesta->resultado[$cont]['nombre'] = $fila['nombre'] . " - ( " . $fila['estado'] . " )";
    $respuesta->resultado[$cont]['valor'] = $fila['valor'];

    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>