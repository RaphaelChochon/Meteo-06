<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | NOAA</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Fichiers NOAA de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | NOAA" />
		<meta property="og:description" content="Fichiers NOAA de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Fichiers NOAA de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | NOAA" />
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

		<script type="text/javascript">
			function openNoaaFileMonth(file_name)
			{
				var url = "NOAA/raw/month/";
				url = url + file_name;
				window.location=url;
			}
			function openNoaaFileYear(file_name)
			{
				var url = "NOAA/raw/year/";
				url = url + file_name;
				window.location=url;
			}
		</script>
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
				<h2>Rapports climatologiques de la station au format NOAA</h2>
				<h4 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h4>
				<?php if ($diff>$offline_time) : ?>
					<h4 class="offline_station">Station actuellement hors ligne depuis
						<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
					</h4>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 divCenter">
			<p>Vous pouvez via les listes déroulantes ci-dessous, accéder aux rapports climatologiques mensuels et annuels bruts de la station au format "NOAA". Ce sont des fichiers texte très simple qui sont mis à jours toutes les nuits.</p>
			<h4>Rapports mensuels :</h4>
			<select name="Month" onchange="openNoaaFileMonth(value)">
				<?php
					$path = "NOAA/raw/month";
					$blacklist = array('.','..');
					// get everything except hidden files
					$files = preg_grep('/^([^.])/', scandir($path));
					// boucle
					foreach ($files as $file) {
						if (!in_array($file, $blacklist)) {
							$properName = substr("$file", 5, 7);
							echo '<option value="',$file,'">',$properName,'</option>';
						}
					}
				?>
				<option selected value="#">- Selectionnez le mois -</option>'
			</select>
			<hr>
			<h4>Rapports annuels :</h4>
			<select name="Year" onchange="openNoaaFileYear(value)">
				<?php
					$path = "NOAA/raw/year";
					$blacklist = array('.','..');
					// get everything except hidden files
					$files = preg_grep('/^([^.])/', scandir($path));
					// boucle
					foreach ($files as $file) {
						if (!in_array($file, $blacklist)) {
							$properName = substr("$file", 5, 4);
							echo '<option value="',$file,'">',$properName,'</option>';
						}
					}
				?>
				<option selected value="#">- Selectionnez l'année -</option>'
			</select>
				<br><br>
			</div>
		</div>
	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
	<link href="vendors/custom/custom.css" rel="stylesheet">
	<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>