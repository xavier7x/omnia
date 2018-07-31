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
        FROM cli_pedido_cabecera
        WHERE usuario = '".$usuario."'
        ORDER BY idpedido DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        /*
        $respuesta->rows[$cont]['idenvio'] = $fila['idenvio'];
        $respuesta->rows[$cont]['titulo'] = $fila['titulo'];
        $respuesta->rows[$cont]['nombre'] = $fila['nombre'];    
        $respuesta->rows[$cont]['direccion'] = $fila['direccion']; 
        $respuesta->rows[$cont]['estado'] = $fila['estado'];
        $respuesta->rows[$cont]['sys_update'] = $fila['sys_update'];
        $respuesta->rows[$cont]['sys_create'] = $fila['sys_create'];
        */
        $respuesta->rows[$cont] = $fila;
        $respuesta->rows[$cont]['btn_gestion'] = '<button type="button" class="btn btn-primary gestion_select" data-idpedido="'.$fila['idpedido'].'"><span class="glyphicon glyphicon-search"></span></button>';

        $cont++;
    }

}
//****************************

print_r(json_encode($respuesta));

?>