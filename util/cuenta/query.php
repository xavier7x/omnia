<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

$usuario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario']))
){
    $usuario = $_POST['usuario'];
}

if(
    !empty($usuario)
){  
    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_usuarios
        WHERE usuario = '".$usuario."'
        LIMIT 1
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->rows[$cont] = $fila;

        $cont++;
    }    

}
//****************************

print_r(json_encode($respuesta));

?>