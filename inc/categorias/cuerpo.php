<div class="banner-top">
	<div class="container">
		<h3>Categoria</h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Lorem</h4>
        -->
		<div class="clearfix"></div>    
	</div>
</div>
<div class="content-top offer-w3agile">
	<div class="container ">
	   <div class="spec ">
            <h3><?php echo ucfirst(strtolower($globalCategoria['nombre'])); ?></h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
        </div>		
        <?php

            $resultadoInicio = $conexion->DBConsulta("
                SELECT a.*
                FROM subcategorias AS a
                WHERE a.idcategoria = '".$globalCategoria['idcategoria']."'
                AND a.estado = 'ACTIVA'
                AND (
                    SELECT COUNT(*) AS total 
                    FROM productos 
                    WHERE idsubcategoria = a.idsubcategoria
                    AND estado = 'ACTIVO'
                ) > 0
                ORDER BY a.nombre
            ", 2);

            $cuerpoSubCat = '';

            foreach($resultadoInicio as $filaInicio){
                // Validar que exista la imagen o colocar la de error
                $imagenProducto = $pdet_valor['hostapp'].'/images/subcategorias/error.png?v='.$pdet_valor['webversion'];
                if(file_exists('images/subcategorias/'.$filaInicio['nombre_seo'].'.png')){
                    $imagenProducto = $pdet_valor['hostapp'].'/images/subcategorias/'.$filaInicio['nombre_seo'].'.png?v='.$pdet_valor['webversion'];                        
                }

                //******************************
                    
                $cuerpoSubCat .= '<div class="col-md-4 kic-top1">';
                $cuerpoSubCat .= '<a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$filaInicio['nombre_seo'].'">';
                $cuerpoSubCat .= '<img src="'.$imagenProducto.'" class="img-responsive" alt="'.$filaInicio['nombre'].'">';
                $cuerpoSubCat .= '</a>';
                $cuerpoSubCat .= '<a href="'.$pdet_valor['hostapp'].'/'.$globalCategoria['nombre_seo'].'/'.$filaInicio['nombre_seo'].'"><h6>'.$filaInicio['nombre'].'</h6></a>';
                $cuerpoSubCat .= '<p>'.$filaInicio['descripcion'].'</p>';
                $cuerpoSubCat .= '</div>';

                //******************************
                    
            }

            echo $cuerpoSubCat;
        ?>
	</div>
</div>