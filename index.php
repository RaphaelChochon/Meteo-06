<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Accueil</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<?php if ($banniere_info_active == true) : ?>
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
			<div class="col-md-12" align="center">
				<p>Bienvenue sur le site de la station météo de <?php echo $station_name; ?>. Vous y touverez les données météos de la station en direct, mais aussi des tableaux récapitulatifs sur plusieurs périodes et des graphiques. <?php if ($presence_webcam == true){echo'Une webcam est également disponible sur cette station <a href="webcam.php">en cliquant ici</a>';};?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<h3 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h3>
				<?php if ($diff>$offline_time) : ?>
					<h4 class="offline_station">Station actuellement hors ligne depuis
						<?php echo $heures; ?> h et <?php echo $minutes; ?> min
					</h4>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètres</th>
						<th>Valeur actuelle</th>
						<th class="text-info">Mini du jour</th>
						<th class="text-danger">Maxi du jour</th>
						</tr>
					</thead>
					<tbody>
						<td>Température</td>
						<td><?php echo $temp; ?> °C</td>
						<td><?php echo $mintemp; ?> °C à <?php echo $mintemptime; ?></td>
						<td><?php echo $maxtemp; ?> °C à <?php echo $maxtemptime; ?></td>
					</tbody>
					<tbody>
						<td>Hygrométrie</td>
						<td><?php echo $hygro; ?> %</td>
						<td><?php echo $minhygro; ?> % à <?php echo $minhygrotime; ?></td>
						<td><?php echo $maxhygro; ?> % à <?php echo $maxhygrotime; ?></td>
					</tbody>
					<tbody>
						<td>Point de rosée</td>
						<td><?php echo $dewpoint; ?> °C</td>
						<td><?php echo $mindewpoint; ?> °C à <?php echo $mindewpointtime; ?></td>
						<td><?php echo $maxdewpoint; ?> °C à <?php echo $maxdewpointtime; ?></td>
					</tbody>
					<tbody>
						<td>Pression atmo.</td>
						<td><?php echo $barometer; ?> hPa</td>
						<td><?php echo $minbarometer; ?> hPa à <?php echo $minbarometertime; ?></td>
						<td><?php echo $maxbarometer; ?> hPa à <?php echo $maxbarometertime; ?></td>
					</tbody>
					<tbody>
						<td>Vitesse du vent (rafale / 10min et direction)</td>
						<td><?php echo $wind; ?> km/h (<?php echo $windgust; ?> km/h direction <?php echo $windgustdir; ?> °)</td>
						<td><?php echo $minwind; ?> km/h à <?php echo $minwindtime; ?></td>
						<td><?php echo $maxwind; ?> km/h à <?php echo $maxwindtime; ?> (<?php echo $maxwinddir; ?>°)</td>
					</tbody>
				<?php if ($presence_uv == true) : ?>
					<tbody>
						<td>Indice UV</td>
						<td><?php echo $uv; ?></td>
						<td><?php echo $minuv; ?> à <?php echo $minuvtime; ?></td>
						<td><?php echo $maxuv; ?> à <?php echo $maxuvtime; ?></td>
					</tbody>
				<?php endif; ?>
				<?php if ($presence_radiation == true) : ?>
					<tbody>
						<td>Rayonnement solaire</td>
						<td><?php echo $radiation; ?> W/m²</td>
						<td></td>
						<td><?php echo $maxradiation; ?> W/m² à <?php echo $maxradiationtime; ?></td>
					</tbody>
					<tbody>
						<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Quantité d'eau évaporée dans l'atmosphère, que ce soit par évaporation d'eau liquide (eau libre ou eau du sol) ou par transpiration de la biomasse. ENCYCL. : L'évaporation et la transpiration permettent à l'atmosphère de s'enrichir en eau, compensant ainsi les pertes dues aux précipitations. C'est pourquoi l'intensité de ces deux processus est fortement liée à la tension de vapeur de l'air. (Source : Larousse, dictionnaire du climat, G. Beltrando, L. Chémery ; 1995)">Évapo-transpiration (ET)</a></span></td>
						<td><?php echo $et; ?> mm/heure</td>
						<td>Cumul journée : <?php echo $etcumul; ?> mm</td>
						<td><?php echo $maxet; ?> mm/heure à <?php echo $maxettime; ?></td>
					</tbody>
				<?php endif; ?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9" align="center">
				<h4><b>Précipitations</b></h4>
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètres</th>
						<th>Valeur actuelle</th>
						<th class="text-info">Mini du jour</th>
						<th class="text-danger">Maxi du jour</th>
						</tr>
					</thead>
					<tbody>
						<td>Intensité pluie</td>
						<td><?php echo $rainrate; ?> mm/h</td>
						<td><?php echo $minrainRate; ?> mm/h à <?php echo $minrainRatetime; ?></td>
						<td><?php echo $maxrainRate; ?> mm/h à <?php echo $maxrainRatetime; ?></td>
					</tbody>
					<tbody>
						<td>Cumul de pluie aujourd'hui</td>
						<td colspan='3'><?php echo $cumul; ?> mm</td>
					</tbody>
					<tbody>
						<td>Cumul pluie / 24h glissantes</td>
						<td colspan='3'><?php echo $cumul24; ?> mm</td>
					</tbody>
					<tbody>
						<td>Cumul pluie / 48h glissantes</td>
						<td colspan='3'><?php echo $cumul48; ?> mm</td>
					</tbody>
					<tbody>
						<td>Cumul pluie / 72h glissantes</td>
						<td colspan='3'><?php echo $cumul72; ?> mm</td>
					</tbody>
				</table>
				<h4><b>Indices calculés</b></h4>
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètres</th>
						<th>Valeur actuelle</th>
						<th><span class="text-info">Mini</span>/<span class="text-danger">Maxi</span> du jour</th>
						</tr>
					</thead>
					<tbody>
						<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Le refroidissement éolien, parfois aussi appelé facteur vent dans le langage populaire, désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia).">Refroidissement éolien</a></span></td>
						<td><?php echo $windchill; ?> °C</td>
						<td><span class="text-info">Mini</span> <?php echo $minwindchill; ?> °C à <?php echo $minwindchilltime; ?></td>
					</tbody>
					<tbody>
						<td><span><a tabindex="0" data-placement="right" data-toggle="popover" data-trigger="focus" data-content="Indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia).">Indice de chaleur</a></span></td>
						<td><?php echo $heatindex; ?> °C</td>
						<td><span class="text-danger">Maxi</span> <?php echo $maxheatindex; ?> °C à <?php echo $maxheatindextime; ?></td>

					</tbody>
				</table>
			</div>
			<div class="col-md-3" align="center">
				<h4><b>Vigilances Météo-France</b></h4>
				<iframe id="vigi_mf" src="http://www.infoclimat.fr/infoclimat/vignette_vigi.php?d=<?php echo $vigi_dpt_mf; ?>"></iframe>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9" align="center">
				<h4><b>Radar de précipitations</b></h4>
				<p><img class="image" src="<?php echo $radar_url; ?>" border="2px solid black"><br>
				Source : <a href="<?php echo $radar_source_url; ?>" target="blank"><?php echo $radar_source; ?></a></p>
			</div>
			<div class="col-md-3" align="center">
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
