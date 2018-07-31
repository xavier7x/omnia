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
    FROM sys_parametros
");

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************

/*
    si ha cambiado el nombre seo validar que solo use letras minusculas, numero y nada de caracteres especiales excepto guion medio
    si ha cambiado el nombre seo validar que cuando este correcto nadie mas lo este usando, excepto el mismo producto
    si el nombre seo cambio , cambiar el nombre de las imagenes que existan
*/


$usuario = "";
$idproducto = 0;
$nombre = "";
$nombre_seo = "";
$sku = "";
$costo = 0;
$precio = 0;
$estado = "";
$mayor_edad = "";
$idimpuesto = 0;
$idsubcategoria = 0;
$idmarca = 0;
$idproveedor = 0;
$descripcion_corta = "";
$descripcion_larga = "";
$idproducto = 0;
$costo_anterior = 0;
$precio_anterior = 0;
$idproducto_otro = 0;
$nombre_otro = "";
$nombre_seo_old = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['idproducto']) && !empty($_POST['idproducto'])) && 
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['nombre_seo']) && !empty($_POST['nombre_seo'])) && 
    (isset($_POST['sku']) && !empty($_POST['sku'])) && 
    (isset($_POST['costo']) && !empty($_POST['costo'])) && 
    (isset($_POST['precio']) && !empty($_POST['precio'])) && 
    (isset($_POST['estado']) && !empty($_POST['estado'])) && 
    (isset($_POST['mayor_edad']) && !empty($_POST['mayor_edad'])) && 
    (isset($_POST['idimpuesto']) && !empty($_POST['idimpuesto'])) && 
    (isset($_POST['idsubcategoria']) && !empty($_POST['idsubcategoria'])) && 
    (isset($_POST['idproveedor']) && !empty($_POST['idproveedor'])) && 
    (isset($_POST['idmarca']) && !empty($_POST['idmarca'])) && 
    (isset($_POST['descripcion_corta']) && !empty($_POST['descripcion_corta'])) 
    
){
    $usuario = $_POST['usuario'];
    $idproducto = $_POST['idproducto'];
    $nombre = $_POST['nombre'];
    $nombre_seo = strtolower($_POST['nombre_seo']);
    $sku = $_POST['sku'];
    $costo = $_POST['costo'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];
    $mayor_edad = $_POST['mayor_edad'];
    $idimpuesto = $_POST['idimpuesto'];
    $idsubcategoria = $_POST['idsubcategoria'];
    $idproveedor = $_POST['idproveedor'];
    $idmarca = $_POST['idmarca'];
    $descripcion_corta = $_POST['descripcion_corta'];
}

if(
    (isset($_POST['descripcion_larga']) && !empty($_POST['descripcion_larga'])) 
){
    $descripcion_larga = addslashes($_POST['descripcion_larga']);
}

