<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");
set_time_limit(0);
date_default_timezone_set('America/Guayaquil');

$respuesta = new stdClass();
$respuesta->estado = 1;
$respuesta->mensaje = "";

$conexion = new DBManager();
$conexion->DBConectar();

Funciones::EscribirLogs("actualizarhorarioatencion", "Inicio de cron");

//************************************************

$conexion->DBConsulta("
    UPDATE sys_cron_job SET 
    sys_start = NOW()
    WHERE idlogcron = '3'
");

/****************************** */
    $resultado = $conexion->DBConsulta("
        SELECT * 
        FROM bodega_atencion;
    ");
    if($resultado){
    $fecha = date("Y-m-d");
    $nuevafecha = '';
    $days = '';
    $idAtencion = array();
    $cont = 0;
    $sql = '';
    foreach($resultado as $fila){
        if($cont == 0){
            $sql .= "UPDATE bodega_atencion SET inicio='$fecha 09:00:00', fin='$fecha 23:00:00' WHERE idatencion =".$fila['idatencion'].";";
        }else{
            $days = '+'.$cont.' days';
            $nuevafecha = strtotime($days , strtotime($fecha));
            $nuevafecha = date('Y-m-d',$nuevafecha);
            $sql .= "UPDATE bodega_atencion SET inicio='$nuevafecha 09:00:00', fin='$nuevafecha 23:00:00' WHERE idatencion =".$fila['idatencion'].";";
        }
        $cont++;
    }
    $flagError = false;
    $querys = explode(";",$sql);
    foreach($querys as $query){
        $update = $conexion->DBConsulta("$query");
        if($update){
            $respuesta->mensaje .= "$query ok";
            Funciones::EscribirLogs("actualizarhorarioatencion", "$query ok");
        }else{
            $respuesta->mensaje .= "$query no ejecutado";
            Funciones::EscribirLogs("actualizarhorarioatencion", "$query no ejecutado");
        }
    }
    $respuesta = 'Todo Correcto';
    }else{
        $respuesta = 'Error al obtener datos para actualizar';
    }

    Funciones::EscribirLogs("actualizarhorarioatencion", "Fin de cron");

print_r(json_encode($respuesta));
?>