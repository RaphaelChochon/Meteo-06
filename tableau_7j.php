<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Tableau 7 jours</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Résumé des 7 derniers jours sur la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="//<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Tableau 7 jours" />
		<meta property="og:description" content="Résumé des 7 derniers jours sur la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="//<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpg" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Résumé des 7 derniers jours sur la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Tableau 7 jours" />
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
		<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="vendors/bootstrap/js/tooltip.js"></script>
		<script src="vendors/bootstrap/js/popover.js"></script>
		<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
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

		<!-- DEBUT DU CORPS DE PAGE -->

		<!-- DEBUT DU SCRIPT PHP -->
		<!-- Va permettre de récupérer les dernières valeurs en BDD -->
		<?php require("sql/req_tableau_7j.php");?>
		<!-- FIN DU SCRIPT PHP -->

		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Résumé sur 7 jours glissants</h3>
				<h4 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h4>
				<?php if ($diff>$offline_time) : ?>
					<h4 class="offline_station">Station actuellement hors ligne depuis
						<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
					</h4>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètre</th>
						<th class="text-info">Mini/7 jours</th>
						<th class="text-danger">Maxi/7 jours</th>
						</tr>
					</thead>
					<tbody>
						<td>Température</td>
						<td><?php echo $mintemp7j; ?> °C le <?php echo $mintemptime7j; ?></td>
						<td><?php echo $maxtemp7j; ?> °C le <?php echo $maxtemptime7j; ?></td>
					</tbody>
					<tbody>
						<td>Hygrométrie</td>
						<td><?php echo $minhygro7j; ?> % le <?php echo $minhygrotime7j; ?></td>
						<td><?php echo $maxhygro7j; ?> % le <?php echo $maxhygrotime7j; ?></td>
					</tbody>
					<tbody>
						<td>Point de rosée</td>
						<td><?php echo $mindewpoint7j; ?> °C le <?php echo $mindewpointtime7j; ?></td>
						<td><?php echo $maxdewpoint7j; ?> °C le <?php echo $maxdewpointtime7j; ?></td>
					</tbody>
					<tbody>
						<td>Pression atmo.</td>
						<td><?php echo $minbarometer7j; ?> hPa le <?php echo $minbarometertime7j; ?></td>
						<td><?php echo $maxbarometer7j; ?> hPa le <?php echo $maxbarometertime7j; ?></td>
					</tbody>
					<tbody>
						<td>Rafale de vent</td>
						<td></td>
						<td><?php echo $maxwindgust7j; ?> km/h le <?php echo $maxwindgusttime7j; ?></td>
					</tbody>
				<?php if ($presence_uv == true) : ?>
					<tbody>
						<td>Indice UV</td>
						<td></td>
						<td><?php echo $maxuv7j; ?> le <?php echo $maxuvtime7j; ?></td>
					</tbody>
				<?php endif; ?>
				<?php if ($presence_radiation == true) : ?>
					<tbody>
						<td>Rayonnement solaire</td>
						<td></td>
						<td><?php echo $maxradiation7j; ?> W/m² le <?php echo $maxradiationtime7j; ?></td>
					</tbody>
					<tbody>
						<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Quantité d'eau évaporée dans l'atmosphère, que ce soit par évaporation d'eau liquide (eau libre ou eau du sol) ou par transpiration de la biomasse. ENCYCL. : L'évaporation et la transpiration permettent à l'atmosphère de s'enrichir en eau, compensant ainsi les pertes dues aux précipitations. C'est pourquoi l'intensité de ces deux processus est fortement liée à la tension de vapeur de l'air. (Source : Larousse, dictionnaire du climat, G. Beltrando, L. Chémery ; 1995)">Évapo-transpiration (ET)</a></span></td>
						<td>Cumul sur 7 jours : <?php echo $cumulet7j; ?> mm</td>
						<td><?php echo $maxet7j; ?> mm/heure le <?php echo $maxettime7j; ?></td>
					</tbody>
				<?php endif; ?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Précipitations</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<tbody>
						<td>Intensité pluie</td>
						<td><?php echo $maxrainrate7j; ?> mm/h le <?php echo $maxrainratetime7j; ?></td>
					</tbody>
					<tbody>
						<td>Cumul de pluie sur 7 jours</td>
						<td><?php echo $cumulrain7j; ?> mm</td>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Indices calculés</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètres</th>
						<th><span class="text-info">Mini sur 7j</span> / <span class="text-danger">Maxi sur 7j</span></th>
						</tr>
					</thead>
					<tbody>
						<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Le refroidissement éolien, parfois aussi appelé facteur vent dans le langage populaire, désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia).">Refroidissement éolien</a></span></td>
						<td><span class="text-info">Mini</span> <?php echo $minwindchill7j; ?> °C le <?php echo $minwindchilltime7j; ?></td>
					</tbody>
					<tbody>
						<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia).">Indice de chaleur</a></span></td>
						<td><span class="text-danger">Maxi</span> <?php echo $maxheatindex7j; ?> °C le <?php echo $maxheatindextime7j; ?></td>

					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12" align="center">

			</div>
		</div>



	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
