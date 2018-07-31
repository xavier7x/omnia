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
    validar el nombre seo que solo use letras minusculas, numero y nada de caracteres especiales excepto guion medio
    validar que el nombre seo cuando este correcto nadie mas lo este usando
    validar que se cree la carpeta con la nueva carpeta para las imagenes 320x320, y su respectivo codigo si el producto se creo correctamente
*/

$usuario = "";
$nombre = "";
$nombre_seo = "";
$sku = "";
$costo = 0;
$precio = 0;
$estado = "";
$mayor_edad = "";
$idimpuesto = 0;
$idsubcategoria = 0;
$idproveedor = 0;
$idmarca = 0;
$descripcion_corta = "";
$descripcion_larga = "";
$idproducto = 0;
$idproducto_otro = 0;
$nombre_otro = "";

if(
    (isset($_POST['usuario']) && !empty($_POST['usuario'])) && 
    (isset($_POST['nombre']) && !empty($_POST['nombre'])) && 
    (isset($_POST['nombre_seo']) && !empty($_POST['nombre_seo'])) && 
    (isset($_POST['sku']) && !empty($_POST['sku'])) && 
    (isset($_POST['costo']) && !empty($_POST['costo'])) && 
    (isset($_POST['precio']) && !empty($_POST['precio'])) && 
    (isset($_POST['estado']) && !empty($_POST['estado'])) && 
    (isset($_POST['mayor_edad']) && !empty($_POST['mayor_edad'])) && 
    (isset($_POST['idimpuesto']) && !empty($_POST['idimpuesto'])) && 
    (isset($_POST['idproveedor']) && !empty($_POST['idproveedor'])) && 
    (isset($_POST['idsubcategoria']) && !empty($_POST['idsubcategoria'])) && 
    (isset($_POST['idmarca']) && !empty($_POST['idmarca'])) && 
    (isset($_POST['descripcion_corta']) && !empty($_POST['descripcion_corta'])) 
    
){
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $nombre_seo = strtolower($_POST['nombre_seo']);
    $sku = $_POST['sku'];
    $costo = $_POST['costo'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];
    $mayor_edad = $_POST['mayor_edad'];
    $idimpuesto = $_POST['idimpuesto'];
    $idproveedor = $_POST['idproveedor'];
    $idsubcategoria = $_POST['idsubcategoria'];
    $idmarca = $_POST['idmarca'];
    $descripcion_corta = $_POST['descripcion_corta'];
}

if(
    (isset($_POST['descripcion_larga']) && !empty($_POST['descripcion_larga'])) 
){
    $descripcion_larga = $_POST['descripcion_larga'];
}

