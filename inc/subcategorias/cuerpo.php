<div class="banner-top">
	<div class="container">
		<h3><?php echo $globalSubcategoria['nombre']; ?></h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Lorem</h4>
        -->
		<div class="clearfix"></div>
        
	</div>
</div>
<div class="content-top offer-w3agile">
    <div class="container">
        <div class="spec ">
            <h3>Productos</h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
        </div>
        <div class=" con-w3l agileinf">
            <?php
            
            $resultadoTotal = $conexion->DBConsulta("
                SELECT COUNT(*) AS total
                FROM productos AS a
                INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                WHERE a.estado = 'ACTIVO'
                AND d.idsector = '".$_SESSION['idsector']."'
                AND b.stock > 0
                AND a.idsubcategoria = '".$globalSubcategoria['idsubcategoria']."'
            ", 2);
            
            $cuerpoProductos = '';
            $numTotal = 0;
            $filasxpagina = 12;
            $numpaginas = 0;
            
            foreach($resultadoTotal as $filaTotal){
                $numTotal = (int)$filaTotal['total'];
            }
            
            if( $numTotal > 0 ) {
                
                $numpaginas = ceil( $numTotal / $filasxpagina );
                $inicioPag = $filasxpagina * $varAcceso['num_pagina'] - $filasxpagina;

                $resultadoInicio = $conexion->DBConsulta("
                    SELECT a.idproducto, a.nombre, a.nombre_seo, a.precio, a.precio_anterior, a.descripcion_corta, a.descripcion_larga,
                    b.stock, e.valor
                    FROM productos AS a
                    INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                    INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                    INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                    INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                    WHERE a.estado = 'ACTIVO'
                    AND d.idsector = '".$_SESSION['idsector']."'
                    AND b.stock > 0
                    AND a.idsubcategoria = '".$globalSubcategoria['idsubcategoria']."'
                    ORDER BY a.nombre
                    LIMIT ".$inicioPag.",".$filasxpagina."
                ", 2);

                $claseProducto = new Producto();                

                foreach($resultadoInicio as $filaInicio){
                    // Validar que exista la imagen o colocar la de error
                    $imagenProducto = $pdet_valor['hostapp'].'/images/productos/0/320x320/error.png?v='.$pdet_valor['webversion'];
                    if(file_exists('images/productos/'.$filaInicio['idproducto'].'/320x320/'.$filaInicio['nombre_seo'].'.png')){
                        $imagenProducto = $pdet_valor['hostapp'].'/images/productos/'.$filaInicio['idproducto'].'/320x320/'.$filaInicio['nombre_seo'].'.png?v='.$pdet_valor['webversion'];                        
                    }

                    //******************************

                    $cuerpoProductos .= $claseProducto->graficarProductoModal(
                        'pro-1' ,
                        $globalCntModal ,
                        $pdet_valor['hostapp'] ,
                        $imagenProducto ,
                        $filaInicio['idproducto'],
                        $filaInicio['nombre'] ,
                        $filaInicio['nombre_seo'] ,
                        $filaInicio['descripcion_larga'] ,
                        $filaInicio['descripcion_corta'] ,   
                        $filaInicio['precio_anterior'] ,
                        $filaInicio['precio'] ,
                        $filaInicio['valor'] 
                    );

                    //******************************

                    $globalCntModal++;
                }

                if(!empty($cuerpoProductos)){
                    echo $cuerpoProductos;
                }else{
                    echo '<div class="alert alert-danger alert-dismissable">Número de página inválido.</div>';
                }
                
            }

            ?>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-12">
            <nav>
                <ul class="pagination pagination-lg">
                    <?php

                    if( 
                        $numpaginas > 0 && 
                        !empty($cuerpoProductos)

                    ){

                        if( $numpaginas == 1 ){
                            $enlacesPro = '<li class="active"><a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$globalSubcategoria['nombre_seo'].'/1">1</a></li>';

                            echo $enlacesPro;
                        }else{
                            $enlacesPro = '';

                            for( $f = 1; $f <= $numpaginas; $f++ ){

                                $claseActive = '';

                                if( $f == $varAcceso['num_pagina'] ){
                                    $claseActive = 'active';
                                }

                                if( $f == 1 ){
                                    //$enlacesPro .= '<li><a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$globalSubcategoria['nombre_seo'].'/1"><span aria-hidden="true">«</span></a></li>';
                                    $enlacesPro .= '<li class="'.$claseActive.'"><a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$globalSubcategoria['nombre_seo'].'/1">1</a></li>';
                                }else{
                                    if( $f == $numpaginas ){
                                        $enlacesPro .= '<li class="'.$claseActive.'"><a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$globalSubcategoria['nombre_seo'].'/'.$f.'"><span aria-hidden="true">'.$f.'</span></a></li>';
                                        //$enlacesPro .= '<li><a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$globalSubcategoria['nombre_seo'].'/'.$f.'"><span aria-hidden="true">»</span></a></li>';
                                    }else{
                                        $enlacesPro .= '<li class="'.$claseActive.'"><a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$globalSubcategoria['nombre_seo'].'/'.$f.'"><span aria-hidden="true">'.$f.'</span></a></li>';
                                    }
                                }
                            }

                            echo $enlacesPro;

                        }

                    }

                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>