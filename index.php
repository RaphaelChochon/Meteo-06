<?php
	require_once __DIR__ . '/include/access_rights.php';
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/include/functions.php';
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Accueil</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="<?php echo $hashtag_meteo; ?> Relevés météo de la station <?php echo $station_name; ?> - Précipitations, température, pression, graphiques, webcam, pluie, orage"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Station météo <?php echo $short_station_name; ?> | Relevés en direct" />
		<meta property="og:description" content="<?php echo $hashtag_meteo; ?> Relevés météo de la station <?php echo $station_name; ?> - Précipitations, température, pression, graphiques, webcam, pluie, orage" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="<?php echo $hashtag_meteo; ?> Relevés météo de la station <?php echo $station_name; ?> - Précipitations, température, pression, graphiques, webcam, pluie, orage" />
		<meta name="twitter:title" content="Station météo <?php echo $short_station_name; ?> | Relevés en direct" />
		<meta name="twitter:site" content="<?php echo $tw_account_name; ?>" />
		<meta name="twitter:image" content="<?php echo $url_site; ?>/img/capture_site.jpg" />
		<meta name="twitter:creator" content="<?php echo $tw_account_name; ?>" />
		<!-- Fin des balises META SEO -->
		<?php include __DIR__ . '/config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- JQUERY JS -->
		<script defer src="content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="content/custom/custom.css?v=1.3" rel="stylesheet">
		<script defer src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<script>
			document.addEventListener('DOMContentLoaded', function () {
				$(function () {
					$('[data-toggle="tooltip"]').tooltip()
				})
			});
		</script>
	</head>
	<body>
		<div class="container">
			<header>
				<?php include __DIR__. '/header.php';?>
			</header>
			<br>
			<nav>
				<?php include __DIR__ . '/nav.php';?>
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
			<?php include __DIR__ . "/sql/req_tableau_jour.php";?>

			<!-- Texte de prez -->
			<div class="row d-none d-md-block">
				<div class="col-sm-12">
					<p class="text-justify">
						Bienvenue sur le site de la station météo de <?php echo $station_name; ?>. Vous y touverez les données météos de la station en direct, mais aussi des tableaux récapitulatifs sur plusieurs périodes et des graphiques. <?php if ($presence_webcam){echo'Une webcam est également disponible sur cette station <a href="webcam.php">en cliquant ici</a>.';};?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 mb-3">
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
			<!-- Tab now + min + max -->
			<div class="row">
				<div class="col-md-8">
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Actu.</th>
								<th class="textMin">Mini.</th>
								<th class="textMax">Maxi.</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									Tempé.
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Les <u>températures</u> minimales et maximales de la journée sont calculées d'après la méthode officielle OMM : entre 18h UTC la veille et 18h UTC le jour même pour la Tn (minimale), et entre 06h UTC le jour même et 06h UTC le lendemain pour la Tx (maximale)">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo $TempNow; ?>&#8239;°C
								</td>
								<td class="textMin">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($TnDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${TnDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $Tn; ?>&#8239;°C
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($TxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${TxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $Tx; ?>&#8239;°C
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
							</tr>
							<tr>
								<th>
									Humidité
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="L'<u>humidité relative</u> minimale et maximale de la journée est calculée entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo $HrNow; ?>&#8239;%
								</td>
								<td class="textMin">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($HrMinDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${HrMinDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $HrMin; ?>&#8239;%
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($HrMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${HrMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $HrMax; ?>&#8239;%
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
							</tr>
							<tr>
								<th>
									Point de rosée
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Le <u>point de rosée</u> minimal et maximal de la journée est calculé entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo $TdNow; ?>&#8239;°C
								</td>
								<td class="textMin">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($TdMinDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${TdMinDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $TdMin; ?>&#8239;°C
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($TdMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${TdMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $TdMax; ?>&#8239;°C
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
							</tr>
							<tr>
								<th>
									Tempé. ressentie
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>température ressentie</u> minimale et maximale de la journée est calculée entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo ''; ?>
								</td>
								<td class="textMin">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($windChillMinDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${windChillMinDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $windChillMin; ?>&#8239;
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
									
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La température ressentie minimale correspond au windchill, aussi appelé refroidissement éolien, ou parfois facteur vent dans le langage populaire : désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia). <b>Cette information n'a pas d'unité et ne correspond pas à une température observée</b>.">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($heatIndexMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${heatIndexMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $heatIndexMax; ?>&#8239;
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>

									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La température ressentie maximale correspond à l'humidex, c'est un indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia). <b>Cette information n'a pas d'unité et ne correspond pas à une température observée</b>.">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>
									Pression
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>pression atmosphérique</u> minimale et maximale de la journée est calculée entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo $PrNow; ?> hPa
								</td>
								<td class="textMin">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($PrMinDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${PrMinDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $PrMin; ?> hPa
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($PrMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${PrMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $PrMax; ?> hPa
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-4">
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Actu.</th>
								<th class="textMax">Maxi.</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									UV max
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="<u>Indice UV</u> maximale de la journée, entre 00h UTC et 23h59 UTC inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo $UvNow; ?>
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($UvMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${UvMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $UvMax; ?>
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
							</tr>
							<tr>
								<th>
									Ray. sol. max
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Le <u>rayonnement solaire</u> maximale de la journée, entre 00h UTC et 23h59 UTC inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textBold">
									<?php echo $RadNow; ?>&#8239;W/m²
								</td>
								<td class="textMax">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($RadMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${RadMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $RadMax; ?>&#8239;W/m²
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
							</tr>
							<tr>
								<th colspan="2">
									Cumul ET
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Le <u>cumul d'évapotranspiration</u> de la journée, entre 00h UTC et 23h59 UTC inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textSum">
									<?php echo $EtSum; ?>&#8239;mm
								</td>
							</tr>
							<tr>
								<th colspan="2">
									Cumul de pluie aujd.
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>cumul de précipitations</u> d'une journée, est calculée d'après la méthode officielle OMM, il s'agit donc de la <u>somme des précipitations</u> qui se sont produites entre 06h UTC le jour même et 06h UTC le lendemain">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textSum">
									<?php echo $RrAujd; ?>&#8239;mm
								</td>
							</tr>
							<tr>
								<th colspan="2">
									Cumul de pluie hier.
									<span class="float-right">
										<svg class="bi bi-info-circle" width="0.7em" height="0.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>cumul de précipitations</u> d'une journée, est calculée d'après la méthode officielle OMM, il s'agit donc de la <u>somme des précipitations</u> qui se sont produites entre 06h UTC le jour même et 06h UTC le lendemain">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textSum">
									<?php echo $RrHier; ?>&#8239;mm
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Row Vent + RR -->
			<div class="row">
				<div class="col-md-7">
					<!-- START tableau vent -->
					<h5 class="text-center">Vent</h5>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Paramètre</th>
								<th>Valeur</th>
								<th>Direction</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><b>Vent moyen instant.</b></th>
								<td class="textBold">
									<?php echo $windSpeedNow; ?>&#8239;km/h
								</td>
								<td class="textBold">
									<?php if (!is_null($windDirCardinalNow)) {
										echo $windDirCardinalNow.' ('.$windDirNow.'°)';
									}?>
								</td>
							</tr>
							<tr>
								<th>Vent moyen 10 min.</th>
								<td>
									<?php echo $windSpeedAvg10min; ?>&#8239;km/h
								</td>
								<td>
									<?php if (!is_null($windDirAvg10min)) {
										echo $windDirCardinal10min.' ('.$windDirAvg10min.'°)';
									}?>
								</td>
							</tr>
							<tr>
								<th>Vent moyen 1 heure</th>
								<td>
									<?php echo $windSpeedAvg1h; ?>&#8239;km/h
								</td>
								<td>
									<?php if (!is_null($windDirAvg1h)) {
										echo $windDirCardinal1h.' ('.$windDirAvg1h.'°)';
									}?>
								</td>
							</tr>
							<tr>
								<th><b>Rafale instant.</b></th>
								<td class="textBold">
									<?php echo $windGustNow; ?>&#8239;km/h
								</td>
								<td class="textBold">
									<?php if (!is_null($windGustDirCardinalNow)) {
										echo $windGustDirCardinalNow.' ('.$windGustDirNow.'°)';
									}?>
								</td>
							</tr>
							<tr>
								<th>Rafale max. 10 min.</th>
								<td>
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($tsWindGustMax10min)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',$tsWindGustMax10min).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $windGustMax10min; ?>&#8239;km/h
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td>
									<?php if (!is_null($windGustMaxDirCardinal10min)) {
										echo $windGustMaxDirCardinal10min.' ('.$windGustMaxDir10min.'°)';
									}?>
								</td>
							</tr>
							<tr>
								<th>Rafale max. 1 heure</th>
								<td>
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($tsWindGustMax1h)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',$tsWindGustMax1h).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $windGustMax1h; ?>&#8239;km/h
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td>
									<?php if (!is_null($windGustMaxDirCardinal1h)) {
										echo $windGustMaxDirCardinal1h.' ('.$windGustMaxDir1h.'°)';
									}?>
								</td>
							</tr>
							<tr>
								<th class="textBold">
									Rafale max du jour
								</th>
								<td class="textBold">
									<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($windGustMaxDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${windGustMaxDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
										<?php echo $windGustMax; ?>&#8239;km/h
										<sup>
											<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
												<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											</svg>
										</sup>
									</span>
								</td>
								<td class="textBold">
									<?php if (!is_null($windGustMaxDirCardinal)) {
										echo $windGustMaxDirCardinal.' ('.$windGustMaxDir.'°)';
									}?>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- END tableau vent -->
				</div>
				<div class="col-md-5">
					<!-- START tableau Précip -->
					<h5 class="text-center">Cumuls de précipitations</h5>
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#PrecipNow">Cumuls récents</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#PrecipPlus">+ de cumuls</a>
						</li>
					</ul>
					<div id="tabContentPrecip" class="tab-content">
						<div class="tab-pane fade active show" id="PrecipNow">
							<table class="table table-striped table-bordered table-hover table-sm">
								<thead>
									<tr>
										<th>Paramètre</th>
										<th><span class="textSum">Cumul</span> <span class="textMax">(intensité&nbsp;max)</span></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>Intensité instant.</th>
										<td class="textMax"><?php echo $RrateNow; ?>&#8239;mm/h</td>
									</tr>
									<tr>
										<th>Cumul 3h gliss.</th>
										<td>
											<span class="textSum">
												<?php echo $Rr3h; ?>&#8239;mm
											</span>
											<?php if ($RRateMax3h != 0) : ?>
												<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($RRateMax3hDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',$RRateMax3hDt).' loc.'; date_default_timezone_set("UTC");} ?>">
													<span class="textMax">
														(<?php echo $RRateMax3h; ?>&#8239;mm/h)
													</span>
													<sup>
														<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
															<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
															<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
														</svg>
													</sup>
												</span>
											<?php endif; ?>
										</td>
									</tr>
									<tr>
										<th>Cumul 6h-6h UTC</th>
										<td class="textSum">
											<span class="textSum">
												<?php echo $RrAujd; ?>&#8239;mm
											</span>
											<?php if ($RRateMaxAujd != 0) : ?>
												<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($RRateMaxAujdDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${RRateMaxAujdDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
													<span class="textMax">
														(<?php echo $RRateMaxAujd; ?>&#8239;mm/h)
													</span>
													<sup>
														<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
															<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
															<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
														</svg>
													</sup>
												</span>
											<?php endif; ?>
										</td>
									</tr>
									<tr>
										<th>Cumul veille 6h-6h&nbsp;UTC</th>
										<td class="textSum">
											<span class="textSum">
												<?php echo $RrHier; ?>&#8239;mm
											</span>
											<?php if ($RRateMaxHier != 0) : ?>
												<span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php if (!is_null($RRateMaxHierDt)) { date_default_timezone_set("Europe/Paris"); echo 'à&nbsp;'.date('H\hi',strtotime("${RRateMaxHierDt}Z")).' loc.'; date_default_timezone_set("UTC");} ?>">
													<span class="textMax">
														(<?php echo $RRateMaxHier; ?>&#8239;mm/h)
													</span>
													<sup>
														<svg class="bi bi-plus-circle" width="0.9em" height="0.9em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
															<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
															<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
														</svg>
													</sup>
												</span>
											<?php endif; ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade" id="PrecipPlus">
						<table class="table table-striped table-bordered table-hover table-sm">
								<thead>
									<tr>
										<th>Paramètre</th>
										<th>Cumul</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>Cumul depuis minuit loc.</th>
										<td><?php echo $RrTodayMidnight; ?>&#8239;mm</td>
									</tr>
									<tr>
										<th>Cumul sur 12h gliss.</th>
										<td><?php echo $Rr12h; ?>&#8239;mm</td>
									</tr>
									<tr>
										<th>Cumul sur 24h gliss.</th>
										<td><?php echo $Rr24h; ?>&#8239;mm</td>
									</tr>
									<tr>
										<th>Cumul sur 7j gliss.</th>
										<td><?php echo $Rr7j; ?>&#8239;mm</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END tableau Précip -->
					<!-- Boutton vers récap quotidien -->
					<div class="row bg-light py-2 mx-1 rounded">
						<div class="col-sm-12">
							<h5 class="text-center">
								Plus de données et graphiques pour :
							</h5>
						</div>
						<div class="col text-left" style="width:100%;">
							<a role="button" class="btn btn-sm btn-primary" href="./resume-quotidien.php?day=<?php date_default_timezone_set("Europe/Paris"); echo date('Y-m-d', strtotime($dateDay.'-1 day')); date_default_timezone_set("UTC"); ?>">
								<svg class="bi bi-caret-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M10 12.796L4.519 8 10 3.204v9.592zm-.659.753l-5.48-4.796a1 1 0 010-1.506l5.48-4.796A1 1 0 0111 3.204v9.592a1 1 0 01-1.659.753z" clip-rule="evenodd"/>
								</svg>
								Journée d'hier
							</a>
						</div>
						<div class="col text-right" style="width:100%;">
							<a role="button" class="btn btn-sm btn-primary" href="./resume-quotidien.php?day=<?php date_default_timezone_set("Europe/Paris"); echo date('Y-m-d'); date_default_timezone_set("UTC"); ?>">
								Aujourd'hui
								<svg class="bi bi-caret-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M6 12.796L11.481 8 6 3.204v9.592zm.659.753l5.48-4.796a1 1 0 000-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 001.659.753z" clip-rule="evenodd"/>
								</svg>
							</a>
						</div>
					</div>
				</div>
			</div>
			<hr class="my-3">
			<div class="row mt-3">
				<div class="col-lg-9">
					<!-- START radar de précip -->
					<h5 class="text-center">Radar de précipitations</h5>
					<div class="img-thumbnail text-center">
						<img class="img-fluid" src="<?php echo $radar_url; ?>" alt="Image radar des précipitations par <?php echo $radar_source; ?>">
					</div>
					<p class="source">
						Source : <a href="<?php echo $radar_source_url; ?>" target="blank"><?php echo $radar_source; ?></a>
					</p>
					<!-- END radar de précip -->
				</div>
				<div class="col-lg-3 text-center">
					<!-- START Webcam -->
					<?php if ($presence_gif) : ?>
					<div class="mb-3">
						<h5 class="text-center">Webcam</h5>
						<p class="text-center mb-0">
							Animation <?php echo $gif_time; ?> heures<br>
							<span style="font-size: 0.6em">Cliquez dessus pour accéder à la dernière image</span>
						</p>
						<a href="webcam.php">
							<div class="img-thumbnail text-center">
								<img class="img-fluid" src="<?php echo $gif_url; ?>" alt="Animation GIF de la webcam de <?php echo $station_name; ?>">
							</div>
						</a>
					</div>
					<?php endif; ?>
					<!-- START VIGI MF + RS -->
					<h5 class="text-center">Vigi. Météo-France</h5>
					<?php include __DIR__ . '/config/widget_vigi.php'; ?>
				</div>
			</div>

			<footer class="footer bg-light rounded">
				<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div> <!-- END du container -->
	</body>
</html>
