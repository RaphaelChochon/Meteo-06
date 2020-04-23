<?php require_once 'config/config.php';?>
<?php require_once 'sql/connect_pdo.php';?>
<?php require_once 'sql/import.php';?>
<?php require_once 'include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | SAMPLE</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="SAMPLE de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | SAMPLE" />
		<meta property="og:description" content="SAMPLE de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="SAMPLE de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | SAMPLE" />
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
		<script src="vendors/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="vendors/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="vendors/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="vendors/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- ######### Pour Highcharts ######### -->
		<!-- Highcharts BASE -->
		<script defer src="vendors/highcharts/js/highcharts-8.0.4.js"></script>
		<!-- Spécifique pour HeatMap -->
		<script defer src="vendors/highcharts/modules/heatmap-8.0.4.js"></script>
		<!-- Highcharts more et modules d'export -->
		<script defer src="vendors/highcharts/js/highcharts-more-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/export-data-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/annotations-8.0.4.js"></script>
		<!-- Highcharts Boost -->
		<script defer src="vendors/highcharts/modules/boost-8.0.4.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
		<!-- Font Awesome CSS for Tempus Dominus -->
		<link href="vendors/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
		<!-- Moment.js -->
		<script defer type="text/javascript" src="vendors/moment/moment.js"></script>
		<script defer type="text/javascript" src="vendors/moment/moment-locale-fr.js"></script>
		<!-- Tempus Dominus -->
		<script defer type="text/javascript" src="vendors/tempusdominus/tempusdominus-bootstrap-4.min.js"></script>
		<link rel="stylesheet" href="vendors/tempusdominus/tempusdominus-bootstrap-4.min.css" />
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
			<br>

			<!-- DEBUT DU CORPS DE PAGE -->
			<!-- Bannière infos -->
			<?php if ($banniere_info_active) : ?>
				<div class="alert alert-<?php echo $banniere_info_type; ?>">
					<h4 class="alert-heading"><?php echo $banniere_info_titre; ?></h4>
					<hr>
					<p class="mb-0"><?php echo $banniere_info_message; ?></p>
				</div>
			<?php endif; ?>

			<!-- On récupère les valeurs en BDD pour peupler les tableaux ci-après -->

			<div class="row">
				<div class="col-md-12">
					<!-- START module en ligne/Hors ligne -->
					<h3 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="textOnlineStation text-center"';?>>
							Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?>
					</h3>
					<?php if ($diff>$offline_time) : ?>
						<h4 class="textOfflineStation text-center">
							Station actuellement hors ligne depuis
							<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
						</h4>
					<?php endif; ?>
					<!-- FIN module en ligne/Hors ligne -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<div class="input-group date" id="dtPicker" data-target-input="nearest">
							<input type="text" class="form-control datetimepicker-input" data-target="#dtPicker"/>
							<div class="input-group-append" data-target="#dtPicker" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						$(function () {
							$('#dtPicker').datetimepicker({
								// viewMode: 'years',
								format: 'MM-YYYY',
								// format: 'DD-MM-YYYY HH:mm',
								locale: moment.locale('fr'),
								maxDate: moment(),
								useCurrent: true
							});
						});
					</script>
				</div>
			</div>
			<footer class="footer bg-light">
				<?php include 'footer.php';?>
			</footer>
		</div>
	</body>
</html>
