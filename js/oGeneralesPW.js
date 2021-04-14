var oGeneralesPW = oGeneralesPW || {
	anchoPantalla: $('html').width(),
	sliderServicios: 0,
	sliderNosotros: 0
};

/** *************************************** **/
/** FUNCIONES MENU ---> PONER TODAS JUTNAS! **/
/** *************************************** **/
oGeneralesPW.fnHome = function(){
	var altomenu = 40;
	$('html, body').animate({
		scrollTop: $('#seccionHome').offset().top
	}, 1000, function(){
		var dTop = $(document).scrollTop();
		$('html, body').animate({
			scrollTop: dTop - altomenu
		}, 500);
	});
}
oGeneralesPW.fnServicios = function(){
	if($('.menuPrincipalRESP').css('display') == 'none'){
		var altomenu = 80;
	}else{
		var altomenu = 248;
	}
	$('html, body').animate({
		scrollTop: $('#seccionServicios').offset().top
	}, 1000, function(){
		var dTop = $(document).scrollTop();
		$('html, body').animate({
			scrollTop: dTop - altomenu
		}, 500);
	});
}
oGeneralesPW.fnNosotros = function(){
	if($('.menuPrincipalRESP').css('display') == 'none'){
		var altomenu = 80;
	}else{
		var altomenu = 248;
	}
	$('html, body').animate({
		scrollTop: $('#seccionNosotros').offset().top
	}, 1000, function(){
		var dTop = $(document).scrollTop();
		$('html, body').animate({
			scrollTop: dTop - altomenu
		}, 500);
	});
}
oGeneralesPW.fnAppdate = function(){
	if($('.menuPrincipalRESP').css('display') == 'none'){
		var altomenu = 80;
	}else{
		var altomenu = 248;
	}
	$('html, body').animate({
		scrollTop: $('#seccionAppdate').offset().top
	}, 1000, function(){
		var dTop = $(document).scrollTop();
		$('html, body').animate({
			scrollTop: dTop - altomenu
		}, 500);
	});
}
oGeneralesPW.fnGaleria = function(){
	if($('.menuPrincipalRESP').css('display') == 'none'){
		var altomenu = 80;
	}else{
		var altomenu = 248;
	}
	$('html, body').animate({
		scrollTop: $('#seccionGaleria').offset().top
	}, 1000, function(){
		var dTop = $(document).scrollTop();
		$('html, body').animate({
			scrollTop: dTop - altomenu
		}, 500);
	});
}
oGeneralesPW.fnContacto = function(){
	if($('.menuPrincipalRESP').css('display') == 'none'){
		var altomenu = 80;
		$('html, body').animate({
			scrollTop: $('#seccionContacto').offset().top
		}, 1000, function(){
			var dTop = $(document).scrollTop();
			$('html, body').animate({
				scrollTop: dTop - altomenu
			}, 500);
		});
	}else{
		var altomenu = 248;
		$('html, body').animate({
			scrollTop: $('#seccionContacto').offset().top - 248
		}, 1000);

	}
}

