<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->resultado = array();

$idsector = 0;

if(
    (isset($_POST['idsector']) && !empty($_POST['idsector']))
){
    $idsector = $_POST['idsector'];
}

if(
    !empty($idsector)
){      
    // Aun queda verificar si ya se llego al total de pedidos establecidos para el horario
    // El id sector se trae para asociarlo con el id de bodega

    $resultado = $conexion->DBConsulta("
        SELECT a.* 
        FROM bodega_atencion AS a
        INNER JOIN bodegas_zonas AS b ON (a.idbodega = b.idbodega)
        INNER JOIN sectores AS c ON (b.idzona = c.idzona)
        WHERE a.inicio >= NOW()
        AND DATE(a.fin) <= CURDATE() + INTERVAL 1 DAY 
        AND c.idsector = '".$idsector."'
        AND (
            SELECT COUNT(*) 
            FROM cli_pedido_cabecera 
            WHERE idatencion = a.idatencion
            AND estado != 'CANCELADO'
        ) < a.total_pedidos
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->resultado[$cont]['idatencion'] = $fila['idatencion'];    
        $respuesta->resultado[$cont]['nombre'] = substr($fila['inicio'], 0, -3).' - '.substr(substr($fila['fin'], 0, -3), 11);

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>