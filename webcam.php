<?php require_once 'config.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Webcam</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	<div class="container">
		<header>
			<?php include 'header.php';?>
		</header>
	<br>
		<nav>
			<?php include 'nav.php';?>
		</nav>

		<!-- DEBUT DU CORPS DE PAGE -->
		<div class="row">
			<div class="col-md-12" align="center">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Webcam <?php echo $station_name; ?> - <?php echo $webcam_view_1; ?></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" align="center"><div class="thumbnail"><img id="webcam_last" src="<?php echo $webcam_url_1; ?>" alt="<?php echo $station_name; ?>"></div></div>
						</div>
						Cette image est rafraichie toutes les <?php echo $webcam_refresh_1; ?> minutes et contient les derniers relevés de la station météo positionnée quelques dizaine de mètres plus loin.
					</div>
				</div>
			<!--
				IF PRESENCE_SECOND_WEBCAM TRUE
			-->
			<?php if ($presence_second_webcam == true) : ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Webcam <?php echo $station_name; ?> - <?php echo $webcam_view_2; ?></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" align="center"><div class="thumbnail"><img id="webcam_last" src="<?php echo $webcam_url_2; ?>" alt="<?php echo $station_name; ?>"></div></div>
						</div>
						Cette image est rafraichie toutes les <?php echo $webcam_refresh_2; ?> minutes et contient les derniers relevés de la station météo positionnée quelques dizaine de mètres plus loin.
					</div>
				</div>
			<?php endif; ?>
			<!--
				END IF
			-->
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Timelapse de la veille</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" align="center">
								<video controls width="100%" preload="metadata" poster="<?php echo $timelapse_poster_url_1; ?>">
								<source src="<?php echo $timelapse_url_1; ?>" type="video/webm" />
							</video>
							</div>
						</div>
						Ce timelapse est réalisé tous les soirs (vers 22h30 environ), à partir des images de la webcam collectées toute la journée (soit une image toutes les <?php echo $webcam_refresh_1; ?> minutes).<br><br>
					<?php if ($presence_archive_timelapse == true) : ?>
						<div class="col-md-6 col-md-offset-3" align="center">
							<a href="<?php echo $timelapse_archive_url_1; ?>" target="blank" class="btn btn-default btn-lg btn-block">Accéder aux archives ici</a>
						</div>
					<?php endif; ?>
					</div>
				</div>

			<!--
			IF PRESENCE_SECOND_WEBCAM TRUE
			-->
			<?php if ($presence_second_timelapse == true) : ?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Timelapse de la veille</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" align="center">
								<video controls width="100%" preload="metadata" poster="<?php echo $timelapse_poster_url_2; ?>">
								<source src="<?php echo $timelapse_url_2; ?>" type="video/webm" />
							</video>
							</div>
						</div>
						Ce timelapse est réalisé tous les soirs (vers 22h30 environ), à partir des images de la webcam collectées toute la journée (soit une image toutes les <?php echo $webcam_refresh_2; ?> minutes).<br><br>
					<?php if ($presence_archive_timelapse == true) : ?>
						<div class="col-md-6 col-md-offset-3" align="center">
							<a href="<?php echo $timelapse_archive_url_2; ?>" target="blank" class="btn btn-default btn-lg btn-block">Accéder aux archives ici</a>
						</div>
					<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<!--
				END IF
			-->


			</div>
		</div>

	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	<link href="vendors/bootswatch-flatly/bootstrap.css" rel="stylesheet">
	<link href="vendors/custom/custom.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="vendors/bootstrap/js/bootstrap.js"></script>
	</body>
</html>
