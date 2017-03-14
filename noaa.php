<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | NOAA</title>
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
			<div class="col-md-12" align="center">
			<h2>Rapports climatologiques de la station au format NOAA</h2>
			<p>Vous pouvez via les listes déroulantes ci-dessous, accéder aux rapports climatologiques mensuels et annuels bruts de la station au format "NOAA". Ce sont des fichiers texte très simple qui sont mis à jours toutes les nuits.</p>
			<h4>Rapports mensuels :</h4>
			<select name="Month" onchange="openNoaaFileMonth(value)">
				<?php
					if($dossier = opendir('NOAA/raw/month')) {
						while(false !== ($fichier = readdir($dossier))) {
							if($fichier != '.' && $fichier != '..' && $fichier != '.gitignore') {
								$properName = substr("$fichier", 5, 7);
								//echo '<option value="$fichier">$fichier</option>';
								echo '<option value="',$fichier,'">',$properName,'</option>';
								//echo '<li><a href="./NOAA/raw/' . $fichier . '">' . $fichier . '</a></li>';
							}
						}
					closedir($dossier);
					}
				?>
				<option selected value="#">- Selectionnez le mois -</option>'
			</select>
			<hr>
			<h4>Rapports annuels :</h4>
			<select name="Year" onchange="openNoaaFileYear(value)">
				<?php
					if($dossier = opendir('NOAA/raw/year')) {
						while(false !== ($fichier = readdir($dossier))) {
							if($fichier != '.' && $fichier != '..' && $fichier != '.gitignore') {
								$properName = substr("$fichier", 5, 4);
								echo '<option value="',$fichier,'">',$properName,'</option>';
							}
						}
					closedir($dossier);
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