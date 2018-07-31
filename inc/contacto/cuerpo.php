<!--banner-->
<div class="banner-top">
	<div class="container">
		<h3>Formulario</h3>
        <!--
		<h4><a href="index.html">Home</a><label>/</label>Contact</h4>
		<div class="clearfix"> </div>
        -->
	</div>
</div>
<!-- contact -->
<div class="contact">
	<div class="container">
		<div class="spec ">
			<h3>Contáctenos</h3>
            <div class="ser-t">
                <b></b>
                <span><i></i></span>
                <b class="line"></b>
            </div>
		</div>
		<div class=" contact-w3">	
			<div class="col-md-5 contact-right">
                <img src="<?php echo $pdet_valor['hostapp']; ?>/images/system/motorizado.png" class="img-responsive" alt="Motorizado">
				<iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1993.574404125142!2d-79.91354686501238!3d-2.096048050077356!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sec!4v1483757970794" style="border:0"></iframe>
			</div>
			<div class="col-md-7 contact-left">
				<h4>Información de contacto</h4>
				<p>Tus comentarios y solicitudes especiales son importantes para nosotros , no dudes en escribirnos amamos brindar asesoria, nosotros te ayudaremos a completar tus compras.</p>
				<ul class="contact-list">
					<li> <i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $pdet_valor['direccionempresacorta']; ?></li>
					<li><i class="fa fa-envelope" aria-hidden="true"></i><a rel="nofollow" href="mailto:<?php echo $pdet_valor['mailatencioncliente']; ?>"><?php echo $pdet_valor['mailatencioncliente']; ?></a></li>
					<li> <i class="fa fa-phone" aria-hidden="true"></i><?php echo $pdet_valor['telefonopedidos']; ?></li>
				</ul>
				<div id="container">
					<!--Horizontal Tab-->
					<div id="parentHorizontalTab">
						<ul class="resp-tabs-list hor_1">
							<li><i class="fa fa-envelope" aria-hidden="true"></i></li>
							<li> <i class="fa fa-whatsapp" aria-hidden="true"></i></span></li>
							<li> <i class="fa fa-phone" aria-hidden="true"></i></li>
						</ul>
						<div class="resp-tabs-container hor_1">
							<div>
								<form role="form" id="formContacto">
									<input type="text" id="nombre" maxlength="150" placeholder="Nombre" required>
									<input type="email" id="email" maxlength="240" placeholder="mail@ejemplo.com" required>
									<textarea id="mensaje" placeholder="Mensaje..." maxlength="500" required></textarea>
									<input type="submit" id="submitFormContacto" value="Enviar" >
								</form>
							</div>
							<div>
								<div class="map-grid">
								<h5>¿Cómo escribirnos a Whatsapp?</h5>
									<ul>
										<li><i class="fa fa-arrow-right" aria-hidden="true"></i>Agrega nuestro número.</li>
										<li><i class="fa fa-arrow-right" aria-hidden="true"></i><?php echo $pdet_valor['whatsapp']; ?></li>
										<li><i class="fa fa-arrow-right" aria-hidden="true"></i>Cuando estemos en tu lista de contactos.</li>
										<li><i class="fa fa-arrow-right" aria-hidden="true"></i>Haz clic en el boton de Whatsapp.</li>
									</ul>
								</div>
							</div>
							<div>
								<div class="map-grid">
									<h5>Comuníquese con nosotros</h5>
									<ul>
										<li>Móvil : <?php echo $pdet_valor['whatsapp']; ?></li>
										<li>Teléfono : <?php echo $pdet_valor['telefonopedidos']; ?></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>				
			</div>			
		<div class="clearfix"></div>
	</div>
	</div>
</div>
<!-- //contact -->