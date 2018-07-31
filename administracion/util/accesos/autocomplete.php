<?php
include("../system/session.php");
include("../system/conexionMySql.php");
include("../system/funciones.php");

$session = new AdmSession();
$session->startSession();

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta = array();

$term = trim(strip_tags($_GET['term']));

// Extraer los datos

$sql = "
    SELECT usuario, nombre 
    FROM sys_usuarios
    WHERE (usuario LIKE '%".$term."%'
    OR nombre LIKE '%".$term."%')
    AND usuario != 'admin'
    AND administrador = 'NO'
    LIMIT 5
";
  
if($_SESSION['usuario'] == 'admin'){
    
    $sql = "
        SELECT usuario, nombre 
        FROM sys_usuarios
        WHERE (usuario LIKE '%".$term."%'
        OR nombre LIKE '%".$term."%')
        LIMIT 5
    ";
    
}elseif($_SESSION['administrador'] == 'SI'){
    
    $sql = "
        SELECT usuario, nombre 
        FROM sys_usuarios
        WHERE (usuario LIKE '%".$term."%'
        OR nombre LIKE '%".$term."%')
        AND usuario != 'admin'
        LIMIT 5
    ";
    
}

$resultado = $conexion->DBConsulta($sql);

$cont = 0;

foreach($resultado as $fila){
    $respuesta[$cont]['usuario'] =  trim($fila['usuario']);
    $respuesta[$cont]['nombre'] =  $fila['nombre'];
    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>