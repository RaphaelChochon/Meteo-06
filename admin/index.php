<?php
	require_once __DIR__ . '/../include/access_rights.php';
	if (!$auth->isLoggedIn()) {
		// Redirection
		header('Location: /admin/login.php'); 
		exit();
	}
	if (defined('RESET_PWD')) {
		// Redirection
		header('Location: https://auth.meteo06.fr/reset-pwd.php');
		exit();
	}

	require_once __DIR__ . '/../config/config.php';
	require_once __DIR__ . '/../sql/connect_pdo.php';
	require_once __DIR__ . '/../sql/import.php';
	require_once __DIR__ . '/../include/functions.php';
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | ADMIN</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php include __DIR__ . '/../config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- JQUERY JS -->
		<script src="../content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="../content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="../content/custom/custom.css?v=1.3" rel="stylesheet">
		<script defer src="../content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="../content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- Font Awesome CSS -->
		<link href="../content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<header>
				<?php include __DIR__ . '/../header.php';?>
			</header>
			<br>
			<nav>
				<?php include __DIR__ . '/../nav.php';?>
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

			<div class="row">
				<div class="col-md-12">
					<!-- START module en ligne/Hors ligne -->
					<h3 <?php if ($diff>$offline_time){echo'class="textOfflineStation text-center"';}echo'class="textOnlineStation text-center"';?>>
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
						Cette page permet l'accès à différents outils de gestion de la station. Ces outils sont réservés au propriétaire de la station et à l'équipe de gestion.
					</p>
				</div>
			</div>
			<!-- Vérif des droits d'accès -->
			<?php if (defined('USER_IS_ADMIN') || defined('USER_IS_TEAM') || defined('USER_IS_PROPRIO')) :?>
				<div class="row mb-3">
					<div class="col-md-12">
						<!-- Données intérieures -->
						<div class="card d-inline-block border-primary card-admin m-1">
							<div class="card-header">
								Données console
							</div>
							<div class="card-body">
								<h4 class="card-title">
									Sondes intérieures
								</h4>
								<p class="card-text overflow-hidden card-text-admin">
									Affichage de la température et l'humidité de la console.
								</p>
								<a role="button" href="/admin/sondes-interieures.php" class="btn btn-outline-primary btn-lg btn-block">Accès</a>
							</div>
						</div>
						<?php if ($presence_iss_radio) : ?>
							<!-- Réception -->
							<div class="card d-inline-block border-primary card-admin m-1">
								<div class="card-header">
									Statistiques de réception et tension
								</div>
								<div class="card-body">
									<h4 class="card-title">
										Statistiques techniques
									</h4>
									<p class="card-text overflow-hidden card-text-admin">
										Affichage des statistiques de réception entre la console et l'ISS et de la tension des piles de la console (pour les VP2).
									</p>
									<a role="button" href="/admin/stats-reception.php" class="btn btn-outline-primary btn-lg btn-block">Accès</a>
								</div>
							</div>
						<?php endif; ?>
						<!-- Extract data -->
						<div class="card d-inline-block border-primary card-admin m-1">
							<div class="card-header">
								Données station
							</div>
							<div class="card-body">
								<h4 class="card-title">
									Extraction de données
								</h4>
								<p class="card-text overflow-hidden card-text-admin">
									Extraire des données sous la forme d'un fichier CSV en créant une requête.
								</p>
								<a role="button" href="/admin/extract-data.php" class="btn btn-outline-primary btn-lg btn-block">Accès</a>
							</div>
						</div>
						<?php if (defined('USER_IS_ADMIN') || defined('USER_IS_TEAM')) :?>
							<!-- Gestion bannière -->
							<div class="card d-inline-block border-primary card-admin m-1">
								<div class="card-header">
									Bannière/message
								</div>
								<div class="card-body">
									<h4 class="card-title">
										Gestion de la bannière
									</h4>
									<p class="card-text overflow-hidden card-text-admin">
										Ajouter et/ou modifier le texte de la bannière (qui s'affiche sur toutes les pages) pour annoncer une panne par exemple.
									</p>
									<a role="button" href="/admin/gestion-banniere.php" class="btn btn-outline-primary btn-lg btn-block">Accès</a>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php else :?>
				<div class="row">
					<div class="col-md-6 mx-auto">
						<div class="alert alert-danger">
							<h4 class="alert-heading mt-1">Au mauvais endroit...</h4>
							<p class="text-justify mb-0">
								<strong>Oops !</strong> Il semblerait que vous n'ayez pas les droits suffisants pour accéder à cette page. Vous n'êtes peut-être pas sur le site de votre station ?
							</p>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<footer class="footer bg-light rounded">
				<?php include __DIR__ . '/../footer.php';?>
			</footer>
		</div>
	</body>
</html>
