<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");
include("../system/encryp.php");

$conexion = new DBManager();
$conexion->DBConectar();
$desencryptacion = new EnDecryptText();
$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->error = 0;
$respuesta->errores = array();
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

$nombre = "";
$mail = "";
$usuario = "";
$anterior_contrasena = "";
$nueva_contrasena = "";
$confirmar_contrasena = "";
$cambiar_contrasena = 0;

if(
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['mail']) && !empty($_POST['mail'])) && 
    (isset($_POST['usuario']) && !empty($_POST['usuario']))
){
    $nombre = $_POST['nombre'];
    $mail = $_POST['mail'];
    $usuario = $_POST['usuario'];
}

if(
    (isset($_POST['anterior_contrasena']) && !empty($_POST['anterior_contrasena'])) && 
    (isset($_POST['nueva_contrasena']) && !empty($_POST['nueva_contrasena'])) && 
    (isset($_POST['confirmar_contrasena']) && !empty($_POST['confirmar_contrasena'])) && 
    (isset($_POST['cambiar_contrasena']) && !empty($_POST['cambiar_contrasena']))
){
    $anterior_contrasena = $_POST['anterior_contrasena'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $cambiar_contrasena = $_POST['cambiar_contrasena'];
}

if(
    !empty($nombre) && 
    !empty($mail) && 
    !empty($usuario) 
){ 
    $flag = true;
    $permitidos = "abcdefghijklmnopqrstuvwxyz0123456789";
    
    if((int)$cambiar_contrasena == 1){
        // Verificar que la contraseña anterior sea igual
        
        $bd_contrasena = '';
        
        $resultado = $conexion->DBConsulta("
            SELECT contrasena
            FROM cli_usuarios
            WHERE usuario = '".$usuario."'
            LIMIT 1
        ");

        foreach($resultado as $fila){
            $bd_contrasena = $desencryptacion->Decrypt_Text($fila['contrasena']);
        }
        
        if(
            empty($bd_contrasena) || 
            $bd_contrasena !== $anterior_contrasena
        ){
            $respuesta->errores[]= "* La anterior contraseña no es válida.";
            $flag = false;
        }
        
        // Verificar si la contraseña cumple con la longitud minima y maxima
    
        if (strlen($nueva_contrasena) < 5 || strlen($nueva_contrasena) > 15){
            $respuesta->errores[]= "* La nueva contraseña no cumple con la longitud requerida.";
            $flag = false;
        } 
        
        // Verificar que la constraseña solo use minusculas y numeros
    
        for ($i=0; $i<strlen($nueva_contrasena); $i++){
            if (strpos($permitidos, substr($nueva_contrasena,$i,1)) === false){
                $respuesta->errores[]= "* La nueva contraseña debe contener solo números y letras minúscula.";
                $flag = false;
                break;
            }
        }
        
        // Verficar que las contraseñas sean iguales
    
        if ($nueva_contrasena !== $confirmar_contrasena){
            $respuesta->errores[]= "* Las nuevas contraseñas no son identicas.";
            $flag = false;
        } 
        
        // Verficar que las contraseñas sean iguales
    
        if ($anterior_contrasena === $nueva_contrasena){
            $respuesta->errores[]= "* Las nueva contraseña no puede ser identica a la anterior.";
            $flag = false;
        } 
    }
    
    // Verificar si el mail ya esta siendo usado en la aplicacion, considerando todas las tablas de usuario
    
    $totalMail = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_usuarios
        WHERE mail = '".$mail."'
        AND usuario != '".$usuario."'
    ");
    
    foreach($resultado as $fila){
        $totalMail += $fila['total'];    
    } 
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM sys_usuarios
        WHERE mail = '".$mail."'
        AND usuario != '".$usuario."'
    ");
    
    foreach($resultado as $fila){
        $totalMail += $fila['total'];    
    } 
    
    if($totalMail > 0){
        $respuesta->errores[]= "* El email ya existe en la aplicación.";
        $flag = false;
    }
    
    //***************************************************************************
    
    if($flag == true){
        $contrasena_enc_sql = '';
        
        if((int)$cambiar_contrasena == 1){
            $contrasena_enc = $desencryptacion->Encrypt_Text($nueva_contrasena);
            $contrasena_enc_sql = " contrasena = '".$contrasena_enc."' , ";
        }
        
        $resultado = $conexion->DBConsulta("
            UPDATE cli_usuarios SET 
            nombre = '".$nombre."' ,
            mail = '".$mail."' ,
            ".$contrasena_enc_sql."
            user_update = '".$usuario."' ,
            sys_update = NOW()
            WHERE usuario = '".$usuario."'
        ");
        
        if($resultado == true){
            $respuesta->estado = 1;
            $respuesta->mensaje = "Actualización realizada con éxito";
        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "Error al realizar la actualización";
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = '';
        $respuesta->error = 1;
    }
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ nombre - mail - usuario ]";
}

print_r(json_encode($respuesta));

?>