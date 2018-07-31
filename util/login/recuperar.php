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
$nombre = "";
$mail = "";
$contrasena = "";
$estado = "";

if(
    (isset($_POST['mail']) && !empty($_POST['mail'])) 
){    
    $mail = $_POST['mail'];
}

// Verificar que los de session no esten vacios y sin declarar

if(
    !empty($mail) 
){    
    $flag = false;
    
    // Verificar que exista un cliente con el mail
    
    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_usuarios
        WHERE mail = '".$mail."'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $usuario = $fila['usuario'];        
        $contrasena = $desencryptacion->Decrypt_Text($fila['contrasena']);
        $nombre = $fila['nombre'];
        $mail = $fila['mail'];
        $estado = $fila['estado'];
        
        $flag = true;
    }
    
    //***************************************************************************
    
    if(
        !empty($usuario) &&
        !empty($contrasena) &&
        !empty($nombre) &&
        !empty($estado) 
    ){ 
    
        if($flag == true){

            $body = 'Estimado '.$nombre.',<br><br>';
            $body .= 'Sus datos de usuario son:<br><br>';
            $body .= 'Usuario: '.$usuario.'<br>';
            $body .= 'Contraseña: '.$contrasena.'<br>';
            $body .= 'Email: '.$mail.'<br>';
            $body .= 'Estado: '.$estado.'<br>';

            $conexion->DBConsulta("
                INSERT INTO cli_envio_correo
                (idtipoalerta, usuario, email, titulo, cuerpo, user_create, sys_create) 
                VALUES 
                ('2','".$usuario."','".$mail."','Recuperación de usuario','".$body."','".$usuario."',NOW())
            ");

            //******************************************

            $respuesta->estado = 1;
            $respuesta->mensaje = "Estimado se ha enviado un correo con sus datos de usuario, favor revise en la bandeja de entrada o spam";
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "El email que ingreso, no se encuentra registrado en la aplicación";
        }
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Error del sistema: Variable vacias";
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ mail ]";
}

print_r(json_encode($respuesta));

?>