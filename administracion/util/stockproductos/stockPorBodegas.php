<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

$idbodega = 0;

if(
    (isset($_POST['idbodega']) && !empty($_POST['idbodega']))
){
    $idbodega = $_POST['idbodega'];
}

if(
    !empty($idbodega)
){ 
    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT a.*, 
        IFNULL(( SELECT stock FROM productos_stock WHERE idproducto = a.idproducto AND idbodega = '".$idbodega."' LIMIT 1),0) AS stock,
        IFNULL(( SELECT nombre FROM bodegas WHERE idbodega = '".$idbodega."' LIMIT 1),'') AS nombre_bodega
        FROM productos AS a
        ORDER BY a.estado, a.idproducto DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->rows[$cont] = $fila;

        $respuesta->rows[$cont]['idbodega'] = $idbodega;
        $respuesta->rows[$cont]['tiene_imagen'] = "NO";        

        if(file_exists('../../../images/productos/'.$fila['idproducto'].'/320x320/'.$fila['nombre_seo'].'.png')){
            $respuesta->rows[$cont]['tiene_imagen'] = "SI";
        }

        $respuesta->rows[$cont]['btn_gestion'] = '<button type="button" class="btn btn-primary gestion_add" data-idproducto="'.$fila['idproducto'].'" data-nombre_producto="'.$fila['nombre'].'" data-idbodega="'.$idbodega.'" data-nombre_bodega="'.$fila['nombre_bodega'].'"><span class="glyphicon glyphicon-plus"></span></button><button type="button" class="btn btn-danger gestion_delete" data-idproducto="'.$fila['idproducto'].'" data-nombre_producto="'.$fila['nombre'].'" data-idbodega="'.$idbodega.'" data-nombre_bodega="'.$fila['nombre_bodega'].'"><span class="glyphicon glyphicon-minus"></span></button>';

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>