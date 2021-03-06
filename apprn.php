<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$parametrosJSON = file_get_contents('php://input');
$parametros = json_decode($parametrosJSON, TRUE); 

/* ***** CONEXION ***** */
$hostBD = 'localhost';
$usuarioBD = 'prowebsolutions';
$passBD = '123456321asd';
$baseBD = 'appdate';
$conexion = mysqli_connect($hostBD,$usuarioBD,$passBD,$baseBD);


if(!isset($parametros['accion'])){
	die();
}else{
	$accion = $parametros['accion'];
}

if($accion == 'logout'){
	$params = $parametros['params'];
	$SQL = "DELETE FROM usuarios_dispositivos WHERE id_dispositivo = '".$params['deviceID']."'";
	mysqli_query($conexion, $SQL);
	echo '{"LOGOUT": "OK"}';
}

if($accion == 'login'){
	$params = $parametros['params'];
	$origen = "APP";
	if(isset($params['origen'])){$origen = $params['origen'];}
	
	if(isset($params['usuario']) and isset($params['pass']) and $params['usuario'] != '' and $params['pass'] != ''){
		// LOGIN FORMULARIO
		$SQL = "SELECT id, nombre, apellido FROM clientes WHERE mail = '".$params['usuario']."' AND clave = '".$params['pass']."' AND habilitado = 1 AND eliminado = 0";
		$RS = mysqli_query($conexion, $SQL);
		$RES = mysqli_fetch_object($RS);
		$ID = $RES->id;
		$NYA = $RES->nombre.' '.$RES->apellido;
		if($ID > 0){
			if($origen == "APP"){
				$SQL = "INSERT INTO usuarios_dispositivos (id_usuario, id_dispositivo, nya) VALUES ('".$ID."', '".$params['deviceID']."', '".$NYA."')";
				mysqli_query($conexion, $SQL);
			}
			$JSON['ID'] = $ID;
			$JSON['NYA'] = utf8_encode($NYA);
			$JSON = json_encode($JSON);
		}else{
			$JSON = '{"ID": "0", "NYA": ""}';
		}
	}else{
		// LOGIN DE SESION POR ID DE DISPOSITIVO
		$SQL = "SELECT id_usuario, nya FROM usuarios_dispositivos WHERE id_dispositivo = '".$params['deviceID']."'";
		//echo $SQL; exit();
		$RS = mysqli_query($conexion, $SQL);
		$RES = mysqli_fetch_object($RS);
		$ID = $RES->id_usuario;
		$NYA = $RES->nya;
		if($ID > 0){
			$JSON['ID'] = $ID;
			$JSON['NYA'] = utf8_encode($NYA);
			$JSON = json_encode($JSON);
		}else{
			$JSON = '{"ID": "0", "NYA": ""}';
		}
	}
	
	echo $JSON;
}

