<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM provincias
    WHERE estado = 'ACTIVA'
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->resultado[$cont]['idprovincia'] = $fila['idprovincia'];    
    $respuesta->resultado[$cont]['nombre'] = $fila['nombre'];
    
    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>