if(
    !empty($usuario) && 
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
    
    // validar el nombre seo que solo use letras minusculas, numero y nada de caracteres especiales excepto guion medio
    for ($i=0; $i<strlen($nombre_seo); $i++){
        if (strpos($permitidos, substr($nombre_seo,$i,1)) === false){
            $mensajeFlag = "El nombre SEO, solo puede contener letras desde la (a) hasta la (z) en minusculas, números y guion (-)";
            $flag = false;
            break;
        }
    }
    
    if( $flag == true ){
        // validar que el nombre seo cuando este correcto nadie mas lo este usando
        $resultado = $conexion->DBConsulta("
            SELECT COUNT(*) AS total
            FROM productos
            WHERE nombre_seo = '".$nombre_seo."'
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
            WHERE sku = '".$sku."'
            LIMIT 1
        ");

        $contOtro = 0;

        foreach($resultado as $fila){
            $idproducto_otro = $fila['idproducto'];
            $nombre_otro = $fila['nombre'];
            $contOtro++;
        }

        if($contOtro == 0){

            if( (float)$costo < (float)$precio ){

                $resultado = $conexion->DBConsulta("
                    INSERT INTO productos
                    (nombre, nombre_seo, descripcion_corta, descripcion_larga, 
                    sku, estado, idsubcategoria, idmarca, idproveedor, 
                    precio, costo, idimpuesto,  
                    mayor_edad, user_create, sys_create) 
                    VALUES 
                    ('".$nombre."','".$nombre_seo."','".$descripcion_corta."',".(empty($descripcion_larga) ? "NULL" : "'".$descripcion_larga."'").",
                    '".$sku."','".$estado."','".$idsubcategoria."','".$idmarca."','".$idproveedor."',
                    '".$precio."','".$costo."','".$idimpuesto."',
                    '".$mayor_edad."','".$usuario."',NOW())
                ");

                if($resultado == true){
                    // Extraer el ultimo Idproducto
                    $resultado = $conexion->DBConsulta("
                        SELECT idproducto
                        FROM productos
                        ORDER BY idproducto DESC
                        LIMIT 1
                    ");

                    foreach($resultado as $fila){
                        $idproducto = $fila['idproducto'];
                    }
                    
                    // validar que se cree la carpeta con la nueva carpeta para las imagenes 320x320, y su respectivo codigo si el producto se creo correctamente
                    
                    $carpetasFlag = true;
                    
                    if( (int)$idproducto > 0 ){
                        
                        $estructura = '../../../images/productos/'.$idproducto.'/320x320';

                        if(!mkdir($estructura, 0777, true)) {
                            $carpetasFlag = false;
                        }

                        $fichero = '../../../images/productos/index.html';
                        $nuevo_fichero = '../../../images/productos/'.$idproducto.'/index.html';

                        if (!copy($fichero, $nuevo_fichero)) {
                            $carpetasFlag = false;
                        }

                        $fichero2 = '../../../images/productos/index.html';
                        $nuevo_fichero2 = '../../../images/productos/'.$idproducto.'/320x320/index.html';

                        if (!copy($fichero2, $nuevo_fichero2)) {
                            $carpetasFlag = false;
                        }
                        
                    }
                    // Envio de alerta sistemas ( 1 - CREACION PRODUCTO )                                        
                    //--- Obtenemos los usuarios que tiene la alerta 1

                    $usuarios_alerta_1 = array();

                    $resultado = $conexion->DBConsulta("
                        SELECT b.usuario, b.nombre, b.mail
                        FROM sys_usuario_alerta AS a
                        INNER JOIN sys_usuarios AS b ON (a.usuario = b.usuario)
                        WHERE a.idtipoalerta = '1'
                        AND b.estado = 'ACTIVO'
                    ");

                    foreach($resultado as $fila){
                        $usuarios_alerta_1[] = array(
                            'usuario' => $fila['usuario'],
                            'nombre' => $fila['nombre'],
                            'mail' => $fila['mail']
                        );
                    }

                    for($f=0; $f < count($usuarios_alerta_1); $f++){

                        $body_1 = 'Estimado '.$usuarios_alerta_1[$f]['nombre'].',<br><br>';
                        $body_1 .= 'Se ha creado con éxito el producto:<br>';
                        $body_1 .= 'IDPRODUCTO:  '.$idproducto.'<br>';
                        $body_1 .= 'NOMBRE: '.$nombre.'<br><br>';
                        
                        if($carpetasFlag == false){
                            $body_1 .= 'ATENCION: DEBE INFORMAR A SISTEMAS QUE HUBIERON ERRORES AL CREAR LAS CARPETAS PARA LAS IMAGENES<br><br>';
                        }

                        $conexion->DBConsulta("
                            INSERT INTO sys_envio_correo
                            (idtipoalerta, usuario, email, titulo, cuerpo, user_create, sys_create) 
                            VALUES 
                            ('1','".$usuarios_alerta_1[$f]['usuario']."','".$usuarios_alerta_1[$f]['mail']."','Creación de producto','".$body_1."','".$usuario."',NOW())
                        ");

                    }

                    $respuesta->estado = 1;
                    $respuesta->mensaje = "Registro guardado con éxito";
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error al realizar la inserción";
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
    $respuesta->mensaje = "No envio los siguientes parámetros [ usuario - nombre - nombre_seo - sku - costo - precio - estado - mayor_edad - idimpuesto - idsubcategoria - idmarca - idproveedor - descripcion_corta ]";
}

print_r(json_encode($respuesta));

?>