<?php
include("../../lib/php/phpmailer/class.phpmailer.php");
include("../system/conexionMySql.php");
include("../system/funciones.php");
include("../system/encryp.php");
set_time_limit(0);

$desencryptacion = new EnDecryptText();
$conexion = new DBManager();
$conexion->DBConectar();
$respuesta = new stdClass();
$respuesta->estado = 1;
$respuesta->mensaje = "";

Funciones::EscribirLogs("cronenviocorreos", "Inicio de cron");

// Extraer los parametros SMTP

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM cli_smtp
    LIMIT 1
");

$smtp_valor = array();

foreach($resultado_param as $fila){
    $smtp_valor['auth'] = $fila['auth'];
    $smtp_valor['secure'] = $fila['secure'];
    $smtp_valor['host'] = $fila['host'];
    $smtp_valor['port'] = $fila['port'];
    $smtp_valor['username'] = $fila['username'];
    $smtp_valor['password'] = $desencryptacion->Decrypt_Text($fila['password']);
    $smtp_valor['from'] = $fila['from'];
    $smtp_valor['fromname'] = $fila['fromname'];
    $smtp_valor['altbody'] = $fila['altbody'];
    $smtp_valor['piecorreo'] = $fila['piecorreo'];
}

//************************************************
$conexion->DBConsulta("
    UPDATE cli_cron_job SET 
    sys_start = NOW()
    WHERE idlogcron = '1'
");

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM cli_envio_correo
    WHERE procesado IS NULL
");

$datos = array();
$cont = 0;

foreach($resultado as $fila){
    $datos[$cont]['id'] = $fila['id'];
    $datos[$cont]['procesado'] = 'NO';
    $datos[$cont]['error'] = "";
    $datos[$cont]['email'] = $fila['email'];
    $datos[$cont]['titulo'] = $fila['titulo'];
    $datos[$cont]['cuerpo'] = $fila['cuerpo'];
    
    $cont++;   
}

if(count($datos) > 0){
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    // Activamos / Desactivamos el “debug” de SMTP
    // 0 = Apagado
    // 1 = Mensaje de Cliente
    // 2 = Mensaje de Cliente y Servidor
    //$mail->SMTPDebug = 2;
    // Log del debug SMTP en formato HTML
    //$mail->Debugoutput = 'html';

    $mail->SMTPAuth = $smtp_valor['auth'];
    $mail->SMTPSecure = $smtp_valor['secure'];
    $mail->Port = $smtp_valor['port'];
    $mail->Host = $smtp_valor['host'];
    $mail->Username   = $smtp_valor['username'];
    $mail->Password = $smtp_valor['password'];
    $mail->From = $smtp_valor['from'];
    $mail->FromName = $smtp_valor['fromname'];
    $mail->AltBody = $smtp_valor['altbody'];
    
    for($f=0; $f < count($datos); $f++){
        $mail->AddAddress($datos[$f]['email'],'');
        $mail->Subject = $datos[$f]['titulo'];

        $body = '';
        $body .= $datos[$f]['cuerpo'];        
        $body .= $smtp_valor['piecorreo'];
        
        $mail->MsgHTML($body);
        
        if($mail->Send()){
            
            $datos[$f]['procesado'] = 'SI';
            
        }else{
            $msn = $mail->ErrorInfo;
            if(strlen($mail->ErrorInfo) > 350){
                $msn = substr($mail->ErrorInfo,350);
            }
            $datos[$f]['error'] = $msn;
            //break;
        }
        $mail->clearAddresses();
        $mail->clearAttachments();
        //break;
    }
    
    for($f=0; $f < count($datos); $f++){
        if(!empty($datos[$f]['error'])){
            $conexion->DBConsulta("
                UPDATE cli_envio_correo SET 
                procesado = '".$datos[$f]['procesado']."',
                error = '".$datos[$f]['error']."',
                user_update = 'admin',
                sys_update = NOW()
                WHERE id = '".$datos[$f]['id']."'
            ");
        }else{
            $conexion->DBConsulta("
                UPDATE cli_envio_correo SET 
                procesado = '".$datos[$f]['procesado']."',
                error = NULL,
                user_update = 'admin',
                sys_update = NOW()
                WHERE id = '".$datos[$f]['id']."'
            ");
        }
    }
}

//$respuesta->datos = $datos;

$conexion->DBConsulta("
    UPDATE cli_cron_job SET 
    sys_end = NOW()
    WHERE idlogcron = '1'
");
//************************************************

Funciones::EscribirLogs("cronenviocorreos", "Fin de cron");

print_r(json_encode($respuesta));

?>