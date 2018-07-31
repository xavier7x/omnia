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

$idproducto = "";

if(
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) 
){
    $idproducto = $_POST['idproducto'];
}

if(
    !empty($idproducto)
){ 
    
    // Devolver el total de items que tenemos en el carrito
    
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM productos 
        WHERE idproducto = '".$idproducto."'
        LIMIT 1
    ");
    
    foreach($resultado as $fila){
        $fila['tiene_imagen'] = "NO";
        
        if(file_exists('../../../images/productos/'.$fila['idproducto'].'/320x320/'.$fila['nombre_seo'].'.png')){
            $fila['tiene_imagen'] = "SI";
        }
        
        $respuesta->producto[] = $fila;
    }
    
    $respuesta->estado = 1;
    $respuesta->mensaje = "";
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ idproducto ]";
}

print_r(json_encode($respuesta));

?>