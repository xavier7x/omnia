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
$idmotivoegr = 0;
$cantidad = 0;
$comentario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) && 
    (isset($_POST['idmotivoegr']) && !empty($_POST['idmotivoegr'])) && 
    (isset($_POST['cantidad']) && !empty($_POST['cantidad']))     
){
    $usuario = $_POST['usuario'];
    $idbodega = $_POST['idbodega'];
    $idproducto = $_POST['idproducto'];
    $idmotivoegr = $_POST['idmotivoegr'];
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
    !empty($idmotivoegr) && 
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
        
        // Resto el stock anterior por la cantidad de egreso
        
        $stock_nuevo = (int)$stock_anterior - (int)$cantidad;
        
        if((int)$stock_nuevo >= 0){
            
            $costo_total = (int)$cantidad  * (float)$producto['costo'];
            $precio_total = (int)$cantidad  * (float)$producto['precio'];
            $margen = (float)$producto['precio'] - (float)$producto['costo'];
            $margen_total = (float)$margen * (int)$cantidad;

            if($exis_prod_bod > 0){
                // Inserto el nuevo stock
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
                        INSERT INTO bodega_log_stock_egreso
                        ( idbodega, idproducto, idmotivoegr, 
                        cantidad, stock_anterior, stock_nuevo, 
                        comentario, 
                        costo, costo_total, 
                        precio, precio_total, 
                        margen, margen_total, 
                        user_create, sys_create) 
                        VALUES 
                        ('".$idbodega."','".$idproducto."','".$idmotivoegr."',
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
                $respuesta->estado = 2;
                $respuesta->mensaje = "El producto no tiene cargado stock como para proceder a realizar un egreso";
            } 
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "No puede restar mas de lo que se tiene en stock [".$stock_anterior."]";
        }
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "No se encontraron los datos del producto";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idbodega - idproducto - idmotivoegr - cantidad ]";
}

print_r(json_encode($respuesta));

?>