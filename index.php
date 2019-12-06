<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Accueil</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Suivez les relevés météos en live de la station <?php echo $station_name; ?> sur ce site. Précipitations, températures, pression, pluie, graphiques, archives et webcam <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Accueil" />
		<meta property="og:description" content="Suivez les relevés météos en live de la station <?php echo $station_name; ?> sur ce site. Précipitations, températures, pression, pluie, graphiques, archives et webcam <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Suivez les relevés météos en live de la station <?php echo $station_name; ?> sur ce site. Précipitations, températures, pression, pluie, graphiques, archives et webcam <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Accueil" />
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
		<link href="vendors/custom/custom.css?v=1.1" rel="stylesheet">
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
		<?php if ($banniere_info_active) : ?>
			<div class="alert alert-dismissible alert-<?php echo $banniere_info_type; ?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4><?php echo $banniere_info_titre; ?></h4>
				<p><?php echo $banniere_info_message; ?></p>
			</div>
		<?php endif; ?>

		<!-- DEBUT DU SCRIPT PHP -->
		<!-- Va permettre de récupérer les dernières valeurs en BDD -->
		<?php require("sql/req_tableau_jour.php");?>
		<!-- FIN DU SCRIPT PHP -->


		<div class="row">
			<div class="col-md-12 divCenter">
				<p>Bienvenue sur le site de la station météo de <?php echo $station_name; ?>. Vous y touverez les données météos de la station en direct, mais aussi des tableaux récapitulatifs sur plusieurs périodes et des graphiques. <?php if ($presence_webcam){echo'Une webcam est également disponible sur cette station <a href="webcam.php">en cliquant ici</a>';};?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 divCenter">
				<h3 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h3>
				<?php if ($diff>$offline_time) : ?>
					<h4 class="offline_station">Station actuellement hors ligne depuis
						<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
					</h4>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 divCenter">
				<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
					<thead>
						<tr>
							<th>Paramètres</th>
							<th>Valeur actuelle</th>
							<th class="text-info">Mini du jour</th>
							<th class="text-danger">Maxi du jour</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Température</td>
							<td><?php echo $temp; ?> °C</td>
							<td><?php echo $mintemp; ?> °C à <?php echo $mintemptime; ?></td>
							<td><?php echo $maxtemp; ?> °C à <?php echo $maxtemptime; ?></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Hygrométrie</td>
							<td><?php echo $hygro; ?> %</td>
							<td><?php echo $minhygro; ?> % à <?php echo $minhygrotime; ?></td>
							<td><?php echo $maxhygro; ?> % à <?php echo $maxhygrotime; ?></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Point de rosée</td>
							<td><?php echo $dewpoint; ?> °C</td>
							<td><?php echo $mindewpoint; ?> °C à <?php echo $mindewpointtime; ?></td>
							<td><?php echo $maxdewpoint; ?> °C à <?php echo $maxdewpointtime; ?></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Pression atmo.</td>
							<td><?php echo $barometer; ?> hPa</td>
							<td><?php echo $minbarometer; ?> hPa à <?php echo $minbarometertime; ?></td>
							<td><?php echo $maxbarometer; ?> hPa à <?php echo $maxbarometertime; ?></td>
						</tr>
					</tbody>
				</table>
				<?php if ($presence_uv || $presence_radiation) : ?>
				<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
					<thead>
						<tr>
							<th>Paramètres</th>
							<th>Valeur actuelle</th>
							<th>Moyennes</th>
							<th><span class="text-danger">Maxi du jour</span></th>
						</tr>
					</thead>
				<?php if ($presence_uv) : ?>
					<tbody>
						<tr>
							<td>Indice UV</td>
							<td><?php echo $uv; ?></td>
							<td>10 min. : <?php echo $avg_UV_10; ?></td>
							<td><?php echo $maxuv; ?> à <?php echo $maxuvtime; ?></td>
						</tr>
					</tbody>
				<?php endif; ?>
				<?php if ($presence_radiation) : ?>
					<tbody>
						<tr>
							<td>Rayonnement solaire</td>
							<td><?php echo $radiation; ?> W/m²</td>
							<td>10 min. : <?php echo $avg_radiation_10; ?> W/m²</td>
							<td><?php echo $maxradiation; ?> W/m² à <?php echo $maxradiationtime; ?></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Quantité d'eau évaporée dans l'atmosphère, que ce soit par évaporation d'eau liquide (eau libre ou eau du sol) ou par transpiration de la biomasse. ENCYCL. : L'évaporation et la transpiration permettent à l'atmosphère de s'enrichir en eau, compensant ainsi les pertes dues aux précipitations. C'est pourquoi l'intensité de ces deux processus est fortement liée à la tension de vapeur de l'air. (Source : Larousse, dictionnaire du climat, G. Beltrando, L. Chémery ; 1995)">Évapo-transpiration (ET)</a></span></td>
							<td><?php echo $et; ?> mm/heure</td>
							<td>Cumul journée : <?php echo $etcumul; ?> mm</td>
							<td><?php echo $maxet; ?> mm/heure à <?php echo $maxettime; ?></td>
						</tr>
					</tbody>
				<?php endif; ?>
				</table>
				<?php endif; ?>
				<h4><b>Vent</b></h4>
				<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
					<thead>
						<tr>
							<th>Paramètres</th>
							<th>Valeur</th>
							<th>Direction</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><b>Vent moyen instant.</b></td>
							<td><b><?php echo $wind; ?> km/h</b></td>
							<td><b><?php echo $cardinalWindDir; ?> (<?php echo $windDir; ?>°)</b></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Moyen sur 10 min.</td>
							<td><?php echo $avg_wind_10; ?> km/h</td>
							<td><?php echo $cardinalDir10; ?> (<?php echo $avg_windDir_10; ?>°)</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Moyen sur 1 heure</td>
							<td><?php echo $avg_wind_1h; ?> km/h</td>
							<td><?php echo $cardinalDir1h; ?> (<?php echo $avg_windDir_1h; ?>°)</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td><b>Rafales instant.</b></td>
							<td><b><?php echo $windgust; ?> km/h</b></td>
							<td><b><?php echo $cardinalWindGustDir; ?> (<?php echo $windGustDir; ?>°)</b></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Rafales sur 10 min.</td>
							<td><?php echo $avg_windGust_10; ?> km/h</td>
							<td><?php echo $cardinalGustDir10; ?> (<?php echo $avg_windGustDir_10; ?>°)</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td>Rafales sur 1 heure</td>
							<td><?php echo $avg_windGust_1h; ?> km/h</td>
							<td><?php echo $cardinalGustDir1h; ?> (<?php echo $avg_windGustDir_1h; ?>°)</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td><b>Rafale max du jour</b></td>
							<td><b><?php echo $maxwind; ?> km/h à <?php echo $maxwindtime; ?></b></td>
							<td><b><?php echo $cardinalMaxWindDir; ?> (<?php echo $maxwinddir; ?>°)</b></td>
						</tr>
					</tbody>
				</table>


			</div>
		</div>
		<div class="row">
			<div class="col-md-9 divCenter">
				<h4><b>Précipitations</b></h4>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#precip-recentes" data-toggle="tab" aria-expanded="true">Cumuls récents</a></li>
					<li class=""><a href="#precip-old" data-toggle="tab" aria-expanded="false">12 h et +</a></li>
				</ul>
				<!-- ONGLETS -->
				<div id="myTabContent" class="tab-content">
					<!-- Onglet précip récentes -->
					<div class="tab-pane fade active in" id="precip-recentes">
						<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
							<thead>
								<tr>
									<th>Paramètres</th>
									<th>Valeur actuelle</th>
									<th class="text-danger">Maxi du jour</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Intensité pluie</td>
									<td><?php echo $rainrate; ?> mm/h</td>
									<td><?php echo $maxrainRate; ?> mm/h à <?php echo $maxrainRatetime; ?></td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul de pluie aujourd'hui</td>
									<td colspan='2'><?php echo $cumul; ?> mm</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul de pluie 1h</td>
									<td colspan='2'><?php echo $cumul1; ?> mm</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul de pluie 3h</td>
									<td colspan='2'><?php echo $cumul3; ?> mm</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul de pluie 6h</td>
									<td colspan='2'><?php echo $cumul6; ?> mm</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- Onglet précips old -->
					<div class="tab-pane fade" id="precip-old">
						<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
							<thead>
								<tr>
									<th>Paramètres</th>
									<th>Valeur</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Cumul pluie / 12h glissantes</td>
									<td><?php echo $cumul12; ?> mm</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul pluie / 24h glissantes</td>
									<td><?php echo $cumul24; ?> mm</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul pluie / 48h glissantes</td>
									<td><?php echo $cumul48; ?> mm</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td>Cumul pluie / 72h glissantes</td>
									<td><?php echo $cumul72; ?> mm</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<h4><b>Indices calculés</b></h4>
				<table class="table table-striped table-bordered table-responsive table-hover tabLeft">
					<thead>
						<tr>
							<th>Paramètres</th>
							<th>Valeur actuelle</th>
							<th><span class="text-info">Mini</span>/<span class="text-danger">Maxi</span> du jour</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Aussi appelé windchill, le refroidissement éolien, parfois aussi appelé facteur vent dans le langage populaire, désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia). Cette donnée n'a pas d'unité et ne correspond pas à une température observée.">Refroidissement éolien</a></span></td>
							<td><?php echo $windchill; ?></td>
							<td><span class="text-info">Mini</span> <?php echo $minwindchill; ?> à <?php echo $minwindchilltime; ?></td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Aussi appelé humidex, c'est un indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia). Cette donnée n'a pas d'unité et ne correspond pas à une température observée.">Indice de chaleur</a></span></td>
							<td><?php echo $heatindex; ?></td>
							<td><span class="text-danger">Maxi</span> <?php echo $maxheatindex; ?> à <?php echo $maxheatindextime; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-3 divCenter">
				<?php {
					include 'config/widget_vigi.php';
				};?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9 divCenter">
				<h4><b>Radar de précipitations</b></h4>
				<p><img class="image imgRadar" src="<?php echo $radar_url; ?>" alt="Image radar de pluie"><br>
				Source : <a href="<?php echo $radar_source_url; ?>" target="blank"><?php echo $radar_source; ?></a></p>
			</div>
			<div class="col-md-3 divCenter">
				<h4><b>Réseaux sociaux</b></h4>
				<?php include 'config/res_sociaux.php';?>
			</div>
		</div>
	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
