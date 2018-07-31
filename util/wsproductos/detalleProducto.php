<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();

$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";
$respuesta->producto = array();

// Extraer los parametros
/*
$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM cli_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}
*/
//****************************

$usuario = "";
$idproducto = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) 
){

    $usuario = $_POST['usuario'];
    $idproducto = $_POST['idproducto'];
}

if(
    !empty($usuario) && 
    !empty($idproducto)
){ 
    
    // Guardar el log de visualizacion de producto
    
    $conexion->DBConsulta("
        INSERT INTO cli_visualizacion_producto
        (idproducto, user_create, sys_create) 
        VALUES 
        ('".$idproducto."','".$usuario."',NOW())
    ");

    // Devolver el total de items que tenemos en el carrito
    /*
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM productos 
        WHERE idproducto = '".$idproducto."'
        LIMIT 1
    ");
    
    foreach($resultado as $fila){
        $valor = 0;
    
        $resultadoInt = $conexion->DBConsulta("
            SELECT valor
            FROM impuestos 
            WHERE idimpuesto = '".$fila['idimpuesto']."'
            LIMIT 1
        ");

        foreach($resultadoInt as $filaInt){
            $valor = $filaInt['valor'];    
        }
        
        if((int)$valor > 0){
            $sub_pre = ($fila['precio'] * (int)$valor) / 100;
            $fila['precio'] = number_format(((float)$fila['precio'] + $sub_pre), 2, '.', '');
        }
        
        //******************************************************************
        
        $fila['tiene_imagen'] = "NO";

        if(file_exists('../../images/productos/md/'.$fila['idproducto'].'.png')){
            $fila['tiene_imagen'] = "SI";
        }
        
        $respuesta->producto[] = $fila;
    }
    */
    $respuesta->estado = 1;
    $respuesta->mensaje = "";
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idproducto ]";
}

print_r(json_encode($respuesta));

?>