/** SERVICIOS **/
oGeneralesPW.fnServiciosDescripcion = function(num){
	$('.seccionServicios .tablaServicios .descripcionServicios').show();
	if(oGeneralesPW.sliderServicios == 1){
		clearTimeout(oGeneralesPW.timeoutSercivios);
		if(oGeneralesPW.anchoPantalla > 980){
			var altomenu = 40;
			$('html, body').animate({
				scrollTop: $('.flechaServicios').offset().top - 50
			}, 1000);
		}
	}
	$('.contServicios').hide('slow');
	$('#servicio'+num).show('slow');
	if(num == 1){$('.seccionServicios .tablaServicios .descripcionServicios').css('background','rgba(181, 11, 37, 0.8)');}
	if(num == 2){$('.seccionServicios .tablaServicios .descripcionServicios').css('background','rgba(150, 8, 30, 0.8)');}
	if(num == 3){$('.seccionServicios .tablaServicios .descripcionServicios').css('background','rgba(93, 3, 15, 0.8)');}
	if(num == 4){$('.seccionServicios .tablaServicios .descripcionServicios').css('background','rgba(61, 0, 7, 0.8)');}
	if(oGeneralesPW.sliderServicios == 0){
		var proximoNum = parseInt(num) + 1;
		if(proximoNum == 5){proximoNum = 1;}
		oGeneralesPW.timeoutSercivios = setTimeout(function(){oGeneralesPW.fnServiciosDescripcion(proximoNum);}, 8000);
	}
}
oGeneralesPW.fnGiroFlechaResponsiveServicios = function(num){
	if(num == 1){
		$('#servicio1R').toggle('fast');
		$('#servicio2R').hide('fast');
		$('#servicio3R').hide('fast');
		$('#servicio4R').hide('fast');
		$('#servicio2RRotarFlechita').remove();
		$('#servicio3RRotarFlechita').remove();
		$('#servicio4RRotarFlechita').remove();
		if($('#servicio1RRotarFlechita').length == 0){
			$("<style id='servicio1RRotarFlechita'>.tablaServiciosRESP #cajaServicio1R:before{transform: rotate(90deg);}</style>").appendTo('head');
		}else{
			$('#servicio1RRotarFlechita').remove();
		}
	}
	if(num == 2){
		$('#servicio1R').hide('fast');
		$('#servicio2R').toggle('fast');
		$('#servicio3R').hide('fast');
		$('#servicio4R').hide('fast');
		$('#servicio1RRotarFlechita').remove();
		$('#servicio3RRotarFlechita').remove();
		$('#servicio4RRotarFlechita').remove();
		if($('#servicio2RRotarFlechita').length == 0){
			$("<style id='servicio2RRotarFlechita'>.tablaServiciosRESP #cajaServicio2R:before{transform: rotate(90deg);}</style>").appendTo('head');
		}else{
			$('#servicio2RRotarFlechita').remove();
		}
	}
	if(num == 3){
		$('#servicio1R').hide('fast');
		$('#servicio2R').hide('fast');
		$('#servicio3R').toggle('fast');
		$('#servicio4R').hide('fast');
		$('#servicio1RRotarFlechita').remove();
		$('#servicio2RRotarFlechita').remove();
		$('#servicio4RRotarFlechita').remove();
		if($('#servicio3RRotarFlechita').length == 0){
			$("<style id='servicio3RRotarFlechita'>.tablaServiciosRESP #cajaServicio3R:before{transform: rotate(90deg);}</style>").appendTo('head');
		}else{
			$('#servicio3RRotarFlechita').remove();
		}
	}
	if(num == 4){
		$('#servicio1R').hide('fast');
		$('#servicio2R').hide('fast');
		$('#servicio3R').hide('fast');
		$('#servicio4R').toggle('fast');
		$('#servicio1RRotarFlechita').remove();
		$('#servicio2RRotarFlechita').remove();
		$('#servicio3RRotarFlechita').remove();
		if($('#servicio4RRotarFlechita').length == 0){
			$("<style id='servicio4RRotarFlechita'>.tablaServiciosRESP #cajaServicio4R:before{transform: rotate(90deg);}</style>").appendTo('head');
		}else{
			$('#servicio4RRotarFlechita').remove();
		}
	}
	setTimeout(function(){
		if($('#servicio'+num+'RRotarFlechita').length != 0){
			$('html, body').animate({
				scrollTop: $("#servicio"+num+"R").offset().top - 190
			}, 1000)
		}
	}, 200);
}

