	<div class="row">
			<div class="hidden-xs hidden-sm col-md-3" align="center"><a href="./"><img src="img/logo.<?php echo $extension_logo; ?>" style="max-height: 130px;"></a></div>
			<div class="hidden-md hidden-lg" align="center"><a href="./"><img src="img/logo.<?php echo $extension_logo; ?>" style="max-height: 100px;"></a></div>
			<div class="col-md-9" align="center">
				<h1><?php if ($presence_webcam == true){
					echo 'Webcam et station météo';
				}
				else {
					echo 'Station météo';
				};?>
				<?php echo $station_name; ?></h1>
			</div>
	</div>