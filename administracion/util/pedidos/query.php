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
        FROM cli_pedido_cabecera
        WHERE idbodega = '".$idbodega."'
        AND DATE(inicio) >= '".$inicio."'
        AND DATE(fin) <= '".$fin."'
        ORDER BY idpedido DESC
    ");

    $cont = 0;

    foreach($resultado as $fila){
        /*
        $respuesta->rows[$cont]['idenvio'] = $fila['idenvio'];
        $respuesta->rows[$cont]['titulo'] = $fila['titulo'];
        $respuesta->rows[$cont]['nombre'] = $fila['nombre'];    
        $respuesta->rows[$cont]['direccion'] = $fila['direccion']; 
        $respuesta->rows[$cont]['estado'] = $fila['estado'];
        $respuesta->rows[$cont]['sys_update'] = $fila['sys_update'];
        $respuesta->rows[$cont]['sys_create'] = $fila['sys_create'];
        */
        $respuesta->rows[$cont] = $fila;
        
        $button = '<button type="button" title="Visualizar" class="btn btn-primary gestion_select" data-idpedido="'.$fila['idpedido'].'">';
        $button .= '<span class="glyphicon glyphicon-search"></span>';
        $button .= '</button>';
        
        $respuesta->rows[$cont]['generado'] = 'NO';
        $cntGenerado = 0;
        
        if(file_exists('../../docped/'.$fila['idpedido'].'.pdf')){
            
            $respuesta->rows[$cont]['generado'] = '<a href="docped/'.$fila['idpedido'].'.pdf" target="_blank">SI</a>';
            $cntGenerado++;
            
        }
        
        if(
            $respuesta->rows[$cont]['generado'] == 'NO'
        ){
            
            $button .= '<button type="button" title="Imprimir" class="btn btn-info gestion_print" data-idpedido="'.$fila['idpedido'].'">';
            $button .= '<span class="glyphicon glyphicon-print"></span>';
            $button .= '</button>';
            
        }
        
        if(
            (int)$actualizar == 1 &&
            $fila['estado_interno'] == 'CREADO'
            //$fila['estado_interno'] != 'CERRADO' 
        ){    
            if(
                $fila['estado_interno'] == 'CREADO'
            ){ 
                $button .= '<button type="button" title="Facturar" class="btn btn-warning gestion_update" data-idpedido="'.$fila['idpedido'].'"';
                $button .= ' data-idbodega="'.$idbodega.'" ';
                $button .= ' data-estado_interno="'.$fila['estado_interno'].'" data-sig_estado_interno="FACTURADO">';
                $button .= '<span class="glyphicon glyphicon-file"></span>';
                $button .= '</button>';
            }            
            /*
            if(
                $fila['estado_interno'] == 'FACTURADO'
            ){ 
                $button .= '<button type="button" title="Entregado" class="btn btn-warning gestion_update" data-idpedido="'.$fila['idpedido'].'"';
                $button .= ' data-idbodega="'.$idbodega.'" ';
                $button .= ' data-estado_interno="'.$fila['estado_interno'].'" data-sig_estado_interno="ENTREGADO">';
                $button .= '<span class="glyphicon glyphicon-user"></span>';
                $button .= '</button>';
            }
            
            if(
                $fila['estado_interno'] == 'ENTREGADO'
            ){ 
                $button .= '<button type="button" title="Cerrar" class="btn btn-warning gestion_update" data-idpedido="'.$fila['idpedido'].'"';
                $button .= ' data-idbodega="'.$idbodega.'" ';
                $button .= ' data-estado_interno="'.$fila['estado_interno'].'" data-sig_estado_interno="CERRADO">';
                $button .= '<span class="glyphicon glyphicon-star"></span>';
                $button .= '</button>';
            }
            */
            
            if(
                $fila['estado_interno'] == 'CREADO'
                //$fila['estado_interno'] == 'FACTURADO'
            ){
                $button .= '<button type="button" title="Cancelar" class="btn btn-danger gestion_delete" data-idpedido="'.$fila['idpedido'].'"';
                $button .= ' data-idbodega="'.$idbodega.'" ';
                $button .= ' data-estado_interno="'.$fila['estado_interno'].'" data-sig_estado_interno="CANCELADO">';
                $button .= '<span class="glyphicon glyphicon-trash"></span>';
                $button .= '</button>';
            }
            
        }
        
        $respuesta->rows[$cont]['btn_gestion'] = $button;

        $cont++;
    }

}
//****************************

print_r(json_encode($respuesta));

?>