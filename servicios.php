<?php
	$SQL_NOMBRES_SERVICIOS = "SELECT * FROM ep_serviciosnombre";
	$RS_NOMBRES_SERVICIOS = mysqli_query($conexion, $SQL_NOMBRES_SERVICIOS);
	while($ns = mysqli_fetch_object($RS_NOMBRES_SERVICIOS)){
		if($ns->id == 1){$nombreServicio1 = $ns->nombre;}
		if($ns->id == 2){$nombreServicio2 = $ns->nombre;}
		if($ns->id == 3){$nombreServicio3 = $ns->nombre;}
		if($ns->id == 4){$nombreServicio4 = $ns->nombre;}
	}
?>
<div class='seccionPpal seccionServicios' id='seccionServicios'>
	<table class='tablaServicios'>
		<tr>
			<td class='tdServicio' id='cajaServicio1' onclick='oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(1);'>
				<div class='textoServicio'><?php echo $nombreServicio1; ?></div>
				<img src='images/servicio1.png' alt='<?php echo $nombreServicio1; ?>' />
				<img src='images/iconos/flechaServicios.png' class='flechaServicios' alt='' />
			</td>
			<td class='tdSeparador'></td>
			<td class='tdServicio' id='cajaServicio2' onclick='oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(2);'>
				<div class='textoServicio'><?php echo $nombreServicio2; ?></div>
				<img src='images/servicio2.png' alt='<?php echo $nombreServicio2; ?>' />
				<img src='images/iconos/flechaServicios.png' class='flechaServicios' alt='' />
			</td>
			<td class='tdSeparador'></td>
			<td class='tdServicio' id='cajaServicio3' onclick='oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(3);'>
				<div class='textoServicio'><?php echo $nombreServicio3; ?></div>
				<img src='images/servicio3.png' alt='<?php echo $nombreServicio3; ?>' />
				<img src='images/iconos/flechaServicios.png' class='flechaServicios' alt='' />
			</td>
			<td class='tdSeparador'></td>
			<td class='tdServicio' id='cajaServicio4' onclick='oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(4);'>
				<div class='textoServicio'><?php echo $nombreServicio4; ?></div>
				<img src='images/servicio4.png' alt='<?php echo $nombreServicio4; ?>' />
				<img src='images/iconos/flechaServicios.png' class='flechaServicios' alt='' />
			</td>
		</tr>
		<tr>
			<td colspan=7><br/><br/></td>
		</tr>
		<tr>
			<td colspan=7 class='descripcionServicios' id='descripcionServicios'>
				<div class='contServicios' id='servicio1'>
					<h1 class='tituloServicioPpal'><?php echo $nombreServicio1; ?></h1>
					<?php
						$SQL_APPDATE = "SELECT * FROM ep_servicios WHERE idServicio = '1' ORDER BY orden ASC";
						$RS_APPDATE = mysqli_query($conexion, $SQL_APPDATE);
						while($sa = mysqli_fetch_object($RS_APPDATE)){
							if($sa->tipo == 1){
								echo utf8_encode($sa->texto)."<br/><br/>";
							}
							if($sa->tipo == 2){
								echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($sa->texto)."</span><br/><br/>";
							}
						}
					?>
					<p>
					
						Descarga APPDATE desde las Tiendas habituales de tu celular.<br/>
								¡Es gratis y lo seguirá siendo!

					</p>
					<br/><br/>
					<p style='font-size: 100px;line-height: 10px;margin: 0;cursor: pointer;color:rgba(255,255,255,0.5);'>
						<span style='color:#FFF;margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(1);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(2);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(3);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(4);">&middot;</span>
					</p>
				</div>
				<div class='contServicios' id='servicio2'>
					<h1 class='tituloServicioPpal'><?php echo $nombreServicio2; ?></h1>
					<?php
						$SQL_MONITOREO = "SELECT * FROM ep_servicios WHERE idServicio = '2' ORDER BY orden ASC";
						$RS_MONITOREO = mysqli_query($conexion, $SQL_MONITOREO);
						while($sm = mysqli_fetch_object($RS_MONITOREO)){
							if($sm->tipo == 1){
								echo utf8_encode($sm->texto)."<br/><br/>";
							}
							if($sm->tipo == 2){
								echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($sm->texto)."</span><br/><br/>";
							}
						}
					?>
					<p>Si te interesa contar con este servicio, <span class='link' onclick='oGeneralesPW.fnContacto();'>¡CONTACTANOS!</span></p>
					<br/><br/>
					<p style='font-size: 100px;line-height: 10px;margin: 0;cursor: pointer;color:rgba(255,255,255,0.5);'>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(1);">&middot;</span>
						<span style='color:#FFF;margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(2);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(3);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(4);">&middot;</span>
					</p>
				</div>
				<div class='contServicios' id='servicio3'>
					<h1 class='tituloServicioPpal'><?php echo $nombreServicio3; ?></h1>
					<?php
						$SQL_CONSULTORIA = "SELECT * FROM ep_servicios WHERE idServicio = '3' ORDER BY orden ASC";
						$RS_CONSULTORIA = mysqli_query($conexion, $SQL_CONSULTORIA);
						while($sc = mysqli_fetch_object($RS_CONSULTORIA)){
							if($sc->tipo == 1){
								echo utf8_encode($sc->texto)."<br/><br/>";
							}
							if($sc->tipo == 2){
								echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($sc->texto)."</span><br/><br/>";
							}
						}
					?>
					<p>Si te interesa contar con este servicio, <span class='link' onclick='oGeneralesPW.fnContacto();'>¡CONTACTANOS!</span></p>
					<br/><br/>
					<p style='font-size: 100px;line-height: 10px;margin: 0;cursor: pointer;color:rgba(255,255,255,0.5);'>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(1);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(2);">&middot;</span>
						<span style='color:#FFF;margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(3);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(4);">&middot;</span>
					</p>
				</div>
				<div class='contServicios' id='servicio4'>
					<h1 class='tituloServicioPpal'><?php echo $nombreServicio4; ?></h1>
					<?php
						$SQL_INCIDENCIA = "SELECT * FROM ep_servicios WHERE idServicio = '4' ORDER BY orden ASC";
						$RS_INCIDENCIA = mysqli_query($conexion, $SQL_INCIDENCIA);
						while($si = mysqli_fetch_object($RS_INCIDENCIA)){
							if($si->tipo == 1){
								echo utf8_encode($si->texto)."<br/><br/>";
							}
							if($si->tipo == 2){
								echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($si->texto)."</span><br/><br/>";
							}
						}
					?>
					<p>Si te interesa contar con este servicio, <span class='link' onclick='oGeneralesPW.fnContacto();'>¡CONTACTANOS!</span></p>
					<br/><br/>
					<p style='font-size: 100px;line-height: 10px;margin: 0;cursor: pointer;color:rgba(255,255,255,0.5);'>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(1);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(2);">&middot;</span>
						<span style='margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(3);">&middot;</span>
						<span style='color:#FFF;margin-left:-30px;' onclick="oGeneralesPW.sliderServicios=1;oGeneralesPW.fnServiciosDescripcion(4);">&middot;</span>
					</p>
				</div>
			</td>
		</tr>
	</table>
	<!-- responsive -->
	<table class='tablaServiciosRESP'>
		<tr>
			<td class='opcionServiciosRESP' id='cajaServicio1R' onclick="oGeneralesPW.fnGiroFlechaResponsiveServicios(1);"><?php echo $nombreServicio1; ?></td>
		</tr>
		<tr>
			<td class='contServiciosRESP' id='servicio1R'>
				<h1 class='tituloServicioPpal'><?php echo $nombreServicio1; ?></h1>
				<?php
					$SQL_APPDATE = "SELECT * FROM ep_servicios WHERE idServicio = '1' ORDER BY orden ASC";
					$RS_APPDATE = mysqli_query($conexion, $SQL_APPDATE);
					while($sa = mysqli_fetch_object($RS_APPDATE)){
						if($sa->tipo == 1){
							echo utf8_encode($sa->texto)."<br/><br/>";
						}
						if($sa->tipo == 2){
							echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($sa->texto)."</span><br/><br/>";
						}
					}
				?>
				<p>
					<br/><br/>
					Descargá APPDATE desde las Tiendas habituales. <br/>
					¡Es gratis y lo seguirá siendo!<br/>
					<a href="https://play.google.com/store/apps/details?id=com.prowebsolutions.appdatelegislativo" target="_blank">
						<img src="images/iconos/playstore.png" alt="playstore" style="float: left;width:40%;margin: 70px 20px;margin-top:100px;">
					</a>
					<a href="https://itunes.apple.com/us/app/appdate-legislativo/id1459810250?ls=1&mt=8" target="_blank">
						<img src="images/iconos/ios.png" alt="ios" style="float: right;width:40%;margin: 70px 20px;margin-top:100px;">
					</a>
				</p>
				<br/><br/>
			</td>
		</tr>
		<tr>
			<td class='opcionServiciosRESP' id='cajaServicio2R' onclick="oGeneralesPW.fnGiroFlechaResponsiveServicios(2);"><?php echo $nombreServicio2; ?></td>
		</tr>
		<tr>
			<td class='contServiciosRESP' id='servicio2R'>
				<h1 class='tituloServicioPpal'><?php echo $nombreServicio2; ?></h1>
				<?php
					$SQL_MONITOREO = "SELECT * FROM ep_servicios WHERE idServicio = '2' ORDER BY orden ASC";
					$RS_MONITOREO = mysqli_query($conexion, $SQL_MONITOREO);
					while($sm = mysqli_fetch_object($RS_MONITOREO)){
						if($sm->tipo == 1){
							echo utf8_encode($sm->texto)."<br/><br/>";
						}
						if($sm->tipo == 2){
							echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($sm->texto)."</span><br/><br/>";
						}
					}
				?>
				<br/><br/>
				<p>Si te interesa contar con este servicio, <span class='link' onclick='oGeneralesPW.fnContacto();'>¡CONTACTANOS!</span></p>
				<br/><br/>
			</td>
		</tr>
		<tr>
			<td class='opcionServiciosRESP' id='cajaServicio3R' onclick="oGeneralesPW.fnGiroFlechaResponsiveServicios(3);"><?php echo $nombreServicio3; ?></td>
		</tr>
		<tr>
			<td class='contServiciosRESP' id='servicio3R'>
				<h1 class='tituloServicioPpal'><?php echo $nombreServicio3; ?></h1>
				<?php
					$SQL_CONSULTORIA = "SELECT * FROM ep_servicios WHERE idServicio = '3' ORDER BY orden ASC";
					$RS_CONSULTORIA = mysqli_query($conexion, $SQL_CONSULTORIA);
					while($sc = mysqli_fetch_object($RS_CONSULTORIA)){
						if($sc->tipo == 1){
							echo utf8_encode($sc->texto)."<br/><br/>";
						}
						if($sc->tipo == 2){
							echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($sc->texto)."</span><br/><br/>";
						}
					}
				?>
				<br/><br/>
				<p>Si te interesa contar con este servicio, <span class='link' onclick='oGeneralesPW.fnContacto();'>¡CONTACTANOS!</span></p>
				<br/><br/>
			</td>
		</tr>
		<tr>
			<td class='opcionServiciosRESP' id='cajaServicio4R' onclick="oGeneralesPW.fnGiroFlechaResponsiveServicios(4);"><?php echo $nombreServicio4; ?></td>
		</tr>
		<tr>
			<td class='contServiciosRESP' id='servicio4R'>
				<h1 class='tituloServicioPpal'><?php echo $nombreServicio4; ?></h1>
				<?php
					$SQL_INCIDENCIA = "SELECT * FROM ep_servicios WHERE idServicio = '4' ORDER BY orden ASC";
					$RS_INCIDENCIA = mysqli_query($conexion, $SQL_INCIDENCIA);
					while($si = mysqli_fetch_object($RS_INCIDENCIA)){
						if($si->tipo == 1){
							echo utf8_encode($si->texto)."<br/><br/>";
						}
						if($si->tipo == 2){
							echo "<span class='subtituloServicio'><img src='images/iconos/abajoHome.png' class='imgBullet' />".utf8_encode($si->texto)."</span><br/><br/>";
						}
					}
				?>
				<br/><br/>
				<p>Si te interesa contar con este servicio, <span class='link' onclick='oGeneralesPW.fnContacto();'>¡CONTACTANOS!</span></p>
				<br/><br/>
			</td>
		</tr>
	</table>
	<div class='abajoSeccion'>
		<img src='images/iconos/abajoHome.png' alt='' onclick='oGeneralesPW.fnNosotros();' />
	</div>
</div>