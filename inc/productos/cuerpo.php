 <!--banner-->
<div class="banner-top">
	<div class="container">
		<h3>Detalles del producto</h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Lorem</h4>
        -->
		<div class="clearfix"></div>
	</div>
</div>
<div class="single">
    <div class="container">
        <div itemscope itemtype="http://schema.org/Product" class="single-top-main">
            <div class="col-md-5 single-top">
                <div class="single-w3agile">
                    <div id="picture-frame">
                        <?php
                        
                        // Guardar el log de visualizacion de producto
    
                        $conexion->DBConsulta("
                            INSERT INTO cli_visualizacion_producto
                            (idproducto, user_create, sys_create) 
                            VALUES 
                            ('".$globalProducto['idproducto']."','".$_SESSION['usuario']."',NOW())
                        ", 2);
                        
                        //***********************************************************
                        
                        $imgPro = $pdet_valor['hostapp'].'/images/productos/0/320x320/error.png?v='.$pdet_valor['webversion'];
                        if(file_exists('images/productos/'.$globalProducto['idproducto'].'/320x320/'.$globalProducto['nombre_seo'].'.png')){
                            $imgPro = $pdet_valor['hostapp'].'/images/productos/'.$globalProducto['idproducto'].'/320x320/'.$globalProducto['nombre_seo'].'.png?v='.$pdet_valor['webversion'];                        
                        }
                        
                        ?>
                        <img itemprop="image" src="<?php echo $imgPro; ?>" data-src="<?php echo $imgPro; ?>" alt="<?php echo $globalProducto['nombre']; ?>" class="img-responsive"/>
                    </div>
                </div>
            </div>
            <div class="col-md-7 single-top-left ">
                <div class="single-right">
                    <h3 itemprop="name"><?php echo $globalProducto['nombre']; ?></h3>
                    <div class="pr-single">
                        <p itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="reduced"><del>
                            <?php
                            
                            if((int)$globalProducto['valor'] > 0){
                                $sub_pre = ($globalProducto['precio'] * (int)$globalProducto['valor']) / 100;
                                $globalProducto['precio'] = number_format(((float)$globalProducto['precio'] + $sub_pre), 2, '.', '');
                                $sub_pre_ant = ($globalProducto['precio_anterior'] * (int)$globalProducto['valor']) / 100;
                                $globalProducto['precio_anterior'] = number_format(((float)$globalProducto['precio_anterior'] + $sub_pre_ant), 2, '.', '');
                            }
                            
                            if( 
                                !empty($globalProducto['precio_anterior']) && 
                                $globalProducto['precio_anterior'] > $globalProducto['precio'] 
                            ){
                                echo "$".$globalProducto['precio_anterior'];
                            }else{
                                echo "";
                            }
                            ?>
                            </del><span itemprop="price">$<?php echo $globalProducto['precio']; ?></span></p>
                    </div>
                    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="block block-w3">
                        <div itemprop="ratingValue" class="starbox small ghosting"></div>
                    </div>
                    <p itemprop="description" class="in-pa"><?php echo $globalProducto['descripcion_larga']; ?></p>
                    <div class="add add-3">
                        <button class="btn btn-danger my-cart-btn my-cart-b" data-idproducto="<?php echo $globalProducto['idproducto']; ?>" data-cantidad="1" data-image="<?php echo $imgPro; ?>">Añadir</button>
                        
                        <?php 
                        
                        $url_producto = $_SERVER["REQUEST_URI"];
                        $url_face_share = 'https://www.facebook.com/sharer.php?p[url]='.$pdet_valor['hostapp'].$url_producto.'&p[title]='.$globalProducto['nombre'].'&p[summary]='.$globalProducto['descripcion_larga'].'&p[images][0]='.urlencode($imgPro);
                        $url_plus_share = 'https://plus.google.com/share?url='.$pdet_valor['hostapp'].$url_producto;
                        $url_whatsapp_share = 'https://api.whatsapp.com/send?text='.$pdet_valor['hostapp'].$url_producto;
                        $url_linkedin_share = 'https://www.linkedin.com/shareArticle?mini=true&url='.$pdet_valor['hostapp'].$url_producto.'&title='.$globalProducto['nombre'].'&summary='.$globalProducto['descripcion_larga'];
                        
                        ?>
                        
                        <!--<button onclick="window.open('<?php echo $url_face_share; ?>','name','width=600,height=400')" data-type="facebook" type="button" class="btn btn-default btn-facebook social-sharing">
				        <a href="<?php echo $url_face_share; ?>" target="popup" ><i class="fa fa-facebook"></i></a> Compartir
			            </button>-->
			        </div>
			        <div class="clearfix"> </div>
			        <div class="add add-3">
			            <h5>Compartir</h5>
			        </div>
                    <div class="clearfix"> </div>
                    <ul class="social-top">
					    <li><a href="" id="shareBtn" class="icon facebook"><i class="fa fa-facebook" aria-hidden="true"></i><span></span></a></li>
					    <!--<li><a href="#" class="icon twitter"><i class="fa fa-twitter" aria-hidden="true"></i><span></span></a></li>-->
					    <li><a onclick="window.open('<?php echo $url_plus_share; ?>','name','width=600,height=400')" data-type="google-plus" href="<?php echo $url_plus_share; ?>" target="popup" class="icon google"><i class="fa fa-google-plus" aria-hidden="true"></i><span></span></a></li>
                        <li><a onclick="window.open('<?php echo $url_linkedin_share; ?>','name','width=600,height=400')" data-type="linkedin" href="<?php echo $url_linkedin_share; ?>" target="popup" class="icon linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i><span></span></a></li>
                        <div class="mkt-cnt-whatsapp">
                            <li><a target="_blank" href="<?php echo $url_whatsapp_share; ?>" data-action="share/whatsapp/share" class="icon whatsapp"><i class="fa fa-whatsapp" aria-hidden="true"></i><span></span></a></li>
                        </div>
					    <!--<li><a href="<?php echo $url_plus_share; ?>" target="popup" class="icon dribbble"><i class="fa fa-google-plus" aria-hidden="true"></i><span></span></a></li>-->
				    </ul>
			        <div class="add add-3">
                        <script>
                        document.getElementById('shareBtn').onclick = function() {
                            FB.ui({
                                method: 'share',
                                hashtag: '#todoadomicilio',
                                mobile_iframe: true,
                                display: 'popup',
                                href: '<?php echo $pdet_valor['hostapp'].$url_producto; ?>',
                            }, function(response){});
                        }
                        </script>
                    </div>
			       
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>	
    </div>
</div>
<!---->
<div class="content-top offer-w3agile">
    <div class="container ">
        <div class="spec ">
            <h3>Ofertas especiales</h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
        </div>
        <div class=" con-w3l wthree-of">
            <?php

            $resultadoInicio = $conexion->DBConsulta("
                SELECT a.idproducto, a.nombre, a.nombre_seo, a.precio, a.precio_anterior, a.descripcion_corta, a.descripcion_larga,
                b.stock, e.valor
                FROM productos AS a
                INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                WHERE a.estado = 'ACTIVO'
                AND a.precio_anterior IS NOT NULL
                AND a.precio < IFNULL(a.precio_anterior, 0)
                AND d.idsector = '".$_SESSION['idsector']."'
                AND b.stock > 0
                AND a.idsubcategoria = '".$globalProducto['idsubcategoria']."'
                AND a.idproducto != '".$globalProducto['idproducto']."'
                ORDER BY RAND()
                LIMIT 8
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

            ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>