if($accion == "listadoNoticias"){
	$params = $parametros['params'];
	$origen = "APP";
	$cantLimit = 10;
	if(isset($params['cantLimit']) and $params['cantLimit'] > 0){$cantLimit = $params['cantLimit'];}
	if(isset($params['origen'])){$origen = $params['origen'];}
	
	$ORDER_BY = " ORDER BY noticias.id DESC, noticias.fecha DESC, -noticias.orden DESC";
	$LIMIT = " LIMIT ".$params['limit'].", ".$cantLimit."";
	$WHERE = " WHERE noticias.eliminada = '0' AND noticias.status = '1' ";
	$RELEVANCIA = "";
	$FAVORITOS = "";
	
	if($params['favoritos'] == 1 and $params['idClienteLogin'] != 0){
		$FAVORITOS = " INNER JOIN favoritos ON favoritos.idNoticia = noticias.id ";
		$WHERE .= " AND favoritos.idCliente = '".$params['idClienteLogin']."' AND favoritos.tipoUsuario = '".$parametros['tipoUsuario']."' ";
	}
	
	// ID CLIENTE
	if(isset($params['cliente']) and $params['cliente'] > 0){
		$WHERE .= " AND (noticias.tipo = '1' OR (noticias.tipo = '2' AND noticias.usuarios LIKE '%|".$params['cliente']."|%')) ";
	}else{
		$WHERE .= " AND noticias.tipo = '1' ";
	}
	
	// MENU
	if($params['menu'] != ''){$WHERE .= " AND secciones LIKE '%|".$params['menu']."|%'";}
	
	// BUSQUEDA
	if(isset($params['filtroAplicado']) and $params['filtroAplicado'] == 'busqueda'){
		if($params['busqueda'] != ''){
			$RELEVANCIA = ", IF(UPPER(titulo) LIKE '%".
				strtoupper(utf8_decode($params['busqueda'])).
				"%',100,0) + IF(UPPER(tema) LIKE '%".
				strtoupper(utf8_decode($params['busqueda'])).
				"%', 80, 0) + IF(UPPER(texto) LIKE '%".
				strtoupper(utf8_decode($params['busqueda'])).
				"%', 60, 0) + IF(UPPER(keywords) LIKE '%".
				strtoupper(utf8_decode($params['busqueda'])).
				"%', 40, 0) + IF(UPPER(personas) LIKE '%".
				strtoupper(utf8_decode($params['busqueda'])).
				"%', 20, 0) + IF(UPPER(distrito) LIKE '%".
				strtoupper(utf8_decode($params['busqueda'])).
				"%', 10, 0) AS relevancia ";
			$WHERE .= " HAVING relevancia <> 0 ";
		}
		if($params['desde'] != ''){
			$fecDes = explode('/', $params['desde']);
			if(count($fecDes) > 1){
				$fecDes = $fecDes[2].'-'.$fecDes[1].'-'.$fecDes[0];
			}else{
				$fecDes = $params['desde'];
			}
			$WHERE .= " AND fecha >= '".$fecDes."'";
		}
		if($params['hasta'] != ''){
			$fecHas = explode('/', $params['hasta']);
			if(count($fecHas) > 1){
				$fecHas = $fecHas[2].'-'.$fecHas[1].'-'.$fecHas[0];
			}else{
				$fecHas = $params['hasta'];
			}
			$WHERE .= " AND fecha <= '".$fecHas."'";
		}
		if($params['tema'] != '' and $params['tema'] != '0'){$WHERE .= " AND UPPER(noticias.tema) = UPPER('".utf8_decode($params['tema'])."')";}
		if($params['distrito'] != '' and $params['distrito'] != '0'){$WHERE .= " AND noticias.legislaturas LIKE '|".$params['distrito']."|'";}
	}
	
	$SQL = "SELECT DISTINCT noticias.* ".$RELEVANCIA."FROM noticias".$FAVORITOS.$WHERE.$ORDER_BY.$LIMIT;
	if(isset($params['verQuery']) and $params['verQuery'] == 1){
		echo $SQL;exit();
	}
	$RS = mysqli_query($conexion, $SQL);
	
	if(mysqli_num_rows($RS) > 0){
		$noticias = Array();
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
			$noticias[$i]['id'] = $n->id;
			$noticias[$i]['secciones'] = $n->secciones;
			$noticias[$i]['legislatura'] = utf8_encode($legislaturas);
			$noticias[$i]['tema'] = utf8_encode($n->tema);
			$noticias[$i]['titulo'] = utf8_encode($n->titulo);
			if($n->imagen != ''){
				if($origen == 'APP'){$noticias[$i]['contenido'] = substr(cambiarCaracteresEspeciales(strip_tags(utf8_decode($n->texto))),0,150);}
				if($origen == 'WEB'){$noticias[$i]['contenido'] = substr(strip_tags(utf8_decode($n->texto)),0,150);}
				$noticias[$i]['imagen'] = $n->imagen;
			}else{
				if($origen == 'APP'){$noticias[$i]['contenido'] = substr(cambiarCaracteresEspeciales(strip_tags(utf8_decode($n->texto))),0,300);}
				if($origen == 'WEB'){$noticias[$i]['contenido'] = substr(strip_tags(utf8_decode($n->texto)),0,300);}
				$noticias[$i]['imagen'] = "";
			}
			$noticias[$i]['fecha'] = utf8_encode($fecha);
			$i++;
		}
		$JSON = Array();
		$JSON['noticias'] = $noticias;
		if($i < 9){
			$JSON['SN'] = 'FIN';
		}
		
		$JSON = json_encode($JSON);
	}else{
		if($params['limit'] == 0){
			$JSON = '{"SN": "Ooops! No encontramos resultados para ese criterio de b??squeda. Vuelve a intentar!"}'; // ---> MENSAJE editable si no se encontro ningun resultado
		}else{
			$JSON = '{"SN": "FIN"}'; // ---> VACIO si ya hay noticias cargadas y solo llego al final
		}
	}
	
	echo $JSON;
}

