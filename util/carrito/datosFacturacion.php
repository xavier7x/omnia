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
        SELECT *  
        FROM cli_datos_facturacion
        WHERE estado = 'ACTIVO'
        AND usuario = '".$usuario."'
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idfacturacion'] = $fila['idfacturacion'];    
        $respuesta->resultado[$cont]['nombre'] = $fila['titulo'];

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>