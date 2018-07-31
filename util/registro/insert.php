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
$respuesta->error = 0;
$respuesta->errores = array();

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

$nombre = "";
$mail = "";
$usuario = "";
$contrasena = "";
$confirmar_contrasena = "";
$idprovincia = 0;
$idcanton = 0;
$idzona = 0;
$idsector = 0;

$old_usuario = "";
$old_tipocliente = "";
$old_idsector = 0;

if(
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['mail']) && !empty($_POST['mail'])) && 
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['contrasena']) && !empty($_POST['contrasena'])) && 
    (isset($_POST['confirmar_contrasena']) && !empty($_POST['confirmar_contrasena'])) && 
    (isset($_POST['idprovincia']) && !empty($_POST['idprovincia'])) && 
    (isset($_POST['idcanton']) && !empty($_POST['idcanton'])) && 
    (isset($_POST['idzona']) && !empty($_POST['idzona'])) && 
    (isset($_POST['idsector']) && !empty($_POST['idsector'])) && 
    (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) && 
    (isset($_SESSION['tipocliente']) && !empty($_SESSION['tipocliente'])) && 
    (isset($_SESSION['idsector']) && !empty($_SESSION['idsector']))
){
    $nombre = $_POST['nombre'];
    $mail = $_POST['mail'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $idprovincia = $_POST['idprovincia'];
    $idcanton = $_POST['idcanton'];
    $idzona = $_POST['idzona'];
    $idsector = $_POST['idsector'];
    
    $old_usuario = $_SESSION['usuario'];
    $old_tipocliente = $_SESSION['tipocliente'];
    $old_idsector = $_SESSION['idsector'];
}

// Verificar que los de session no esten vacios y sin declarar

if(
    !empty($nombre) && 
    !empty($mail) && 
    !empty($usuario) && 
    !empty($contrasena) && 
    !empty($confirmar_contrasena) && 
    !empty($idprovincia) && 
    !empty($idcanton) && 
    !empty($idzona) && 
    !empty($idsector) && 
    !empty($old_usuario) && 
    !empty($old_tipocliente) && 
    !empty($old_idsector) 
){ 
    $flag = true;
    $permitidos = "abcdefghijklmnopqrstuvwxyz0123456789";
    
    // Verificar si el usuario existe en la aplicacion, considerando todas las tablas de usuario
    
    $totalUsuario = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_usuarios
        WHERE usuario = '".$usuario."'
    ");
    
    foreach($resultado as $fila){
        $totalUsuario += $fila['total'];    
    } 
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM sys_usuarios
        WHERE usuario = '".$usuario."'
    ");
    
    foreach($resultado as $fila){
        $totalUsuario += $fila['total'];    
    } 
    
    if(
        $totalUsuario > 0
    ){
        $respuesta->errores[] = "* El usuario ya existe en la aplicación.";
        $flag = false;
    }
    
    // Que el usuario no sea una palabra reservada del sistema (admin - visitante - cliente)
    
    if (
        $usuario == 'admin' || 
        $usuario == 'visitante' || 
        $usuario == 'cliente' 
    ){
        $respuesta->errores[] = "* El usuario ya esta siendo usado.";
        $flag = false;
    }
    
    // Verificar si el usuario cumple con la longitud minima y maxima
    
    if (strlen($usuario) < 5 || strlen($usuario) > 15){
        $respuesta->errores[] = "* El usuario no cumple con la longitud requerida.";
        $flag = false;
    } 
    
    // Verificar que el usuario solo use minusculas y numeros
    
    for ($i=0; $i<strlen($usuario); $i++){
        if (strpos($permitidos, substr($usuario,$i,1)) === false){
            $respuesta->errores[] = "* El usuario debe contener solo números y letras minúscula.";
            $flag = false;
            break;
        }
    }
    
    // Verificar si la contraseña cumple con la longitud minima y maxima
    
    if (strlen($contrasena) < 5 || strlen($contrasena) > 15){
        $respuesta->errores[] = "* La contraseña no cumple con la longitud requerida.";
        $flag = false;
    } 
    
    // Verificar que la constraseña solo use minusculas y numeros
    
    for ($i=0; $i<strlen($contrasena); $i++){
        if (strpos($permitidos, substr($contrasena,$i,1)) === false){
            $respuesta->errores[] = "* La contraseña debe contener solo números y letras minúscula.";
            $flag = false;
            break;
        }
    }
    
    // Verficar que las contraseñas sean iguales
    
    if ($contrasena !== $confirmar_contrasena){
        $respuesta->errores[] = "* Las contraseñas no son identicas.";
        $flag = false;
    } 
    
    // Verificar si el mail ya esta siendo usado en la aplicacion, considerando todas las tablas de usuario
    
    $totalMail = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_usuarios
        WHERE mail = '".$mail."'
    ");
    
    foreach($resultado as $fila){
        $totalMail += $fila['total'];    
    } 
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM sys_usuarios
        WHERE mail = '".$mail."'
    ");
    
    foreach($resultado as $fila){
        $totalMail += $fila['total'];    
    } 
    
    if($totalMail > 0){
        $respuesta->errores[] = "* El email ya existe en la aplicación.";
        $flag = false;
    }
    
    //***************************************************************************
    
    if($flag == true){
        $contrasena_enc = $desencryptacion->Encrypt_Text($contrasena);
        
        $resultado = $conexion->DBConsulta("
            INSERT INTO cli_usuarios
            (usuario, nombre, mail, estado, contrasena, tipocliente, idsector, user_create, sys_create) 
            VALUES 
            ('".$usuario."','".$nombre."','".$mail."','ACTIVO','".$contrasena_enc."','cliente','".$idsector."','".$usuario."',NOW())
        ");
        
        if($resultado == true){
            
            // Resetear las nuevas variables de sesion
            
            $_SESSION['usuario'] = $usuario;
            $_SESSION['tipocliente'] = 'cliente';
            $_SESSION['idsector'] = $idsector;
            
            // Actualizar el carrito cabecera con el nuevo usuario
            
            $conexion->DBConsulta("
                UPDATE cli_carrito_cabecera SET 
                usuario = '".$usuario."',
                user_update = '".$usuario."',
                sys_update = NOW(),
                user_create = '".$usuario."'
                WHERE usuario = '".$old_usuario."'
            ");
            
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
            
            // Guardar el registro para enviar el mail al cliente que se ha registrado en nuestra aplicacion
            
            $body = 'Estimado '.$nombre.',<br><br>';
            $body .= 'Se ha creado exitosamente el usuario en nuestra aplicación.<br><br>';
            $body .= 'Usuario: '.$usuario.'<br>';
            $body .= 'Contraseña: '.$contrasena.'<br>';
            
            $conexion->DBConsulta("
                INSERT INTO cli_envio_correo
                (idtipoalerta, usuario, email, titulo, cuerpo, user_create, sys_create) 
                VALUES 
                ('1','".$usuario."','".$mail."','Creación de usuario','".$body."','".$usuario."',NOW())
            ");
            
            $respuesta->estado = 1;
            $respuesta->mensaje = "Gracias por registrate";
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "Error al realizar la inserción";
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = '';
        $respuesta->error = 1;
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ nombre - mail - usuario - contrasena - confirmar_contrasena - idprovincia - idcanton - idzona - idsector - SESSION_usuario - SESSION_tipocliente - SESSION_idsector ]";
}

print_r(json_encode($respuesta));

?>