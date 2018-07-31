<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";

// Extraer los parametros

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM cli_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************

$usuario = "";
$titulo = "";
$nombre = "";
$movil1 = "";
$movil2 = "";
$direccion = "";
$estado = "";
$idprovincia = 0;
$idcanton = 0;
$idzona = 0;
$idsector = 0;

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['titulo']) && !empty($_POST['titulo'])) && 
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['movil1']) && !empty($_POST['movil1'])) && 
    (isset($_POST['direccion']) && !empty($_POST['direccion'])) && 
    (isset($_POST['estado']) && !empty($_POST['estado'])) && 
    (isset($_POST['idprovincia']) && !empty($_POST['idprovincia'])) && 
    (isset($_POST['idcanton']) && !empty($_POST['idcanton'])) && 
    (isset($_POST['idzona']) && !empty($_POST['idzona'])) && 
    (isset($_POST['idsector']) && !empty($_POST['idsector']))
    
){
    $usuario = $_POST['usuario'];
    $titulo = $_POST['titulo'];
    $nombre = $_POST['nombre'];
    $movil1 = $_POST['movil1'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
    $idprovincia = $_POST['idprovincia'];
    $idcanton = $_POST['idcanton'];
    $idzona = $_POST['idzona'];
    $idsector = $_POST['idsector'];
}

if(
    (isset($_POST['movil2']) && !empty($_POST['movil2'])) 
){
    $movil2 = $_POST['movil2'];
}

if(
    !empty($usuario) && 
    !empty($titulo) && 
    !empty($nombre) && 
    !empty($movil1) && 
    !empty($direccion) && 
    !empty($estado) && 
    !empty($idprovincia) && 
    !empty($idcanton) && 
    !empty($idzona) && 
    !empty($idsector) 
){ 


    // Verificar si trae un movil2 que es opcional
    $movil2_text = " NULL ";
    
    if($movil2 != ''){
        $movil2_text = " '".$movil2."' ";
    }

    $resultado = $conexion->DBConsulta("
        INSERT INTO cli_datos_envio
        (usuario, estado, titulo, 
        nombre, direccion, movil1, movil2, 
        idprovincia, idcanton, idzona, idsector, 
        user_create, sys_create) 
        VALUES 
        ('".$usuario."','".$estado."','".$titulo."',
        '".$nombre."','".$direccion."','".$movil1."',".$movil2_text.",
        '".$idprovincia."','".$idcanton."','".$idzona."','".$idsector."',
        '".$usuario."', NOW())
    ");
    
    if($resultado == true){    
        $respuesta->estado = 1;
        $respuesta->mensaje = "Registro guardado con éxito";
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Error al realizar la inserción";
    }
    
}else{    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - titulo - nombre - movil1 - direccion - estado - idprovincia - idcanton - idzona - idsector ]";
}

print_r(json_encode($respuesta));

?>