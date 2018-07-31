<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$idzona = 0;

if(
    (isset($_POST['idzona']) && !empty($_POST['idzona'])) 
){

    $idzona = $_POST['idzona'];
}

if(
    !empty($idzona) 
){ 

    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM sectores
        WHERE estado = 'ACTIVO'
        AND idzona = '".$idzona."'
        ORDER BY nombre ASC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idsector'] = $fila['idsector'];    
        $respuesta->resultado[$cont]['nombre'] = $fila['nombre'];
        $respuesta->resultado[$cont]['costo_envio'] = $fila['costo_envio'];

        $cont++;
    }
    
}

//****************************

print_r(json_encode($respuesta));

?>