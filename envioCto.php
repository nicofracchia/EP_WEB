<?php
$para = "info@esferapublica.com.ar";
$para2 = "nico_fracchia_91@hotmail.com";
$asunto = 'Contacto desde la web Esfera Pública';
$mensaje = 'NOMBRE Y APELLIDO: '.utf8_decode($_REQUEST['nya']).' 
MAIL: '.utf8_decode($_REQUEST['mail']).'
TELÉFONO: '.utf8_decode($_REQUEST['telefono']).'
CONSULTA:
'.utf8_decode($_REQUEST['consulta']);
$cabeceras = "From: ".$_REQUEST['mail']."\r\n"."Reply-To: ".$_REQUEST['mail']."\r\n"."X-Mailer: PHP/".phpversion();
if(mail($para, $asunto, $mensaje, $cabeceras)){
	mail($para2, $asunto, $mensaje, $cabeceras);
	echo 1;
}else{
	echo 0;
}