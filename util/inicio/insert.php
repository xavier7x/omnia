<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
//$respuesta->estado = 2;
//$respuesta->mensaje = "Sin acciones";

//****************************

/*
    validar el nombre seo que solo use letras minusculas, numero y nada de caracteres especiales excepto guion medio
    validar que el nombre seo cuando este correcto nadie mas lo este usando
    validar que se cree la carpeta con la nueva carpeta para las imagenes 320x320, y su respectivo codigo si el producto se creo correctamente
*/
$email_suscrito = "";
if((isset($_POST['email_suscrito']) && !empty($_POST['email_suscrito']))){
    $email_suscrito = $_POST['email_suscrito'];
}

if(!empty($email_suscrito)){
    $mensajeFlag = "";
    $resultado = $conexion->DBConsulta("
            SELECT COUNT(*) AS total
            FROM email_boletines
            WHERE correo_news = '".$email_suscrito."'
        ");
        
        foreach($resultado as $fila){
            
            if( (int)$fila['total'] > 0){
                $mensajeFlag = "Tu correo ya ha sido registrado";
                $respuesta->estado = 2;
                $respuesta->mensaje = $mensajeFlag;
            }else{
                $resultado = $conexion->DBConsulta("
                INSERT INTO email_boletines
                (correo_news, estado, fecha_alta) 
                VALUES 
                ('".$email_suscrito."','ACTIVO',NOW())
                ");
                if($resultado == true){
                    $respuesta->estado = 1;
                     $respuesta->mensaje = "Desde ahora podras estar al día con nuestra información y promociones";
                }else{
                    $respuesta->estado = 2;
                    $respuesta->mensaje = "Error al realizar la inserción";
                }
            }
        }
    }else{
        $respuesta->estado = 2;
        $respuesta->mensaje = "No envio los siguientes parámetros [ email ]";
    }

print_r(json_encode($respuesta));

?>