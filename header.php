<div class="row">
		<div class="hidden-xs hidden-sm col-md-3" align="center"><a href="./"><img src="img/logo_nm06.png"></a></div>
		<div class="hidden-md hidden-lg" align="center"><a href="./"><img src="img/logo_nm06_mobi.png"></a></div>
		<div class="col-md-9" align="center"><h1>
		<?php if ($presence_webcam == true){
			echo 'Webcam et station météo';
		}
		else {
			echo 'Station météo';
		};?>
		<br><?php echo $station_name; ?></h1></div>
		<!--<div class="hidden-xs hidden-sm col-md-3" align="center"><a href="webcam.php"><img id="webcam_header" src="http://nicemeteo.fr/webcam/viewcam.jpg"></a></div>-->
</div>
