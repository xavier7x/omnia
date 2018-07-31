<?php
include("../system/session.php");
include("../system/conexionMySql.php");
include("../system/funciones.php");
include("../system/encryp.php");

$session = new AdmSession();
$session->startSession();

$conexion = new DBManager();
$conexion->DBConectar();
$desencryptacion = new EnDecryptText();
$respuesta = new stdClass();
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
$contrasena = "";
$idsector = 0;
$tipocliente = "";

$old_usuario = "";
$old_tipocliente = "";
$old_idsector = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['contrasena']) && !empty($_POST['contrasena'])) && 
    (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) && 
    (isset($_SESSION['tipocliente']) && !empty($_SESSION['tipocliente'])) && 
    (isset($_SESSION['idsector']) && !empty($_SESSION['idsector']))
){    
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    $old_usuario = $_SESSION['usuario'];
    $old_tipocliente = $_SESSION['tipocliente'];
    $old_idsector = $_SESSION['idsector'];
}

// Verificar que los de session no esten vacios y sin declarar

if(
    !empty($usuario) && 
    !empty($contrasena) && 
    !empty($old_usuario) && 
    !empty($old_tipocliente) && 
    !empty($old_idsector) 
){    
    $flag = false;
    
    // Verificar que exista un cliente con el mismo email o usuario, tambien extraer el idsector
    
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_usuarios
        WHERE ( usuario = '".$usuario."'
        OR mail = '".$usuario."')
        AND estado = 'ACTIVO'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $usuario = $fila['usuario'];
        $idsector = $fila['idsector'];
        $tipocliente = $fila['tipocliente'];
        $prev_contrasena = $desencryptacion->Decrypt_Text($fila['contrasena']);
        
        if($contrasena === $prev_contrasena){
            $flag = true;
        }
    }
    
    //***************************************************************************
    
    if($flag == true){
        // Resetear las nuevas variables de sesion

        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipocliente'] = $tipocliente;
        $_SESSION['idsector'] = $idsector;
        
        // Eliminar algun carrito cabecera que ya tenga cargado con su verdadero usuario
        
        $conexion->DBConsulta("
            DELETE FROM cli_carrito_cabecera 
            WHERE usuario = '".$usuario."'
        ");

        // Actualizar el carrito cabecera con el nuevo usuario

        $conexion->DBConsulta("
            UPDATE cli_carrito_cabecera SET 
            usuario = '".$usuario."',
            user_update = '".$usuario."',
            sys_update = NOW(),
            user_create = '".$usuario."'
            WHERE usuario = '".$old_usuario."'
        ");
        
        // Extraer el codigo de los productos y cantidad que tenga previamente cargados
        
        $productos_old = array();
        
        $resultado = $conexion->DBConsulta("
            SELECT *
            FROM cli_carrito_detalle 
            WHERE usuario = '".$usuario."'
        ");
        
        foreach($resultado as $fila){
            $productos_old[] = array(
                "idproducto" => $fila['idproducto'],
                "cantidad" => $fila['cantidad']
            );
        }
        
        // Extraer los productos nuevos que tenga cargados
        
        $productos_new = array();
        
        $resultado = $conexion->DBConsulta("
            SELECT *
            FROM cli_carrito_detalle 
            WHERE usuario = '".$old_usuario."'
        ");

        foreach($resultado as $fila){
            $productos_new[] = array(
                "idproducto" => $fila['idproducto'],
                "cantidad" => $fila['cantidad']
            );
        }
        
        // Verificar si tiene items ya añadidos en su carrito y proceder a eliminar los del usuario real
        
        $productos_delete = array();
        
        for($f=0; $f < count($productos_old); $f++){
            for($i=0; $i < count($productos_new); $i++){
                if($productos_old[$f]['idproducto'] == $productos_new[$i]['idproducto']){
                    // Si el producto anterior es igual a uno de los productos nuevos, se procede a eliminar el anterior
                    $conexion->DBConsulta("
                        DELETE FROM cli_carrito_detalle 
                        WHERE usuario = '".$usuario."'
                        AND idproducto = '".$productos_old[$f]['idproducto']."'
                    ");
                    
                    $productos_delete[] = array(
                        "idproducto" => $productos_old[$f]['idproducto'],
                        "cantidad" => $productos_old[$f]['cantidad']
                    );
                }
            }
        }
        
        // Añadir a la tabla de produtos eliminados
        
        for($f=0; $f < count($productos_delete); $f++){ 
            
            $conexion->DBConsulta("
                INSERT INTO cli_carrito_producto_eliminado
                (usuario, idproducto, cantidad, user_create, sys_create) 
                VALUES ('".$usuario."','".$productos_delete[$f]['idproducto']."',
                '".$productos_delete[$f]['cantidad']."','".$usuario."',NOW())
            ");       
            
        }

        // Actualizar el carrito detalle con el nuevo usuario

        $conexion->DBConsulta("
            UPDATE cli_carrito_detalle SET 
            usuario = '".$usuario."',
            user_update = '".$usuario."',
            sys_update = NOW(),
            user_create = '".$usuario."'
            WHERE usuario = '".$old_usuario."'
        ");

        // Actualizar los productos actualizados

        $conexion->DBConsulta("
            UPDATE cli_carrito_producto_actualizado SET 
            usuario = '".$usuario."',
            user_create = '".$usuario."'
            WHERE usuario = '".$old_usuario."'
        ");

        // Actualizar los productos eliminados

        $conexion->DBConsulta("
            UPDATE cli_carrito_producto_eliminado SET 
            usuario = '".$usuario."',
            user_create = '".$usuario."'
            WHERE usuario = '".$old_usuario."'
        ");
        
        // Guardar el registro
        
        $conexion->DBConsulta("
            INSERT INTO cli_log_login
            (ip, navegador, usuario, tipocliente, sys_date) 
            VALUES 
            ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."','".$_SESSION['usuario']."','".$_SESSION['tipocliente']."',NOW())
        ");
        
        //******************************************

        $respuesta->estado = 1;
        $respuesta->mensaje = "Ingreso realizado con éxito";
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Usuario o contraseña incorrectos";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - contrasena - SESSION_usuario - SESSION_tipocliente - SESSION_idsector ]";
}

print_r(json_encode($respuesta));

?>