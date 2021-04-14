<?php
	/** ***** PREVENT CACHE ***** **/
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	/** ***** ************* ***** **/

	/* ***** CONEXION ***** */
	$hostBD = 'localhost';
	$usuarioBD = 'prowebsolutions';
	$passBD = '123456321asd';
	$baseBD = 'appdate';
	$conexion = mysqli_connect($hostBD,$usuarioBD,$passBD,$baseBD);
?>

<!DOCTYPE html>
<html> 
	<head>
		<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="icon" type="image/png" href="images/logo.png">
		<title> Esfera Pública </title>
	</head>
	<body>
		<?php
			include_once('menuPrincipal.php');
			include_once('home.php');
			include_once('separador1.php');
			include_once('servicios.php');
			include_once('separador2.php');
			include_once('nosotros.php');
			include_once('separador3.php');
			include_once('contacto.php');
			include_once('pie.php');
		?>
		<link rel="stylesheet" type="text/css" href="css/estilos.css" />
		<link rel="stylesheet" type="text/css" href="css/estilosSlider.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src='js/oGeneralesPW.js'></script>
		<script src='js/sliderPW.js'></script>
	</body>
</html>