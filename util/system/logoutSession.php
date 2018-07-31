<?php
include('session.php');
include('conexionMySql.php');
include("funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$out_session = new AdmSession();
$out_session->startSession();

if( 
    ( isset($_SESSION['usuario']) && !empty($_SESSION['usuario']) ) &&
    ( isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) )
){

    //********  Log Open Con Sesion

    $conexion->DBConsulta("
        INSERT INTO cli_log_logout
        (ip, navegador, usuario, tipocliente, sys_date) 
        VALUES 
        ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."','".$_SESSION['usuario']."','".$_SESSION['tipocliente']."',NOW())
    ");
    
}

//**************************************

$out_session->endSession('../../');

?>