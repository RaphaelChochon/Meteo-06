<?php include 'config.php';?>
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
		<div class="alert alert-dismissible alert-warning">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Attention !</h4>
			<p>Ce site est en travaux, les données présentées sont donc susceptibles d'être fausses</p>
		</div>


		<!-- DEBUT DU SCRIPT PHP -->
		<!-- Va permettre de récupérer les dernières valeurs en BDD -->
		<?php require("sql/req_tableau.php");?>
		<!-- FIN DU SCRIPT PHP -->


		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètre</th>
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
						<td>Pression atmosphérique</td>
						<td><?php echo $barometer; ?> hPa</td>
						<td><?php echo $minbarometer; ?> hPa à <?php echo $minbarometertime; ?></td>
						<td><?php echo $maxbarometer; ?> hPa à <?php echo $maxbarometertime; ?></td>
					</tbody>
					<tbody>
						<td>Vitesse du vent (rafale/10min et direction)</td>
						<td><?php echo $wind; ?> km/h (<?php echo $windgust; ?> km/h direction <?php echo $windgustdir; ?> °)</td>
						<td><?php echo $minwind; ?> km/h à <?php echo $minwindtime; ?></td>
						<td><?php echo $maxwind; ?> km/h à <?php echo $maxwindtime; ?> (<?php echo $maxwinddir; ?>°)</td>
					</tbody>
					<tbody>
						<td>Indice UV</td>
						<td><?php echo $uv; ?></td>
						<td><?php echo $minuv; ?> à <?php echo $minuvtime; ?></td>
						<td><?php echo $maxuv; ?> à <?php echo $maxuvtime; ?></td>
					</tbody>
					<tbody>
						<td>Intensité pluie actuelle</td>
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
						<th>Paramètre</th>
						<th>Valeur actuelle</th>
						<th><span class="text-info">Mini</span>/<span class="text-danger">Maxi</span> du jour</th>
						</tr>
					</thead>
					<tbody>
						<td><abbr title="Le refroidissement éolien, parfois aussi appelé facteur vent dans le langage populaire, désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia)"><a href="https://fr.wikipedia.org/wiki/Refroidissement_%C3%A9olien" target="blank">Refroidissement éolien</a></abbr></td>
						<td><?php echo $windchill; ?> °C</td>
						<td><span class="text-info">Mini</span> <?php echo $minwindchill; ?> °C à <?php echo $minwindchilltime; ?></td>
					</tbody>
					<tbody>
						<td><abbr title="Indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia)"><a href="https://fr.wikipedia.org/wiki/Indice_de_chaleur" target="blank">Indice de chaleur</a></abbr></td>
						<td><?php echo $heatindex; ?> °C</td>
						<td><span class="text-danger">Maxi</span> <?php echo $maxheatindex; ?> °C à <?php echo $maxheatindextime; ?></td>

					</tbody>
				</table>
			</div>
		</div>
	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
	<link href="vendors/custom/custom.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
