<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

$idbodega = 0;
$idproducto = 0;

if(
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) 
){
    $idbodega = $_POST['idbodega'];
    $idproducto = $_POST['idproducto'];
}

if(
    !empty($idbodega) && 
    !empty($idproducto) 
){ 
    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT a.*, b.nombre AS nombre_motivo
        FROM bodega_log_stock_egreso AS a
        INNER JOIN bodega_motivos_egreso AS b ON (a.idmotivoegr = b.idmotivoegr) 
        WHERE a.idbodega = '".$idbodega."'
        AND a.idproducto = '".$idproducto."'
        ORDER BY a.idegresobod DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        $respuesta->rows[$cont] = $fila;

        $cont++;
    }
}

//****************************

print_r(json_encode($respuesta));

?>