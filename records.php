<?php
	require_once __DIR__ . '/include/access_rights.php';
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/include/functions.php';

	if (isset($_GET['more'])) {
		$limitRecords = 30;
	} else {
		$limitRecords = 10;
	}
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Records</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="<?php echo $hashtag_meteo; ?> Records de la station <?php echo $station_name; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Records" />
		<meta property="og:description" content="<?php echo $hashtag_meteo; ?> Records de la station <?php echo $station_name; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="<?php echo $hashtag_meteo; ?> Records de la station <?php echo $station_name; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Records" />
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
		<script src="content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="content/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<script>
			$(function () {
				$('[data-toggle="popover"]').popover()
			})
		</script>
	</head>
	<body>
	<body>
		<div class="container">
			<header>
				<?php include __DIR__ . '/header.php';?>
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
			<?php require("sql/req_records.php");?>

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
				<div class="col-md-12 text-center">
					<h5>Records de la station depuis son installation le <?php echo $date_install_station; ?></h5>
				</div>
			</div>

			<p class="text-center mt-3">
				Cliquez sur la date du jour dans chaque tableau pour accéder au détail.
			</p>

			<hr class="my-4">

			<?php if (!isset($_GET['more'])) :?>
				<div class="row mb-3">
					<div class="col text-center">
						<a role="button" class="btn btn-primary" href="./records.php?more">
							<svg class="bi bi-plus-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
								<path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
								<path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
							</svg>
							&nbsp;
							Charger plus de records ?
						</a>
					</div>
				</div>
			<?php else : ?>
				<div class="row mb-3">
					<div class="col text-center">
						<a role="button" class="btn btn-primary" href="./records.php">
							Charger moins de records ?
						</a>
					</div>
				</div>
			<?php endif ; ?>

			<hr class="my-4">

			<div class="row">
				<div class="col-md-4">
					<h4 class="text-center">Tnn</h4>
					<p class="text-center">
						Tn quotidiennes min.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Tn</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabTn as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMin">'.$value['TnMin'].'&#8239;°C</td>';
											echo '<td>'.$value['TnMinDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-4">
					<h4 class="text-center">Txx</h4>
					<p class="text-center">
						Tx quotidiennes max.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Tx</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabTx as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['TxMax'].'&#8239;°C</td>';
											echo '<td>'.$value['TxMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-4">
					<h4 class="text-center">Amplitude</h4>
					<p class="text-center">
						Amplitude quotidienne max. des températures
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Amplitude</th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabTempRange as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['TempRange'].'&#8239;°C</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				
			</div>

			<div class="row justify-content-md-center">
				<div class="col col-md-auto">
					<h4 class="text-center">RR max.</h4>
					<p class="text-center">
						Cumul de précipitations quotidien max.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>RR</th>
									<th>Int. max. (<span class="badge badge-primary">UTC</span>)</th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabRr as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></th>';
											echo '<td class="textSum">'.$value['RrMax'].'&#8239;mm</td>';
											echo '<td>'.$value['RRateMax'].'&#8239;mm/h ('.$value['RRateMaxDt'].')</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col col-md-auto">
					<h4 class="text-center">Int. pluie max.</h4>
					<p class="text-center">
						Intensité de pluie max.
					</p>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Int. max.</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabRrate as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></th>';
											echo '<td class="textMax">'.$value['RRateMax'].'&#8239;mm/h</td>';
											echo '<td>'.$value['RRateMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- IF rayonnement -->
				<?php if ($presence_uv) : ?>
				<div class="col col-md-auto">
					<h4 class="text-center">ET max.</h4>
					<p class="text-center">
						Cumul d'évapotranspiration (ET) quotidien
					</p>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>ET</th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabEtSum as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textSum">'.$value['EtSum'].'&#8239;mm</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h4 class="text-center">Humidité min</h4>
					<p class="text-center">
						Humidité quotidienne min.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Hr</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabHrMin as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMin">'.$value['HrMin'].'&#8239;%</td>';
											echo '<td>'.$value['HrMinDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-4">
					<h4 class="text-center">Humidité max</h4>
					<p class="text-center">
						Humidité quotidienne max.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Hr</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabHrMax as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['HrMax'].'&#8239;%</td>';
											echo '<td>'.$value['HrMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-4">
					<h4 class="text-center">Rafale max</h4>
					<p class="text-center">
						Rafale quotidienne max.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Rafale</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabWindGust as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['windGust'].'&#8239;km/h</td>';
											echo '<td>'.$value['windGustDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


			<div class="row justify-content-md-center">
				<div class="col col-md-auto">
					<h4 class="text-center">Td min.</h4>
					<p class="text-center">
						Point de rosée quotidien min.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Td</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabTdMin as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMin">'.$value['TdMin'].'&#8239;°C</td>';
											echo '<td>'.$value['TdMinDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col col-md-auto">
					<h4 class="text-center">Td max.</h4>
					<p class="text-center">
						Point de rosée quotidien max.
					</p>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Td</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabTdMax as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['TdMax'].'&#8239;°C</td>';
											echo '<td>'.$value['TdMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- IF rayonnement -->
				<?php if ($presence_radiation) : ?>
				<div class="col col-md-auto">
					<h4 class="text-center">Ray. sol. max.</h4>
					<p class="text-center">
						Rayonnement solaire quotidien max.
					</p>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Ray. sol</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabRadMax as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['RadMax'].'&#8239;W/m²</td>';
											echo '<td>'.$value['RadMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<div class="row justify-content-md-center">
				<div class="col col-md-auto">
					<h4 class="text-center">Pression min.</h4>
					<p class="text-center">
						Pression atmo. quotidienne min.
					</p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Pression</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabPrMin as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMin">'.$value['PrMin'].'&#8239;hPa</td>';
											echo '<td>'.$value['PrMinDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col col-md-auto">
					<h4 class="text-center">Pression max.</h4>
					<p class="text-center">
						Pression atmo. quotidienne max.
					</p>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>Pression</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabPrMax as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['PrMax'].'&#8239;hPa</td>';
											echo '<td>'.$value['PrMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- IF rayonnement -->
				<?php if ($presence_uv) : ?>
				<div class="col col-md-auto">
					<h4 class="text-center">UV max.</h4>
					<p class="text-center">
						Indice UV quotidien max.
					</p>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-sm">
							<thead>
								<tr>
									<th>Jour</th>
									<th>UV</th>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
									date_default_timezone_set('UTC');
									foreach ($tabUvMax as $ts => $value) {
										echo '<tr>';
											$dt = date('d/m/Y', $ts);
											echo '<th>';
											echo '<span><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$dt.'</a></span></th>';
											echo '<td class="textMax">'.$value['UvMax'].'&#8239;</td>';
											echo '<td>'.$value['UvMaxDt'].'</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<footer class="footer bg-light rounded">
					<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div>
	</body>
</html>
