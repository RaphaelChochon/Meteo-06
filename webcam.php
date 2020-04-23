<?php require_once 'config/config.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Webcam</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Webcam en live de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Webcam" />
		<meta property="og:description" content="Webcam en live de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
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
		<!-- JQUERY JS -->
		<script defer src="content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="content/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>
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
		<br>
		<div class="row">
			<div class="col-md-12">
				<!-- Webcam(s) -->
				<div class="card text-center">
					<div class="card-header">
						<h3>Webcam <?php echo $station_name; ?></h3>
					</div>
					<div class="card-body">
						<h5 class="card-title"><?php echo $webcam_view_1; ?></h5>
						<div class="img-thumbnail text-center">
							<img class="img-fluid" src="<?php echo $webcam_url_1; ?>" alt="Dernière image de la webcam de <?php echo $station_name; ?>">
						</div>
						<!-- IF deuxième webcam -->
						<?php if ($presence_second_webcam) : ?>
						<hr>
						<h5 class="card-title"><?php echo $webcam_view_2; ?></h5>
						<div class="img-thumbnail text-center">
							<img class="img-fluid" src="<?php echo $webcam_url_2; ?>" alt="Dernière image de la webcam de <?php echo $station_name; ?>">
						</div>
						<?php endif; ?>
						<!-- FIN deuxième webcam -->
					</div>
					<div class="card-footer text-muted">
						Image rafraichie toutes les <?php echo $webcam_refresh_1; ?> minutes
					</div>
				</div>
				<br>
				<!-- Timelapse(s) -->
				<div class="card text-center">
					<div class="card-header">
						<h3>Timelapse de la veille</h3>
					</div>
					<div class="card-body">
						<h5 class="card-title"><?php echo $webcam_view_1; ?></h5>
						<div class="img-thumbnail text-center">
							<video controls width="100%" preload="metadata" poster="<?php echo $timelapse_poster_url_1; ?>">
								<source src="<?php echo $timelapse_url_1; ?>" type="video/webm" />
							</video>
						</div>
						<br>
						<a role="button" class="btn btn-primary" href="<?php echo $timelapse_archive_url_1; ?>" target="_blank">Consulter les archives</a>
						<!-- IF deuxième timelapse -->
						<?php if ($presence_second_timelapse) : ?>
						<hr>
						<h5 class="card-title"><?php echo $webcam_view_2; ?></h5>
						<div class="img-thumbnail text-center">
							<video controls width="100%" preload="metadata" poster="<?php echo $timelapse_poster_url_2; ?>">
								<source src="<?php echo $timelapse_url_2; ?>" type="video/webm" />
							</video>
						</div>
						<br>
						<a role="button" class="btn btn-primary" href="<?php echo $timelapse_archive_url_2; ?>" target="_blank">Consulter les archives</a>
						<?php endif; ?>
						<!-- FIN deuxième timelapse -->
					</div>
					<div class="card-footer text-muted">
						Ce timelapse est réalisé tous les soirs (vers 22h30 environ), à partir des images de la webcam collectées toute la journée.
					</div>
				</div>
			</div>
		</div>
		<br>

	<footer class="footer bg-light">
		<?php include 'footer.php';?>
	</footer>
	</div>
	</body>
</html>
