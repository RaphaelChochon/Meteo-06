<?php require_once 'config/config.php';?>
<?php require_once 'sql/connect_pdo.php';?>
<?php require_once 'sql/import.php';?>
<?php require_once 'include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Tableau hier</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Résumé de la journée d'hier sur la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Tableau hier" />
		<meta property="og:description" content="Résumé de la journée d'hier sur la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Résumé de la journée d'hier sur la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Tableau hier" />
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
		<script src="vendors/bootstrap/js/popper-1.16.0.min.js"></script>
		<script src="vendors/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<script>
			$(function () {
				$('[data-toggle="popover"]').popover()
			})
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
			<?php require("sql/req_tableau_hier.php");?>

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Résumé de la journée d'hier (<?php echo $yesterday_human; ?> - <?php echo $stophier_human; ?>)</h3>
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
				<div class="col-md-12">
					<!-- <table class="table table-striped table-bordered table-responsive table-hover"> -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Paramètre</th>
								<th class="text-info">Mini d'hier</th>
								<th class="text-danger">Maxi d'hier</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Température</td>
								<td><?php echo $mintemphier; ?> °C le <?php echo $mintemptimehier; ?></td>
								<td><?php echo $maxtemphier; ?> °C le <?php echo $maxtemptimehier; ?></td>
							</tr>
							<tr>
								<td>Hygrométrie</td>
								<td><?php echo $minhygrohier; ?> % le <?php echo $minhygrotimehier; ?></td>
								<td><?php echo $maxhygrohier; ?> % le <?php echo $maxhygrotimehier; ?></td>
							</tr>
							<tr>
								<td>Point de rosée</td>
								<td><?php echo $mindewpointhier; ?> °C le <?php echo $mindewpointtimehier; ?></td>
								<td><?php echo $maxdewpointhier; ?> °C le <?php echo $maxdewpointtimehier; ?></td>
							</tr>
							<tr>
								<td>Pression atmo.</td>
								<td><?php echo $minbarometerhier; ?> hPa le <?php echo $minbarometertimehier; ?></td>
								<td><?php echo $maxbarometerhier; ?> hPa le <?php echo $maxbarometertimehier; ?></td>
							</tr>
							<tr>
								<td>Rafale de vent</td>
								<td></td>
								<td><?php echo $maxwindgusthier; ?> km/h le <?php echo $maxwindgusttimehier; ?></td>
							</tr>
					<?php if ($presence_uv) : ?>
							<tr>
								<td>Indice UV</td>
								<td></td>
								<td><?php echo $maxuvhier; ?> le <?php echo $maxuvtimehier; ?></td>
							</tr>
					<?php endif; ?>
					<?php if ($presence_radiation) : ?>
							<tr>
								<td>Rayonnement solaire</td>
								<td></td>
								<td><?php echo $maxradiationhier; ?> W/m² le <?php echo $maxradiationtimehier; ?></td>
							</tr>
							<tr>
								<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Quantité d'eau évaporée dans l'atmosphère, que ce soit par évaporation d'eau liquide (eau libre ou eau du sol) ou par transpiration de la biomasse. ENCYCL. : L'évaporation et la transpiration permettent à l'atmosphère de s'enrichir en eau, compensant ainsi les pertes dues aux précipitations. C'est pourquoi l'intensité de ces deux processus est fortement liée à la tension de vapeur de l'air. (Source : Larousse, dictionnaire du climat, G. Beltrando, L. Chémery ; 1995)">Évapo-transpiration (ET)</a></span></td>
								<td>Cumul journée d'hier : <?php echo $cumulethier; ?> mm</td>
								<td><?php echo $maxethier; ?> mm/heure le <?php echo $maxettimehier; ?></td>
							</tr>
					<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 divCenter">
					<h3>Précipitations hier</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 divCenter">
					<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
						<tbody>
							<tr>
								<td>Intensité pluie</td>
								<td><?php echo $maxrainratehier; ?> mm/h le <?php echo $maxrainratetimehier; ?></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Cumul de pluie</td>
								<td><?php echo $cumulrainhier; ?> mm</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 divCenter">
					<h3>Indices calculés</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 divCenter">
					<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
						<thead>
							<tr>
								<th>Paramètres</th>
								<th><span class="text-info">Mini sur hier</span> / <span class="text-danger">Maxi sur hier</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Aussi appelé windchill, le refroidissement éolien, parfois aussi appelé facteur vent dans le langage populaire, désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia). Cette donnée n'a pas d'unité et ne correspond pas à une température observée.">Refroidissement éolien</a></span></td>
								<td><span class="text-info">Mini</span> <?php echo $minwindchillhier; ?> le <?php echo $minwindchilltimehier; ?></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Aussi appelé humidex, c'est un indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia). Cette donnée n'a pas d'unité et ne correspond pas à une température observée.">Indice de chaleur</a></span></td>
								<td><span class="text-danger">Maxi</span> <?php echo $maxheatindexhier; ?> le <?php echo $maxheatindextimehier; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<footer class="footer bg-light">
				<?php include 'footer.php';?>
			</footer>
		</div>
	</body>
</html>
