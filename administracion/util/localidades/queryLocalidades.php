<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

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
        WHERE idzona = '".$idzona."'
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->rows[$cont] = $fila;

        $respuesta->rows[$cont]['btn_gestion'] = '<button type="button" class="btn btn-warning gestion_update" data-idsector="'.$fila['idsector'].'"><span class="glyphicon glyphicon-pencil"></span></button>';

        $cont++;
    }
    
}

//****************************

print_r(json_encode($respuesta));

?>