<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

$usuario = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario']))
){
    $usuario = $_POST['usuario'];
}

if(
    !empty($usuario)
){  
    
    // Verificar si no tiene ningun dato de envio
    
    $contPrev = 0;
    
    $resultado = $conexion->DBConsulta("
        SELECT COUNT(*) AS total
        FROM cli_datos_envio
        WHERE usuario = '".$usuario."'
    ");

    foreach($resultado as $fila){
        $contPrev = $fila['total'];
    }
    
    if($contPrev == 0){
        // Extraer los datos

        $resultado = $conexion->DBConsulta("
            SELECT *
            FROM cli_datos_facturacion
            WHERE usuario = '".$usuario."'
            LIMIT 1
        ");

        $cont = 0;

        foreach($resultado as $fila){
            /*
            $respuesta->rows[$cont]['idfacturacion'] = $fila['idfacturacion'];
            $respuesta->rows[$cont]['titulo'] = $fila['titulo'];
            $respuesta->rows[$cont]['nombre'] = $fila['nombre'];    
            $respuesta->rows[$cont]['direccion'] = $fila['direccion']; 
            $respuesta->rows[$cont]['estado'] = $fila['estado'];
            $respuesta->rows[$cont]['sys_update'] = $fila['sys_update'];
            $respuesta->rows[$cont]['sys_create'] = $fila['sys_create'];
            */
            $respuesta->rows[$cont] = $fila;

            $cont++;
        }
    }

}
//****************************

print_r(json_encode($respuesta));

?>