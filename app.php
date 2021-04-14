<?php
header('Access-Control-Allow-Origin: *');
	/* ***** CONEXION ***** */
	if($_SERVER['SERVER_NAME'] == 'localhost'){
		$hostBD = 'localhost';
		$usuarioBD = 'root';
		$passBD = '';
		$baseBD = 'appdateLegislativo';
	}else{
		$hostBD = 'prowebsolutions.com.ar';
		$usuarioBD = 'pwGeneral';
		$passBD = '123456321asd';
		$baseBD = 'appdateLegislativo';
	}
	if($_SERVER['SERVER_NAME'] == 'esferapublica.com.ar'){
		$hostBD = 'localhost';
		$usuarioBD = 'prowebsolutions';
		$passBD = '123456321asd';
		$baseBD = 'appdate';
	}
	$conexion = mysqli_connect($hostBD,$usuarioBD,$passBD,$baseBD);


if(!isset($_REQUEST['accion'])){
	die();
}else{
	$accion = $_REQUEST['accion'];
}


if($accion == "internaNoticia"){
	$SQL = "SELECT * FROM noticias WHERE id = '".$_REQUEST['idNoticia']."'";
	$RS = mysqli_query($conexion, $SQL);
	$RES = mysqli_fetch_array($RS);
	
	$SQL_FAVORITOS = "SELECT * FROM favoritos WHERE idCliente = '".$_REQUEST['idClienteLogin']."' AND idNoticia = '".$_REQUEST['idNoticia']."'";
	$RS_FAVORITOS = mysqli_query($conexion, $SQL_FAVORITOS);
	if(mysqli_num_rows($RS_FAVORITOS) > 0){$RES['favorito'] = 1;}else{$RES['favorito'] = 0;}
	
	$SQL_SECCIONES = "SELECT * FROM secciones WHERE";
	$secciones = explode('|',$RES['secciones']);
	foreach($secciones as $s){
		if($s != ''){
			$SQL_SECCIONES .= " id = '".$s."' OR ";
		}
	}
	$SQL_SECCIONES .= " id = 'cvsxs'";
	$RS_SECCIONES = mysqli_query($conexion, $SQL_SECCIONES);
	$RES['secciones'] = '';
	while($s = mysqli_fetch_object($RS_SECCIONES)){
		$RES['secciones'] .= $s->seccion." | ";
	}
	$RES['secciones'] = substr($RES['secciones'],0,-2);
	
	$SQL_LEGISLATURAS = "SELECT * FROM legislaturas WHERE";
	$legislaturas = explode('|',$RES['legislaturas']);
	foreach($legislaturas as $l){
		if($l != ''){
			$SQL_LEGISLATURAS .= " id = '".$l."' OR ";
		}
	}
	$SQL_LEGISLATURAS .= " id = 'cvsxs'";
	$RS_LEGISLATURAS = mysqli_query($conexion, $SQL_LEGISLATURAS);
	$RES['legislaturas'] = '';
	while($l = mysqli_fetch_object($RS_LEGISLATURAS)){
		$RES['legislaturas'] .= $l->legislatura." | ";
	}
	$RES['legislaturas'] = substr($RES['legislaturas'],0,-2);
	
	$adjuntos = '';
	if(is_dir("appdateLegislativoCMS/adjuntosNoticias/".$_REQUEST['idNoticia']."/")){
		foreach(scandir("appdateLegislativoCMS/adjuntosNoticias/".$_REQUEST['idNoticia']."/") as $a){
			if($a != '.' and $a != '..'){
				$HREF = str_replace('app.php', '/appdateLegislativoCMS/adjuntosNoticias/'.$_REQUEST['idNoticia'].'/'.$a, $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']);
				$adjuntos .= "	<a href='".$HREF."' target='_blank' style='float:left;margin-right:25px;'>".utf8_decode($a)."</a>";
			}
		}
	}
	$RES['texto'] .= $adjuntos;
	
	// AJUSTE PARA IMAGENES CON HTTPS
	$RES['imagen'] = str_replace('http://','https://',$RES['imagen']);
	
	//AJUSTE PARA MOSTRAR SOLO PRIMER PARRAFO SI NO ESTA LOGUEADO
	if($_REQUEST['idClienteLogin'] < 1){
		$txt = explode('</p>', $RES['texto']);
		$txt = $txt[0].'</p>';
		$RES['texto'] = $txt;
	}
	
	$RES = array_map("utf8_encode", $RES);
	echo json_encode($RES);
}
if($accion == "listadoNoticias"){
	$HTML = '';
	$params = $_REQUEST['params'];
	$ORDER_BY = " ORDER BY noticias.id DESC, noticias.fecha DESC, -noticias.orden DESC";
	$LIMIT = " LIMIT ".$params['limit'].", 10";
	$WHERE = " WHERE noticias.eliminada = '0' ";
	$FAVORITOS = "";
	if(isset($_REQUEST['tipoUsuario']) and $_REQUEST['tipoUsuario'] != 1){
		$WHERE .= " AND noticias.status = '1' ";
	}
	if(isset($params['idClienteLogin']) and $params['idClienteLogin'] != 0){
		if(!isset($_REQUEST['tipoUsuario']) or $_REQUEST['tipoUsuario'] != 1){
			$WHERE .= " AND (noticias.tipo = '1' OR (noticias.tipo = '2' AND noticias.usuarios LIKE '%".$params['idClienteLogin']."%')) ";
		}
	}else{
		$WHERE .= " AND noticias.tipo = '1' ";
	}
	if($params['favoritos'] == 1 and $params['idClienteLogin'] != 0){
		$FAVORITOS = " INNER JOIN favoritos ON favoritos.idNoticia = noticias.id ";
		$WHERE .= " AND favoritos.idCliente = '".$params['idClienteLogin']."' AND favoritos.tipoUsuario = '".$_REQUEST['tipoUsuario']."' ";
	}
	if($params['desde'] != ''){
		$fecDes = explode('/', $params['desde']);
		$fecDes = $fecDes[2].'-'.$fecDes[1].'-'.$fecDes[0];
		$WHERE .= " AND fecha >= '".$fecDes."'";
	}
	if($params['hasta'] != ''){
		$fecHas = explode('/', $params['hasta']);
		$fecHas = $fecHas[2].'-'.$fecHas[1].'-'.$fecHas[0];
		$WHERE .= " AND fecha <= '".$fecHas."'";
	}
	if($params['tema'] != '' and $params['tema'] != '0'){$WHERE .= " AND UPPER(noticias.tema) = UPPER('".utf8_decode($params['tema'])."')";}
	if($params['menu'] != ''){$WHERE .= " AND secciones LIKE '%|".$params['menu']."|%'";}
	$RELEVANCIA = "";
	if($params['busqueda'] != ''){
		$RELEVANCIA = ", IF(UPPER(titulo) LIKE '%".strtoupper($params['busqueda'])."%',100,0) + IF(UPPER(tema) LIKE '%".strtoupper($params['busqueda'])."%', 80, 0) + IF(UPPER(texto) LIKE '%".strtoupper($params['busqueda'])."%', 60, 0) + IF(UPPER(keywords) LIKE '%".strtoupper($params['busqueda'])."%', 40, 0) + IF(UPPER(personas) LIKE '%".strtoupper($params['busqueda'])."%', 20, 0) + IF(UPPER(distrito) LIKE '%".strtoupper($params['busqueda'])."%', 10, 0) AS relevancia ";
		$WHERE .= " HAVING relevancia <> 0 ";
	}
	$SQL = "SELECT DISTINCT noticias.* ".$RELEVANCIA."FROM noticias".$FAVORITOS.$WHERE.$ORDER_BY.$LIMIT;
	//echo $SQL;//exit();
	$RS = mysqli_query($conexion, $SQL);
	if(mysqli_num_rows($RS) > 0){
		$HTML = "";
		$i = 0;
		while($n = mysqli_fetch_object($RS)){
			$legislaturas = '';
			$leg = explode('|',$n->legislaturas);
			foreach($leg as $l){
				if($l != ''){
					$SQL_LEGISLATURAS = "SELECT legislatura FROM legislaturas WHERE id = '".$l."'";
					$RS_LEGISLATURAS = mysqli_query($conexion, $SQL_LEGISLATURAS);
					$RES = mysqli_fetch_object($RS_LEGISLATURAS);
					$legislaturas .= $RES->legislatura.' - ';
				}
			}
			$legislaturas = substr($legislaturas,0,-2);
			
			$fecha = explode('-',$n->fecha);
			$fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
			
			$iconoStatus = "publicada.svg";
			if($n->status == '2' or $n->status == 2){
				$iconoStatus = "publicar.svg";
			}
			
			$HTML .= "<tr onclick='oGenerales.cargaNoticiaInterna(".$n->id.");'>";
			$HTML .= "	<td class='txtNoticia' colspan=2>";
			$HTML .= "		<div class='datosNoticia'>";
			$HTML .= "			<img src='images/iconos/".$iconoStatus."' id='iconoPublicada_".$n->id."' class='iconoPublicada' alt='Status' />";
			$HTML .= "			<span class='legislaturaNoticia'>".utf8_encode($legislaturas)." </span>";
			$HTML .= "			<span class='seccionNoticia'>".utf8_encode($n->tema)."</span>";
			$HTML .= "		</div>";
			$HTML .= "	</td>";
			$HTML .= "</tr>";
			$HTML .= "<tr class='contNoticia' onclick='oGenerales.cargaNoticiaInterna(".$n->id.");'>";
			if($n->imagen != ''){
				$HTML .= "	<td class='txtNoticia'>";
				$HTML .= "		<div class='tituloNoticia' style='letter-spacing:0px;'>".utf8_encode($n->titulo)."</div>";
				$HTML .= "		<div class='resumenNoticia'>".strip_tags(utf8_encode(substr($n->texto,0,150)))."...</div>";
				$HTML .= "		<div class='tiempoNoticia'>".utf8_encode($fecha)."</div>";
				$HTML .= "	</td>";
				$HTML .= "	<td class='imgNoticia'>";
				$HTML .= "		<img src='".$n->imagen."' alt='' />";
				$HTML .= "	</td>";
			}else{
				$HTML .= "	<td class='txtNoticia' colspan=2>";
				$HTML .= "		<div class='tituloNoticia' style='letter-spacing:0px;'>".utf8_encode($n->titulo)."</div>";
				$HTML .= "		<div class='resumenNoticia'>".strip_tags(utf8_encode(substr($n->texto,0,300)))."...</div>";
				$HTML .= "		<div class='tiempoNoticia'>".utf8_encode($fecha)."</div>";
				$HTML .= "	</td>";
			}
			$HTML .= "</tr>";
			$i++;
			if($i == 6){
				if(!isset($params['idClienteLogin']) or $params['idClienteLogin'] == 0){
					$SQL_PUBLICIDAD = "SELECT imagen, link FROM publicidad WHERE habilitada = 1 AND eliminada = 0 ORDER BY -orden DESC, id ASC LIMIT ".($params['limit'] / 10).",1";
					$RS_PUBLICIDAD = mysqli_query($conexion, $SQL_PUBLICIDAD);
					$RES_PUBLICIDAD = mysqli_fetch_object($RS_PUBLICIDAD);
					$HTML .= "<tr style='box-shadow: 0 15px 10px -10px #CCC;'><td colspan=2 style='text-align: center;padding: 20px;'><a href='".$RES_PUBLICIDAD->link."' target='_blank'><img src='".$RES_PUBLICIDAD->imagen."' alt='' style='max-width:100%;max-height:150px;' /></a></td></tr>";
					$i = 0;
				}
			}
		}
	}else{
		if($params['limit'] == 0){
			$HTML = "<tr id='trOoops'><td>Ooops! No encontramos resultados para ese criterio de búsqueda. Vuelve a intentar!</td></tr>";
		}
	}
	echo $HTML;
}

if($accion == 'cargaComboTemas'){
	$SQL_TEMAS = "SELECT tema FROM noticias WHERE eliminada = '0' AND status = '1' GROUP BY tema ORDER BY tema ASC";
	$RS_TEMAS = mysqli_query($conexion, $SQL_TEMAS);
	$HTML = "<option value='0'>Temas</option>";
	while($t = mysqli_fetch_object($RS_TEMAS)){
		$HTML .= "<option>".$t->tema."</option>";
	}
	echo utf8_encode($HTML);
}
if($accion == 'cargaComboDistritos'){
	$SQL_DISTRITOS = "SELECT * FROM legislaturas";
	$RS_DISTRITOS = mysqli_query($conexion, $SQL_DISTRITOS);
	$HTML = "<option value='0'>Distritos</option>";
	while($d = mysqli_fetch_object($RS_DISTRITOS)){
		$HTML .= "<option value='".$d->id."'>".$d->legislatura."</option>";
	}
	echo utf8_encode($HTML);
}
if($accion == 'login'){
	$SQL_LOGIN = "SELECT * FROM clientes WHERE mail = '".$_REQUEST['usuario']."' AND clave = '".$_REQUEST['pass']."' AND habilitado = 1 AND eliminado = 0";
	$RS_LOGIN = mysqli_query($conexion, $SQL_LOGIN);
	if(mysqli_num_rows($RS_LOGIN) == 1){
		$return = mysqli_fetch_array($RS_LOGIN);
		$return['permisos'] = 'NADA';
		echo json_encode(array_map('utf8_encode', $return), JSON_UNESCAPED_UNICODE);
	}else{
		$SQL_LOGIN_US = "SELECT * FROM usuarios WHERE mail = '".$_REQUEST['usuario']."' AND clave = '".$_REQUEST['pass']."' AND habilitado = 1 AND eliminado = 0";
		$RS_LOGIN_US = mysqli_query($conexion, $SQL_LOGIN_US);
		if(mysqli_num_rows($RS_LOGIN_US) == 1){
			$return = mysqli_fetch_array($RS_LOGIN_US);
			$return['permisos'] = $return['tipo'];
			echo json_encode(array_map('utf8_encode', $return), JSON_UNESCAPED_UNICODE);
		}else{
			echo 0;
		}
	}
}
if($accion == 'envioSolicitudServicio'){
	$para = "info@esferapublica.com.ar";
	$para = "prowebsem@gmail.com";
	$para2 = "prowebsem@gmail.com";
	$asunto = 'Solicitud de servicio desde APPDATE';
$mensaje = 'NOMBRE Y APELLIDO: '.$_REQUEST['nya'].' 
MAIL: '.$_REQUEST['mail'].'
TELÉFONO: '.$_REQUEST['telefono'].'
COMENTARIO:
'.$_REQUEST['comentario'];
	$cabeceras = "From: ".$_REQUEST['mail']."\r\n"."Reply-To: ".$_REQUEST['mail']."\r\n"."X-Mailer: PHP/".phpversion();
	
	if(mail($para, $asunto, utf8_decode($mensaje), $cabeceras)){
		mail($para2, $asunto, utf8_decode($mensaje), $cabeceras);
		echo "Su solicitud fue enviada. A la brevedad nos contactaremos con usted!";
	}else{
		echo "Hubo un error enviando la solicitud. Por favor, intente nuevamente más tarde.";
	}
}
if($accion == 'envioRegistroCliente'){
	$SQL_VERIFICAMAIL = "SELECT COUNT(*) AS cant FROM clientes WHERE mail = '".$_REQUEST['mail']."' AND eliminado = '0'";
	$RS_VERIFICAMAIL = mysqli_query($conexion,$SQL_VERIFICAMAIL);
	$RES = mysqli_fetch_object($RS_VERIFICAMAIL);
	$cant = $RES->cant;
	if($cant == 0){
		$SQL_CLIENTE = "INSERT INTO clientes (nombre, apellido, telefono, mail, clave, habilitado, eliminado) VALUES ('".$_REQUEST['nombre']."', '".$_REQUEST['apellido']."', '".$_REQUEST['telefono']."', '".$_REQUEST['mail']."', '".$_REQUEST['clave']."', '0', '0')";
		if($RS_CLIENTE = mysqli_query($conexion, $SQL_CLIENTE)){
			echo "El usuario se registró correctamente. A la brevedad nos contactaremos con usted para habilitarlo.";
		}else{
			echo "Hubo un problema creando el usuario. Intente nuevamente más tarde";
		}
	}else{
		echo "Ya existe un usuario con ese mail. Debe ingresar uno diferente.";
	}
}
if($accion == 'guardarFavorito'){
	$SQL_CHK = "SELECT * FROM favoritos WHERE idCliente = '".$_REQUEST['idCliente']."' AND idNoticia = '".$_REQUEST['idNoticia']."' AND tipoUsuario = '".$_REQUEST['tipoUsuario']."'";
	$RS_CHK = mysqli_query($conexion, $SQL_CHK);
	if(mysqli_num_rows($RS_CHK) > 0){
		$SQL_FAVORITO = "DELETE FROM favoritos WHERE idCliente = '".$_REQUEST['idCliente']."' AND idNoticia = '".$_REQUEST['idNoticia']."' AND tipoUsuario = '".$_REQUEST['tipoUsuario']."'";
		$return = 0;
	}else{
		$SQL_FAVORITO = "INSERT INTO favoritos (idCliente,idNoticia,tipoUsuario) VALUES ('".$_REQUEST['idCliente']."', '".$_REQUEST['idNoticia']."', '".$_REQUEST['tipoUsuario']."')";
		$return = 1;
	}
	if(mysqli_query($conexion, $SQL_FAVORITO)){
		echo $return;
	}
}
if($accion == 'modificaStatusNoticia'){
	if($_REQUEST['tipoUsuario'] == '1' or $_REQUEST['tipoUsuario'] == 1){
		$SQL_CHK = "SELECT status FROM noticias WHERE id = '".$_REQUEST['idNoticia']."'";
		$RS_CHK = mysqli_query($conexion, $SQL_CHK);
		$s = mysqli_fetch_object($RS_CHK);
		if($s->status == 1 or $s->status == '1'){
			mysqli_query($conexion, "UPDATE noticias SET status = '0' WHERE id = '".$_REQUEST['idNoticia']."'");
			echo "0";
		}else{
			mysqli_query($conexion, "UPDATE noticias SET status = '1' WHERE id = '".$_REQUEST['idNoticia']."'");
			echo "1";
		}
	}else{
		echo '1';
	}
}
if($accion == 'nuevaNoticiaAPP'){
	// SUBO IMAGEN Y CREO EL NOMBRE PARA LA BASE
	if(isset($_FILES["imagenNuevaNoticia"]) and $_FILES['imagenNuevaNoticia']['size'] != 0){
		$file = $_FILES["imagenNuevaNoticia"];
		$ruta_provisional = $file["tmp_name"];
		$nombre = time();
		$extencion = explode('/',$file["type"]);
		$extencion = $extencion[1];
		$carpeta = "appdateLegislativoCMS/images/noticias/";
		$src = $carpeta.$nombre.'.'.$extencion;
		$nomBD = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/';
		$nomBD1 = explode('/',$_SERVER['PHP_SELF']);
		for($i = 0; $i < count($nomBD1) - 1; $i++){
			$nomBD .= $nomBD1[$i].'/';
		}
		$IMGBD = $nomBD.$carpeta.$nombre.'.'.$extencion;
		move_uploaded_file($ruta_provisional, $src);
	}else{
		$IMGBD = "";
	}
	// ACOMODO FECHA
	$fecha = explode('/', $_GET['fecha']);
	$FECHA = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
	
	// CREO LA NOTICIA
	$SQL  = "INSERT INTO noticias (titulo, texto, imagen, tema, secciones, personas, distrito, legislaturas, fecha, usuarios, tipo, status, keywords) VALUES ";
	$SQL .= "('".utf8_decode($_GET['titulo'])."', '".utf8_decode(nl2br($_GET['contenido']))."', '".$IMGBD."', '".utf8_decode($_GET['tema'])."', '".$_GET['secciones']."', '";
	$SQL .= utf8_decode($_GET['enestanota'])."', '".$_GET['otro']."', '".$_GET['distrito']."', '".$FECHA."', '".$_GET['usuarios']."', '";
	$SQL .= $_GET['tipo']."', '".$_GET['estado']."', '')";
	
	if(mysqli_query($conexion, $SQL)){
		if($_GET['usuarios_push'] != '0'){
			$ID = mysqli_fetch_object(mysqli_query($conexion, "SELECT MAX(id) AS nuevoId FROM noticias"));
			$ID = $ID->nuevoId;
			enviarPushNotification($ID, $_GET['usuarios_push']);
		}
		echo "La noticia se guardó correctamente";
	}else{
		echo "Se producjo un error al guardar la noticia. Por favor, intentente nuevamente más tarde.";
	}
}
if($accion == 'datosNoticiaModificar'){
	$SQL = "SELECT * FROM noticias WHERE id = '".$_REQUEST['idNoticiaEditar']."'";
	$RS = mysqli_query($conexion, $SQL);
	$RES = mysqli_fetch_array($RS);
	$RES = array_map("utf8_encode", $RES);
	$breaks = array("<br />","<br>","<BR>","<br/>");
	echo strip_tags(json_encode($RES));
}
if($accion == 'modificarNoticiaAPP'){
	// ACOMODO FECHA
	$fecha = explode('/', $_GET['fecha']);
	$FECHA = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
	
	// MODIFICO LA NOTICIA
	$SQL  = "UPDATE noticias SET 
				titulo = '".utf8_decode($_GET['titulo'])."',
				texto = '".utf8_decode(nl2br($_GET['contenido']))."',
				tema = '".utf8_decode($_GET['tema'])."',
				secciones = '".$_GET['secciones']."',
				personas = '".utf8_decode($_GET['enestanota'])."',
				distrito = '".$_GET['otro']."',
				legislaturas = '".$_GET['distrito']."', 
				fecha = '".$FECHA."',
				usuarios = '".$_GET['usuarios']."',
				tipo = '".$_GET['tipo']."',
				status = '".$_GET['estado']."',
				keywords = ''
			WHERE id = '".$_GET['idNoticia']."'";
	
	if(mysqli_query($conexion, $SQL)){
		if($_GET['usuarios_push'] != 0 and $_GET['usuarios_push'] != '0'){
			enviarPushNotification($_GET['idNoticia'], $_GET['usuarios_push']);
		}
		echo "La noticia se guardó correctamente";
	}else{
		echo "Se producjo un error al guardar la noticia. Por favor, intentente nuevamente más tarde.";
	}
}
if($accion == 'listadoClientes'){
	if($_REQUEST['tipoNoticia'] != '2' or $_REQUEST['tipoUsuarioAPP'] != '1'){
		echo "";
	}else{
		$SQL_GRUPOSCLIENTES = "SELECT * FROM clientesgrupos WHERE eliminado = '0' ORDER BY grupo";
		$RS_GRUPOSCLIENTES = mysqli_query($conexion, $SQL_GRUPOSCLIENTES);
		$HTML_CLIENTES = "<span style='color:#FFF;font-size: 20px;font-weight: bold;'>Clientes:</span><br/>";
		while($g = mysqli_fetch_object($RS_GRUPOSCLIENTES)){
			$HTML_CLIENTES .= "	<input type='checkbox' class='chkGrupoClientes' id='grupoClientes_".$g->id."' style='width: 30px;height: 30px;vertical-align: middle;margin-right: 20px;' onchange='fnCambiaCheckGrupoClientes(this);' />";
			$HTML_CLIENTES .= "	<label for='grupoClientes_".$g->id."' style='color:#FFF;font-weight:bold;'>".utf8_encode($g->grupo)."</label><br/>";
			
			$SQL_CLIENTES = "SELECT * FROM clientes WHERE habilitado = '1' AND eliminado = '0' AND idGrupo = '".$g->id."' ORDER BY apellido ASC, nombre ASC";
			$RS_CLIENTES = mysqli_query($conexion, $SQL_CLIENTES);
			while($s = mysqli_fetch_object($RS_CLIENTES)){
				$HTML_CLIENTES .= "<input type='checkbox' name='clientes[]' class='chkUsuario' value='".$s->id."' id='cliente_".$s->id."' data-grupo='grupoClientes_".$g->id."' data-idGrupo='".$g->id."' onchange='fnValidaChkGrupal(this);' style='margin-left:20px;width: 30px;height: 30px;vertical-align: middle;margin-right: 20px;' />";
				$HTML_CLIENTES .= "<label for='cliente_".$s->id."' style='color:#FFF;'>".utf8_encode($s->apellido).", ".utf8_encode($s->nombre)."</label><br/>";
			}
		}
		echo $HTML_CLIENTES;
	}
}
if($accion == 'listadoClientes_push'){
	if(isset($_REQUEST['tipoUsuarioAPP']) and $_REQUEST['tipoUsuarioAPP'] != '1'){
		echo "";
	}else{
		$SQL_GRUPOSCLIENTES = "SELECT * FROM clientesgrupos WHERE eliminado = '0' ORDER BY grupo";
		$RS_GRUPOSCLIENTES = mysqli_query($conexion, $SQL_GRUPOSCLIENTES);
		$HTML_CLIENTES = "<span style='color:#FFF;font-size: 20px;font-weight: bold;'>Clientes:</span><br/>";
		while($g = mysqli_fetch_object($RS_GRUPOSCLIENTES)){
			$HTML_CLIENTES .= "	<input type='checkbox' class='chkGrupoClientes' id='grupoClientes_".$g->id."' style='width: 30px;height: 30px;vertical-align: middle;margin-right: 20px;' onchange='fnCambiaCheckGrupoClientes(this);' />";
			$HTML_CLIENTES .= "	<label for='grupoClientes_".$g->id."' style='color:#FFF;font-weight:bold;'>".utf8_encode($g->grupo)."</label><br/>";
			
			$SQL_CLIENTES = "SELECT c.* FROM clientes AS c INNER JOIN tokens AS t ON c.id = t.idCliente WHERE c.habilitado = '1' AND c.eliminado = '0' AND c.idGrupo = '".$g->id."' AND t.permisos = '0' GROUP BY c.id ORDER BY c.apellido ASC, c.nombre ASC";
			$RS_CLIENTES = mysqli_query($conexion, $SQL_CLIENTES);
			while($s = mysqli_fetch_object($RS_CLIENTES)){
				$HTML_CLIENTES .= "<input type='checkbox' name='clientes_push[]' class='chkUsuario_push' value='".$s->id."' id='cliente_push_".$s->id."' data-grupo='grupoClientes_".$g->id."' data-idGrupo='".$g->id."' onchange='fnValidaChkGrupal(this);' style='margin-left:20px;width: 30px;height: 30px;vertical-align: middle;margin-right: 20px;' />";
				$HTML_CLIENTES .= "<label for='cliente_push_".$s->id."' style='color:#FFF;'>".utf8_encode($s->apellido).", ".utf8_encode($s->nombre)."</label><br/>";
			}
		}
		echo $HTML_CLIENTES;
	}
}
if($accion == 'guardarToken'){
	$SQL = "INSERT INTO tokens (token,idCliente,permisos) VALUES ('".$_REQUEST['token']."','".$_REQUEST['cliente']."','".$_REQUEST['permisos']."')";
	$RS = mysqli_query($conexion, $SQL);
	echo '';
}


// PUSH NOTIFICATIONS

function enviarPushNotification($idNoticia, $clientes){
	global $conexion;
	
	// DATOS FCM
	$firebase_api = "AAAAS5LOTVE:APA91bFCnmcbGR-gHAYZ69ApmFm9d90r8NdxiN5BxUwREEAohRmz7XRJS0wxVxD0DonX3XjC1L3XdUILdkFdWeavr8MfOomxJuFjGidSEjUUX-008RHML6S_UhC8wGNWc-VH5761Z9To";
	$firebase_api_iOS = "AAAApBaD7Zg:APA91bGEfzDqpvgOnzXQQXOoOfLPuxVT7f61VUXeV-PlHsMs-l1YTI2AOM5Hpr99AAo5S42SRG-dSsqrvod-w9d9e2xDCSroU-zWx0ZIRJHWA6yM6hFvoUu2hxjax04dmzlHDzYBk4SU";
	$url = 'https://fcm.googleapis.com/fcm/send';
	$headers = array(
		'Authorization: key=' . $firebase_api,
		'Content-Type: application/json'
	);
	$headers_iOS = array(
		'Authorization: key=' . $firebase_api_iOS,
		'Content-Type: application/json'
	);

	// DATOS NOTICIA
	$SQL_NOTICIA = "SELECT titulo, tema, texto FROM noticias WHERE id = '".$idNoticia."'";
	$RS_NOTICIA = mysqli_query($conexion, $SQL_NOTICIA);
	$RES_NOTICIA = mysqli_fetch_object($RS_NOTICIA);
	$requestData = array();
	$requestData['title'] = utf8_encode($RES_NOTICIA->tema).' '.utf8_encode($RES_NOTICIA->titulo);
	$requestData['body'] = substr(strip_tags(cambiarCaracteresEspeciales($RES_NOTICIA->texto)), 0, 200).'...';
	$requestData['idNoticia'] = $idNoticia;
	
	// TOKENS Y ENVIO
	$clientes = explode('|', $clientes);
	foreach($clientes as $c){
		$SQL_TOKENS = "SELECT token FROM tokens WHERE (permisos = '0' AND idCliente = '".$c."') OR (permisos = '1') GROUP BY token";
		$RS_TOKENS = mysqli_query($conexion, $SQL_TOKENS);
		$ENVIO_PUSH_OK = '0';
		$ENVIO_PUSH_IOS_OK = '0';
		while($t = mysqli_fetch_object($RS_TOKENS)){
			$fields = array(
				'to' => $t->token,
				'data' => $requestData,
			);
			// ANDROID
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if($result !== FALSE){
				$ENVIO_PUSH_OK = '1';
			}
			curl_close($ch);
			// IOS
			$ch_iOS = curl_init();
			curl_setopt($ch_iOS, CURLOPT_URL, $url);
			curl_setopt($ch_iOS, CURLOPT_POST, true);
			curl_setopt($ch_iOS, CURLOPT_HTTPHEADER, $headers_iOS);
			curl_setopt($ch_iOS, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch_iOS, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch_iOS, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch_iOS);
			if($result !== FALSE){
				$ENVIO_PUSH_IOS_OK = '1';
			}
			curl_close($ch_iOS);
		}
		if($ENVIO_PUSH_OK == '1'){
			mysqli_query($conexion, "INSERT INTO clientespush (idCliente, idNoticia) VALUES ('".$c."','".$idNoticia."')");
		}
		if($ENVIO_PUSH_IOS_OK == '1'){
			mysqli_query($conexion, "INSERT INTO clientespush (idCliente, idNoticia) VALUES ('".$c."','".$idNoticia."')");
		}
	}
}

function cambiarCaracteresEspeciales($txt){
	$txt = str_replace("&quot;", '"', $txt);
	$txt = str_replace("&amp;", "&", $txt);
	$txt = str_replace("&lt;", "<", $txt);
	$txt = str_replace("&gt;", ">", $txt);
	$txt = str_replace("&nbsp;", " ", $txt);
	$txt = str_replace("&iexcl;", "¡", $txt);
	$txt = str_replace("&cent;", "¢", $txt);
	$txt = str_replace("&pound;", "£", $txt);
	$txt = str_replace("&curren;", "¤", $txt);
	$txt = str_replace("&yen;", "¥", $txt);
	$txt = str_replace("&brvbar;", "¦", $txt);
	$txt = str_replace("&sect;", "§", $txt);
	$txt = str_replace("&uml;", "¨", $txt);
	$txt = str_replace("&copy;", "©", $txt);
	$txt = str_replace("&ordf;", "ª", $txt);
	$txt = str_replace("&laquo;", "«", $txt);
	$txt = str_replace("&not;", "¬", $txt);
	$txt = str_replace("&reg;", "®", $txt);
	$txt = str_replace("&macr;", "¯", $txt);
	$txt = str_replace("&deg;", "°", $txt);
	$txt = str_replace("&plusmn;", "±", $txt);
	$txt = str_replace("&sup2;", "²", $txt);
	$txt = str_replace("&sup3;", "³", $txt);
	$txt = str_replace("&acute;", "´", $txt);
	$txt = str_replace("&micro;", "µ", $txt);
	$txt = str_replace("&para;", "¶", $txt);
	$txt = str_replace("&middot;", "·", $txt);
	$txt = str_replace("&cedil;", "¸", $txt);
	$txt = str_replace("&sup1;", "¹", $txt);
	$txt = str_replace("&ordm;", "º", $txt);
	$txt = str_replace("&raquo;", "»", $txt);
	$txt = str_replace("&frac14;", "¼", $txt);
	$txt = str_replace("&frac12;", "½", $txt);
	$txt = str_replace("&frac34;", "¾", $txt);
	$txt = str_replace("&iquest;", "¿", $txt);
	$txt = str_replace("&Agrave;", "À", $txt);
	$txt = str_replace("&Aacute;", "Á", $txt);
	$txt = str_replace("&Acirc;", "Â", $txt);
	$txt = str_replace("&Atilde;", "Ã", $txt);
	$txt = str_replace("&Auml;", "Ä", $txt);
	$txt = str_replace("&Aring;", "Å", $txt);
	$txt = str_replace("&AElig;", "Æ", $txt);
	$txt = str_replace("&Ccedil;", "Ç", $txt);
	$txt = str_replace("&Egrave;", "È", $txt);
	$txt = str_replace("&Eacute;", "É", $txt);
	$txt = str_replace("&Ecirc;", "Ê", $txt);
	$txt = str_replace("&Euml;", "Ë", $txt);
	$txt = str_replace("&Igrave;", "Ì", $txt);
	$txt = str_replace("&Iacute;", "Í", $txt);
	$txt = str_replace("&Icirc;", "Î", $txt);
	$txt = str_replace("&Iuml;", "Ï", $txt);
	$txt = str_replace("&ETH;", "Ð", $txt);
	$txt = str_replace("&Ntilde;", "Ñ", $txt);
	$txt = str_replace("&Ograve;", "Ò", $txt);
	$txt = str_replace("&Oacute;", "Ó", $txt);
	$txt = str_replace("&Ocirc;", "Ô", $txt);
	$txt = str_replace("&Otilde;", "Õ", $txt);
	$txt = str_replace("&Ouml;", "Ö", $txt);
	$txt = str_replace("&times;", "×", $txt);
	$txt = str_replace("&Oslash;", "Ø", $txt);
	$txt = str_replace("&Ugrave;", "Ù", $txt);
	$txt = str_replace("&Uacute;", "Ú", $txt);
	$txt = str_replace("&Ucirc;", "Û", $txt);
	$txt = str_replace("&Uuml;", "Ü", $txt);
	$txt = str_replace("&Yacute;", "Ý", $txt);
	$txt = str_replace("&THORN;", "Þ", $txt);
	$txt = str_replace("&szlig;", "ß", $txt);
	$txt = str_replace("&agrave;", "à", $txt);
	$txt = str_replace("&aacute;", "á", $txt);
	$txt = str_replace("&acirc;", "â", $txt);
	$txt = str_replace("&atilde;", "ã", $txt);
	$txt = str_replace("&auml;", "ä", $txt);
	$txt = str_replace("&aring;", "å", $txt);
	$txt = str_replace("&aelig;", "æ", $txt);
	$txt = str_replace("&ccedil;", "ç", $txt);
	$txt = str_replace("&egrave;", "è", $txt);
	$txt = str_replace("&eacute;", "é", $txt);
	$txt = str_replace("&ecirc;", "ê", $txt);
	$txt = str_replace("&euml;", "ë", $txt);
	$txt = str_replace("&igrave;", "ì", $txt);
	$txt = str_replace("&iacute;", "í", $txt);
	$txt = str_replace("&icirc;", "î", $txt);
	$txt = str_replace("&iuml;", "ï", $txt);
	$txt = str_replace("&eth;", "ð", $txt);
	$txt = str_replace("&ntilde;", "ñ", $txt);
	$txt = str_replace("&ograve;", "ò", $txt);
	$txt = str_replace("&oacute;", "ó", $txt);
	$txt = str_replace("&ocirc;", "ô", $txt);
	$txt = str_replace("&otilde;", "õ", $txt);
	$txt = str_replace("&ouml;", "ö", $txt);
	$txt = str_replace("&divide;", "÷", $txt);
	$txt = str_replace("&oslash;", "ø", $txt);
	$txt = str_replace("&ugrave;", "ù", $txt);
	$txt = str_replace("&uacute;", "ú", $txt);
	$txt = str_replace("&ucirc;", "û", $txt);
	$txt = str_replace("&uuml;", "ü", $txt);
	$txt = str_replace("&yacute;", "ý", $txt);
	$txt = str_replace("&thorn;", "þ", $txt);
	$txt = str_replace("&yuml;", "ÿ", $txt);
	$txt = str_replace("&euro;", "€", $txt);
	
	return $txt;
}

/**

// PARA PROBAR PUSH CON USUARIO DE FLOR (26), Hay q sacar el die() de arriba para que llegue sin accion

if(isset($_REQUEST['pruebaPush']) and $_REQUEST['pruebaPush'] == 1){
	echo "enviando...";
	enviarPushNotification(1136, 26);
	echo "<br><br>Enviada";
}
**/