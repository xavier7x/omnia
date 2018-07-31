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

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM sys_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************

$nombre = "";
$email = "";
$mensaje = "";

if(
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['email']) && !empty($_POST['email'])) && 
    (isset($_POST['mensaje']) && !empty($_POST['mensaje'])) 
){    
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = addslashes($_POST['mensaje']);
}

if(
    !empty($nombre) && 
    !empty($email) && 
    !empty($mensaje) 
){

    $body = 'Nombre: '.$nombre.'<br>';
    $body .= 'Email: '.$email.'<br>';
    $body .= 'Mensaje: '.$mensaje;

    $conexion->DBConsulta("
        INSERT INTO cli_envio_correo
        (idtipoalerta, usuario, email, titulo, cuerpo, user_create, sys_create) 
        VALUES 
        ('4','admin','".$email."','Formulario de contacto','".$body."','admin',NOW())
    ");
    
    $correos = explode(",", $pdet_valor['mailatencioncliente']);
    
    for($f=0; $f < count($correos); $f++){
        $conexion->DBConsulta("
            INSERT INTO sys_envio_correo
            (idtipoalerta, usuario, email, titulo, cuerpo, user_create, sys_create) 
            VALUES 
            ('2','admin','".$correos[$f]."','Formulario de contacto','".$body."','admin',NOW())
        ");
    }

    //******************************************

    $respuesta->estado = 1;
    $respuesta->mensaje = "Estimado se ha enviado un correo con sus dudas, pronto nos pondremos en contacto con usted";
    
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ nombre - email - mensaje ]";
}

print_r(json_encode($respuesta));

?>