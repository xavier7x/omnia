<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM bodegas
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->rows[$cont] = $fila;
    
    $respuesta->rows[$cont]['btn_gestion'] = '<button type="button" class="btn btn-warning gestion_update" data-idbodega="'.$fila['idbodega'].'"><span class="glyphicon glyphicon-pencil"></span></button>';
    
    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>