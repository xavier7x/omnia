<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$idprovincia = 0;

if(
    (isset($_POST['idprovincia']) && !empty($_POST['idprovincia'])) 
){

    $idprovincia = $_POST['idprovincia'];
}

if(
    !empty($idprovincia) 
){ 

    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cantones
        WHERE estado = 'ACTIVO'
        AND idprovincia = '".$idprovincia."'
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idcanton'] = $fila['idcanton'];    
        $respuesta->resultado[$cont]['nombre'] = $fila['nombre'];

        $cont++;
    }
    
}

//****************************

print_r(json_encode($respuesta));

?>