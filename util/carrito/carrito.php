<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";
$respuesta->total = 0;

// Extraer los parametros

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM cli_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************

$usuario = "";
$idsector = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) &&
    (isset($_POST['idsector']) && !empty($_POST['idsector'])) 
    
){
    $usuario = $_POST['usuario'];
    $idsector = $_POST['idsector'];
}

if(
    !empty($usuario) && 
    !empty($idsector)
){
    // Extraer los datos del carrito

    $resultado = $conexion->DBConsulta("
        SELECT a.idproducto, a.cantidad, b.nombre, b.nombre_seo, b.estado, b.precio, c.valor, d.stock
        FROM cli_carrito_detalle AS a
        INNER JOIN productos AS b ON (a.idproducto = b.idproducto)
        INNER JOIN impuestos AS c ON (b.idimpuesto = c.idimpuesto)
        INNER JOIN productos_stock AS d ON (b.idproducto = d.idproducto)
        INNER JOIN bodegas_zonas AS e ON (d.idbodega = e.idbodega)
        INNER JOIN sectores AS f ON (e.idzona = f.idzona)
        WHERE f.idsector = '".$idsector."'
        AND a.usuario = '".$usuario."'
        ORDER BY b.nombre ASC        
    ");

    $cont = 0;

    foreach($resultado as $fila){
        
        $img_pro = $pdet_valor['hostapp'].'/images/productos/0/320x320/error.png?v='.$pdet_valor['webversion'];
        if(file_exists('../../images/productos/'.$fila['idproducto'].'/320x320/'.$fila['nombre_seo'].'.png')){
            $img_pro = $pdet_valor['hostapp'].'/images/productos/'.$fila['idproducto'].'/320x320/'.$fila['nombre_seo'].'.png?v='.$pdet_valor['webversion'];                        
        }
        
        if((int)$fila['valor'] > 0){
            $sub_pre = ($fila['precio'] * (int)$fila['valor']) / 100;
            $fila['precio'] = number_format(((float)$fila['precio'] + $sub_pre), 2, '.', '');
        }
        
        $total = ($fila['precio'] * $fila['cantidad']);
        
        $respuesta->rows[$cont]['idproducto'] = $fila['idproducto'];    
        $respuesta->rows[$cont]['nombre'] = $fila['nombre'];
        $respuesta->rows[$cont]['nombre_seo'] = $fila['nombre_seo'];
        $respuesta->rows[$cont]['estado'] = $fila['estado'];
        $respuesta->rows[$cont]['precio'] = $fila['precio'];
        $respuesta->rows[$cont]['cantidad'] = $fila['cantidad'];
        $respuesta->rows[$cont]['valor'] = $fila['valor'];
        $respuesta->rows[$cont]['stock'] = $fila['stock'];
        $respuesta->rows[$cont]['img_pro'] = $img_pro;
        $respuesta->rows[$cont]['total'] = number_format((float)$total, 2, '.', '');

        $respuesta->total += $total;
        $cont++;
    }
    
    $respuesta->estado = 1;
    $respuesta->mensaje = "";
    $respuesta->total = number_format($respuesta->total, 2, '.', '');
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario ]";
}

print_r(json_encode($respuesta));

?>