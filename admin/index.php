<?php require_once '../config/config.php';?>
<?php require_once '../sql/connect_pdo.php';?>
<?php require_once '../sql/import.php';?>
<?php require_once '../include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | ADMIN</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php include '../config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- JQUERY JS -->
		<script src="../vendors/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="../vendors/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="../vendors/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="../vendors/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="../vendors/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
		<!-- Font Awesome CSS for Tempus Dominus -->
		<link href="../vendors/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<header>
				<?php include '../header.php';?>
			</header>
			<br>
			<nav>
				<?php include '../nav.php';?>
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
				<div class="col-md-12">
					<p class="text-justify">
						Cette page permet l'accès à différents outils de gestion de la station. Ces outils sont réservés au propriétaire et à l'équipe de gestion.
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 align-baseline">
					<!-- Extract data -->
					<div class="card d-inline-block border-primary card-admin m-1">
						<div class="card-header">
							Données station
						</div>
						<div class="card-body">
							<h4 class="card-title">
								Extraction des données
							</h4>
							<p class="card-text overflow-hidden card-text-admin">
								Extraire des données sous la forme d'un fichier CSV en créant une requête.
							</p>
							<a role="button" href="/admin/extract-data.php" class="btn btn-outline-primary btn-lg btn-block">Accès</a>
						</div>
					</div>
				</div>
			</div>
			<footer class="footer bg-light">
				<?php include '../footer.php';?>
			</footer>
		</div>
	</body>
</html>
