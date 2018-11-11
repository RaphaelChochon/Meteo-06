<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Webcam</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Webcam en live de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Webcam" />
		<meta property="og:description" content="Webcam en live de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Webcam en live de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Webcam" />
		<meta name="twitter:site" content="<?php echo $tw_account_name; ?>" />
		<meta name="twitter:image" content="<?php echo $url_site; ?>/img/capture_site.jpg" />
		<meta name="twitter:creator" content="<?php echo $tw_account_name; ?>" />
		<!-- Fin des balises META SEO -->
		<?php include 'config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link href="vendors/bootswatch-flatly/bootstrap.css" rel="stylesheet">
		<link href="vendors/custom/custom.css?v=1.1" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="vendors/bootstrap/js/bootstrap.js"></script>
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
			<div class="col-md-12 divCenter">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Webcam <?php echo $station_name; ?></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12 divCenter">
								<h4><?php echo $webcam_view_1; ?></h4>
								<div class="thumbnail"><img id="webcam_last" src="<?php echo $webcam_url_1; ?>" alt="<?php echo $station_name; ?>"></div>
								<p>Image rafraichie toutes les <?php echo $webcam_refresh_1; ?> minutes</p>
								<!--
									IF PRESENCE_SECOND_WEBCAM TRUE
								-->
								<?php if ($presence_second_webcam === "true") : ?>
								<hr>
								<h4><?php echo $webcam_view_2; ?></h4>
								<div class="thumbnail"><img id="webcam_last" src="<?php echo $webcam_url_2; ?>" alt="<?php echo $station_name; ?>"></div>
								<p>Image rafraichie toutes les <?php echo $webcam_refresh_2; ?> minutes</p>
								<?php endif; ?>
								<!--
									END IF
								-->
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Timelapse de la veille</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12 divCenter">
								<video controls width="100%" preload="metadata" poster="<?php echo $timelapse_poster_url_1; ?>">
								<source src="<?php echo $timelapse_url_1; ?>" type="video/webm" />
								</video>
							<p>Ce timelapse est réalisé tous les soirs (vers 22h30 environ), à partir des images de la webcam collectées toute la journée (soit une image toutes les <?php echo $webcam_refresh_1; ?> minutes).</p>
							</div>

						<?php if ($presence_archive_timelapse === "true") : ?>
							<div class="col-md-6 col-md-offset-3 divCenter">
								<a href="<?php echo $timelapse_archive_url_1; ?>" target="blank" class="btn btn-default btn-lg btn-block">Accéder aux archives ici</a>
								<br>
							</div>
						<?php endif; ?>
						</div>
					<!--
						IF PRESENCE_SECOND_TIMELAPSE TRUE
					-->
					<?php if ($presence_second_timelapse === "true") : ?>
					<div class="row">
							<div class="col-md-12 divCenter">
							<hr>
								<video controls width="100%" preload="metadata" poster="<?php echo $timelapse_poster_url_2; ?>">
								<source src="<?php echo $timelapse_url_2; ?>" type="video/webm" />
								</video>
							<p>Ce timelapse est réalisé tous les soirs (vers 22h30 environ), à partir des images de la webcam collectées toute la journée (soit une image toutes les <?php echo $webcam_refresh_2; ?> minutes).</p>
							</div>
							<?php if ($presence_archive_timelapse === "true") : ?>
								<div class="col-md-6 col-md-offset-3 divCenter">
									<a href="<?php echo $timelapse_archive_url_2; ?>" target="blank" class="btn btn-default btn-lg btn-block">Accéder aux archives ici</a>
								</div>
							<?php endif; ?>
					</div>
					<?php endif; ?>
					<!--
						END IF
					-->
					</div>
				</div>
			</div>
		</div>

	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
