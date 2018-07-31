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

// Extraer los parametros del cliente
/*
$resultado_param_cli = $conexion->DBConsulta("
    SELECT *
    FROM cli_parametros
");

$pdet_valor_cli = array();

foreach($resultado_param_cli as $fila){
    $pdet_valor_cli[trim($fila['idparametro'])] = trim($fila['valor']);
}
*/
//****************************

$usuario = "";
$idbodega = 0;
$idpedido = 0;
$estado_interno = "";
$sig_estado_interno = "";
$comentario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['idpedido']) && !empty($_POST['idpedido'])) && 
    (isset($_POST['estado_interno']) && !empty($_POST['estado_interno'])) && 
    (isset($_POST['sig_estado_interno']) && !empty($_POST['sig_estado_interno']))
){
    $usuario = $_POST['usuario'];
    $idbodega = $_POST['idbodega'];
    $idpedido = $_POST['idpedido'];
    $estado_interno = $_POST['estado_interno'];
    $sig_estado_interno = $_POST['sig_estado_interno'];
}

if(
    (isset($_POST['comentario']) && !empty($_POST['comentario']))
){
    
    $comentario = $_POST['comentario'];
}

if(
    !empty($usuario) && 
    !empty($idbodega) && 
    !empty($idpedido) && 
    !empty($estado_interno) && 
    !empty($sig_estado_interno) 
){
    // Validar que el estado sea igual, caso contrario ya sufrio cambios
    $camTotal = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_pedido_cabecera
        WHERE idpedido = '".$idpedido."'
        AND estado_interno = '".$estado_interno."'
    ");

    foreach($resultado as $fila){
        $camTotal = $fila['total'];
    }
    
    if($camTotal == 1){
        // Actualizar el sig_estado del pedido
        $resultado = $conexion->DBConsulta("            
            UPDATE cli_pedido_cabecera SET                                 
            estado = '".$sig_estado_interno."',
            estado_interno = '".$sig_estado_interno."',
            user_update = '".$usuario."',
            sys_update = NOW()
            WHERE idpedido = '".$idpedido."'
        ");        
        
        if($resultado == true){            
            $productos = array();
            
            // Extraer el detalle-cantidad y obviar los de idproducto (0) cero
            
            $resultado = $conexion->DBConsulta("
                SELECT idproducto, cantidad 
                FROM cli_pedido_detalle
                WHERE idpedido = '".$idpedido."'
            ");

            foreach($resultado as $fila){
                $productos[] = $fila;
            }
            
            // Actualizar el stock de los productos
            
            for($f = 0; $f < count($productos); $f++){
                $conexion->DBConsulta("
                    UPDATE productos_stock, (
                        SELECT (a.stock + ".(int)$productos[$f]['cantidad'].") AS mysum
                        FROM productos_stock AS a
                        WHERE a.idproducto = '".$productos[$f]['idproducto']."'
                        AND a.idbodega = '".$idbodega."'
                    ) AS b
                    SET 
                    stock = b.mysum,
                    user_update = '".$usuario."',
                    sys_update = NOW()
                    WHERE idproducto = '".$productos[$f]['idproducto']."'
                    AND idbodega = '".$idbodega."'
                ");
            }
            
            // Guardar LOG del pedido CLI

            $conexion->DBConsulta("
                INSERT INTO cli_pedido_log
                (idpedido, proceso, comentario, 
                user_create, sys_create) 
                VALUES 
                ('".$idpedido."','".$sig_estado_interno."',".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                '".$usuario."',NOW())
            ");

            // Guardar LOG del pedido SYS

            $conexion->DBConsulta("
                INSERT INTO sys_pedido_log
                (idpedido, proceso, comentario, 
                user_create, sys_create) 
                VALUES 
                ('".$idpedido."','".$sig_estado_interno."',".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                '".$usuario."',NOW())
            ");
            
            $respuesta->estado = 1;
            $respuesta->mensaje = "Pedido cancelado con éxito";
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "Error al realizar la actualización";
        }
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "El pedido sufrio cambios en su estado, se procedera a consultar nuevamente";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idbodega - idpedido - estado_interno - sig_estado_interno - comentario ]";
}

print_r(json_encode($respuesta));

?>