if(
    !empty($usuario) && 
    !empty($idproducto) && 
    !empty($nombre) && 
    !empty($nombre_seo) && 
    !empty($sku) && 
    !empty($costo) && 
    !empty($precio) && 
    !empty($estado) && 
    !empty($mayor_edad) && 
    !empty($idimpuesto) && 
    !empty($idsubcategoria) && 
    !empty($idproveedor) && 
    !empty($idmarca) && 
    !empty($descripcion_corta) 
){
    
    $flag = true;
    $mensajeFlag = "";    
    $permitidos = "abcdefghijklmnopqrstuvwxyz0123456789-";
    $changeSeo = 0;
    
    // Validar si el nombre seo cambio
    
    $resultado = $conexion->DBConsulta("
        SELECT nombre_seo
        FROM productos
        WHERE idproducto = '".$idproducto."'
        LIMIT 1
    ");

    foreach($resultado as $fila){
        $nombre_seo_old = $fila['nombre_seo'];
        
        if( $nombre_seo_old == $nombre_seo ){
            $flag = true;
        }else{
            // El nombre seo cambio
            $flag = false;
            $changeSeo++;
        }
    }
    
    if( $flag == false ){
        // validar el nombre seo que solo use letras minusculas, numero y nada de caracteres especiales excepto guion medio
        for ($i=0; $i<strlen($nombre_seo); $i++){
            if (strpos($permitidos, substr($nombre_seo,$i,1)) === false){
                $mensajeFlag = "El nombre SEO, solo puede contener letras desde la (a) hasta la (z) en minusculas, números y guion (-)";
                $flag = false;
                break;
            }else{
                $flag = true;
            }
        }
    }
    
    if( $flag == true ){
        // validar que el nombre seo cuando este correcto nadie mas lo este usando
        $resultado = $conexion->DBConsulta("
            SELECT COUNT(*) AS total
            FROM productos
            WHERE idproducto != '".$idproducto."'
            AND nombre_seo = '".$nombre_seo."'
        ");

        foreach($resultado as $fila){
            if( (int)$fila['total'] > 0){
                $mensajeFlag = "El nombre SEO, no se puede repetir en la base de datos";
                $flag = false;
            }
        }
    }

    if( $flag == true){
        
        // Validar que el SKU no este siendo usado por otro producto

        $resultado = $conexion->DBConsulta("
            SELECT *
            FROM productos
            WHERE idproducto != '".$idproducto."'
            AND sku = '".$sku."'
            LIMIT 1
        ");

        $contOtro = 0;

        foreach($resultado as $fila){
            $idproducto_otro = $fila['idproducto'];
            $nombre_otro = $fila['nombre'];
            $contOtro++;
        }

        if($contOtro == 0){

            // Validar que el costo sea diferente del costo anterior para guardarlo
            $resultado = $conexion->DBConsulta("
                SELECT costo
                FROM productos
                WHERE idproducto = '".$idproducto."'
                LIMIT 1
            ");

            foreach($resultado as $fila){
                $costo_anterior = $fila['costo'];
            }

            // Validar que el precio sea diferente del precio anterior para guardarlo
            $resultado = $conexion->DBConsulta("
                SELECT precio
                FROM productos
                WHERE idproducto = '".$idproducto."'
                LIMIT 1
            ");

            foreach($resultado as $fila){
                $precio_anterior = $fila['precio'];
            }

            if( (float)$costo < (float)$precio ){

                $resultado = $conexion->DBConsulta("
                    UPDATE productos SET 
                    nombre = '".$nombre."',
                    nombre_seo = '".$nombre_seo."',
                    descripcion_corta = '".$descripcion_corta."',
                    descripcion_larga = ".(empty($descripcion_larga) ? "NULL" : " '".$descripcion_larga."' ")." ,        
                    sku = '".$sku."',
                    estado = '".$estado."',
                    idsubcategoria = '".$idsubcategoria."',
                    idmarca = '".$idmarca."',
                    precio = '".$precio."',
                    ".( (float)$precio_anterior != (float)$precio ? " precio_anterior = '".$precio_anterior."', " : "" )."   
                    costo = '".$costo."',
                    ".( (float)$costo_anterior != (float)$costo ? " costo_anterior = '".$costo_anterior."', " : "" )."   
                    idimpuesto = '".$idimpuesto."',
                    mayor_edad = '".$mayor_edad."',
                    user_update = '".$usuario."',
                    sys_update = NOW()
                    WHERE idproducto = '".$idproducto."'
                ");

                if($resultado == true){
                    
                    $msnChange = "";
                    
                    if( $changeSeo > 0){
                        if(file_exists('../../../images/productos/'.$idproducto.'/320x320/'.$nombre_seo_old.'.png')){
                            if(!rename ('../../../images/productos/'.$idproducto.'/320x320/'.$nombre_seo_old.'.png', 
                                        '../../../images/productos/'.$idproducto.'/320x320/'.$nombre_seo.'.png')){
                                $msnChange = " - Error: Al actualizar el nombre de las imagenes al nuevo nombre SEO, comunicar a sistemas";
                            }
                        }                        
                    }
                    
                    $respuesta->estado = 1;
                    $respuesta->mensaje = "Registro actualizado con éxito".$msnChange;
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error al realizar la actualización";
                }
            }else{
                $respuesta->estado = 2;
                $respuesta->mensaje = "Recuerde que el costo debe ser menor al precio";
            }


        }else{
            $respuesta->estado = 2;
            $respuesta->mensaje = "El SKU (".$sku."), ya esta siendo usado por el producto ( ".$idproducto_otro." - ".$nombre_otro." )";
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = $mensajeFlag;
    }
}else{
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - idproducto - nombre - nombre_seo - sku - costo - precio - estado - mayor_edad - idimpuesto - idsubcategoria - idmarca - idproveedor - descripcion_corta ]";
}

print_r(json_encode($respuesta));

?>