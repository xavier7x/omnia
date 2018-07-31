<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$idbodega = "";
$inicio = "";
$fin = "";

if(
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['inicio']) && !empty($_POST['inicio'])) && 
    (isset($_POST['fin']) && !empty($_POST['fin'])) 
){
    $idbodega = $_POST['idbodega'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
}

if(
    !empty($idbodega) && 
    !empty($inicio) && 
    !empty($fin) 
){   

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_pedido_cabecera
        WHERE idbodega = '".$idbodega."'
        AND DATE(inicio) >= '".$inicio."'
        AND DATE(fin) <= '".$fin."'
        ORDER BY idpedido DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idpedido'] = $fila['idpedido'];
        $respuesta->resultado[$cont]['nombre'] = $fila['idpedido'] . ' - ' . $fila['usuario'];

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>