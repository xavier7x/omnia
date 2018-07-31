<?php
include("../system/session.php");
include("../system/conexionMySql.php");
include("../system/funciones.php");

$session = new AdmSession();
$session->startSession();

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";
$respuesta->error = 0;
$respuesta->carrito_detalle = array();

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
$idfacturacion = 0;
$idenvio = 0;
$idatencion = 0;
$idmetodopago = 0;
$comentario = "";
$idsector = 0;
$idbodega = 0;
$costo_envio = 0;
$idpedido = 0;
$nombre = "";
$mail = "";
$bodega_nombre = "";
$productos = array();
$total_pedidos_horario = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idfacturacion']) && !empty($_POST['idfacturacion'])) && 
    (isset($_POST['idenvio']) && !empty($_POST['idenvio'])) && 
    (isset($_POST['idatencion']) && !empty($_POST['idatencion'])) && 
    (isset($_POST['idmetodopago']) && !empty($_POST['idmetodopago'])) && 
    (isset($_SESSION['idsector']) && !empty($_SESSION['idsector'])) 
){
    $usuario = $_POST['usuario'];
    $nombre = $_POST['usuario'];
    $idfacturacion = $_POST['idfacturacion'];
    $idenvio = $_POST['idenvio'];
    $idatencion = $_POST['idatencion'];
    $idmetodopago = $_POST['idmetodopago'];
    $idsector = $_SESSION['idsector'];
}

if(
    (isset($_POST['comentario']) && !empty($_POST['comentario'])) 
){
    $comentario = $_POST['comentario'];
}

