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

$usuario = "";
$idbodega = 0;
$idproducto = 0;
$idmotivoing = 0;
$cantidad = 0;
$comentario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) && 
    (isset($_POST['idmotivoing']) && !empty($_POST['idmotivoing'])) && 
    (isset($_POST['cantidad']) && !empty($_POST['cantidad']))     
){
    $usuario = $_POST['usuario'];
    $idbodega = $_POST['idbodega'];
    $idproducto = $_POST['idproducto'];
    $idmotivoing = $_POST['idmotivoing'];
    $cantidad = $_POST['cantidad'];
}

if(
    (isset($_POST['comentario']) && !empty($_POST['comentario'])) 
){
    $comentario = $_POST['comentario'];
}

if(
    !empty($usuario) && 
    !empty($idbodega) && 
    !empty($idproducto) && 
    !empty($idmotivoing) && 
    !empty($cantidad) 
){
    
    // Cantidad anterior y saber si el registro existe
    
    $stock_anterior = 0;
    $exis_prod_bod = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT stock
        FROM productos_stock
        WHERE idproducto = '".$idproducto."'
        AND idbodega = '".$idbodega."'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $stock_anterior = $fila['stock'];
        $exis_prod_bod++;
    }
    
    // Extraer los datos maestros del producto
    
    $producto = array();
    
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM productos
        WHERE idproducto = '".$idproducto."'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $producto = $fila;
    }
    
    if(count($producto) > 0){
        
        // Operaciones para guardar el log
        
        $stock_nuevo = (int)$stock_anterior + (int)$cantidad;
        $costo_total = (int)$cantidad  * (float)$producto['costo'];
        $precio_total = (int)$cantidad  * (float)$producto['precio'];
        $margen = (float)$producto['precio'] - (float)$producto['costo'];
        $margen_total = (float)$margen * (int)$cantidad;
            
        if($exis_prod_bod == 0){
            // Inserto el nuevo stock
            $resultado = $conexion->DBConsulta("
                INSERT INTO productos_stock
                (idproducto, idbodega, stock, 
                minimo, maximo, 
                user_create, sys_create) 
                VALUES 
                ('".$idproducto."','".$idbodega."','".$stock_nuevo."',
                '".$pdet_valor['stockminimo']."','".$pdet_valor['stockmaximo']."',
                '".$usuario."',NOW())
            ");
            
            if($resultado == true){
                // Guardar el LOG
                
                $resultado = $conexion->DBConsulta("
                    INSERT INTO bodega_log_stock_ingreso
                    ( idbodega, idproducto, idmotivoing, 
                    cantidad, stock_anterior, stock_nuevo, 
                    comentario, 
                    costo, costo_total, 
                    precio, precio_total, 
                    margen, margen_total, 
                    user_create, sys_create) 
                    VALUES 
                    ('".$idbodega."','".$idproducto."','".$idmotivoing."',
                    '".$cantidad."','".$stock_anterior."','".$stock_nuevo."',
                    ".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                    '".$producto['costo']."','".$costo_total."',
                    '".$producto['precio']."','".$precio_total."',
                    '".$margen."','".$margen_total."',
                    '".$usuario."',NOW())
                ");
                
                if($resultado == true){
                    $respuesta->estado = 1;
                    $respuesta->mensaje = "Stock actualizado con éxito";
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error al realizar la inserción del log [inserción]";
                }
            }else{
                $respuesta->estado = 2;
                $respuesta->mensaje = "Error al realizar la inserción del stock";
            }
        }else{
            // Actualizo el nuevo stock
            $resultado = $conexion->DBConsulta("
                UPDATE productos_stock SET                 
                stock = '".$stock_nuevo."',
                user_update = '".$usuario."',
                sys_update = NOW()
                WHERE idproducto = '".$idproducto."'
                AND idbodega = '".$idbodega."'
            ");
            
            if($resultado == true){
                // Guardar el LOG
                
                $resultado = $conexion->DBConsulta("
                    INSERT INTO bodega_log_stock_ingreso
                    ( idbodega, idproducto, idmotivoing, 
                    cantidad, stock_anterior, stock_nuevo, 
                    comentario, 
                    costo, costo_total, 
                    precio, precio_total, 
                    margen, margen_total, 
                    user_create, sys_create) 
                    VALUES 
                    ('".$idbodega."','".$idproducto."','".$idmotivoing."',
                    '".$cantidad."','".$stock_anterior."','".$stock_nuevo."',
                    ".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                    '".$producto['costo']."','".$costo_total."',
                    '".$producto['precio']."','".$precio_total."',
                    '".$margen."','".$margen_total."',
                    '".$usuario."',NOW())
                ");
                
                if($resultado == true){
                    $respuesta->estado = 1;
                    $respuesta->mensaje = "Stock actualizado con éxito";
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error al realizar la inserción del log [actualización]";
                }
            }else{
                $respuesta->estado = 2;
                $respuesta->mensaje = "Error al realizar la actualización del stock";
            }
        }      
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "No se encontraron los datos del producto";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idbodega - idproducto - idmotivoing - cantidad ]";
}

print_r(json_encode($respuesta));

?>