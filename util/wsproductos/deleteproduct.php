<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();

$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";
$respuesta->total = 0;
$respuesta->total_carrito = 0;

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
$idproducto = 0;
$cantidad = 0;
$idsector = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) && 
    (isset($_POST['cantidad']) && !empty($_POST['cantidad'])) && 
    (isset($_POST['idsector']) && !empty($_POST['idsector']))
){

    $usuario = $_POST['usuario'];
    $idproducto = $_POST['idproducto'];
    $cantidad = $_POST['cantidad'];
    $idsector = $_POST['idsector'];
}

if(
    !empty($usuario) && 
    !empty($idproducto) && 
    !empty($cantidad) && 
    !empty($idsector)
){ 
    
    // Eliminar el producto
    
    $resultado = $conexion->DBConsulta("
        DELETE FROM cli_carrito_detalle 
        WHERE usuario = '".$usuario."'
        AND idproducto = '".$idproducto."'
    ");
    
    if($resultado == true){
        
        // Guardar el log de eliminacion
        $conexion->DBConsulta("
            INSERT INTO cli_carrito_producto_eliminado
            (usuario, idproducto, cantidad, user_create, sys_create) 
            VALUES 
            ('".$usuario."','".$idproducto."','".$cantidad."','".$usuario."',NOW())
        ");
        
        //**************************************************************************************************************
    
        $resultadoCabTotal = $conexion->DBConsulta("
            SELECT IFNULL(SUM(cantidad),0) AS total
            FROM cli_carrito_detalle 
            WHERE usuario = '".$usuario."'
        ");

        foreach($resultadoCabTotal as $filaCabTotal){
            $respuesta->total = $filaCabTotal['total'];
        }
        
        //*********************************************************
    
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

        foreach($resultado as $fila){

            if((int)$fila['valor'] > 0){
                $sub_pre = ($fila['precio'] * (int)$fila['valor']) / 100;
                $fila['precio'] = number_format(((float)$fila['precio'] + $sub_pre), 2, '.', '');
            }

            $total = ($fila['precio'] * $fila['cantidad']);

            $respuesta->total_carrito += $total;
        }

        //*********************************************************

        $respuesta->estado = 1;
        $respuesta->mensaje = "";
        $respuesta->total_carrito = number_format($respuesta->total_carrito, 2, '.', '');
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Error al realizar la eliminación";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idproducto - cantidad ]";
}

print_r(json_encode($respuesta));

?>