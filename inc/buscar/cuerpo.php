<?php 
$consultaProducto = '';
if(isset( $_GET['p1']) && !empty($_GET['p1']) ){ 
    $consultaProducto = $_GET['p1']; 
} 
?>
<div class="banner-top">
	<div class="container">
		<h3>Busqueda</h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Lorem</h4>
        -->
		<div class="clearfix"></div>    
	</div>
</div>
<div class="content-top offer-w3agile">
    <div class="container">
        <div class="col-md-12">
            <form role="form" autocomplete="off" id="formBuscarProductos">
                <div class="form-group input-group">
                    <input maxlength="50" id="buscar_producto" type="text" class="form-control" placeholder="Buscar..." required/>
                    <span class="input-group-btn"><button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span></button></span>
                </div>
            </form>	
        </div>
        <div class="spec ">
            <h3><?php echo $consultaProducto; ?></h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
        </div>
        <div class=" con-w3l agileinf">
            <?php

            if(!empty($consultaProducto)){
                
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
                    AND ( a.nombre LIKE '%".$consultaProducto."%' 
                    OR a.descripcion_corta LIKE '%".$consultaProducto."%' 
                    OR a.descripcion_larga LIKE '%".$consultaProducto."%' )
                    ORDER BY a.nombre
                    LIMIT 12
                ", 2);

                $claseProducto = new Producto();
                $cuerpoProductos = '';

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

                echo $cuerpoProductos;
                
                if(empty($cuerpoProductos)){
                    echo '<div class="alert alert-danger alert-dismissable">No se encontraron coincidencias.</div>';
                }
            }else{
                echo '<div class="alert alert-danger alert-dismissable">No tiene texto para consultar.</div>';
            }

            ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>