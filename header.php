	<div class="row">
			<div class="hidden-xs hidden-sm col-md-3 divCenter"><a href="./"><img src="img/logo.<?php echo $extension_logo; ?>" alt="logo" style="max-height: 130px;"></a></div>
			<div class="hidden-md hidden-lg divCenter"><a href="./"><img src="img/logo.<?php echo $extension_logo; ?>" alt="logo" style="max-height: 100px;"></a></div>
			<div class="col-md-9 divCenter">
				<h1><?php if ($presence_webcam){
					echo 'Webcam et station météo';
				}
				else {
					echo 'Station météo';
				};?>
				<?php echo $station_name; if (!is_null($site_manager_link)) {echo $site_manager_link;}; ?></h1>
			</div>
	</div>
