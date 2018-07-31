<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM productos 
    ORDER BY estado, idproducto DESC
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
    
    $respuesta->rows[$cont]['tiene_imagen'] = "NO";
    
    if(file_exists('../../../images/productos/'.$fila['idproducto'].'/320x320/'.$fila['nombre_seo'].'.png')){
        $respuesta->rows[$cont]['tiene_imagen'] = "SI";
    }
    
    $respuesta->rows[$cont]['btn_gestion'] = '<button type="button" class="btn btn-warning gestion_update" data-idproducto="'.$fila['idproducto'].'"><span class="glyphicon glyphicon-pencil"></span></button>';

    $cont++;
}


//****************************

print_r(json_encode($respuesta));

?>