if($accion == 'filtroTema'){
	$SQL_TEMAS = "SELECT tema FROM noticias WHERE eliminada = '0' AND status = '1' GROUP BY tema ORDER BY tema ASC";
	$RS_TEMAS = mysqli_query($conexion, $SQL_TEMAS);
	$JSON = Array();
	$i = 0;
	while($t = mysqli_fetch_object($RS_TEMAS)){
		$JSON['temas'][$i] = utf8_encode($t->tema);
		$i++;
	}
	echo json_encode($JSON);
}

if($accion == 'noticiaInterna'){
	$params = $parametros['params'];
	
	$SQL = "SELECT * FROM noticias WHERE id = '".$params['idNoticia']."' AND eliminada = 0";
	$RS = mysqli_query($conexion, $SQL);
	$RES = mysqli_fetch_array($RS);
	
	$SQL_FAVORITOS = "SELECT * FROM favoritos WHERE idCliente = '".$params['idCliente']."' AND idNoticia = '".$params['idNoticia']."'";
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
	
	
	// REVISAR COMO PARSEAR LOS ADJUNTOS!!!
	// ESTO SOLO SIRVE PARA WEB
	$RES['adjuntos'] = "";
	if(is_dir("appdateLegislativoCMS/adjuntosNoticias/".$params['idNoticia']."/")){
		foreach(scandir("appdateLegislativoCMS/adjuntosNoticias/".$params['idNoticia']."/") as $a){
			if($a != '.' and $a != '..'){
				$HREF = str_replace('apprn.php', 'appdateLegislativoCMS/adjuntosNoticias/'.$params['idNoticia'].'/'.$a, $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']);
				$RES['adjuntos'] .= "	<a href='".$HREF."' target='_blank' class='archivoAdjuntoNoticia'>".utf8_decode($a)."</a><br/>";
			}
		}
	}
	//$RES['texto'] .= $adjuntos;
	
	// AJUSTE PARA IMAGENES CON HTTPS
	$RES['imagen'] = str_replace('http://','https://',$RES['imagen']);
	
	//AJUSTE PARA MOSTRAR SOLO PRIMER PARRAFO SI NO ESTA LOGUEADO
	if($params['idCliente'] < 1){
		$txt = explode('</p>', $RES['texto']);
		$txt = $txt[0].'</p>';
		$RES['texto'] = $txt;
	}
	
	$RES = array_map("utf8_encode", $RES);
	
	// AJUSTE DE TEXTO PARA TODO EXCEPTO WEB
	$texto = $RES['texto'];
	if(!isset($params['origen']) or $params['origen'] != 'WEB'){
		// ARMO OBJETO DE TEXTO PARSEANDO PARRAFOS Y LINKS
		$texto = Array();
		// FORMATEO LAS LISTAS Y SACO CARACTERES ESPECIALES Y LOS <p>
		$txt = cambiarCaracteresEspeciales($RES['texto']);
		$txt = str_replace('<ul>','',$txt);
		$txt = str_replace('</ul>','',$txt);
		$txt = str_replace('<li>',' ??? ',$txt);
		$txt = str_replace('</li>','',$txt);
		$txt = str_replace('<p>','',$txt);
		// ARMO LOS BLOQUES DE TEXTO PARA EL ARRAY CON LOS </p>
		$bloques = explode('</p>',$txt);
		// PARSEO LOS LINKS PARA CADA BLOQUE O DEJO SOLO TEXTO
		$b = 0;
		foreach($bloques as $bloque){
			$bloque = explode('<a href="', $bloque);
			$i = 0;
			foreach($bloque as $t){
				$parseado = explode('">', $t);
				if(count($parseado) == 1){
					$texto[$b][$i]['tipo'] = 'txt';
					$texto[$b][$i]['txt'] = strip_tags($parseado[0]);
				}
				if(count($parseado) > 1){
					$texto[$b][$i]['tipo'] = 'enlace';
					$texto[$b][$i]['url'] = $parseado[0];
					$parseado2 = explode('</a>', $parseado[1]);
					$texto[$b][$i]['txtUrl'] = $parseado2[0];
					$texto[$b][$i]['txt'] = strip_tags($parseado2[1]);
				}
				$i++;
			}
			$b++;
		}
	}
	
	// ARMO ARRAY CON LOS DATOS PARA DEVOLVER Y NADA MAS
	$noticia['id'] = $RES['id'];
	$noticia['titulo'] = strip_tags(cambiarCaracteresEspeciales($RES['titulo']));
	$noticia['texto'] = $texto;
	$noticia['imagen'] = $RES['imagen'];
	$noticia['tema'] = $RES['tema'];
	$noticia['secciones'] = $RES['secciones'];
	$noticia['personas'] = $RES['personas'];
	$noticia['distrito'] = $RES['distrito'];
	$noticia['legislatura'] = $RES['legislaturas'];
	$noticia['fecha'] = $RES['fecha'];
	$noticia['favorito'] = $RES['favorito'];
	$noticia['adjuntos'] = $RES['adjuntos'];
	
	
	echo json_encode($noticia);
}

if($accion == 'publicidad'){
	$SQL = "SELECT imagen, link FROM publicidad WHERE habilitada = 1 AND eliminada = 0 ORDER BY orden ASC";
	$RS = mysqli_query($conexion, $SQL);
	$pub = Array();
	$i = 0;
	while($p = mysqli_fetch_object($RS)){
		$pub[$i]['imagen'] = $p->imagen;
		$pub[$i]['url'] = $p->link;
		$i++;
	}
	echo json_encode($pub);
}

if($accion == 'ultimosTemas'){
	$SQL = "SELECT DISTINCT tema FROM noticias WHERE eliminada = 0 AND status = 1 ORDER BY id DESC LIMIT 0, 10";
	$RS = mysqli_query($conexion, $SQL);
	$temas = Array();
	$i = 0;
	while($t = mysqli_fetch_object($RS)){
		$temas[$i]['tema'] = utf8_encode($t->tema);
		$i++;
	}
	echo json_encode($temas);
}

function cambiarCaracteresEspeciales($txt){
	$txt = str_replace("&quot;", '"', $txt);
	$txt = str_replace("&ldquo;", '"', $txt);
	$txt = str_replace("&rdquo;", '"', $txt);
	$txt = str_replace("&quot;", '"', $txt);
	$txt = str_replace("&amp;", "&", $txt);
	$txt = str_replace("&lt;", "<", $txt);
	$txt = str_replace("&gt;", ">", $txt);
	$txt = str_replace("&nbsp;", " ", $txt);
	$txt = str_replace("&iexcl;", "??", $txt);
	$txt = str_replace("&cent;", "??", $txt);
	$txt = str_replace("&pound;", "??", $txt);
	$txt = str_replace("&curren;", "??", $txt);
	$txt = str_replace("&yen;", "??", $txt);
	$txt = str_replace("&brvbar;", "??", $txt);
	$txt = str_replace("&sect;", "??", $txt);
	$txt = str_replace("&uml;", "??", $txt);
	$txt = str_replace("&copy;", "??", $txt);
	$txt = str_replace("&ordf;", "??", $txt);
	$txt = str_replace("&laquo;", "??", $txt);
	$txt = str_replace("&not;", "??", $txt);
	$txt = str_replace("&reg;", "??", $txt);
	$txt = str_replace("&macr;", "??", $txt);
	$txt = str_replace("&deg;", "??", $txt);
	$txt = str_replace("&plusmn;", "??", $txt);
	$txt = str_replace("&sup2;", "??", $txt);
	$txt = str_replace("&sup3;", "??", $txt);
	$txt = str_replace("&acute;", "??", $txt);
	$txt = str_replace("&micro;", "??", $txt);
	$txt = str_replace("&para;", "??", $txt);
	$txt = str_replace("&middot;", "??", $txt);
	$txt = str_replace("&cedil;", "??", $txt);
	$txt = str_replace("&sup1;", "??", $txt);
	$txt = str_replace("&ordm;", "??", $txt);
	$txt = str_replace("&raquo;", "??", $txt);
	$txt = str_replace("&frac14;", "??", $txt);
	$txt = str_replace("&frac12;", "??", $txt);
	$txt = str_replace("&frac34;", "??", $txt);
	$txt = str_replace("&iquest;", "??", $txt);
	$txt = str_replace("&Agrave;", "??", $txt);
	$txt = str_replace("&Aacute;", "??", $txt);
	$txt = str_replace("&Acirc;", "??", $txt);
	$txt = str_replace("&Atilde;", "??", $txt);
	$txt = str_replace("&Auml;", "??", $txt);
	$txt = str_replace("&Aring;", "??", $txt);
	$txt = str_replace("&AElig;", "??", $txt);
	$txt = str_replace("&Ccedil;", "??", $txt);
	$txt = str_replace("&Egrave;", "??", $txt);
	$txt = str_replace("&Eacute;", "??", $txt);
	$txt = str_replace("&Ecirc;", "??", $txt);
	$txt = str_replace("&Euml;", "??", $txt);
	$txt = str_replace("&Igrave;", "??", $txt);
	$txt = str_replace("&Iacute;", "??", $txt);
	$txt = str_replace("&Icirc;", "??", $txt);
	$txt = str_replace("&Iuml;", "??", $txt);
	$txt = str_replace("&ETH;", "??", $txt);
	$txt = str_replace("&Ntilde;", "??", $txt);
	$txt = str_replace("&Ograve;", "??", $txt);
	$txt = str_replace("&Oacute;", "??", $txt);
	$txt = str_replace("&Ocirc;", "??", $txt);
	$txt = str_replace("&Otilde;", "??", $txt);
	$txt = str_replace("&Ouml;", "??", $txt);
	$txt = str_replace("&times;", "??", $txt);
	$txt = str_replace("&Oslash;", "??", $txt);
	$txt = str_replace("&Ugrave;", "??", $txt);
	$txt = str_replace("&Uacute;", "??", $txt);
	$txt = str_replace("&Ucirc;", "??", $txt);
	$txt = str_replace("&Uuml;", "??", $txt);
	$txt = str_replace("&Yacute;", "??", $txt);
	$txt = str_replace("&THORN;", "??", $txt);
	$txt = str_replace("&szlig;", "??", $txt);
	$txt = str_replace("&agrave;", "??", $txt);
	$txt = str_replace("&aacute;", "??", $txt);
	$txt = str_replace("&acirc;", "??", $txt);
	$txt = str_replace("&atilde;", "??", $txt);
	$txt = str_replace("&auml;", "??", $txt);
	$txt = str_replace("&aring;", "??", $txt);
	$txt = str_replace("&aelig;", "??", $txt);
	$txt = str_replace("&ccedil;", "??", $txt);
	$txt = str_replace("&egrave;", "??", $txt);
	$txt = str_replace("&eacute;", "??", $txt);
	$txt = str_replace("&ecirc;", "??", $txt);
	$txt = str_replace("&euml;", "??", $txt);
	$txt = str_replace("&igrave;", "??", $txt);
	$txt = str_replace("&iacute;", "??", $txt);
	$txt = str_replace("&icirc;", "??", $txt);
	$txt = str_replace("&iuml;", "??", $txt);
	$txt = str_replace("&eth;", "??", $txt);
	$txt = str_replace("&ntilde;", "??", $txt);
	$txt = str_replace("&ograve;", "??", $txt);
	$txt = str_replace("&oacute;", "??", $txt);
	$txt = str_replace("&ocirc;", "??", $txt);
	$txt = str_replace("&otilde;", "??", $txt);
	$txt = str_replace("&ouml;", "??", $txt);
	$txt = str_replace("&divide;", "??", $txt);
	$txt = str_replace("&oslash;", "??", $txt);
	$txt = str_replace("&ugrave;", "??", $txt);
	$txt = str_replace("&uacute;", "??", $txt);
	$txt = str_replace("&ucirc;", "??", $txt);
	$txt = str_replace("&uuml;", "??", $txt);
	$txt = str_replace("&yacute;", "??", $txt);
	$txt = str_replace("&thorn;", "??", $txt);
	$txt = str_replace("&yuml;", "??", $txt);
	$txt = str_replace("&euro;", "???", $txt);
	
	return $txt;
}