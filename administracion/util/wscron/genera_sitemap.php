<?php
include("../system/conexionMySql.php");
include("../system/funciones.php");
set_time_limit(0);

/*
    Considerar como un maximo de 5000
    caso contrario devidirlas como udemy.com/sitemap.xml
*/

$conexion = new DBManager();
$conexion->DBConectar();

$respuesta = new stdClass();
$respuesta->estado = 1;
$respuesta->mensaje = "";
$creadoXml = false;

$xml = new DomDocument('1.0', 'UTF-8');

$raiz = $xml->createElement('urlset');
$raiz = $xml->appendChild($raiz);

$CommentString = ' Fecha generacion: '.date('Y-m-d H:i:s').' ';
$comentario = $xml->createComment( str_replace('--', '-'.chr(194).chr(173).'-', $CommentString) );
$raiz->appendChild($comentario); 

$raiz->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$raiz->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

Funciones::EscribirLogs("generasitemap", "Inicio de cron");

//************************************************

$conexion->DBConsulta("
    UPDATE sys_cron_job SET 
    sys_start = NOW()
    WHERE idlogcron = '2'
");

$hostapp = "";

//************************************************

$resultado = $conexion->DBConsulta("
    SELECT valor
    FROM cli_parametros
    WHERE idparametro = 'hostapp'
    LIMIT 1
");

foreach($resultado as $fila){    
    
    $creadoXml = true;
    $hostapp = $fila["valor"];
    
    $nodo = $xml->createElement('url');
    $nodo = $raiz->appendChild($nodo);
    
    $subnodo1 = $xml->createElement('loc',$fila["valor"]);
    $subnodo2 = $xml->createElement('changefreq','daily');
    $subnodo3 = $xml->createElement('priority','1.00');
    
    $subnodo = $nodo->appendChild($subnodo1);
    $subnodo = $nodo->appendChild($subnodo2);
    $subnodo = $nodo->appendChild($subnodo3);
}

$resultado = $conexion->DBConsulta("
    SELECT valor
    FROM sys_parametros
    WHERE idparametro = 'hostapp'
    LIMIT 1
");

foreach($resultado as $fila){    
    $nodo = $xml->createElement('url');
    $nodo = $raiz->appendChild($nodo);
    
    $subnodo1 = $xml->createElement('loc',$fila["valor"]);
    $subnodo2 = $xml->createElement('changefreq','daily');
    $subnodo3 = $xml->createElement('priority','1.00');
    
    $subnodo = $nodo->appendChild($subnodo1);
    $subnodo = $nodo->appendChild($subnodo2);
    $subnodo = $nodo->appendChild($subnodo3);
}

// Obtener el menu y menos los inactivos y tampoco los que son dinamicos o no tienen resultados

$resultado = $conexion->DBConsulta("
    SELECT ventana
    FROM cli_menu_con_sesion
    WHERE estado = 'ACTIVO'
    AND ventana IS NOT NULL
    AND idmenu NOT IN ( 7, 9 )
");

foreach($resultado as $fila){    
    $nodo = $xml->createElement('url');
    $nodo = $raiz->appendChild($nodo);
    
    $subnodo1 = $xml->createElement('loc',$hostapp.'/'.$fila["ventana"]);
    $subnodo2 = $xml->createElement('changefreq','daily');
    $subnodo3 = $xml->createElement('priority','1.00');
    
    $subnodo = $nodo->appendChild($subnodo1);
    $subnodo = $nodo->appendChild($subnodo2);
    $subnodo = $nodo->appendChild($subnodo3);
}

// Obtener categorias activas

$resultado = $conexion->DBConsulta("
    SELECT idcategoria, nombre_seo
    FROM categorias
    WHERE estado = 'ACTIVA'
");

foreach($resultado as $fila){    
    $nodo = $xml->createElement('url');
    $nodo = $raiz->appendChild($nodo);
    
    $subnodo1 = $xml->createElement('loc',$hostapp.'/'.$fila["nombre_seo"]);
    $subnodo2 = $xml->createElement('changefreq','daily');
    $subnodo3 = $xml->createElement('priority','1.00');
    
    $subnodo = $nodo->appendChild($subnodo1);
    $subnodo = $nodo->appendChild($subnodo2);
    $subnodo = $nodo->appendChild($subnodo3);
    
    //*******************************************************
    
    $resultadoInt = $conexion->DBConsulta("
        SELECT nombre_seo
        FROM subcategorias
        WHERE estado = 'ACTIVA'
        AND idcategoria = '".$fila["idcategoria"]."'
    ");

    foreach($resultadoInt as $filaInt){    
        $nodo = $xml->createElement('url');
        $nodo = $raiz->appendChild($nodo);

        $subnodo1 = $xml->createElement('loc',$hostapp.'/'.$fila["nombre_seo"].'/'.$filaInt['nombre_seo']);
        $subnodo2 = $xml->createElement('changefreq','daily');
        $subnodo3 = $xml->createElement('priority','1.00');

        $subnodo = $nodo->appendChild($subnodo1);
        $subnodo = $nodo->appendChild($subnodo2);
        $subnodo = $nodo->appendChild($subnodo3);
    }
}

// Obtener los productos

$resultado = $conexion->DBConsulta("
    SELECT nombre_seo
    FROM productos
    WHERE estado = 'ACTIVO'
");

foreach($resultado as $fila){    
    $nodo = $xml->createElement('url');
    $nodo = $raiz->appendChild($nodo);
    
    $subnodo1 = $xml->createElement('loc',$hostapp.'/productos/'.$fila["nombre_seo"]);
    $subnodo2 = $xml->createElement('changefreq','daily');
    $subnodo3 = $xml->createElement('priority','1.00');
    
    $subnodo = $nodo->appendChild($subnodo1);
    $subnodo = $nodo->appendChild($subnodo2);
    $subnodo = $nodo->appendChild($subnodo3);
    
}

//************************************************

$conexion->DBConsulta("
    UPDATE sys_cron_job SET 
    sys_end = NOW()
    WHERE idlogcron = '2'
");

//************************************************

$xml->formatOutput = true;
$xml->saveXML();
$xml->save('../../../sitemap.xml');

Funciones::EscribirLogs("generasitemap", "Fin de cron");

if($creadoXml == true){
    $respuesta->estado = 1;
    $respuesta->mensaje = "";
}else{
    $respuesta->estado = 0;
    $respuesta->mensaje = "Error al generar el xml";
}

print_r(json_encode($respuesta));

?>