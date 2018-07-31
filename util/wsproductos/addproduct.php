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
$proceso = "";

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
    (isset($_POST['proceso']) && !empty($_POST['proceso']))
){

    $proceso = $_POST['proceso'];
}

if(
    !empty($usuario) && 
    !empty($idproducto) && 
    !empty($idsector) && 
    ( !empty($cantidad) && $cantidad > 0) 
){

    // Verificar si el usuario tiene un carrito cabecera
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_carrito_cabecera 
        WHERE usuario = '".$usuario."'
    ");
    
    $totalCarritoCabecera = 0;
    
    foreach($resultado as $fila){
        $totalCarritoCabecera = $fila['total'];
    }
    
    if($totalCarritoCabecera == 0){
        // Si no lo tiene crearle
        $conexion->DBConsulta("
            INSERT INTO cli_carrito_cabecera
            (usuario, user_create, sys_create) 
            VALUES 
            ('".$usuario."','".$usuario."',NOW())
        ");
    }else{
        // Caso contrario actualizar la auditoria de la tabla carrito cabecera
        $conexion->DBConsulta("
            UPDATE cli_carrito_cabecera SET 
            user_update = '".$usuario."',
            sys_update = NOW() 
            WHERE usuario = '".$usuario."'
        ");
    }
    
    // Verificar si ya tiene el producto añadido
    
    $resultado = $conexion->DBConsulta("
        SELECT cantidad
        FROM cli_carrito_detalle
        WHERE usuario = '".$usuario."'
        AND idproducto = '".$idproducto."'
        LIMIT 1
    ");
    
    $totalProducto = 0;
    $cantidad_anterior = 0;
    
    foreach($resultado as $fila){
        $cantidad_anterior = $fila['cantidad'];
        $totalProducto++;
    }
    
    if($totalProducto == 0){
        // Si no tiene añadido el producto procedo a registrarlo con su respectiva cantidad 
        $conexion->DBConsulta("
            INSERT INTO cli_carrito_detalle
            (usuario, idproducto, cantidad, user_create, sys_create) 
            VALUES 
            ('".$usuario."','".$idproducto."','".$cantidad."','".$usuario."',NOW())
        ");
    }else{
        // Caso contrario si lo tiene añadido actualizar el stock y la auditoria, y sumar con la cantidad anterior
        $cantidad_final = ( $cantidad + $cantidad_anterior );
        
        if( !empty($proceso) ){
            $cantidad_final = $cantidad;
        }
        
        $conexion->DBConsulta("
            UPDATE cli_carrito_detalle SET 
            cantidad = '".$cantidad_final."',
            user_update = '".$usuario."',
            sys_update = NOW() 
            WHERE usuario = '".$usuario."' 
            AND idproducto = '".$idproducto."'
        ");
        
        // Y Registrar el log en la tabla de carrito producto actualizado
        $conexion->DBConsulta("
            INSERT INTO cli_carrito_producto_actualizado
            (usuario, idproducto, cantidad_anterior, cantidad_nueva, user_create, sys_create) 
            VALUES 
            ('".$usuario."','".$idproducto."','".$cantidad_anterior."','".$cantidad."','".$usuario."',NOW())
        ");
    }
    
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
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idproducto - cantidad ]";
}

print_r(json_encode($respuesta));

?>