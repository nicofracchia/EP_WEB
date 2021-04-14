<div class='seccionPpal seccionNosotros' id='seccionNosotros'>
	<!--<div class='tituloSeccion'>NOSOTROS</div>-->
	<table class='tablaNosotros'>
		<tr>
			<td class='cajaSeccionNosotros cajaSeccionNosotros1' onclick='oGeneralesPW.sliderNosotros=1;oGeneralesPW.fnMuestraNosotros(1);'><div class='verticalNos'>¿Quiénes somos?</div></td>
			<td style='width:5%;'></td>
			<td class='cajaSeccionNosotros cajaSeccionNosotros2' onclick='oGeneralesPW.sliderNosotros=1;oGeneralesPW.fnMuestraNosotros(2);'><div class='verticalNos'>¿Qué nos diferencia?</div></td>
			<td style='width:5%;'></td>
			<td class='cajaSeccionNosotros cajaSeccionNosotros3' onclick='oGeneralesPW.sliderNosotros=1;oGeneralesPW.fnMuestraNosotros(3);'><div class='verticalNos'>BIO</div></td>
		</tr>
		<tr>
			<td class='flechitaNosotrosSeleccionado' colspan=5><img src='images/iconos/flechaNosotros.png' alt='' /></td>
		</tr>
		<tr>
			<td colspan=5 id='contenedorNosotros'>
				<table id='XQNosotros'>
					<tr>
						<td colspan="2">
							<h1 style="margin: 0;color: #FFF;font-size:20px;;margin-left: 17px;">¿Quiénes somos?</h1>
						</td>
					</tr>
					<tr>
						<td class='datosNos'>
							<ul style='padding:0;'>
								<?php
									$SQL_QS = "SELECT * FROM ep_nosotros WHERE idServicio = '1' ORDER BY orden ASC";
									$RS_QS = mysqli_query($conexion, $SQL_QS);
									while($qs = mysqli_fetch_object($RS_QS)){
										echo "<li>".utf8_encode($qs->texto)."</li>";
									}
								?>
							</ul>
						</td>
					</tr>
				</table>
				<table id='XQDiferenciamos'>
					<tr>
						<td colspan="2">
							<h1 style="margin: 0;color: #FFF;font-size:20px;;margin-left: 17px;">¿Qué nos diferencia?</h1>
						</td>
					</tr>
					<tr>
						<td class='datosNos'>
							<ul style='padding:0;'>
								<?php
									$SQL_QS = "SELECT * FROM ep_nosotros WHERE idServicio = '2' ORDER BY orden ASC";
									$RS_QS = mysqli_query($conexion, $SQL_QS);
									while($qs = mysqli_fetch_object($RS_QS)){
										echo "<li>".utf8_encode($qs->texto)."</li>";
									}
								?>
							</ul>
						</td>
					</tr>
				</table>
				<table id='quienesSomos'>
					<tr>
						<td colspan="2">
							<h1 style="margin: 0;color: #FFF;font-size:20px;;margin-left: 17px;margin-bottom:20px;">BIO</h1>
						</td>
					</tr>
					<tr>
						<?php
							$SQL_BIO = "SELECT * FROM ep_nosotros_bio";
							$RS_BIO = mysqli_query($conexion, $SQL_BIO);
							while($bio = mysqli_fetch_object($RS_BIO)){
								if($bio->id == 1){//DATOS LORENA
									$nombreL = utf8_encode($bio->nombre);
									$puestoL = utf8_encode($bio->puesto);
									$titulosL = utf8_encode($bio->titulos);
									$textoL = utf8_encode($bio->texto);
								}
								if($bio->id == 2){//DATOS DORIS
									$nombreD = utf8_encode($bio->nombre);
									$puestoD = utf8_encode($bio->puesto);
									$titulosD = utf8_encode($bio->titulos);
									$textoD = utf8_encode($bio->texto);
								}
							}
						?>
						<td class='fotoNos datosNos'>
							<img src='images/nosotros/lorena.png' alt='Lorena Zapata' />
							<h1><?php echo $nombreL; ?></h1>
							<b><?php echo $puestoL; ?></b><br/>
							<?php echo $titulosL; ?>
						</td>
						<td class='fotoNos datosNos'>
							<img src='images/nosotros/doris.png' alt='Doris Stauber' />
							<h1><?php echo $nombreD; ?></h1>
							<b><?php echo $puestoD; ?></b></br>
							<?php echo $titulosD; ?>
						</td>
					</tr>
					<tr>
						<tr class='txtGral'>
							<td class='datosNos'><?php echo $textoL; ?></td>
							<td class='datosNos'><?php echo $textoD; ?></td>
						</tr>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- responsive -->
	<table class='tablaNosotrosRESP'>
		<tr>
			<td class='cajaSeccionNosotros cajaSeccionNosotros1' onclick="oGeneralesPW.fnGiroFlechaResponsiveNosotros(1);">
				<div class='verticalNos'>
					<img src='images/iconos/flechaServiciosDer.png' class='flechaServicios' id='flechaServicios_1qs' alt='' style='vertical-align: middle;' /> 
					¿Quiénes somos?
				</div>
			</td>
		</tr>
		<tr>
			<td style='padding:0;'>
				<table id='XQNosotrosR'>
					<tr>
						<td colspan="2">
							<h1 style="margin: 0;color: #FFF;font-size:35px;;margin-left: 17px;">¿Quiénes somos?</h1>
						</td>
					</tr>
					<tr>
						<td class='datosNos'>
							<ul style='padding:0;'>
								<?php
									$SQL_QS = "SELECT * FROM ep_nosotros WHERE idServicio = '1' ORDER BY orden ASC";
									$RS_QS = mysqli_query($conexion, $SQL_QS);
									while($qs = mysqli_fetch_object($RS_QS)){
										echo "<li>".utf8_encode($qs->texto)."</li>";
									}
								?>
							</ul>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='cajaSeccionNosotros cajaSeccionNosotros2' onclick="oGeneralesPW.fnGiroFlechaResponsiveNosotros(2);">
				<div class='verticalNos'>
					<img src='images/iconos/flechaServiciosDer.png' class='flechaServicios' id='flechaServicios_2qd' alt='' style='vertical-align: middle;' />
					¿Qué nos diferencia?
				</div>
			</td>
		</tr>
		<tr>
			<td style='padding:0;'>
				<table id='XQDiferenciamosR'>
					<tr>
						<td colspan="2">
							<h1 style="margin: 0;color: #FFF;font-size:35px;;margin-left: 17px;">¿Qué nos diferencia?</h1>
						</td>
					</tr>
					<tr>
						<td class='datosNos'>
							<ul style='padding:0;'>
								<?php
									$SQL_QS = "SELECT * FROM ep_nosotros WHERE idServicio = '2' ORDER BY orden ASC";
									$RS_QS = mysqli_query($conexion, $SQL_QS);
									while($qs = mysqli_fetch_object($RS_QS)){
										echo "<li>".utf8_encode($qs->texto)."</li>";
									}
								?>
							</ul>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class='cajaSeccionNosotros cajaSeccionNosotros3' onclick="oGeneralesPW.fnGiroFlechaResponsiveNosotros(3);">
				<div class='verticalNos'>
					<img src='images/iconos/flechaServiciosDer.png' class='flechaServicios' id='flechaServicios_3b' alt='' style='vertical-align: middle;' /> 
					BIO
				</div>
			</td>
		</tr>
		<tr>
			<td style='padding:0;'>
				<table id='quienesSomosR'>
					<tr>
						<td colspan="2">
							<h1 style="margin: 0;color: #FFF;font-size:35px;margin-left: 17px;margin-bottom:20px;">BIO</h1>
						</td>
					</tr>
					<tr>
						<?php
							$SQL_BIO = "SELECT * FROM ep_nosotros_bio";
							$RS_BIO = mysqli_query($conexion, $SQL_BIO);
							while($bio = mysqli_fetch_object($RS_BIO)){
								if($bio->id == 1){//DATOS LORENA
									$nombreL = utf8_encode($bio->nombre);
									$puestoL = utf8_encode($bio->puesto);
									$titulosL = utf8_encode($bio->titulos);
									$textoL = utf8_encode($bio->texto);
								}
								if($bio->id == 2){//DATOS DORIS
									$nombreD = utf8_encode($bio->nombre);
									$puestoD = utf8_encode($bio->puesto);
									$titulosD = utf8_encode($bio->titulos);
									$textoD = utf8_encode($bio->texto);
								}
							}
						?>
						<td class='fotoNos datosNos' onclick="$('#bioDoris').hide('fast');$('#bioLorena').show('fast');$('#flechaBioLorena').css('box-shadow','0 0 15px 5px #FFF');$('#flechaBioDoris').css('box-shadow','none');$('#fotoLorena').css('box-shadow','0 0 15px 5px #FFF');$('#fotoDoris').css('box-shadow','none');">
							<img src='images/nosotros/lorena.png' alt='Lorena Zapata' />
							<h1><?php echo $nombreL; ?></h1>
							<b><?php echo $puestoL; ?></b><br/>
							<?php echo $titulosL; ?>
							<p style='text-align:center;'><img src='images/iconos/flechaServicios.png' alt='Ver Más' class='verMas' id='flechaBioLorena' /></p>
						</td>
						<td class='fotoNos datosNos' onclick="$('#bioDoris').show('fast');$('#bioLorena').hide('fast');$('#flechaBioDoris').css('box-shadow','0 0 15px 5px #FFF');$('#flechaBioLorena').css('box-shadow','none');$('#fotoDoris').css('box-shadow','0 0 15px 5px #FFF');$('#fotoLorena').css('box-shadow','none');">
							<img src='images/nosotros/doris.png' alt='Doris Stauber' />
							<h1><?php echo $nombreD; ?></h1>
							<b><?php echo $puestoD; ?></b></br>
							<?php echo $titulosD; ?>
							<p style='text-align:center;'><img src='images/iconos/flechaServicios.png' alt='Ver Más' class='verMas' id='flechaBioDoris' /></p>
						</td>
					</tr>
					<tr class='txtGral'>
						<td class='datosNos' colspan=2>
							<div id='bioLorena'><?php echo $textoL; ?></div>
							<div id='bioDoris'><?php echo $textoD; ?></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<div class='abajoSeccion'>
		<img src='images/iconos/abajoHome.png' alt='' onclick='oGeneralesPW.fnContacto();' />
	</div>
</div>