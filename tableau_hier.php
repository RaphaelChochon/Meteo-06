<?php require_once 'config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Tableau hier</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
		<?php require("sql/req_tableau_hier.php");?>
		<!-- FIN DU SCRIPT PHP -->

		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Résumé de la journée d'hier (<?php echo $yesterday_human; ?> - <?php echo $stophier_human; ?></h3>
				<h4>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètre</th>
						<th class="text-info">Mini d'hier</th>
						<th class="text-danger">Maxi d'hier</th>
						</tr>
					</thead>
					<tbody>
						<td>Température</td>
						<td><?php echo $mintemphier; ?> °C le <?php echo $mintemptimehier; ?></td>
						<td><?php echo $maxtemphier; ?> °C le <?php echo $maxtemptimehier; ?></td>
					</tbody>
					<tbody>
						<td>Hygrométrie</td>
						<td><?php echo $minhygrohier; ?> % le <?php echo $minhygrotimehier; ?></td>
						<td><?php echo $maxhygrohier; ?> % le <?php echo $maxhygrotimehier; ?></td>
					</tbody>
					<tbody>
						<td>Point de rosée</td>
						<td><?php echo $mindewpointhier; ?> °C le <?php echo $mindewpointtimehier; ?></td>
						<td><?php echo $maxdewpointhier; ?> °C le <?php echo $maxdewpointtimehier; ?></td>
					</tbody>
					<tbody>
						<td>Pression atmo.</td>
						<td><?php echo $minbarometerhier; ?> hPa le <?php echo $minbarometertimehier; ?></td>
						<td><?php echo $maxbarometerhier; ?> hPa le <?php echo $maxbarometertimehier; ?></td>
					</tbody>
					<tbody>
						<td>Rafale de vent</td>
						<td></td>
						<td><?php echo $maxwindgusthier; ?> km/h le <?php echo $maxwindgusttimehier; ?></td>
					</tbody>
				<?php if ($presence_uv == true) : ?>
					<tbody>
						<td>Indice UV</td>
						<td></td>
						<td><?php echo $maxuvhier; ?> le <?php echo $maxuvtimehier; ?></td>
					</tbody>
				<?php endif; ?>
				<?php if ($presence_radiation == true) : ?>
					<tbody>
						<td>Rayonnement solaire</td>
						<td></td>
						<td><?php echo $maxradiationhier; ?> W/m² le <?php echo $maxradiationtimehier; ?></td>
					</tbody>
					<tbody>
						<td>Évapo-transpiration (ET)</td>
						<td>Cumul sur 7 jours : <?php echo $cumulethier; ?> mm</td>
						<td><?php echo $maxethier; ?> mm/<?php echo $archive_interval; ?>min le <?php echo $maxettimehier; ?></td>
					</tbody>
				<?php endif; ?>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Précipitations hier</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<table class="table table-striped table-bordered table-responsive table-hover">
					<tbody>
						<td>Intensité pluie</td>
						<td><?php echo $maxrainratehier; ?> mm/h le <?php echo $maxrainratetimehier; ?></td>
					</tbody>
					<tbody>
						<td>Cumul de pluie</td>
						<td><?php echo $cumulrainhier; ?> mm</td>
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
						<th><span class="text-info">Mini sur hier</span> / <span class="text-danger">Maxi sur hier</span></th>
						</tr>
					</thead>
					<tbody>
						<td><abbr title="Le refroidissement éolien, parfois aussi appelé facteur vent dans le langage populaire, désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia)"><a href="https://fr.wikipedia.org/wiki/Refroidissement_%C3%A9olien" target="blank">Refroidissement éolien</a></abbr></td>
						<td><span class="text-info">Mini</span> <?php echo $minwindchillhier; ?> °C le <?php echo $minwindchilltimehier; ?></td>
					</tbody>
					<tbody>
						<td><abbr title="Indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia)"><a href="https://fr.wikipedia.org/wiki/Indice_de_chaleur" target="blank">Indice de chaleur</a></abbr></td>
						<td><span class="text-danger">Maxi</span> <?php echo $maxheatindexhier; ?> °C le <?php echo $maxheatindextimehier; ?></td>

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
	<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>