if(
    !empty($usuario) && 
    !empty($idfacturacion) && 
    !empty($idenvio) && 
    !empty($idatencion) && 
    !empty($idmetodopago) && 
    !empty($idsector)
){
    // validar que se pueda crear pedidos
    if($pdet_valor['crearpedido'] == 'SI'){
        
        $totalTerminos = 0;
        
        $resultadoTotal = $conexion->DBConsulta("
            SELECT COUNT(*) AS total 
            FROM cli_usuario_terminos
            WHERE usuario = '".$usuario."'
        ");
        
        foreach($resultadoTotal as $filaTotal){
            $totalTerminos = $filaTotal['total'];
        }
        
        if( $totalTerminos > 0 ){
            // validar que el horario de entrega sea valido y tenga pedidos disponibles

            $resultado = $conexion->DBConsulta("
                SELECT COUNT(*) AS total 
                FROM bodega_atencion AS a
                WHERE a.inicio >= NOW()
                AND DATE(a.fin) <= CURDATE() + INTERVAL 1 DAY 
                AND a.idatencion = '".$idatencion."'
                AND (
                    SELECT COUNT(*) 
                    FROM cli_pedido_cabecera 
                    WHERE idatencion = '".$idatencion."'
                    AND estado != 'CANCELADO'
                ) < a.total_pedidos
            ");

            $atencion = 0;

            foreach($resultado as $fila){
                $atencion = $fila['total'];
            }

            if($atencion == 1){
                $datosAtencion = array();
                $datosMetodoPago = array();                       
                $datosEnvio = array();
                $datosFacturacion = array();

                // Recuperar el nombre del usuario

                $resultado = $conexion->DBConsulta("
                    SELECT nombre, mail 
                    FROM cli_usuarios
                    WHERE usuario = '".$usuario."'
                    LIMIT 1
                ");

                foreach($resultado as $fila){
                    $nombre = $fila['nombre'];
                    $mail = $fila['mail'];
                }

                // Recuperar los datos de atencion

                $resultado = $conexion->DBConsulta("
                    SELECT * 
                    FROM bodega_atencion
                    WHERE idatencion = '".$idatencion."'
                    LIMIT 1
                ");

                foreach($resultado as $fila){
                    $datosAtencion = $fila;
                }

                // Recuperar los datos de metodo pago

                $resultado = $conexion->DBConsulta("
                    SELECT * 
                    FROM cli_metodos_pago
                    WHERE idmetodopago = '".$idmetodopago."'
                    LIMIT 1
                ");

                foreach($resultado as $fila){
                    $datosMetodoPago = $fila;
                }

                // Recuperar los datos de envio

                $resultado = $conexion->DBConsulta("
                    SELECT a.*, 
                    b.nombre AS provincia_nom,
                    c.nombre AS canton_nom,
                    d.nombre AS zona_nom,
                    e.nombre AS sector_nom
                    FROM cli_datos_envio AS a
                    INNER JOIN provincias AS b ON (a.idprovincia = b.idprovincia)
                    INNER JOIN cantones AS c ON (a.idcanton = c.idcanton)
                    INNER JOIN zonas AS d ON (a.idzona = d.idzona)
                    INNER JOIN sectores AS e ON (a.idsector = e.idsector)
                    WHERE a.idenvio = '".$idenvio."'
                    LIMIT 1
                ");

                foreach($resultado as $fila){
                    $datosEnvio = $fila;
                }

                // Recuperar los datos de facturacion

                $resultado = $conexion->DBConsulta("
                    SELECT * 
                    FROM cli_datos_facturacion
                    WHERE idfacturacion = '".$idfacturacion."'
                    LIMIT 1
                ");

                foreach($resultado as $fila){
                    $datosFacturacion = $fila;
                }

                if( 
                    count($datosEnvio) > 0 && 
                    count($datosFacturacion) > 0 && 
                    count($datosAtencion) > 0 && 
                    count($datosMetodoPago) > 0 &&
                    !empty($nombre) &&
                    !empty($mail)
                ){

                    $respuesta->datosEnvio = $datosEnvio;
                    $respuesta->datosFacturacion = $datosFacturacion;
                    $respuesta->datosAtencion = $datosAtencion;
                    $respuesta->datosMetodoPago = $datosMetodoPago;

                    // Obtener el idbodega
                    $resultado = $conexion->DBConsulta("
                        SELECT idbodega
                        FROM bodegas_zonas
                        WHERE idzona = '".$datosEnvio['idzona']."'
                        LIMIT 1
                    ");

                    foreach($resultado as $fila){
                        $idbodega = $fila['idbodega'];
                    }                

                    if(
                        (int)$datosEnvio['idsector'] != 0 && 
                        (int)$idbodega != 0
                    ){
                        // Actualizar la variable de sesion del sector y la variable
                        $_SESSION['idsector'] = $datosEnvio['idsector'];
                        $idsector = $datosEnvio['idsector'];
                        $cnt_costo_envio = 0;

                        // Obtener el costo de envio
                        $resultadoCosto = $conexion->DBConsulta("
                            SELECT costo_envio
                            FROM sectores
                            WHERE idsector = '".$idsector."'
                            LIMIT 1
                        ");

                        foreach($resultadoCosto as $fila){
                            $costo_envio = $fila['costo_envio'];
                            $cnt_costo_envio++;
                        }

                        // Obtener el nombre de la bodega
                        $resultadoBodega = $conexion->DBConsulta("
                            SELECT nombre
                            FROM bodegas
                            WHERE idbodega = '".$idbodega."'
                            LIMIT 1
                        ");

                        foreach($resultadoBodega as $fila){
                            $bodega_nombre = $fila['nombre'];
                        } 

                        // Actualizar el ultimo sector seleccionado
                        $resultado = $conexion->DBConsulta("
                            UPDATE cli_usuarios SET 
                            idsector = '".$idsector."',
                            user_update = '".$usuario."',
                            sys_update = NOW()
                            WHERE usuario = '".$usuario."'
                        ");

                        if(
                            $resultado == true && 
                            $cnt_costo_envio > 0
                        ){
                            // Extraer el carrito detalle

                            $resultado = $conexion->DBConsulta("
                                SELECT a.idproducto, a.cantidad, b.nombre, b.estado, b.precio, c.valor, d.stock, d.minimo,
                                b.costo
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
                            $flagCarrito = true;

                            foreach($resultado as $fila){
                                $margen = ($fila['precio'] - $fila['costo']);
                                $margen_total = ($margen * (int)$fila['cantidad']);
                                $costo_total = ($fila['costo'] * (int)$fila['cantidad']);
                                $subtotal = ($fila['precio'] * (int)$fila['cantidad']);
                                $impuesto = ($subtotal * $fila['valor']) / 100;
                                $total = $subtotal + $impuesto;

                                $new_stock = ((int)$fila['stock'] - (int)$fila['cantidad']);
                                if((int)$new_stock < 0){
                                    $new_stock = 0;
                                }

                                $respuesta->carrito_detalle[$cont]['idproducto'] = $fila['idproducto'];    
                                $respuesta->carrito_detalle[$cont]['nombre'] = $fila['nombre'];
                                $respuesta->carrito_detalle[$cont]['estado'] = $fila['estado'];
                                $respuesta->carrito_detalle[$cont]['costo'] = $fila['costo'];
                                $respuesta->carrito_detalle[$cont]['precio'] = $fila['precio'];
                                $respuesta->carrito_detalle[$cont]['margen'] = $margen;
                                $respuesta->carrito_detalle[$cont]['cantidad'] = $fila['cantidad'];
                                $respuesta->carrito_detalle[$cont]['costo_total'] = $costo_total;
                                $respuesta->carrito_detalle[$cont]['margen_total'] = $margen_total;
                                $respuesta->carrito_detalle[$cont]['valor'] = $fila['valor'];
                                $respuesta->carrito_detalle[$cont]['stock'] = $fila['stock'];
                                $respuesta->carrito_detalle[$cont]['impuesto'] = $impuesto;
                                $respuesta->carrito_detalle[$cont]['subtotal'] = $subtotal;
                                $respuesta->carrito_detalle[$cont]['total'] = $total;
                                $respuesta->carrito_detalle[$cont]['new_stock'] = $new_stock;
                                $respuesta->carrito_detalle[$cont]['minimo'] = $fila['minimo'];
                                $respuesta->carrito_detalle[$cont]['valido'] = 1;

                                if(
                                    $fila['estado'] != 'ACTIVO' || 
                                    (int)$fila['stock'] == 0 ||
                                    (int)$fila['cantidad'] > (int)$fila['stock']
                                ){
                                    $flagCarrito = false;
                                    $respuesta->carrito_detalle[$cont]['valido'] = 0;
                                }

                                $cont++;
                            }

                            if(count($respuesta->carrito_detalle) > 0){

                                if(
                                    (int)$pdet_valor['maxitemscarrito'] == 0 ||
                                    count($respuesta->carrito_detalle) <= (int)$pdet_valor['maxitemscarrito'] 
                                ){

                                    if($flagCarrito == true){                            

                                        // Crear la cabecera del pedido, con datos de atencion y pago

                                        // Por motivo de promocion el costo de envio es cero
                                        //$costo_envio = 0;

                                        $resultado = $conexion->DBConsulta("
                                            INSERT INTO cli_pedido_cabecera
                                            (idbodega, usuario, estado, 
                                            estado_interno, comentario, 
                                            costo_total, margen_total, 
                                            descuento, impuesto, 
                                            subtotal, total, costo_envio, total_con_envio, 
                                            idatencion, inicio, fin, 
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
                                            ('".$idbodega."','".$usuario."','CREADO',
                                            'CREADO',".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                                            '0','0',
                                            '0','0',
                                            '0','0','".$costo_envio."','0',
                                            '".$datosAtencion['idatencion']."','".$datosAtencion['inicio']."','".$datosAtencion['fin']."',
                                            '".$datosMetodoPago['idmetodopago']."','".$datosMetodoPago['nombre']."',
                                            '".$datosEnvio['idenvio']."','".$datosEnvio['titulo']."',
                                            '".$datosEnvio['nombre']."','".$datosEnvio['direccion']."',
                                            '".$datosEnvio['movil1']."',".(empty($datosEnvio['movil2']) ? "NULL" : "'".$datosEnvio['movil2']."'").",
                                            '".$datosEnvio['idprovincia']."','".$datosEnvio['provincia_nom']."',
                                            '".$datosEnvio['idcanton']."','".$datosEnvio['canton_nom']."',
                                            '".$datosEnvio['idzona']."','".$datosEnvio['zona_nom']."',
                                            '".$datosEnvio['idsector']."','".$datosEnvio['sector_nom']."',
                                            '".$datosFacturacion['idfacturacion']."','".$datosFacturacion['titulo']."',
                                            '".$datosFacturacion['nombre']."','".$datosFacturacion['direccion']."',
                                            '".$datosFacturacion['num_doc']."','".$datosFacturacion['mail']."',
                                            '".$datosFacturacion['movil1']."',".(empty($datosFacturacion['movil2']) ? "NULL" : "'".$datosFacturacion['movil2']."'").",
                                            '".$usuario."',NOW())
                                        ");

                                        if($resultado == true){

                                            // Rescatar el ultimo idpedido del usuario
                                            $resultado = $conexion->DBConsulta("
                                                SELECT idpedido
                                                FROM cli_pedido_cabecera
                                                WHERE usuario = '".$usuario."'
                                                ORDER BY idpedido DESC
                                                LIMIT 1
                                            ");

                                            foreach($resultado as $fila){
                                                $idpedido = $fila['idpedido'];
                                            }

                                            if( (int)$idpedido != 0 ){
                                                // Crear el detalle del pedido
                                                $productos = $respuesta->carrito_detalle;

                                                for($f=0; $f < count($productos); $f++){

                                                    $resultado = $conexion->DBConsulta("
                                                        INSERT INTO cli_pedido_detalle
                                                        (idpedido, idproducto, nombre, 
                                                        costo, precio, 
                                                        margen, cantidad, 
                                                        costo_total, margen_total,
                                                        valor_descuento, valor_impuesto, 
                                                        descuento, impuesto, 
                                                        subtotal, total, 
                                                        user_create, sys_create) 
                                                        VALUES
                                                        ('".$idpedido."','".$productos[$f]['idproducto']."','".$productos[$f]['nombre']."',
                                                        '".$productos[$f]['costo']."','".$productos[$f]['precio']."',
                                                        '".$productos[$f]['margen']."','".$productos[$f]['cantidad']."',
                                                        '".$productos[$f]['costo_total']."','".$productos[$f]['margen_total']."',
                                                        '0','".$productos[$f]['valor']."',
                                                        '0','".$productos[$f]['impuesto']."',
                                                        '".$productos[$f]['subtotal']."','".$productos[$f]['total']."',
                                                        '".$usuario."',NOW())
                                                    ");

                                                }

                                                // Reprocesar los totales del cuerpo, considerando el costo de envio

                                                $resultado = $conexion->DBConsulta("
                                                    UPDATE cli_pedido_cabecera SET 
                                                    costo_total = (SELECT SUM(costo_total) FROM cli_pedido_detalle WHERE idpedido = '".$idpedido."'),
                                                    margen_total = (SELECT SUM(margen_total) FROM cli_pedido_detalle WHERE idpedido = '".$idpedido."'),
                                                    descuento = (SELECT SUM(descuento) FROM cli_pedido_detalle WHERE idpedido = '".$idpedido."'),
                                                    impuesto = (SELECT SUM(impuesto) FROM cli_pedido_detalle WHERE idpedido = '".$idpedido."'),
                                                    subtotal = (SELECT SUM(subtotal) FROM cli_pedido_detalle WHERE idpedido = '".$idpedido."'),
                                                    total = (SELECT SUM(total) FROM cli_pedido_detalle WHERE idpedido = '".$idpedido."'),
                                                    user_update = '".$usuario."',
                                                    sys_update = NOW()
                                                    WHERE idpedido = '".$idpedido."'
                                                ");

                                                $resultado = $conexion->DBConsulta("
                                                    UPDATE cli_pedido_cabecera,
                                                    (SELECT (a.total + a.costo_envio) AS mysum 
                                                    FROM cli_pedido_cabecera AS a WHERE a.idpedido = '".$idpedido."') AS b 
                                                    SET 
                                                    total_con_envio = b.mysum,
                                                    user_update = '".$usuario."',
                                                    sys_update = NOW()
                                                    WHERE idpedido = '".$idpedido."'
                                                ");

                                                // Actualizar al new stock por idbodega

                                                for($f=0; $f < count($productos); $f++){

                                                    $resultado = $conexion->DBConsulta("
                                                        UPDATE productos_stock SET 
                                                        stock = '".$productos[$f]['new_stock']."',
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
                                                    ('".$idpedido."','CREADO',".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                                                    '".$usuario."',NOW())
                                                ");

                                                // Guardar LOG del pedido SYS

                                                $conexion->DBConsulta("
                                                    INSERT INTO sys_pedido_log
                                                    (idpedido, proceso, comentario, 
                                                    user_create, sys_create) 
                                                    VALUES 
                                                    ('".$idpedido."','CREADO',".(empty($comentario) ? "NULL" : "'".$comentario."'").",
                                                    '".$usuario."',NOW())
                                                ");

                                                // Vaciar el carrito cabecera y detalle                                        

                                                $resultado = $conexion->DBConsulta("
                                                    DELETE FROM cli_carrito_cabecera 
                                                    WHERE usuario = '".$usuario."'
                                                ");

                                                $resultado = $conexion->DBConsulta("
                                                    DELETE FROM cli_carrito_detalle 
                                                    WHERE usuario = '".$usuario."'
                                                ");

                                                // Envio de alerta administracion ( 1 - CERO / MININO STOCK)                                        
                                                //--- Obtenemos los usuarios que tiene la alerta 1  

                                                $usuarios_alerta_1 = array();

                                                $resultado = $conexion->DBConsulta("
                                                    SELECT b.usuario, b.nombre, b.mail
                                                    FROM sys_usuario_alerta_bodegas AS a
                                                    INNER JOIN sys_usuarios AS b ON (a.usuario = b.usuario)
                                                    WHERE a.idtipoalertabod = '1'
                                                    AND a.idbodega = '".$idbodega."'
                                                    AND b.estado = 'ACTIVO'
                                                ");

                                                foreach($resultado as $fila){
                                                    $usuarios_alerta_1[] = array(
                                                        'usuario' => $fila['usuario'],
                                                        'nombre' => $fila['nombre'],
                                                        'mail' => $fila['mail']
                                                    );
                                                }

                                                $flagAlerta1 = false;
                                                $alertaCuerpo1 = '<table border="1">';
                                                $alertaCuerpo1 .= '<thead>';
                                                $alertaCuerpo1 .= '<tr>';
                                                $alertaCuerpo1 .= '<th>IDPRODUCTO</th>';
                                                $alertaCuerpo1 .= '<th>PRODUCTO</th>';
                                                $alertaCuerpo1 .= '<th>STOCK</th>';
                                                $alertaCuerpo1 .= '<th>MINIMO</th>';
                                                $alertaCuerpo1 .= '</tr>';                                    
                                                $alertaCuerpo1 .= '</thead>';
                                                $alertaCuerpo1 .= '<tbody>';

                                                for($f = 0; $f < count($productos); $f++){
                                                    if(
                                                        (int)$productos[$f]['new_stock'] == 0 ||
                                                        (int)$productos[$f]['new_stock'] <= (int)$productos[$f]['minimo']
                                                    ){
                                                        $flagAlerta1 = true;

                                                        $alertaCuerpo1 .= '<tr>';
                                                        $alertaCuerpo1 .= '<td>'.$productos[$f]['idproducto'].'</td>';
                                                        $alertaCuerpo1 .= '<td>'.$productos[$f]['nombre'].'</td>';
                                                        $alertaCuerpo1 .= '<td>'.$productos[$f]['new_stock'].'</td>';
                                                        $alertaCuerpo1 .= '<td>'.$productos[$f]['minimo'].'</td>';
                                                        $alertaCuerpo1 .= '</tr>';
                                                    }
                                                }

                                                $alertaCuerpo1 .= '</tbody>';
                                                $alertaCuerpo1 .= '</table>';

                                                if($flagAlerta1 == true){

                                                    for($f=0; $f < count($usuarios_alerta_1); $f++){

                                                        $body_1 = 'Estimado '.$usuarios_alerta_1[$f]['nombre'].',<br><br>';
                                                        $body_1 .= 'Los siguientes productos tienen cambios relevantes en su stock<br>';
                                                        $body_1 .= 'IDBODEGA: '.$idbodega.'<br>';
                                                        $body_1 .= 'NOMBRE BODEGA: '.$bodega_nombre.'<br><br>';
                                                        $body_1 .= $alertaCuerpo1.'<br><br>';

                                                        $conexion->DBConsulta("
                                                            INSERT INTO sys_envio_correo
                                                            (idtipoalertabod, usuario, email, titulo, cuerpo, user_create, sys_create) 
                                                            VALUES 
                                                            ('1','".$usuarios_alerta_1[$f]['usuario']."','".$usuarios_alerta_1[$f]['mail']."','Stock productos','".$body_1."','".$usuario."',NOW())
                                                        ");

                                                    }

                                                }

                                                // Envio de alerta administracion ( 2 - CREACION PEDIDO)                                        
                                                //--- Obtenemos los usuarios que tiene la alerta 2  

                                                $usuarios_alerta_2 = array();

                                                $resultado = $conexion->DBConsulta("
                                                    SELECT b.usuario, b.nombre, b.mail
                                                    FROM sys_usuario_alerta_bodegas AS a
                                                    INNER JOIN sys_usuarios AS b ON (a.usuario = b.usuario)
                                                    WHERE a.idtipoalertabod = '2'
                                                    AND a.idbodega = '".$idbodega."'
                                                    AND b.estado = 'ACTIVO'
                                                ");

                                                foreach($resultado as $fila){
                                                    $usuarios_alerta_2[] = array(
                                                        'usuario' => $fila['usuario'],
                                                        'nombre' => $fila['nombre'],
                                                        'mail' => $fila['mail']
                                                    );
                                                }

                                                for($f=0; $f < count($usuarios_alerta_2); $f++){

                                                    $body_2 = 'Estimado '.$usuarios_alerta_2[$f]['nombre'].',<br><br>';
                                                    $body_2 .= 'Se ha creado con éxito el pedido # '.$idpedido.'<br><br>';

                                                    $conexion->DBConsulta("
                                                        INSERT INTO sys_envio_correo
                                                        (idtipoalertabod, usuario, email, titulo, cuerpo, user_create, sys_create) 
                                                        VALUES 
                                                        ('2','".$usuarios_alerta_2[$f]['usuario']."','".$usuarios_alerta_2[$f]['mail']."','Creación de pedido','".$body_2."','".$usuario."',NOW())
                                                    ");

                                                }

                                                // Envio de alerta administracion ( 3 - HORARIO SIN CUPO PEDIDOS)
                                                //--- Obtenemos los usuarios que tiene la alerta 3                                         

                                                $usuarios_alerta_3 = array();

                                                $resultado = $conexion->DBConsulta("
                                                    SELECT b.usuario, b.nombre, b.mail
                                                    FROM sys_usuario_alerta_bodegas AS a
                                                    INNER JOIN sys_usuarios AS b ON (a.usuario = b.usuario)
                                                    WHERE a.idtipoalertabod = '3'
                                                    AND a.idbodega = '".$idbodega."'
                                                    AND b.estado = 'ACTIVO'
                                                ");

                                                foreach($resultado as $fila){
                                                    $usuarios_alerta_3[] = array(
                                                        'usuario' => $fila['usuario'],
                                                        'nombre' => $fila['nombre'],
                                                        'mail' => $fila['mail']
                                                    );
                                                }

                                                //---Total de pedido en el horarios

                                                $resultado = $conexion->DBConsulta("
                                                    SELECT COUNT(*) AS total
                                                    FROM cli_pedido_cabecera 
                                                    WHERE idatencion = '".$idatencion."'
                                                    AND estado != 'CANCELADO'
                                                ");

                                                foreach($resultado as $fila){
                                                    $total_pedidos_horario = $fila['total'];
                                                }

                                                if((int)$datosAtencion['total_pedidos'] == (int)$total_pedidos_horario){

                                                    for($f=0; $f < count($usuarios_alerta_3); $f++){

                                                        $body_3 = 'Estimado '.$usuarios_alerta_3[$f]['nombre'].',<br><br>';
                                                        $body_3 .= 'El horario [ '.$datosAtencion['inicio'].' - '.$datosAtencion['fin'].' ] ha llegado a su limite maximo de pedidos [ '.$datosAtencion['total_pedidos'].' ]<br>';
                                                        $body_3 .= 'IDBODEGA: '.$idbodega.'<br>';
                                                        $body_3 .= 'NOMBRE BODEGA: '.$bodega_nombre.'<br><br>';

                                                        $conexion->DBConsulta("
                                                            INSERT INTO sys_envio_correo
                                                            (idtipoalertabod, usuario, email, titulo, cuerpo, user_create, sys_create) 
                                                            VALUES 
                                                            ('3','".$usuarios_alerta_3[$f]['usuario']."','".$usuarios_alerta_3[$f]['mail']."','Maximo de pedidos','".$body_3."','".$usuario."',NOW())
                                                        ");

                                                    }

                                                }

                                                // Envio de alerta al cliente

                                                $body = 'Estimado '.$nombre.',<br><br>';
                                                $body .= 'Se ha creado con éxito su pedido # '.$idpedido.'<br>';
                                                $body .= 'Para mas detalle puede ingresar al portal <a href="'.$pdet_valor['hostapp'].'">'.$pdet_valor['hostwww'].'</a><br><br>';

                                                $conexion->DBConsulta("
                                                    INSERT INTO cli_envio_correo
                                                    (idtipoalerta, usuario, email, titulo, cuerpo, user_create, sys_create) 
                                                    VALUES 
                                                    ('3','".$usuario."','".$mail."','Creación de pedido','".$body."','".$usuario."',NOW())
                                                ");


                                                $respuesta->estado = 1;
                                                $respuesta->mensaje = "Pedido # ".$idpedido.", creado con éxito";
                                            }else{
                                                $respuesta->estado = 2;
                                            $respuesta->mensaje = "Error del sistema en [ idpedido ]";
                                            }
                                        }else{
                                            $respuesta->estado = 2;
                                            $respuesta->mensaje = "Error del sistema en [ CREACION PEDIDO ]";
                                        }
                                    }else{
                                        $respuesta->estado = 2;
                                        $respuesta->mensaje = 'Estimado los siguientes productos presentan inconvenientes en su stock o estado:';
                                        $respuesta->error = 2;
                                    }
                                }else{
                                    $respuesta->estado = 2;
                                    $respuesta->mensaje = 'Ha excedido el límite máximo de items en el carrito ('.$pdet_valor['maxitemscarrito'].')';
                                }
                            }else{
                                $respuesta->estado = 2;
                                $respuesta->mensaje = 'No tiene productos en el carrito';
                            }
                        }else{
                            $respuesta->estado = 2;
                            $respuesta->mensaje = "Error del sistema al actualizar el [ idsector - costo_envio ] del usuario";
                        }
                    }else{
                        $respuesta->estado = 2;
                        $respuesta->mensaje = "Error del sistema en [ datosEnvio['idsector'] - idbodega ]";
                    }

                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error del sistema en [ datosEnvio - datosFacturacion - datosAtencion - datosMetodoPago - nombre - mail ]";
                }

            }else{
                $respuesta->estado = 2;
                $respuesta->mensaje = "El horario de recepción esta inactivo, favor seleccionar otro";
                $respuesta->error = 1;
            }
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "";
            $respuesta->error = 3;
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Por el momento no se pueden generar pedidos";
    }
}else{    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idfacturacion - idenvio - idatencion - idmetodopago - idsector ]";
}

print_r(json_encode($respuesta));

?>