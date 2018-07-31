<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT idmenu, nombre
    FROM sys_menu 
    WHERE es_menu = 'SI'
    AND estado = 'ACTIVO'
    ORDER BY orden
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->rows[$cont]['idmenu'] = $fila['idmenu'];    
    $respuesta->rows[$cont]['nombre'] = $fila['nombre'];
    $respuesta->rows[$cont]['submenu'] = array();
    
    $resultadoInt = $conexion->DBConsulta("
        SELECT idmenu, nombre
        FROM sys_menu 
        WHERE idpadre = '".$fila['idmenu']."'
        AND estado = 'ACTIVO'
        ORDER BY orden
    ");
    
    $contInt = 0;
    
    foreach($resultadoInt as $filaInt){
        $respuesta->rows[$cont]['submenu'][$contInt]['idmenu'] = $filaInt['idmenu'];
        $respuesta->rows[$cont]['submenu'][$contInt]['nombre'] = $filaInt['nombre'];
        
        $contInt++;
    }
    
    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>