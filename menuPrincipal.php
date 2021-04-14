<div class='menuPrincipal'>
	<table class='contenedorItemsMenuPpal'>
		<tr>
			<td class='contLinksMenuPpal'>
				<a href='#' class='seleccionado' onclick='oGeneralesPW.fnHome();' id='menuPpalInicio'>INICIO</a>
				<a href='#' onclick='oGeneralesPW.fnServicios();' id='menuPpalServicios'>SERVICIOS</a>
			</td>
			<td class='logo'>
				<img src='images/logo.png' alt='' onclick='oGeneralesPW.fnHome();' />
			</td>
			<td class='contLinksMenuPpal'>
				<a href='#' onclick='oGeneralesPW.fnNosotros();' id='menuPpalNosotros'>NOSOTROS</a>
				<a href='#' onclick='oGeneralesPW.fnContacto();' id='menuPpalContacto'>CONTACTO</a>
			</td>
		</tr>
	</table>
</div>
<div class='menuPrincipalRESP'>
	<table class='tablaMenuResp'>
		<tr>
			<td class='contLogoResp'><a href='#' onclick='oGeneralesPW.fnHome();'><img src='images/logoResp.png' alt='Esfera Pública' /></a></td>
			<td class='contMenuResp'><img src='images/iconos/menu.png' alt='Menu Principal' onclick="$('#menuResponsiveDesp').fadeToggle('fast');" /></td>
		</tr>
	</table>
	<div id='menuResponsiveDesp'>
		<div class='itemMenuResponsiveDesp' onclick="oGeneralesPW.fnHome();$('#menuResponsiveDesp').fadeOut('fast');">INICIO</div>
		<div class='itemMenuResponsiveDesp' onclick="oGeneralesPW.fnServicios();$('#menuResponsiveDesp').fadeOut('fast');">SERVICIOS</div>
		<div class='itemMenuResponsiveDesp' onclick="oGeneralesPW.fnNosotros();$('#menuResponsiveDesp').fadeOut('fast');">NOSOTROS</div>
		<div class='itemMenuResponsiveDesp' onclick="oGeneralesPW.fnContacto();$('#menuResponsiveDesp').fadeOut('fast');">CONTACTO</div>
	</div>
</div>