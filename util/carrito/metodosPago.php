<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

// Extraer los datos

$resultado = $conexion->DBConsulta("
    SELECT *
    FROM cli_metodos_pago
    WHERE estado = 'ACTIVO'
");

$cont = 0;

foreach($resultado as $fila){
    $respuesta->resultado[$cont]['idmetodopago'] = $fila['idmetodopago'];    
    $respuesta->resultado[$cont]['nombre'] = $fila['nombre'].' ( entre $ '.$fila['minimo_compra'].' y $ '.$fila['maximo_compra'].' )';
    $respuesta->resultado[$cont]['minimo_compra'] = $fila['minimo_compra'];
    $respuesta->resultado[$cont]['maximo_compra'] = $fila['maximo_compra'];
    
    $cont++;
}

//****************************

print_r(json_encode($respuesta));

?>