/** NOSOTROS **/
oGeneralesPW.fnMuestraNosotros = function(num){
	if(oGeneralesPW.sliderNosotros == 1){
		clearTimeout(oGeneralesPW.timeoutNosotros);
	}
	$('#XQNosotros').fadeOut('fast');
	$('#XQDiferenciamos').fadeOut('fast');
	$('#quienesSomos').fadeOut('fast');
	if(num == 1){
		$('#XQNosotros').fadeIn('fast');
		$('.seccionNosotros .tablaNosotros .flechitaNosotrosSeleccionado').fadeIn('fast');
		$('.seccionNosotros .tablaNosotros .flechitaNosotrosSeleccionado').css('padding-left','15%');
	}
	if(num == 2){
		$('#XQDiferenciamos').fadeIn('fast');
		$('.seccionNosotros .tablaNosotros .flechitaNosotrosSeleccionado').fadeIn('fast');
		$('.seccionNosotros .tablaNosotros .flechitaNosotrosSeleccionado').css('padding-left','50%');
	}
	if(num == 3){
		$('#quienesSomos').fadeIn('fast');
		$('.seccionNosotros .tablaNosotros .flechitaNosotrosSeleccionado').fadeIn('fast');
		$('.seccionNosotros .tablaNosotros .flechitaNosotrosSeleccionado').css('padding-left','85%');}
	
	/*
	$('html, body').animate({
		scrollTop: $('#contenedorNosotros').offset().top - 80
	}, 1000);
	*/
	if(oGeneralesPW.sliderNosotros == 0){
		var proximoNum = parseInt(num) + 1;
		if(proximoNum == 4){proximoNum = 1;}
		oGeneralesPW.timeoutNosotros = setTimeout(function(){oGeneralesPW.fnMuestraNosotros(proximoNum);}, 8000);
	}
}
oGeneralesPW.fnGiroFlechaResponsiveNosotros = function(num){
	if(num == 1){
		$('#XQNosotrosR').toggle('fast');
		$('#XQDiferenciamosR').hide('fast');
		$('#quienesSomosR').hide('fast');
		$('#quienesSomosRRotarFlechita').remove();
		$('#XQDiferenciamosRRotarFlechita').remove();
		if($('#XQNosotrosRRotarFlechita').length == 0){
			$("<style id='XQNosotrosRRotarFlechita'>#flechaServicios_1qs{transform: rotate(90deg);}</style>").appendTo('head');
			setTimeout(function(){
				$('html, body').animate({
					scrollTop: $("#XQNosotrosR").offset().top - 190
				}, 800);
			}, 300);
		}else{
			$('#XQNosotrosRRotarFlechita').remove();
		}
	}
	if(num == 2){
		$('#XQNosotrosR').hide('fast');
		$('#XQDiferenciamosR').toggle('fast');
		$('#quienesSomosR').hide('fast');
		$('#quienesSomosRRotarFlechita').remove();
		$('#XQNosotrosRRotarFlechita').remove();
		if($('#XQDiferenciamosRRotarFlechita').length == 0){
			$("<style id='XQDiferenciamosRRotarFlechita'>#flechaServicios_2qd{transform: rotate(90deg);}</style>").appendTo('head');
			setTimeout(function(){
				$('html, body').animate({
					scrollTop: $("#XQDiferenciamosR").offset().top - 190
				}, 800);
			}, 300);
		}else{
			$('#XQDiferenciamosRRotarFlechita').remove();
		}
	}
	if(num == 3){
		$('#XQNosotrosR').hide('fast');
		$('#XQDiferenciamosR').hide('fast');
		$('#quienesSomosR').toggle('fast');
		$('#XQNosotrosRRotarFlechita').remove();
		$('#XQDiferenciamosRRotarFlechita').remove();
		if($('#quienesSomosRRotarFlechita').length == 0){
			$("<style id='quienesSomosRRotarFlechita'>#flechaServicios_3b{transform: rotate(90deg);}</style>").appendTo('head');
			setTimeout(function(){
				$('html, body').animate({
					scrollTop: $("#quienesSomosR").offset().top - 190
				}, 800);
			}, 300);
		}else{
			$('#quienesSomosRRotarFlechita').remove();
		}
	}
}

/** CONTACTO **/
oGeneralesPW.fnEnviaContacto = function(){
	if($('#nya').val() == ''){
		alert('El campo Nombre y Apellido es obligatorio!');
		$('#nya').focus();
		return false;
	}
	if($('#telefono').val() == ''){
		alert('El campo Teléfono es obligatorio!');
		$('#telefono').focus();
		return false;
	}
	if($('#mail').val() == ''){
		alert('El campo Mail es obligatorio!');
		$('#mail').focus();
		return false;
	}
	var validacionMail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if(!validacionMail.test($('#mail').val())){
		alert('Debe ingresar un mail válido!');
		$('#mail').focus();
		return false;
	}
	if($('#consulta').val() == ''){
		alert('El campo Consulta es obligatorio!');
		$('#consulta').focus();
		return false;
	}
	$.ajax({
		url: "envioCto.php", 
		method: 'post',
		data: {
			nya: $('#nya').val(),
			telefono: $('#telefono').val(),
			mail: $('#mail').val(),
			consulta: $('#consulta').val()
		},
		success: function(data){
			if(data == 1){
				alert('Su consulta se envió correctamente. A la brevedad nos contactaremos con usted!');
				$('#nya').val('');
				$('#telefono').val('');
				$('#mail').val('');
				$('#consulta').val('');
			}else{
				alert('No se pudo enviar la consulta. Por favor intente nuevamente más tarde.');
			}
		},
		error: function(a,b,c,d){
			console.log('ERROR EN ENVIO CONTACTO!');
		}
	});
}

/** ******************************************** **/
/** SOLO PARA EJEMPLO, NO SE TIENE QUE USAR ASI! **/
/** ******************************************** **/
oGeneralesPW.cargarMasNoticias = function(){
	var i = 0,
		cantidadParaMostrar = 10;
	$('.contNoticia').each(function(){
		if(i < cantidadParaMostrar){
			$(this).clone().appendTo('.tablaNoticias');
			i++;
		}
	});
}

