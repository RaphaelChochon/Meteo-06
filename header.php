<div class="row">
	<div class="col-md-3 d-flex justify-content-center align-items-center">
		<a href="/"><img src="/img/logo.<?php echo $extension_logo; ?>" class="rounded mx-auto d-block" alt="Logo Association Nice Météo 06"></a>
	</div>
	<div class="col-md-9 d-flex justify-content-center align-items-center">
		<h1 class="text-center">
			<?php if ($presence_webcam){
				echo 'Station météo et webcam';
			} else {
				echo 'Station météo';
			};?>
			<br>
			<?php 
				echo $station_name;
				if (!is_null($site_manager_link)) {
					echo '<br>';
					echo $site_manager_link;
				};
			?>
		</h1>
	</div>
</div>
