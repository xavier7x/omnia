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
$idenvio = 0;
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
    (isset($_POST['idenvio']) && !empty($_POST['idenvio'])) && 
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
    $idenvio = $_POST['idenvio'];
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
    !empty($idenvio) && 
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
        UPDATE cli_datos_envio SET 
        estado = '".$estado."',
        titulo = '".$titulo."',
        nombre = '".$nombre."',
        direccion = '".$direccion."',
        movil1 = '".$movil1."',
        movil2 = ".$movil2_text.",
        idprovincia = '".$idprovincia."',
        idcanton = '".$idcanton."',
        idzona = '".$idzona."',
        idsector = '".$idsector."',
        user_update = '".$usuario."',
        sys_update = NOW()
        WHERE idenvio = '".$idenvio."'
    ");
    
    if($resultado == true){    
        $respuesta->estado = 1;
        $respuesta->mensaje = "Registro actualizado con éxito";
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "Error al realizar la actualización";
    }
    
}else{    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idenvio - titulo - nombre - movil1 - direccion - estado - idprovincia - idcanton - idzona - idsector ]";
}

print_r(json_encode($respuesta));

?>