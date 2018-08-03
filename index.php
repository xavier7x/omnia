<?php
/*

*/
include("util/system/session.php");
include("util/system/conexionMySql.php");
include("util/system/funciones.php");

$session = new AdmSession();
$session->startSession();

$conexion = new DBManager();
$conexion->DBConectar(2);

// Extraer los parametros

$resultado_param = $conexion->DBConsulta("
    SELECT *
    FROM cli_parametros
", 2);

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

// Validacion SQL

$noSql = array("select","insert","update","delete","where","create","drop","table","alter","query");
$noSqlFlag = false;

if(
    isset($_GET['pagina']) && !empty($_GET['pagina']) 
){
    if(in_array($_GET['pagina'], $noSql)){
        $noSqlFlag = true;
    }
}

if(
    isset($_GET['p1']) && !empty($_GET['p1']) 
){
    if(in_array($_GET['p1'], $noSql)){
        $noSqlFlag = true;
    }
}

if(
    isset($_GET['p2']) && !empty($_GET['p2']) 
){
    if(in_array($_GET['p2'], $noSql)){
        $noSqlFlag = true;
    }
}

if(
    isset($_GET['p3']) && !empty($_GET['p3']) 
){
    if(in_array($_GET['p3'], $noSql)){
        $noSqlFlag = true;
    }
}

if( $noSqlFlag == true ){
    header("HTTP/1.0 404 Not Found");   
    echo "<html><script language = 'javascript'>document.location = '".$pdet_valor['hostapp']."/page404.php';</script></html>";
    exit;
}

//****************************

if($pdet_valor['sistemaactivo'] == 'SI'){

    if($session->checkSession()==false){
        
        // Si no tiene una sesion activa iniciar una como visitante, sin resetear la sesion para que no de error        
        $session->createSession('', 'Visitante', 'visitante', 1, 0);

    }
        
    // Verifico si las variables de sesion estan con datos

    if( 
        ( isset($_SESSION['usuario']) && !empty($_SESSION['usuario']) ) &&
        ( isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) ) &&
        ( isset($_SESSION['tipocliente']) && !empty($_SESSION['tipocliente']) ) &&
        isset($_SESSION['idsector'])
      ){
        
        // URL por defecto
        $pagina = $pdet_valor['paginadefecto'];

        if(isset($_GET['pagina']) && !empty($_GET['pagina'])){
            // Saber si esta establecida una pagina en la url
            $pagina = $_GET['pagina'];
        }

        // Trae los permisos de la pagina para el usuario
        $resultado_permisos = $conexion->DBConsulta("
            SELECT nombre, ventana, framework, meta_description, meta_keywords
            FROM cli_menu_con_sesion
            WHERE ventana = '".$pagina."'
            AND estado = 'ACTIVO'
            AND tipocliente LIKE '%".$_SESSION['tipocliente']."%'
            LIMIT 1
        ", 2);

        $varAcceso = array();
        $globalProducto = array();
        $globalCategoria = array();
        $globalSubcategoria = array();
        $globalCntModal = 1;

        foreach($resultado_permisos as $filaPer){
            $varAcceso['nombre'] = $filaPer['nombre'];            
            $varAcceso['ventana'] = $filaPer['ventana'];
            $varAcceso['meta_description'] = $filaPer['meta_description'];
            $varAcceso['meta_keywords'] = $filaPer['meta_keywords'];
            $varAcceso['framework'] = explode(",", $filaPer['framework']);
            
            $varAcceso['idcategoria'] = 0;
            $varAcceso['idsubcategoria'] = 0;
            $varAcceso['num_pagina'] = 0;
            $varAcceso['idproducto'] = 0;
            $varAcceso['buscar'] = ( $filaPer['ventana'] == 'buscar' && isset( $_GET['p1']) && !empty($_GET['p1']) ? $_GET['p1'] : "");
        }
        
        // verificar si tiene la palabra reservada productos
        if(
            count($varAcceso) > 0 && 
            $varAcceso['ventana'] == 'productos'
        ){
            $flagCategorias = false;
            if( 
                isset( $_GET['p1']) && 
                !empty($_GET['p1']) 
            ){
                // si es asi verificar si el dato unique existe caso contrario vaciar el vector para que sea error 404
                // Generar las nuevas metas dependiendo del producto y capturar el idproducto
                $resultadoProducto = $conexion->DBConsulta("
                    SELECT a.*, e.valor
                    FROM productos AS a
                    INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                    WHERE a.nombre_seo = '".$_GET['p1']."'                    
                    LIMIT 1
                ", 2);
                
                foreach($resultadoProducto as $filaProducto){
                    $varAcceso['nombre'] = $filaProducto['nombre'];
                    $varAcceso['meta_description'] = $filaProducto['nombre'];
                    $varAcceso['meta_description'] = $filaProducto['descripcion_corta'];
                    $varAcceso['meta_keywords'] = $filaProducto['descripcion_corta'];                    
                    $varAcceso['idproducto'] = $filaProducto['idproducto'];
                    
                    // Guardo todos los datos del producto en esta variable que funcionara como global
                    $globalProducto = $filaProducto;
                    
                    $flagCategorias = true;
                }
            }
            
            if( $flagCategorias == false ){
                $varAcceso = array();
            }
            
        }elseif( count($varAcceso) == 0 ){
            // Si tiene el parametro pagina [ categoria ] y p1 [ subcategoria ]
            if(
                isset( $_GET['pagina']) && !empty($_GET['pagina'])
            ){
                // Validar que exista la categoria en el vector
                $resultadoCateg = $conexion->DBConsulta("
                    SELECT *
                    FROM categorias
                    WHERE nombre_seo = '".$_GET['pagina']."'                    
                    LIMIT 1
                ", 2);
                
                foreach($resultadoCateg as $filaCateg){
                    
                    if( 
                        isset( $_GET['p1']) && !empty($_GET['p1']) && is_numeric($_GET['p1']) == false
                    ){
                        // validar que exista la subcategoria en el vector
                        $resultadoSubcateg = $conexion->DBConsulta("
                            SELECT *
                            FROM subcategorias
                            WHERE nombre_seo = '".$_GET['p1']."'
                            AND idcategoria = '".$filaCateg['idcategoria']."'
                            LIMIT 1
                        ", 2);

                        foreach($resultadoSubcateg as $filaSubcateg){
                            $varAcceso['nombre'] = $filaSubcateg['nombre'];
                            $varAcceso['ventana'] = 'subcategorias';
                            $varAcceso['meta_description'] = $filaSubcateg['descripcion'];
                            $varAcceso['meta_keywords'] = $filaSubcateg['descripcion'];
                            $varAcceso['framework'] = explode(",", "jquery,bootstrap,font-awesome,totop,easing,jstarbox");

                            $varAcceso['idcategoria'] = 0;
                            $varAcceso['idsubcategoria'] = $filaSubcateg['idsubcategoria'];
                            $varAcceso['num_pagina'] = ( isset( $_GET['p2']) && !empty($_GET['p2']) ? $_GET['p2'] : 1);
                            $varAcceso['idproducto'] = 0;
                            $varAcceso['buscar'] = "";      
                            
                            $globalSubcategoria = $filaSubcateg;
                        }
                    }else{
                        $varAcceso['nombre'] = $filaCateg['nombre'];
                        $varAcceso['ventana'] = 'categorias';
                        $varAcceso['meta_description'] = $filaCateg['descripcion'];
                        $varAcceso['meta_keywords'] = $filaCateg['descripcion'];
                        $varAcceso['framework'] = explode(",", "jquery,bootstrap,font-awesome,totop,easing,jstarbox");

                        $varAcceso['idcategoria'] = $filaCateg['idcategoria'];
                        $varAcceso['idsubcategoria'] = 0;
                        $varAcceso['num_pagina'] = ( isset( $_GET['p1']) && !empty($_GET['p1']) ? $_GET['p1'] : 1);
                        $varAcceso['idproducto'] = 0;
                        $varAcceso['buscar'] = "";
                    }
                        
                    $globalCategoria = $filaCateg;
                }
                
                // Si no existe ninguna no llenar el vector para que sea error 404
                
            }
        }elseif(
            count($varAcceso) > 0 && 
            $varAcceso['ventana'] == 'buscar'
        ){
            // Si no envia datos para buscar se pasara como error
            if( !isset( $_GET['p1']) || empty($_GET['p1']) ){
                $varAcceso = array();
            }
        }

        // Si no tiene accesos a la pagina solicitada, enviar el error 404 personalizado
        if(
            count($varAcceso) == 0
            
        ){            
            header("HTTP/1.0 404 Not Found");   
            echo "<html><script language = 'javascript'>document.location = '".$pdet_valor['hostapp']."/page404.php';</script></html>";
            exit;
            
        }

        //********  Log Open Con Sesion

        $conexion->DBConsulta("
            INSERT INTO cli_log_menu_con_sesion
            (ip, 
            navegador, 
            usuario, tipocliente, 
            pagina, 
            idcategoria, 
            idsubcategoria, 
            num_pagina, 
            idproducto, 
            buscar, 
            sys_date) 
            VALUES 
            ('".Funciones::ObtenerIp()."',
            '".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."',
            '".$_SESSION['usuario']."','".$_SESSION['tipocliente']."',
            '".$varAcceso['ventana']."',
            ".(empty($varAcceso['idcategoria']) ? "NULL" : "'".$varAcceso['idcategoria']."'").",
            ".(empty($varAcceso['idsubcategoria']) ? "NULL" : "'".$varAcceso['idsubcategoria']."'").",
            ".(empty($varAcceso['num_pagina']) ? "NULL" : "'".$varAcceso['num_pagina']."'").",
            ".(empty($varAcceso['idproducto']) ? "NULL" : "'".$varAcceso['idproducto']."'").",
            ".(empty($varAcceso['buscar']) ? "NULL" : "'".$varAcceso['buscar']."'").",
            NOW())
        ", 2);

        // Extraer las categorias y subcategorias y crear vector para el menu de categorias y subcategorias
        
        $resultadoMenu = $conexion->DBConsulta("
            SELECT *
            FROM categorias
            WHERE estado = 'ACTIVA'
            ORDER BY nombre
        ", 2);
        $vectorMenu = array();
        $conVecMenu = 0;
        
        foreach($resultadoMenu as $fila){
            $vectorMenu[$conVecMenu] = $fila;
            $vectorMenu[$conVecMenu]['subcategorias'] = array();
            
            $resultadoMenuInt = $conexion->DBConsulta("
                SELECT a.*
                FROM subcategorias AS a
                WHERE a.estado = 'ACTIVA'
                AND a.idcategoria = '".$fila['idcategoria']."' 
                AND (
                    SELECT COUNT(*)
                    FROM productos
                    WHERE idsubcategoria = a.idsubcategoria
                ) > 0
                ORDER BY a.nombre
            ", 2);
            
            $conVecMenuInt = 0;
            
            foreach($resultadoMenuInt as $filaInt){
                $vectorMenu[$conVecMenu]['subcategorias'][$conVecMenuInt] = $filaInt;
                
                $conVecMenuInt++;
            }
            
            $conVecMenu++;
        }
        
        //print_r($vectorMenu);
        
        // Extraer el menu sistema
        
        $menuSys = array();
        
        $resultadoMenuSys = $conexion->DBConsulta("
            SELECT *
            FROM cli_menu_con_sesion
            WHERE estado = 'ACTIVO'
            AND ventana IS NOT NULL
        ", 2);
        
        foreach($resultadoMenuSys as $filaMenuSys){
            $menuSys[$filaMenuSys['ventana']] = $filaMenuSys;
        }
        
        //print_r($menuSys);
        //********************* CLASE PRODUCTOS
        
        class Producto {
            
            public function graficarProductoModal(
                $inClassMd ,
                $inGlobalCntModal ,
                $inHostApp ,
                $inImgProducto ,
                $inIdProducto,
                $inNombre ,
                $inNombreSeo ,
                $inDescripcionLarga ,
                $inDescripcionCorta ,   
                $inPrecioAnterior ,
                $inPrecio ,
                $inImpuesto = 0
            )
            {
                if((int)$inImpuesto > 0){
                    $sub_pre = ($inPrecio * (int)$inImpuesto) / 100;
                    $inPrecio = number_format(((float)$inPrecio + $sub_pre), 2, '.', '');
                    $sub_pre_ant = ($inPrecioAnterior * (int)$inImpuesto) / 100;
                    $inPrecioAnterior = number_format(((float)$inPrecioAnterior + $sub_pre_ant), 2, '.', '');
                }
                
                $graficoProducto = '<div class="col-md-3 '.$inClassMd.'">'; 
                $graficoProducto .= '<div class="col-m">'; 
                $graficoProducto .= '<a href="#" data-toggle="modal" data-target="#myModal'.$inGlobalCntModal.'" class="offer-img mtk-abre-modal" data-idproducto="'.$inIdProducto.'">';
                $graficoProducto .= '<img src="'.$inImgProducto.'" class="img-responsive" alt="'.$inNombre.'">';
                if( 
                    !empty($inPrecioAnterior) && 
                    $inPrecioAnterior > $inPrecio
                ){
                    $graficoProducto .= '<div class="offer"><p><span>Oferta</span></p></div>';
                }
                $graficoProducto .= '</a>';
                $graficoProducto .= '<div class="mid-1">';
                $graficoProducto .= '<div class="women">';
                $graficoProducto .= '<h6><a href="'.$inHostApp.'/productos/'.$inNombreSeo.'" title="'.$inNombre.'">';
                $graficoProducto .= $inNombre.'</a></h6>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<div class="mid-2">';
                $graficoProducto .= '<p><label>'.( !empty($inPrecioAnterior) && $inPrecioAnterior > $inPrecio ? "$".$inPrecioAnterior : "" ).'</label>';
                $graficoProducto .= '<em class="item_price">$'.$inPrecio.'</em></p>';
                $graficoProducto .= '<div class="block">';
                $graficoProducto .= '<div class="starbox small ghosting"></div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<div class="clearfix"></div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<div class="add add-2">';
                $graficoProducto .= '<button class="btn btn-danger my-cart-btn my-cart-b" data-idproducto="'.$inIdProducto.'" data-cantidad="1" data-image="'.$inImgProducto.'">';
                $graficoProducto .= 'Añadir</button>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '</div>';                    
                $graficoProducto .= '</div>'; 
                $graficoProducto .= '</div>';

                // Crear el modal del producto

                $graficoProducto .= '<div class="modal fade" id="myModal'.$inGlobalCntModal.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                $graficoProducto .= '<div class="modal-dialog modal-dialog-producto" role="document">';
                $graficoProducto .= '<div class="modal-content modal-info">';
                $graficoProducto .= '<div class="modal-header modal-header-producto">';
                $graficoProducto .= '<button type="button" class="close modal-close-producto" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<div class="modal-body modal-spa modal-spa-producto">';
                $graficoProducto .= '<div class="col-md-5 span-2">';
                $graficoProducto .= '<div class="item">';
                $graficoProducto .= '<img src="'.$inImgProducto.'" class="img-responsive" alt="'.$inNombre.'">';
                $graficoProducto .= '</div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<div class="col-md-7 span-1 ">';
                $graficoProducto .= '<h3 class="quick"><a id="prodTitle" href="'.$inHostApp.'/productos/'.$inNombreSeo.'" title="'.$inNombre.'">'.$inNombre.'</a></h3>';
                $graficoProducto .= '<p class="in-para">'.$inDescripcionCorta.'</p>';
                $graficoProducto .= '<div class="price_single">';
                $graficoProducto .= '<span class="reducedfrom "><del class="price_single_ant">'.( !empty($inPrecioAnterior) && $inPrecioAnterior > $inPrecio ? "$".$inPrecioAnterior : "" ).'</del>$'.$inPrecio.'</span>';
                $graficoProducto .= '<div class="clearfix"></div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<h4 class="quick">Descripción:</h4>';
                $graficoProducto .= '<p class="quick_desc">'.$inDescripcionLarga.'</p>';
                $graficoProducto .= '<div class="add-to">';
                $graficoProducto .= '<button class="btn btn-danger my-cart-btn my-cart-btn1" data-idproducto="'.$inIdProducto.'" data-cantidad="1" data-image="'.$inImgProducto.'">';                    
                $graficoProducto .= 'Añadir</button>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '<div class="clearfix"></div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '</div>';
                $graficoProducto .= '</div>';

                //******************************
                
                return $graficoProducto;
            }
        }
        //***********************************

        include('inc/cabpie/cabecera.php');
        include('inc/'.$varAcceso['ventana'].'/cuerpo.php');
        include('inc/cabpie/pie.php');

    }else{

        $session->endSession($pdet_valor['hostapp']);

    }

}else{
    
    include('inc/offline/cuerpo.php');
    
}

?>