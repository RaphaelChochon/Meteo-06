<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | A propos</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="A propos de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | A propos" />
		<meta property="og:description" content="A propos de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php echo $url_site; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpg" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="A propos de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | A propos" />
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css" rel="stylesheet">
		<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
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
		<?php {
			include 'config/a-propos.php';
		};?>
		<!-- FIN DU CORPS DE PAGE -->
	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
