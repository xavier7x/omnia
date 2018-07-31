<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->rows = array();

$idbodega = "";
$inicio = "";
$fin = "";
$actualizar = 0;

if(
    (isset($_POST['idbodega']) && !empty($_POST['idbodega'])) && 
    (isset($_POST['inicio']) && !empty($_POST['inicio'])) && 
    (isset($_POST['fin']) && !empty($_POST['fin'])) 
){
    $idbodega = $_POST['idbodega'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
}

if(
    (isset($_POST['actualizar']) && !empty($_POST['actualizar'])) 
){
    $actualizar = $_POST['actualizar'];
}

if(
    !empty($idbodega) && 
    !empty($inicio) && 
    !empty($fin) 
){  

    // Extraer los datos

    $resultado = $conexion->DBConsulta("
        SELECT *
        FROM cli_factura_cabecera
        WHERE idbodega = '".$idbodega."'
        AND DATE(inicio) >= '".$inicio."'
        AND DATE(fin) <= '".$fin."'
        ORDER BY idfactura DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        
        $respuesta->rows[$cont] = $fila;
        $respuesta->rows[$cont]['numero_factura'] = $fila['establecimiento'].'-'.$fila['punto_emision'].'-'.$fila['secuencial'];
        
        $button = '<button type="button" title="Visualizar" class="btn btn-primary gestion_select" data-idfactura="'.$fila['idfactura'].'">';
        $button .= '<span class="glyphicon glyphicon-search"></span>';
        $button .= '</button>';
        
        $respuesta->rows[$cont]['generada'] = 'NO';
        $cntGenerada = 0;
        
        if(file_exists('../../docfac/'.$fila['establecimiento'].'-'.$fila['punto_emision'].'-'.$fila['secuencial'].'.pdf')){
            
            $respuesta->rows[$cont]['generada'] = '<a href="docfac/'.$fila['establecimiento'].'-'.$fila['punto_emision'].'-'.$fila['secuencial'].'.pdf" target="_blank">SI</a>';
            $cntGenerada++;
            
        }
        
        if(
            $respuesta->rows[$cont]['generada'] == 'NO'
        ){
            
            $button .= '<button type="button" title="Imprimir" class="btn btn-info gestion_print" data-idfactura="'.$fila['idfactura'].'" data-numero_factura="'.$fila['establecimiento'].'-'.$fila['punto_emision'].'-'.$fila['secuencial'].'">';
            $button .= '<span class="glyphicon glyphicon-print"></span>';
            $button .= '</button>';
            
        }
        
        if(
            (int)$actualizar == 1 &&
            $fila['estado_interno'] == 'CREADA' && 
            $cntGenerada == 1
        ){
            
            $button .= '<button type="button" title="Cerrar" class="btn btn-warning gestion_update" data-idfactura="'.$fila['idfactura'].'"';
            $button .= ' data-idbodega="'.$idbodega.'" ';
            $button .= ' data-estado_interno="'.$fila['estado_interno'].'" data-sig_estado_interno="CERRADA">';
            $button .= '<span class="glyphicon glyphicon-saved"></span>';
            $button .= '</button>';

            $button .= '<button type="button" title="Cancelar" class="btn btn-danger gestion_delete" data-idfactura="'.$fila['idfactura'].'"';
            $button .= ' data-idbodega="'.$idbodega.'" ';
            $button .= ' data-estado_interno="'.$fila['estado_interno'].'" data-sig_estado_interno="CANCELADA">';
            $button .= '<span class="glyphicon glyphicon-trash"></span>';
            $button .= '</button>';
            
        }
        
        $respuesta->rows[$cont]['btn_gestion'] = $button;

        $cont++;
    }

}
//****************************

print_r(json_encode($respuesta));

?>