/** PARALLAX **/
function parallax() {
	setpos("#separador1");
	setpos("#separador2");
	setpos("#separador3");
}
function setpos(element, offsetE){
	
	if(oGeneralesPW.anchoPantalla > 980){
		var factor = 200;
	}else{
		var factor = 2;
	}
	
	var factor = factor;    
    var offset = $(element).offset();
    var w = $(window);
    
    var posx = (offset.left - w.scrollLeft()) / factor;
    var posy = (offset.top - w.scrollTop()) / factor * -1;
	    
    $(element).css('background-position', '50% '+posy+'px');
}

/** ACOMODAR TAMAÑOS RESPONSIVE **/
oGeneralesPW.fnAcomodaServicios = function(){
	var alto = ($('#seccionServicios').height()) / 4;
	$('.opcionServiciosRESP').height(alto);
}
oGeneralesPW.fnAcomodaNosotros = function(){
	if($('.menuPrincipalRESP').css('display') != 'none'){
		var alto = $('#seccionNosotros').height() / 3;
		$('.cajaSeccionNosotros').height(alto);
	}
}
oGeneralesPW.fnAcomodaContacto = function(){
	var ventana = window.innerHeight;
	var pie = $('#pie').height();
	var altura = ventana-pie-87;
	$('#seccionContacto').css('min-height',altura+'px');
}

$(document).ready(function(){
	$(window).on('orientationchange', function(event) {
		location.reload();
    });
	oGeneralesPW.fnAcomodaContacto();
	// SLIDER HOME
	sliderPW.inicio({
		ID:	'sliderHome',
		IDThumbs: 'thumbsHome',
		duracion: 1,
		circular: 1,
		automatico: 8
		//automatico: 0
	});
	parallax();
	// EMPIEZA SLIDE SERVICIOS
	oGeneralesPW.fnServiciosDescripcion(1);
	oGeneralesPW.fnMuestraNosotros(1);
	// VALIDACION TELEFONO
	$('#telefono').keydown(function(e){
		var c = e.keyCode;
		if(c < 48 || (c > 57 && c < 96) || (c > 105 && c != 109 && c != 189)){
			 e.preventDefault();
			return false;
		}
	});
	
	/** RESPONSIVE **/
	// ACOMODA HEIGHT TABLA SERVICIOS
	oGeneralesPW.fnAcomodaServicios();
	oGeneralesPW.fnAcomodaNosotros();
});
$(document).scroll(function(){
	parallax();
	var scDoc = $(document).scrollTop();
	var scDocBottom = $(document).scrollTop() + $(window).height();
	
	// MENU PRINCIPAL - ESTILOS
	if(oGeneralesPW.anchoPantalla > 980){
		var scMenuPrincipal = $('.menuPrincipal').offset().top;
		var hMenuPrincipal = $('.menuPrincipal').height() + 23;
		if(scDoc >= scMenuPrincipal){
			$('.menuPrincipal').css({'height':'40px','padding':'0 10%'});
			$('.menuPrincipal .contenedorItemsMenuPpal .contLinksMenuPpal').css({'padding':'0','padding-top':'7px'});
			$('.menuPrincipal .contenedorItemsMenuPpal .contLinksMenuPpal a').css('font-size','15px');
			$('.menuPrincipal .contenedorItemsMenuPpal .logo img').css({'height':'40px','margin-top':'-3px'});
		}
		if(scDoc == 0){
			$('.menuPrincipal').css({'height':'139px','padding':'15px 10%'});
			$('.menuPrincipal .contenedorItemsMenuPpal .contLinksMenuPpal').css({'padding-bottom':'45px','padding-top':'0px'});
			$('.menuPrincipal .contenedorItemsMenuPpal .contLinksMenuPpal a').css('font-size','20px');
			$('.menuPrincipal .contenedorItemsMenuPpal .logo img').css({'height':'138px','margin-top':'0px'});
		}
	}
	
	// MENU PRINCIPAL - LINKS
	var scServPpal = $('#seccionServicios').offset().top - 200;
	var scNosPpal = $('#seccionNosotros').offset().top - 200;
	var scConPpal = $('#seccionContacto').offset().top - 200;
	if(scDoc <= ($('body').height() / 2)){ // HOME
		$('.menuPrincipal a').removeClass('seleccionado');
		$('#menuPpalInicio').addClass('seleccionado');
	}
	if(scDoc >= scServPpal){ // SERVICIOS
		$('.menuPrincipal a').removeClass('seleccionado');
		$('#menuPpalServicios').addClass('seleccionado');
	}
	if(scDoc >= scNosPpal){ // NOSOTROS
		$('.menuPrincipal a').removeClass('seleccionado');
		$('#menuPpalNosotros').addClass('seleccionado');
	}
	if(scDoc >= scConPpal){ // CONTACTO
		$('.menuPrincipal a').removeClass('seleccionado');
		$('#menuPpalContacto').addClass('seleccionado');
	}
});