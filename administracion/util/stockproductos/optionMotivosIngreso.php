<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM bodega_motivos_ingreso
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->resultado[$cont]['idmotivoing'] = $fila['idmotivoing'];    
    $respuesta->resultado[$cont]['nombre'] = $fila['nombre'];

    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>