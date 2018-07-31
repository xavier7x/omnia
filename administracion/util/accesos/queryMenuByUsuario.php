<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->menu = array();

$usuario = $_POST['usuario'];

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT a.*, b.es_menu
    FROM sys_usuarios_accesos AS a    
    INNER JOIN sys_menu AS b ON (a.idmenu = b.idmenu)
    WHERE a.usuario = '".$usuario."'
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->menu[$cont]['idmenu'] = $fila['idmenu'];
    $respuesta->menu[$cont]['es_menu'] = $fila['es_menu'];
    $respuesta->menu[$cont]['visualizar'] = $fila['visualizar'];
    $respuesta->menu[$cont]['insertar'] = $fila['insertar'];
    $respuesta->menu[$cont]['actualizar'] = $fila['actualizar'];
    $respuesta->menu[$cont]['eliminar'] = $fila['eliminar'];
    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>