<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$idcanton = 0;

if(
    (isset($_POST['idcanton']) && !empty($_POST['idcanton'])) 
){

    $idcanton = $_POST['idcanton'];
}

if(
    !empty($idcanton) 
){ 

    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM zonas
        WHERE idcanton = '".$idcanton."'
        ORDER BY nombre ASC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idzona'] = $fila['idzona'];    
        $respuesta->resultado[$cont]['nombre'] = $fila['nombre'];

        $cont++;
    }
    
}

//****************************

print_r(json_encode($respuesta));

?>