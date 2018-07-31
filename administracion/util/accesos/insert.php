<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();

$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

//****************************

$usuario = "";
$session_usuario = "";
$menuChekeado = array();

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) &&
    (isset($_POST['session_usuario']) && !empty($_POST['session_usuario']))
){
    
    $usuario = $_POST['usuario'];
    $session_usuario = $_POST['session_usuario'];
}

if(isset($_POST['menuChekeado'])){
    $menuChekeado = $_POST['menuChekeado'];
}

if(
    !empty($usuario) &&
    !empty($session_usuario)
){
    
    $resultadoDelete = $conexion->DBConsulta("
        DELETE FROM sys_usuarios_accesos 
        WHERE usuario = '".$usuario."'
    ");
    
    if($resultadoDelete == true){
        
        if(count($menuChekeado) > 0){
            
            for($f=0;$f<count($menuChekeado);$f++){
                $conexion->DBConsulta("
                    INSERT INTO sys_usuarios_accesos
                    (idmenu, usuario, 
                    visualizar, insertar, 
                    actualizar, eliminar, 
                    user_create, sys_create) 
                    VALUES 
                    ('".$menuChekeado[$f]['idmenu']."','".$usuario."',
                    '".$menuChekeado[$f]['visualizar']."','".$menuChekeado[$f]['insertar']."',
                    '".$menuChekeado[$f]['actualizar']."','".$menuChekeado[$f]['eliminar']."',
                    '".$session_usuario."',NOW())
                ");
            }
            
            $respuesta->estado = 1;
            $respuesta->mensaje = "Accesos otorgados";
        }else{
            $respuesta->estado = 1;
            $respuesta->mensaje = "Accesos eliminados";
        }
        
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Error al realizar la eliminación";
    }
}else{    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - session_usuario ]";
}

print_r(json_encode($respuesta));

?>