<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

$usuario = "";
$idparametro = "";
$nombre = "";
$descripcion = "";
$valor = "";
$actualizado_por = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idparametro']) && !empty($_POST['idparametro'])) && 
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['descripcion']) && !empty($_POST['descripcion'])) && 
    (isset($_POST['valor']) && !empty($_POST['valor']))
    
){
    $usuario = $_POST['usuario'];
    $idparametro = $_POST['idparametro'];
    $nombre = $_POST['nombre'];
    $descripcion = ($_POST['descripcion']);
    $valor = $_POST['valor'];
}
if(
    !empty($usuario) && 
    !empty($idparametro) && 
    !empty($nombre) && 
    !empty($descripcion) && 
    !empty($valor)
){

                $resultado = $conexion->DBConsulta("
                UPDATE cli_parametros SET idparametro = '".$idparametro."', nombre='".$nombre."', 
                descripcion='".$descripcion."', valor='".$valor."',user_update='".$usuario."',
                sys_update=NOW()
                WHERE idparametro = '".$idparametro."';
                ");

                if($resultado == true){
                    
                    $msnChange = "";
                    $msnChange = $idparametro;
                    $respuesta->estado = 1;
                    $respuesta->mensaje = "Registro actualizado con éxito ".$msnChange;
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error al realizar la actualización";
                }
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idparametro - nombre - descripcion - valor - user_update ]";
}

print_r(json_encode($respuesta));

?>