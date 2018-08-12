<div data-vide-bg="<?php echo $pdet_valor['hostapp']; ?>/videos/vide/video">
    <div class="container">
		<div class="banner-info">
			<h3>Consulta nuestras secciones o utiliza el buscador para encontrar los productos que necesitas</h3>	
			<div class="search-form">
				<form id="consulta-producto">
					<input type="text" id="search_products_text" placeholder="Buscar..." required>
					<input type="submit" value=" ">
				</form>
			</div>		
		</div>	
    </div>
</div>
<!--content-->
<div class="content-top "> 
	<div class="container ">
		<div class="spec ">
			<h3>Categorias Populares</h3>
			<div class="ser-t">
				<b></b>
				<span><i></i></span>
				<b class="line"></b>
			</div>
		</div>
			<div class="tab-head ">
				<nav class="nav-sidebar">
					<ul class="nav tabs ">
                        <?php
                        $catSeleccionada = array();
                        $resultadoCatPopulares = $pdet_valor['catpopulares'];
                        if ($resultadoCatPopulares) {
                            $catSeleccionada = explode(",", $resultadoCatPopulares);
                        } else {
                            echo "Llamar al equipo de Soporte: El error podria estar en los parametros del sitio.";
                        }
                        sort($catSeleccionada);
                        $inicioCategorias = array();
                        $resultadoCatTitulos = $conexion->DBConsulta("
                                SELECT nombre FROM `subcategorias` WHERE idsubcategoria in ($resultadoCatPopulares) order by idsubcategoria asc;
                        ");
                        $contCat = 1;
                        $activeTitle = 'active';
                        foreach ($resultadoCatTitulos as $cateTitulos) {
                            if ($contCat == 1) {
                                echo '<li class="' . $activeTitle . '"><a href="#tab' . $contCat . '" data-toggle="tab">' . $cateTitulos['nombre'] . '</a></li>';
                            } else {
                                echo '<li class=""><a href="#tab' . $contCat . '" data-toggle="tab">' . $cateTitulos['nombre'] . '</a></li>';
                            }
                            $contCat++;
                        }
                        ?>
					</ul>
				</nav>
				<div class=" tab-content tab-content-t ">
					<div class="tab-pane active text-style" id="tab1">
						<div class=" con-w3l">
                            <?php
                            $claseProducto = new Producto();
                            $resultadoSubca = $conexion->DBConsulta("
                                SELECT a.idproducto, a.nombre, a.nombre_seo, a.precio, a.precio_anterior, a.descripcion_corta, a.descripcion_larga,
                                b.stock, e.valor
                                FROM productos AS a
                                INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                                INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                                INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                                INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                                WHERE a.estado = 'ACTIVO'
                                AND d.idsector = '" . $_SESSION['idsector'] . "'
                                AND b.stock > 0
                                AND a.idsubcategoria = '" . $catSeleccionada[0] . "'
                                ORDER BY RAND()
                                LIMIT 4
                            ", 2);

                            $cuerpoProductos = '';

                            foreach ($resultadoSubca as $filaSubca) {
                                // Validar que exista la imagen o colocar la de error
                                $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/0/320x320/error.png?v=' . $pdet_valor['webversion'];
                                if (file_exists('images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png')) {
                                    $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png?v=' . $pdet_valor['webversion'];
                                }

                                //******************************

                                $cuerpoProductos .= $claseProducto->graficarProductoModal(
                                    'm-wthree',
                                    $globalCntModal,
                                    $pdet_valor['hostapp'],
                                    $imagenProducto,
                                    $filaSubca['idproducto'],
                                    $filaSubca['nombre'],
                                    $filaSubca['nombre_seo'],
                                    $filaSubca['descripcion_larga'],
                                    $filaSubca['descripcion_corta'],
                                    $filaSubca['precio_anterior'],
                                    $filaSubca['precio'],
                                    $filaSubca['valor']
                                );

                                //******************************

                                $globalCntModal++;
                            }

                            echo $cuerpoProductos;

                            ?>
							<div class="clearfix"></div>
						 </div>
					</div>
					<div class="tab-pane  text-style" id="tab2">
						<div class=" con-w3l">
							<?php

        $resultadoSubca = $conexion->DBConsulta("
                                SELECT a.idproducto, a.nombre, a.nombre_seo, a.precio, a.precio_anterior, a.descripcion_corta, a.descripcion_larga,
                                b.stock, e.valor
                                FROM productos AS a
                                INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                                INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                                INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                                INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                                WHERE a.estado = 'ACTIVO'
                                AND d.idsector = '" . $_SESSION['idsector'] . "'
                                AND b.stock > 0
                                AND a.idsubcategoria = '" . $catSeleccionada[1] . "'
                                ORDER BY RAND()
                                LIMIT 4
                            ", 2);

        $cuerpoProductos = '';

        foreach ($resultadoSubca as $filaSubca) {
                                // Validar que exista la imagen o colocar la de error
            $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/0/320x320/error.png?v=' . $pdet_valor['webversion'];
            if (file_exists('images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png')) {
                $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png?v=' . $pdet_valor['webversion'];
            }

                                //******************************

            $cuerpoProductos .= $claseProducto->graficarProductoModal(
                'm-wthree',
                $globalCntModal,
                $pdet_valor['hostapp'],
                $imagenProducto,
                $filaSubca['idproducto'],
                $filaSubca['nombre'],
                $filaSubca['nombre_seo'],
                $filaSubca['descripcion_larga'],
                $filaSubca['descripcion_corta'],
                $filaSubca['precio_anterior'],
                $filaSubca['precio'],
                $filaSubca['valor']
            );

                                //******************************

            $globalCntModal++;
        }

        echo $cuerpoProductos;

        ?>
							<div class="clearfix"></div>
						 </div>		  
					</div>
					<div class="tab-pane  text-style" id="tab3">
						<div class=" con-w3l">
							<?php

        $resultadoSubca = $conexion->DBConsulta("
                                SELECT a.idproducto, a.nombre, a.nombre_seo, a.precio, a.precio_anterior, a.descripcion_corta, a.descripcion_larga,
                                b.stock, e.valor
                                FROM productos AS a
                                INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                                INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                                INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                                INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                                WHERE a.estado = 'ACTIVO'
                                AND d.idsector = '" . $_SESSION['idsector'] . "'
                                AND b.stock > 0
                                AND a.idsubcategoria = '" . $catSeleccionada[2] . "'
                                ORDER BY RAND()
                                LIMIT 4
                            ", 2);

        $cuerpoProductos = '';

        foreach ($resultadoSubca as $filaSubca) {
                                // Validar que exista la imagen o colocar la de error
            $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/0/320x320/error.png?v=' . $pdet_valor['webversion'];
            if (file_exists('images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png')) {
                $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png?v=' . $pdet_valor['webversion'];
            }

                                //******************************

            $cuerpoProductos .= $claseProducto->graficarProductoModal(
                'm-wthree',
                $globalCntModal,
                $pdet_valor['hostapp'],
                $imagenProducto,
                $filaSubca['idproducto'],
                $filaSubca['nombre'],
                $filaSubca['nombre_seo'],
                $filaSubca['descripcion_larga'],
                $filaSubca['descripcion_corta'],
                $filaSubca['precio_anterior'],
                $filaSubca['precio'],
                $filaSubca['valor']
            );

                                //******************************

            $globalCntModal++;
        }

        echo $cuerpoProductos;

        ?>
							<div class="clearfix"></div>
						 </div>		  
					</div>
					<div class="tab-pane text-style" id="tab4">
				        <div class=" con-w3l">
							<?php

        $resultadoSubca = $conexion->DBConsulta("
                                SELECT a.idproducto, a.nombre, a.nombre_seo, a.precio, a.precio_anterior, a.descripcion_corta, a.descripcion_larga,
                                b.stock, e.valor
                                FROM productos AS a
                                INNER JOIN productos_stock AS b ON (a.idproducto = b.idproducto)
                                INNER JOIN bodegas_zonas AS c ON (b.idbodega = c.idbodega)
                                INNER JOIN sectores AS d ON (c.idzona = d.idzona)
                                INNER JOIN impuestos AS e ON ( a.idimpuesto = e.idimpuesto )
                                WHERE a.estado = 'ACTIVO'
                                AND d.idsector = '" . $_SESSION['idsector'] . "'
                                AND b.stock > 0
                                AND a.idsubcategoria = '" . $catSeleccionada[3] . "'
                                ORDER BY RAND()
                                LIMIT 4
                            ", 2);

        $cuerpoProductos = '';

        foreach ($resultadoSubca as $filaSubca) {
                                // Validar que exista la imagen o colocar la de error
            $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/0/320x320/error.png?v=' . $pdet_valor['webversion'];
            if (file_exists('images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png')) {
                $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/' . $filaSubca['idproducto'] . '/320x320/' . $filaSubca['nombre_seo'] . '.png?v=' . $pdet_valor['webversion'];
            }

                                //******************************

            $cuerpoProductos .= $claseProducto->graficarProductoModal(
                'm-wthree',
                $globalCntModal,
                $pdet_valor['hostapp'],
                $imagenProducto,
                $filaSubca['idproducto'],
                $filaSubca['nombre'],
                $filaSubca['nombre_seo'],
                $filaSubca['descripcion_larga'],
                $filaSubca['descripcion_corta'],
                $filaSubca['precio_anterior'],
                $filaSubca['precio'],
                $filaSubca['valor']
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
			</div>
		
	</div>
	</div>
	</div>

<!--content-->
<div class="content-mid">
	<div class="container">
		
		<div class="col-md-4 m-w3ls">
			<div class="col-md1 ">
				<a href="<?php echo $pdet_valor['hostapp']; ?>/alimentos-y-bebidas/enlatados-y-envasados">
					<img src="<?php echo $pdet_valor['hostapp']; ?>/images/banner/enlatados.png?v=<?php echo $pdet_valor['webversion']; ?>" class="img-responsive img" alt="Enlatados">
					<div class="big-sa">
						<h6>Nuevas categorias</h6>
						<h3>Enlat<span>ados</span></h3>
						<p>Endulza tu día con un capricho</p>
					</div>
				</a>
			</div>
		</div>
		<div class="col-md-4 m-w3ls1">
			<div class="col-md ">
				<a href="<?php echo $pdet_valor['hostapp']; ?>/alimentos-y-bebidas/galletas">
					<img src="<?php echo $pdet_valor['hostapp']; ?>/images/banner/snacks.png?v=<?php echo $pdet_valor['webversion']; ?>" class="img-responsive img" alt="Snacks">
					<div class="big-sale">
						<div class="big-sale1">
							<h3><span>Snacks</span></h3>
                            <p>Todo para tu fiesta</p>
						</div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-md-4 m-w3ls">
			<div class="col-md2 ">
				<a href="<?php echo $pdet_valor['hostapp']; ?>/alimentos-y-bebidas/aceites">
					<img src="<?php echo $pdet_valor['hostapp']; ?>/images/banner/aceites.png?v=<?php echo $pdet_valor['webversion']; ?>" class="img-responsive img1" alt="Aceites">
					<div class="big-sale2">
						<h3><span>Aceites</span> de cocina</h3>	
					</div>
				</a>
			</div>
			<div class="col-md3 ">
				<a href="<?php echo $pdet_valor['hostapp']; ?>/alimentos-y-bebidas/arroz">
					<img src="<?php echo $pdet_valor['hostapp']; ?>/images/banner/arroz.png?v=<?php echo $pdet_valor['webversion']; ?>" class="img-responsive img1" alt="Arroz">
					<div class="big-sale3">
						<h3>Arr<span>oz</span></h3>
						<p>Para tu día a día</p>
					</div>
				</a>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!--content-->
<!-- Carousel
================================================== -->
<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
  </ol>
  <div class="carousel-inner" role="listbox">
    <div class="item active">
     <a href="<?php echo $pdet_valor['hostapp']; ?>/cuidado-personal"><img class="first-slide" src="<?php echo $pdet_valor['hostapp']; ?>/images/banner/cuidado-personal.png?v=<?php echo $pdet_valor['webversion']; ?>" alt="Cuidado personal"></a>
    </div>
    <div class="item">
     <a href="<?php echo $pdet_valor['hostapp']; ?>/limpieza-y-hogar"><img class="second-slide " src="<?php echo $pdet_valor['hostapp']; ?>/images/banner/limpieza-y-hogar.png?v=<?php echo $pdet_valor['webversion']; ?>" alt="Limpieza y hogar"></a>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<!-- /.carousel -->



<!--content-->
<div class="product">
    <div class="container">
        <div class="spec ">
            <h3>Ofertas especiales</h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
        </div>

        <div class="con-w3l">                
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
                AND d.idsector = '" . $_SESSION['idsector'] . "'
                AND b.stock > 0
                ORDER BY RAND()
                LIMIT 8
            ", 2);

            $cuerpoProductos = '';

            foreach ($resultadoInicio as $filaInicio) {
                // Validar que exista la imagen o colocar la de error
                $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/0/320x320/error.png?v=' . $pdet_valor['webversion'];
                if (file_exists('images/productos/' . $filaInicio['idproducto'] . '/320x320/' . $filaInicio['nombre_seo'] . '.png')) {
                    $imagenProducto = $pdet_valor['hostapp'] . '/images/productos/' . $filaInicio['idproducto'] . '/320x320/' . $filaInicio['nombre_seo'] . '.png?v=' . $pdet_valor['webversion'];
                }

                //******************************

                $cuerpoProductos .= $claseProducto->graficarProductoModal(
                    'pro-1',
                    $globalCntModal,
                    $pdet_valor['hostapp'],
                    $imagenProducto,
                    $filaInicio['idproducto'],
                    $filaInicio['nombre'],
                    $filaInicio['nombre_seo'],
                    $filaInicio['descripcion_larga'],
                    $filaInicio['descripcion_corta'],
                    $filaInicio['precio_anterior'],
                    $filaInicio['precio'],
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
<!-- newsletter
================================================== -->

<div class="clearfix"></div>
    <div class="subscribe">
        <div class="w3-agile-subscribe">
            <h4 class="title_suscribe"> Recibe todas nuestras noticias y promociones </h4>
            <form>
                <input type="email" id="email_suscribe" name="EMAIL" placeholder="Ingresa tu email" required="">
                <input id ="clickSuscribe" value="Suscribete">
            </form>
        </div>
    </div>

<!-- /.newsletter -->