<?php
include('session.php');
include('conexionMySql.php');
include("funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$out_session = new AdmSession();
$out_session->startSession();

//********  Log Open Con Sesion

if( 
    ( isset($_SESSION['usuario']) && !empty($_SESSION['usuario']) ) &&
    ( isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) )
){

    $conexion->DBConsulta("
        INSERT INTO sys_log_logout
        (ip, navegador, usuario, sys_date) 
        VALUES 
        ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."','".$_SESSION['usuario']."',NOW())
    ");
    
}

//**************************************

$out_session->endSession('../../');

?>