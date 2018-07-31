<?php
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
    FROM sys_parametros
", 2);

$pdet_valor = array();

foreach($resultado_param as $fila){
    $pdet_valor[trim($fila['idparametro'])] = trim($fila['valor']);
}

//****************************
/*
$url = $_SERVER['HTTP_HOST'];

if(
    strpos($url, 'marketton.com') !== false
){
    if(
        strpos($url, 'www') === false
    ){
        echo "<html><script language = 'javascript'>document.location = '".$pdet_valor['hostapp']."';</script></html>";
    }
}
*/
//****************************

if($pdet_valor['sistemaactivo'] == 'SI'){

    if($session->checkSession()==true){
        
        // Verifico si las variables de sesion estan con datos
        
        if( 
            ( isset($_SESSION['usuario']) && !empty($_SESSION['usuario']) ) &&
            ( isset($_SESSION['nombre']) && !empty($_SESSION['nombre']) )
          ){
        
            if(isset($_SESSION["fechaSesion"])){
                $fechaGuardada = $_SESSION["fechaSesion"];    
                $ahora = date("Y-m-d H:i:s");
                $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));

                //comparamos el tiempo transcurrido
                if( $tiempo_transcurrido >= ( $pdet_valor['timeout'] * 60 ) ) {

                    $conexion->DBConsulta("
                        INSERT INTO sys_log_logout
                        (ip, navegador, usuario, sys_date) 
                        VALUES 
                        ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."','".$_SESSION['usuario']."',NOW())
                    ");

                    $session->endSession('');
                    exit;

                }else{
                    $_SESSION["fechaSesion"] = date("Y-m-d H:i:s");
                }
            }else{
                $_SESSION["fechaSesion"] = date("Y-m-d H:i:s");
            }

            // URL por defecto
            $pagina = $pdet_valor['paginadefecto'];
            if(isset($_GET['pagina']) && !empty($_GET['pagina'])){
                // Saber si esta establecida una pagina en la url
                $pagina = $_GET['pagina'];
            }

            // Trae los permisos de la pagina para el usuario
            $resultado_permisos = $conexion->DBConsulta("
                SELECT a.*, b.ventana, b.framework, b.nombre
                FROM sys_usuarios_accesos AS a
                INNER JOIN sys_menu AS b ON (a.idmenu = b.idmenu)
                WHERE a.usuario = '".$_SESSION['usuario']."'
                AND b.ventana = '".$pagina."'
                AND b.estado = 'ACTIVO'
                LIMIT 1
            ", 2);

            $varAcceso = array();

            foreach($resultado_permisos as $filaPer){
                $varAcceso['nombre'] = $filaPer['nombre'];
                $varAcceso['ventana'] = $filaPer['ventana'];
                $varAcceso['visualizar'] = $filaPer['visualizar'];
                $varAcceso['insertar'] = $filaPer['insertar'];
                $varAcceso['actualizar'] = $filaPer['actualizar'];
                $varAcceso['eliminar'] = $filaPer['eliminar'];
                $varAcceso['framework'] = explode(",", $filaPer['framework']);
            }

            // Si no tiene accesos a la pagina solicitada, verificar si puede acceder alguna segun el orden del idmenu y orden
            if(count($varAcceso) == 0){
                       
                $flagAccPagina = false;

                $resultadoAccPagina = $conexion->DBConsulta("
                    SELECT a.ventana
                    FROM sys_menu AS a
                    INNER JOIN sys_usuarios_accesos AS b ON (a.idmenu = b.idmenu)
                    WHERE b.usuario = '".$_SESSION['usuario']."'
                    AND a.estado = 'ACTIVO'
                    AND a.es_menu = 'NO'
                    ORDER BY a.idpadre, a.orden
                    LIMIT 1
                ", 2);

                foreach($resultadoAccPagina as $fila){         
                    $pagina = $fila['ventana'];
                    $flagAccPagina = true;
                }

                if($flagAccPagina == false){
                    echo "<script language = 'javascript'>alert('Estimado, usted no tiene modulos asignados en el aplicativo, favor contactar con el administrador del sistema');</script>";
                    
                    $session->endSession('');
                    exit;
                }else{
                    // Obtener los accesos a la nueva pagina
                    $resultadoVeri = $conexion->DBConsulta("
                        SELECT a.*, b.ventana, b.framework, b.nombre
                        FROM sys_usuarios_accesos AS a
                        INNER JOIN sys_menu AS b ON (a.idmenu = b.idmenu)
                        WHERE a.usuario = '".$_SESSION['usuario']."'
                        AND b.ventana = '".$pagina."'
                        AND b.estado = 'ACTIVO'
                        LIMIT 1
                    ", 2);

                    foreach($resultadoVeri as $filaVeri){            
                        $varAcceso['nombre'] = $filaVeri['nombre'];
                        $varAcceso['ventana'] = $filaVeri['ventana'];
                        $varAcceso['visualizar'] = $filaVeri['visualizar'];
                        $varAcceso['insertar'] = $filaVeri['insertar'];
                        $varAcceso['actualizar'] = $filaVeri['actualizar'];
                        $varAcceso['eliminar'] = $filaVeri['eliminar'];
                        $varAcceso['framework'] = explode(",", $filaVeri['framework']);
                    }
                }      
            }            

            //********  Log Open Sesion

            $conexion->DBConsulta("
                INSERT INTO sys_log_menu_con_sesion
                (ip, navegador, usuario, pagina, sys_date) 
                VALUES 
                ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER ['HTTP_USER_AGENT'])."','".$_SESSION['usuario']."','".$pagina."',NOW())
            ", 2);

            // Obtener el menu, segun jerarquias

            $resultadoMenu = $conexion->DBConsulta("
                SELECT a.idmenu, a.nombre, a.idpadre, a.ventana, a.es_menu, a.icono
                FROM sys_menu AS a
                LEFT JOIN sys_usuarios_accesos AS b ON (a.idmenu = b.idmenu)
                WHERE b.usuario = '".$_SESSION['usuario']."' 
                AND a.estado = 'ACTIVO'
                ORDER BY a.idpadre, a.orden
            ", 2);
            $vectorMenu = array();
            $conVecMenu = 0;
            foreach($resultadoMenu as $fila){
                $vectorMenu[$conVecMenu]['idmenu'] = $fila['idmenu'];
                $vectorMenu[$conVecMenu]['nombre'] = $fila['nombre'];
                $vectorMenu[$conVecMenu]['idpadre'] = $fila['idpadre'];
                $vectorMenu[$conVecMenu]['ventana'] = $fila['ventana'];
                $vectorMenu[$conVecMenu]['es_menu'] = $fila['es_menu'];
                $vectorMenu[$conVecMenu]['icono'] = $fila['icono'];
                $conVecMenu++;
            }

            //***********************************

            include('inc/cabpie/cabecera.php');
            include('inc/'.$pagina.'/cuerpo.php');
            include('inc/cabpie/pie.php');
            
        }else{
            
            $session->endSession('');
            
        }

    }else{

        include('inc/login/cuerpo.php');

    }

}else{
    
    include('inc/offline/cuerpo.php');
    
}

?>