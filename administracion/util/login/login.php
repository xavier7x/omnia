<?php
include('../system/session.php');
include("../system/conexionMySql.php");
include("../system/encryp.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();
$desencryptacion = new EnDecryptText();
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
$nombre = "";
$administrador = "";
$contrasena = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) &&
    (isset($_POST['contrasena']) && !empty($_POST['contrasena']))
){
    
    $usuario = $_POST['usuario'];
    $contrasena  = $_POST['contrasena'];
}

if(
    !empty($usuario) && 
    !empty($contrasena)
){
    
    $existe = false;
    $contrasenaWeb = "";
    $cont = 0;
    // Extraigo los datos del usuario
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM sys_usuarios
        WHERE usuario = '".$usuario."'
        AND estado = 'ACTIVO'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $usuario = $fila['usuario'];
        $nombre = $fila['nombre'];
        $administrador = $fila['administrador'];
        $contrasenaWeb = $desencryptacion->Decrypt_Text($fila['contrasena']);
        $cont++;
    }
    
    // Verifico que la contraseña sea la correcta
    if($cont > 0){
        if($contrasena == $contrasenaWeb){
            $existe = true;
        }
    }
    
    if($existe == true){
        $login_session = new AdmSession();
        $login_session->createSession($usuario, $nombre, $administrador);
        
        $conexion->DBConsulta("
            INSERT INTO sys_log_login
            (ip, navegador, usuario, sys_date) 
            VALUES 
            ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."','".$usuario."',NOW())
        ");

        $respuesta->estado = 1;
        $respuesta->mensaje = "Estimado ".$nombre.", ingreso de  realizado con éxito";
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Usuario o contraseña inconrrectos";
    }
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [usuario - contrasena]";
}

print_r(json_encode($respuesta));

?>