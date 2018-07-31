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
$idfactura = 0;
$idbodega = 0;
$idpedido = 0;
$estado_interno = "";
$sig_estado_interno = "";
$establecimiento = "";
$punto_emision = "";
$secuencial = "";
$new_establecimiento = "";
$new_punto_emision = "";
$new_secuencial = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['idpedido']) && !empty($_POST['idpedido'])) && 
    (isset($_POST['estado_interno']) && !empty($_POST['estado_interno'])) && 
    (isset($_POST['sig_estado_interno']) && !empty($_POST['sig_estado_interno'])) &&
    (isset($_POST['establecimiento']) && !empty($_POST['establecimiento'])) &&
    (isset($_POST['punto_emision']) && !empty($_POST['punto_emision'])) &&
    (isset($_POST['secuencial']) && !empty($_POST['secuencial']))
){
    $usuario = $_POST['usuario'];
    $idbodega = $_POST['idbodega'];
    $idpedido = $_POST['idpedido'];
    $estado_interno = $_POST['estado_interno'];
    $sig_estado_interno = $_POST['sig_estado_interno'];
    $establecimiento = $_POST['establecimiento'];
    $punto_emision = $_POST['punto_emision'];
    $secuencial = $_POST['secuencial'];
}

if(
    !empty($usuario) && 
    !empty($idbodega) && 
    !empty($idpedido) && 
    !empty($estado_interno) && 
    !empty($sig_estado_interno) && 
    !empty($establecimiento) && 
    !empty($punto_emision) && 
    !empty($secuencial) 
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
        
        // Extraer el numero de facturacion y sumarle uno, ademas de añadir los ceros
        $cntSec = 0;
        $resultado = $conexion->DBConsulta("
            SELECT establecimiento, punto_emision, 
            LPAD( (secuencial + 1), ".$pdet_valor['longsecuencial'].", '0') AS secuencial_new
            FROM bodega_cajas
            WHERE idbodega = '".$idbodega."'
            LIMIT 1
        ");

        foreach($resultado as $fila){
            $new_establecimiento = $fila['establecimiento'];
            $new_punto_emision = $fila['punto_emision'];
            $new_secuencial = $fila['secuencial_new'];
            $cntSec++;
        }
        
        if($cntSec == 1){            
            // Validar que el numero de facturacion sea igual al que continua
            if(
                $establecimiento == $new_establecimiento && 
                $punto_emision == $new_punto_emision && 
                $secuencial == $new_secuencial 
            ){                
                $pedido_cabecera = array();
                $pedido_detalle = array();
                
                // Extarer los datos de la cabecera

                $resultado = $conexion->DBConsulta("
                    SELECT * 
                    FROM cli_pedido_cabecera
                    WHERE idpedido = '".$idpedido."'
                    LIMIT 1
                ");

                foreach($resultado as $fila){
                    $pedido_cabecera = $fila;
                }
                
                // Extarer los datos del detalle

                $resultado = $conexion->DBConsulta("
                    SELECT * 
                    FROM cli_pedido_detalle
                    WHERE idpedido = '".$idpedido."'
                ");

                foreach($resultado as $fila){
                    $pedido_detalle[] = $fila;
                }
                
                //$respuesta->pedido_cabecera = $pedido_cabecera;
                //$respuesta->pedido_detalle = $pedido_detalle;
                
                if(
                    count($pedido_cabecera) > 0 && 
                    count($pedido_detalle) > 0
                ){
                    // Guardar la cabecera de la factura
                    $resultado = $conexion->DBConsulta("
                        INSERT INTO cli_factura_cabecera
                        (idpedido, idbodega, usuario, 
                        estado, estado_interno, 
                        comentario,  
                        establecimiento, punto_emision, secuencial, 
                        costo_total, margen_total, 
                        descuento, impuesto, 
                        subtotal, total, 
                        costo_envio, idatencion, 
                        inicio, fin, 
                        idmetodopago, nombre_metodopago, 
                        idenvio, titulo_env, 
                        nombre_env, direccion_env, 
                        movil1_env, movil2_env, 
                        idprovincia, provincia_nom, 
                        idcanton, canton_nom, 
                        idzona, zona_nom, 
                        idsector, sector_nom, 
                        idfacturacion, titulo_fac, 
                        nombre_fac, direccion_fac, 
                        num_doc_fac, mail_fac, 
                        movil1_fac, movil2_fac, 
                        user_create, sys_create) 
                        VALUES 
                        ('".$idpedido."','".$idbodega."','".$pedido_cabecera['usuario']."',
                        'CREADA','CREADA',
                        ".(empty($pedido_cabecera['comentario']) ? "NULL" : "'".$pedido_cabecera['comentario']."'").",
                        '".$establecimiento."','".$punto_emision."','".$secuencial."',
                        '0','0',
                        '0','0',
                        '0','0',
                        '".$pedido_cabecera['costo_envio']."','".$pedido_cabecera['idatencion']."',
                        '".$pedido_cabecera['inicio']."','".$pedido_cabecera['fin']."',
                        '".$pedido_cabecera['idmetodopago']."','".$pedido_cabecera['nombre_metodopago']."',
                        '".$pedido_cabecera['idenvio']."','".$pedido_cabecera['titulo_env']."',
                        '".$pedido_cabecera['nombre_env']."','".$pedido_cabecera['direccion_env']."',
                        '".$pedido_cabecera['movil1_env']."',".(empty($pedido_cabecera['movil2_env']) ? "NULL" : "'".$pedido_cabecera['movil2_env']."'").",
                        '".$pedido_cabecera['idprovincia']."','".$pedido_cabecera['provincia_nom']."',
                        '".$pedido_cabecera['idcanton']."','".$pedido_cabecera['canton_nom']."',
                        '".$pedido_cabecera['idzona']."','".$pedido_cabecera['zona_nom']."',
                        '".$pedido_cabecera['idsector']."','".$pedido_cabecera['sector_nom']."',
                        '".$pedido_cabecera['idfacturacion']."','".$pedido_cabecera['titulo_fac']."',
                        '".$pedido_cabecera['nombre_fac']."','".$pedido_cabecera['direccion_fac']."',
                        '".$pedido_cabecera['num_doc_fac']."','".$pedido_cabecera['mail_fac']."',
                        '".$pedido_cabecera['movil1_fac']."',".(empty($pedido_cabecera['movil2_fac']) ? "NULL" : "'".$pedido_cabecera['movil2_fac']."'").",
                        '".$usuario."',NOW())
                    ");
                    
                    if($resultado == true){
                        // Actualizar el secuencial de la bodega-caja
                        $conexion->DBConsulta("
                            UPDATE bodega_cajas SET 
                            secuencial = '".(int)$secuencial."',
                            user_update = '".$usuario."',
                            sys_update = NOW()
                            WHERE idbodega = '".$idbodega."'
                        ");
                        
                        // Obtener el idfactura
                        $resultado = $conexion->DBConsulta("
                            SELECT idfactura
                            FROM cli_factura_cabecera
                            WHERE idpedido = '".$idpedido."'
                            AND idbodega = '".$idbodega."'
                            ORDER BY idfactura DESC
                            LIMIT 1
                        ");

                        foreach($resultado as $fila){
                            $idfactura = $fila['idfactura'];
                        }
                        
                        if( (int)$idfactura != 0 ){                            
                            // Guardar el detalle y el servicio de envio de la factura
                            for($f = 0 ; $f < count($pedido_detalle); $f++){
                                $conexion->DBConsulta("
                                    INSERT INTO cli_factura_detalle(
                                    idfactura, idpedido, idproducto, 
                                    nombre, costo, 
                                    precio, margen,
                                    cantidad, costo_total, 
                                    margen_total, valor_descuento, 
                                    valor_impuesto, descuento, 
                                    impuesto, subtotal, 
                                    total, 
                                    user_create, sys_create) 
                                    VALUES 
                                    ('".$idfactura."','".$idpedido."','".$pedido_detalle[$f]['idproducto']."',
                                    '".$pedido_detalle[$f]['nombre']."','".$pedido_detalle[$f]['costo']."',
                                    '".$pedido_detalle[$f]['precio']."','".$pedido_detalle[$f]['margen']."',
                                    '".$pedido_detalle[$f]['cantidad']."','".$pedido_detalle[$f]['costo_total']."',
                                    '".$pedido_detalle[$f]['margen_total']."','".$pedido_detalle[$f]['valor_descuento']."',
                                    '".$pedido_detalle[$f]['valor_impuesto']."','".$pedido_detalle[$f]['descuento']."',
                                    '".$pedido_detalle[$f]['impuesto']."','".$pedido_detalle[$f]['subtotal']."',
                                    '".$pedido_detalle[$f]['total']."',
                                    '".$usuario."',NOW())
                                ");
                            }
                            // Guardar el servicio de envio como detalle en factura
                            $conexion->DBConsulta("
                                INSERT INTO cli_factura_detalle(
                                idfactura, idpedido, idproducto, 
                                nombre, costo, 
                                precio, margen,
                                cantidad, costo_total, 
                                margen_total, valor_descuento, 
                                valor_impuesto, descuento, 
                                impuesto, subtotal, 
                                total, 
                                user_create, sys_create) 
                                VALUES 
                                ('".$idfactura."','".$idpedido."','0',
                                '".$pdet_valor['servicioenvio']."','".$pedido_cabecera['costo_envio']."',
                                '".$pedido_cabecera['costo_envio']."','0',
                                '1','".$pedido_cabecera['costo_envio']."',
                                '0','0',
                                '0','0',
                                '0','".$pedido_cabecera['costo_envio']."',
                                '".$pedido_cabecera['costo_envio']."',
                                '".$usuario."',NOW())
                            ");
                            
                            // Reprocesar los totales del cuerpo

                            $resultado = $conexion->DBConsulta("
                                UPDATE cli_factura_cabecera SET 
                                costo_total = (SELECT SUM(costo_total) FROM cli_factura_detalle WHERE idfactura = '".$idfactura."'),
                                margen_total = (SELECT SUM(margen_total) FROM cli_factura_detalle WHERE idfactura = '".$idfactura."'),
                                descuento = (SELECT SUM(descuento) FROM cli_factura_detalle WHERE idfactura = '".$idfactura."'),
                                impuesto = (SELECT SUM(impuesto) FROM cli_factura_detalle WHERE idfactura = '".$idfactura."'),
                                subtotal = (SELECT SUM(subtotal) FROM cli_factura_detalle WHERE idfactura = '".$idfactura."'),
                                subtotal_impuesto = (
                                    SELECT SUM(subtotal) FROM cli_factura_detalle 
                                    WHERE idfactura = '".$idfactura."'
                                    AND valor_impuesto > 0
                                ),
                                subtotal_exento = (
                                    SELECT SUM(subtotal) FROM cli_factura_detalle 
                                    WHERE idfactura = '".$idfactura."'
                                    AND valor_impuesto = 0
                                ),
                                total = (SELECT SUM(total) FROM cli_factura_detalle WHERE idfactura = '".$idfactura."'),
                                user_update = '".$usuario."',
                                sys_update = NOW()
                                WHERE idfactura = '".$idfactura."'
                            ");
                            
                            // Actualizar el estado del pedido cabecera a $sig_estado_interno
                            
                            $conexion->DBConsulta("
                                UPDATE cli_pedido_cabecera SET                                 
                                estado = '".$sig_estado_interno."',
                                estado_interno = '".$sig_estado_interno."',
                                user_update = '".$usuario."',
                                sys_update = NOW()
                                WHERE idpedido = '".$idpedido."'
                            ");
                            
                            // Guardar LOG del pedido CLI

                            $conexion->DBConsulta("
                                INSERT INTO cli_pedido_log
                                (idpedido, proceso, comentario, 
                                user_create, sys_create) 
                                VALUES 
                                ('".$idpedido."','".$sig_estado_interno."',NULL,
                                '".$usuario."',NOW())
                            ");

                            // Guardar LOG del pedido SYS

                            $conexion->DBConsulta("
                                INSERT INTO sys_pedido_log
                                (idpedido, proceso, comentario, 
                                user_create, sys_create) 
                                VALUES 
                                ('".$idpedido."','".$sig_estado_interno."',NULL,
                                '".$usuario."',NOW())
                            ");

                            // Guardar el log de la factura cliente
                            
                            $conexion->DBConsulta("
                                INSERT INTO cli_factura_log
                                (idfactura, proceso, comentario, 
                                user_create, sys_create) 
                                VALUES 
                                ('".$idfactura."','CREADA',NULL,
                                '".$usuario."',NOW())
                            ");

                            // Guardar el log de la factura sistema
                            
                            $conexion->DBConsulta("
                                INSERT INTO sys_factura_log
                                (idfactura, proceso, comentario, 
                                user_create, sys_create) 
                                VALUES 
                                ('".$idfactura."','CREADA',NULL,
                                '".$usuario."',NOW())
                            ");

                            $respuesta->estado = 1;
                            $respuesta->mensaje = "Factura generada con éxito";
                        }else{
                            
                        }
                    }else{
                        $respuesta->estado = 2;
                        $respuesta->mensaje = "Error del sistema en [ CREACION FACTURA ]";
                    }
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "No se obtuvieron los datos del pedido [ Cabecera - Detalle ]";
                }
            }else{
                $respuesta->estado = 2;
                $respuesta->mensaje = "El # factura (".$establecimiento."-".$punto_emision."-".$secuencial.") no esta disponible, se procedera a consultar nuevamente";
            }
            
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "No se obtuvo el secuencial";
        }
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "El pedido sufrio cambios en su estado, se procedera a consultar nuevamente";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idbodega - idpedido - estado_interno - sig_estado_interno - establecimiento - punto_emision - secuencial ]";
}

print_r(json_encode($respuesta));

?>