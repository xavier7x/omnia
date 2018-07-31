<?php
include("encryp.php");
$desencryptacion = new EnDecryptText();
$respuesta = new stdClass();
$respuesta->estado = 2;
$respuesta->mensaje = "Sin acciones";
$respuesta->valor = "";

$valor = "";

if(
    (isset($_POST['valor']) && !empty($_POST['valor']))
){
    $valor = $_POST['valor'];
}

if(
    !empty($valor) 
){
    
    $respuesta->valor = $desencryptacion->Encrypt_Text($valor);
    $respuesta->estado = 1;
    $respuesta->mensaje = "Valor encriptado";
    
}else{
    
    $respuesta->estado = 2;
    $respuesta->mensaje = "No envio los siguientes parámetros [ valor ]";
    
}

print_r(json_encode($respuesta));
?>