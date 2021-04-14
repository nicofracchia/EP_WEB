<div class='seccionPpal seccionHome' id='seccionHome'>
	<div class='EPHome'>
		<img src='images/ESFERAPUBLICA.png' alt='' />
	</div>
	<div class='contSlider' id='sliderHome'>
	
		<div class='contItemsSlider'>
			<?php
				$SQL_HOME = "SELECT * FROM ep_home ORDER BY numSlide,orden ASC";
				$RS_HOME = mysqli_query($conexion, $SQL_HOME);
				$slides = Array();
				while($s = mysqli_fetch_object($RS_HOME)){
					if(!isset($slides[$s->numSlide])){
						$slides[$s->numSlide] = Array();
					}
					if(!isset($slides[$s->numSlide]['txtPC']) or $slides[$s->numSlide]['txtPC'] == ''){
						$slides[$s->numSlide]['txtPC']  = "<div class='txtHome txtHomePC'>".$s->texto."</div>";
						$slides[$s->numSlide]['txtRESP']  = $s->texto.' ';
					}else{
						$slides[$s->numSlide]['txtPC'] .= "<div class='txtHome txtHomePC'>".$s->texto."</div>";
						$slides[$s->numSlide]['txtRESP'] .= $s->texto.' ';
					}
				}
				
				foreach($slides as $sl){
					echo "<div class='itemSlider'><div class='bottom'>";
					echo utf8_encode($sl['txtPC']);
					echo "<div class='txtHome txtHomeRESP'>".utf8_encode($sl['txtRESP'])."</div>";
					echo "</div></div>";
				}
			?>
		</div>
		
		
		
		
	</div>
	<div id='thumbsHome'></div>
	
	<div class='abajoSeccion'>
		<img src='images/iconos/abajoHome.png' alt='' onclick='oGeneralesPW.fnServicios();' />
	</div>
	<div class='lineaSubrayadoHome'></div>
</div>
