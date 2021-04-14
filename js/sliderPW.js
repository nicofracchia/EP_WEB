// sliderHome

var sliderPW = sliderPW || {
	IDThumbs: Array(),
	tiemposSlider: Array(),
	estadoSlider: Array(),
	circularSlider: Array(),
	automatico: Array(),
	slideActivado: Array(),
	cantItems: Array()
};

sliderPW.inicio = function(params){
	sliderPW.tiemposSlider[params.ID] = params.duracion || 5;
	sliderPW.estadoSlider[params.ID] = 0;
	sliderPW.circularSlider[params.ID] = params.circular || 0; // 0 = NO circular, 1 = circular
	sliderPW.automatico[params.ID] = params.automatico || 0; // 0 = NO automatico, X = tiempo de espera 
	sliderPW.IDThumbs[params.ID] = params.IDThumbs || 0; // 0 = NO tiene thumbs, "xxx" = ID elemento para thumbs
	
	sliderPW.acomodaCajas(params.ID);
	if(sliderPW.automatico[params.ID] != 0){ // timeout para transicion automatica
		setTimeout(function(){
			sliderPW.sliderNext(params.ID);
			}, 
			sliderPW.automatico[params.ID]*1100
		);
	}
	
	sliderPW.cantItems[params.ID] = $('#'+params.ID+' .contItemsSlider .itemSlider').length;
	
	var i = 0;
	var seleccionadoInicial = 'seleccionado';
	sliderPW.slideActivado[params.ID] = 0;
	$('#'+params.ID+' .contItemsSlider .itemSlider').each(function(){ // cargo un ID para cada item
		$(this).prop('id',params.ID+'_item_'+i);
		if(sliderPW.IDThumbs[params.ID] != 0){ // carga thumbs
			$('#'+sliderPW.IDThumbs[params.ID]).append("<div data-slide='"+params.ID+"' data-num='"+i+"' class='bolita "+seleccionadoInicial+"' id='"+sliderPW.IDThumbs[params.ID]+"_bolita_"+i+"' onclick='sliderPW.activaItemThumb(this);'></div>");
			seleccionadoInicial = '';
		}
		i++;
	});
}
sliderPW.acomodaCajas = function(ID){
	var anchoVentana = $('#'+ID).width();
	var cantItems = sliderPW.cantItems[ID];
	$('#'+ID+' .contItemsSlider .itemSlider').width(anchoVentana);
	$('#'+ID+' .contItemsSlider').width(cantItems*anchoVentana);
	$('#'+ID+' .contItemsSlider').css('transition',sliderPW.tiemposSlider[ID]+'s');
}
sliderPW.sliderNext = function(ID){
	if(sliderPW.estadoSlider[ID] == 0){
		sliderPW.estadoSlider[ID] = 1;// PARA CANCELAR EL SLIDE HASTA Q TERMINE
		var anchoVentana = $('#'+ID).width();
		var cantItems = sliderPW.cantItems[ID];
		var izquierda = parseFloat($('#'+ID+' .contItemsSlider').css('margin-left'));
		var maximoIzquierda = anchoVentana*(cantItems-1)*(-1);
		if(izquierda > maximoIzquierda){
			var izq = izquierda-anchoVentana;
			$('#'+ID+' .contItemsSlider').css('margin-left', izq+'px');
		}else{
			if(sliderPW.circularSlider[ID] == 1){
				sliderPW.circularNext(ID);
			}
		}
		
		// CARGO EN ARRAY EL ITEM ACTIVO SOLO SI TIENE THUMBS
		if(sliderPW.IDThumbs[ID] != 0){
			sliderPW.activaThumbs(ID);
		}
		
		if(sliderPW.automatico[ID] != 0){
			setTimeout(function(){
					sliderPW.estadoSlider[ID] = 0;
					sliderPW.sliderNext(ID);
				}, 
				sliderPW.automatico[ID]*1100
			);
		}else{
			setTimeout(function(){
					sliderPW.estadoSlider[ID] = 0;
				},
				sliderPW.tiemposSlider[ID]*1000
			); // HABILITO EL SLIDE
		}
	}
}
sliderPW.circularNext = function(ID){
	var izquierda = parseFloat($('#'+ID+' .contItemsSlider').css('margin-left'));
	var anchoVentana = $('#'+ID).width();
	
	// SACO TIEMPO DE TRANSICION (temporal)
	$('#'+ID+' .contItemsSlider').css('transition','0s');
	// CLONO PRIMER ITEM 
	var clonado = $('#'+ID+' .contItemsSlider .itemSlider').first().clone();
	// AGRANDO CONTENEDOR PARA QUE LA CAJA NUEVA QUEDE HORIZONTAL (temporal)
	var anchoNuevo = $('#'+ID+' .contItemsSlider').width() + $(clonado).width();
	$('#'+ID+' .contItemsSlider').width(anchoNuevo);
	// AGREGO AL FINAL DEL CONTENEDOR EL ITEM CLONADO
	$(clonado).addClass('ELIMINAR');
	$('#'+ID+' .contItemsSlider').append(clonado);
	setTimeout(function(){ // PARA QUE EJECUTE DESPUES DEL APPEND
		// DEVUELVO TIEMPO DE TRANSICION ORIGINAL
		$('#'+ID+' .contItemsSlider').css('transition',sliderPW.tiemposSlider[ID]+'s');
		// EJECUTA TRANSICION
		var izq = izquierda-anchoVentana;
		$('#'+ID+' .contItemsSlider').css('margin-left', izq+'px');
		setTimeout(function(){ // PARA QUE EJECUTE DESPUES DE TERMINAR TRANSICION
			// SACO TIEMPO DE TRANSICION (temporal)
			$('#'+ID+' .contItemsSlider').css('transition','0s');
			$('#'+ID+' .contItemsSlider').css('margin-left','0px');
			$('#'+ID+' .contItemsSlider .ELIMINAR').remove();
			setTimeout(function(){
				sliderPW.acomodaCajas(ID);
			},1);
		},sliderPW.tiemposSlider[ID]*1000);
	},1);
}
sliderPW.sliderPrev = function(ID){
	/** REVISAR Y TERMIANR!!!!! **/
	var anchoVentana = $('#'+ID).width();
	var cantItems =  sliderPW.cantItems[ID];
	var izquierda = parseFloat($('#'+ID+' .contItemsSlider').css('margin-left'));
	var maximoIzquierda = 0;
	if(izquierda < maximoIzquierda){
		var izq = izquierda+anchoVentana;
		$('#'+ID+' .contItemsSlider').css('margin-left', izq+'px');
	}else{
		// CIRCULAR!!!!
	}
}
sliderPW.activaThumbs = function(ID){
	sliderPW.slideActivado[ID]++;
	if(sliderPW.slideActivado[ID] >= sliderPW.cantItems[ID]){
		sliderPW.slideActivado[ID] = 0;
	}
	var i = sliderPW.slideActivado[ID];
	$('#'+sliderPW.IDThumbs[ID]+' .bolita').removeClass('seleccionado');
	$('#'+sliderPW.IDThumbs[ID]+'_bolita_'+sliderPW.slideActivado[ID]).addClass('seleccionado');
}
sliderPW.activaItemThumb = function(self){
	var ID = $(self).attr('data-slide');
	var num = $(self).attr('data-num');
	
	sliderPW.estadoSlider[ID] = 1;// PARA CANCELAR EL SLIDE HASTA Q TERMINE
	var izq = $('#'+ID).width()*num*-1;
	$('#'+ID+' .contItemsSlider').css('margin-left', izq+'px');
	
	// CARGO EN ARRAY EL ITEM ACTIVO SOLO SI TIENE THUMBS
	if(sliderPW.IDThumbs[ID] != 0){
		sliderPW.slideActivado[ID] = num-1;
		sliderPW.activaThumbs(ID);
	}
	
	setTimeout(function(){
			sliderPW.estadoSlider[ID] = 0;
		},
		sliderPW.tiemposSlider[ID]*1000
	); // HABILITO EL SLIDE
}