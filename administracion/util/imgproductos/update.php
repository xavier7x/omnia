<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

// Extraer los parametros

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM sys_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************

$usuario= "";
$idproducto = 0;
$nombre_seo = "";
$imagen_item = "";
$extension = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) &&
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) && 
    (isset($_POST['nombre_seo']) && !empty($_POST['nombre_seo'])) && 
    (isset($_FILES['imagen_item']) && !empty($_FILES['imagen_item'])) 
){
    $usuario = $_POST['usuario'];
    $idproducto = $_POST['idproducto'];
    $nombre_seo = $_POST['nombre_seo'];
    $imagen_item  = $_FILES['imagen_item'];
}

if(
    !empty($usuario) && 
    !empty($idproducto) && 
    !empty($nombre_seo) && 
    !empty($imagen_item) 
){
    $extension = end(explode('.',$imagen_item['name']));
    $respuesta->imagen = $imagen_item;
    if(
        $extension == $pdet_valor['imgproductoext'] && 
        $imagen_item['size'] <= ( ( $pdet_valor['imgproductopeso'] * 1024) )
    ){
        if(move_uploaded_file($imagen_item['tmp_name'], '../../../images/productos/'.$idproducto.'/320x320/'.$nombre_seo.'.'.$pdet_valor['imgproductoext'])){
            $conexion->DBConsulta("
                UPDATE productos SET 
                user_update = '".$usuario."',
                sys_update = NOW()
                WHERE idproducto = '".$idproducto."'
            ");

            $respuesta->estado = 1;
            $respuesta->mensaje = "Imagen guardada con éxito";
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "Error al realizar la subida de la imagen";
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = 'Solo puede cargar imagen a los productos de formato ('.$pdet_valor['imgproductoext'].') y de tamaño ('.$pdet_valor['imgproductopeso'].' KB) como máximo';
    }
}else{    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idproducto - nombre_seo - imagen_item ]";
}

print_r(json_encode($respuesta));

?>