<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

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
$idsector = 0;
$idproducto = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idsector']) && !empty($_POST['idsector'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto']))
){

    $usuario = $_POST['usuario'];
    $idsector = $_POST['idsector'];
    $idproducto = $_POST['idproducto'];
}

if(
    !empty($usuario) && 
    !empty($idsector) && 
    !empty($idproducto)
){ 


    // Extraer los productos que se encuentren activos y tengan stock mayor o igual a uno

    $resultado = $conexion->DBConsulta("
        SELECT a.idproducto, a.nombre, a.precio,
        b.stock, a.idimpuesto
        FROM productos AS a
        INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
        INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
        INNER JOIN sectores AS d ON (c.idzona = d.idzona)
        WHERE d.idsector = '".$idsector."'
        AND a.idproducto = '".$idproducto."'
        LIMIT 1
    ");

    $cont = 0;

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
        
        $respuesta->rows[$cont]['idproducto'] = $fila['idproducto'];    
        $respuesta->rows[$cont]['nombre'] = $fila['nombre'];
        $respuesta->rows[$cont]['precio'] = $fila['precio'];
        $respuesta->rows[$cont]['stock'] = $fila['stock'];
        $respuesta->rows[$cont]['idimpuesto'] = $fila['idimpuesto'];

        $respuesta->rows[$cont]['tiene_imagen'] = "NO";

        if(file_exists('../../images/productos/md/'.$respuesta->rows[$cont]['idproducto'].'.png')){
            $respuesta->rows[$cont]['tiene_imagen'] = "SI";
        }

        $cont++;
    }
    
    $respuesta->estado = 1;
    $respuesta->mensaje = "";
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idsector - idproducto ]";
}

print_r(json_encode($respuesta));

?>