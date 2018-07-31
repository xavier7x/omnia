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
        FROM cli_pedido_cabecera 
        WHERE usuario = '".$usuario."'
        ORDER BY idpedido DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idpedido'] = $fila['idpedido'];
        $respuesta->resultado[$cont]['nombre'] = $fila['idpedido'] . ' - ' . $fila['titulo_env'];

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>