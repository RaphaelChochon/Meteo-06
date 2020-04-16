<?php require_once 'config/config.php';?>
<?php require_once 'sql/connect_pdo.php';?>
<?php require_once 'sql/import.php';?>
<?php require_once 'include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Résumé quotidien</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Résumé quotidien de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Résumé quotidien" />
		<meta property="og:description" content="Résumé quotidien de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Résumé quotidien de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Résumé quotidien" />
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
		<!-- JQUERY JS -->
		<script src="vendors/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="vendors/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css?v=1.3" rel="stylesheet">
		<script defer src="vendors/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="vendors/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- ######### Pour Highcharts ######### -->
		<!-- Highcharts BASE -->
		<link href="vendors/highcharts/css/highcharts-8.0.4.css" rel="stylesheet">
		<script defer src="vendors/highcharts/js/highcharts-8.0.4.js"></script>
		<!-- Spécifique pour HeatMap -->
		<script defer src="vendors/highcharts/modules/heatmap-8.0.4.js"></script>
		<!-- Highcharts more et modules d'export -->
		<script defer src="vendors/highcharts/js/highcharts-more-6.2.0.js"></script>
		<script defer src="vendors/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/export-data-8.0.4.js"></script>
		<script defer src="vendors/highcharts/modules/annotations-8.0.4.js"></script>
		<!-- Highcharts Boost -->
		<script defer src="vendors/highcharts/modules/boost-8.0.4.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
		<!-- Font Awesome CSS for Tempus Dominus -->
		<link href="vendors/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
		<!-- Moment.js -->
		<script defer type="text/javascript" src="vendors/moment/moment.js"></script>
		<script defer type="text/javascript" src="vendors/moment/moment-locale-fr.js"></script>
		<!-- Tempus Dominus -->
		<script defer type="text/javascript" src="vendors/tempusdominus/tempusdominus-bootstrap-4.min.js"></script>
		<link rel="stylesheet" href="vendors/tempusdominus/tempusdominus-bootstrap-4.min.css" />
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
			<?php require 'sql/req_resume_quotidien.php'; ?>

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Résumé du 15/04/2020</h3>
					<p class="text-justify">

					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<legend>Changer de date :</legend>
						<div class="input-group date" id="dtPicker" data-target-input="nearest">
							<input type="text" class="form-control datetimepicker-input" data-target="#dtPicker"/>
							<div class="input-group-append" data-target="#dtPicker" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						$(function () {
							$('#dtPicker').datetimepicker({
								// viewMode: 'years',
								format: 'DD-MM-YYYY',
								// format: 'DD-MM-YYYY HH:mm',
								locale: moment.locale('fr'),
								maxDate: moment(),
								useCurrent: true
							});
						});
					</script>
				</div>
			</div>
			<!-- <div class="jumbotron">
				<h1 class="display-3">Jeu. 16 avril 2020</h1>
				<div class="row">
					
				</div>
				<p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
				<hr class="my-4">
				<p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
				<p class="lead">
					<a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
				</p>
			</div> -->
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<h4 class="text-center mb-4">Résumé de la journée</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<h5>Température</h5>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Tn</th>
								<td><?php //echo $rainrate; ?>&#8239;°C</td>
							</tr>
							<tr>
								<th>Tx</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
							<tr>
								<th>Tmoy</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
							<tr>
								<th>Amplitude</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-4">
					<h5>Humidité/Pt. de rosée</h5>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Humidité min</th>
								<td><?php //echo $rainrate; ?>&#8239;%</td>
							</tr>
							<tr>
								<th>Humidité max</th>
								<td><?php //echo $Rr3h; ?>&#8239;%</td>
							</tr>
							<tr>
								<th>Td min</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
							<tr>
								<th>Td max</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-4">
					<h5>Pression/UV/Ray. sol.</h5>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Pression min</th>
								<td><?php //echo $rainrate; ?>&#8239;%</td>
							</tr>
							<tr>
								<th>Pression max</th>
								<td><?php //echo $Rr3h; ?>&#8239;%</td>
							</tr>
							<tr>
								<th>UV max</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
							<tr>
								<th>Ray. sol. max</th>
								<td><?php //echo $Rr3h; ?>&#8239;°C</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<h5>Précipitations/ET</h5>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Cumul de pluie</th>
								<td><?php //echo $rainrate; ?>&#8239;mm</td>
							</tr>
							<tr>
								<th>Intensité pluie max</th>
								<td><?php //echo $Rr3h; ?>&#8239;mm/h</td>
							</tr>
							<tr>
								<th>Cumul d'évapotranspiration</th>
								<td><?php //echo $Rr3h; ?>&#8239;mm</td>
							</tr>
							<tr>
								<th>ET horaire max</th>
								<td><?php //echo $Rr3h; ?>&#8239;mm/h</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-8">
					<h5>Vent</h5>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Valeur</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Rafale max</th>
								<td><?php //echo $rainrate; ?>&#8239;km/h</td>
							</tr>
							<tr>
								<th>Vent moyen</th>
								<td><?php //echo $Rr3h; ?>&#8239;km/h</td>
							</tr>
							<tr>
								<th>Cumul d'évapotranspiration</th>
								<td><?php //echo $Rr3h; ?>&#8239;mm</td>
							</tr>
							<tr>
								<th>ET horaire max</th>
								<td><?php //echo $Rr3h; ?>&#8239;mm/h</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<hr class="my-4">
			<!-- Tableau de données 30/10 minutes -->
			<div class="row">
				<div class="col-md-12">
					<h4 class="text-center">Tableau de données</h4>
					<p class="text-justify">
						Affichage des données de la veille à 18h UTC au lendemain à 6h UTC
					</p>
					<?php //var_dump($tabRecapQuoti) ?>
					<!-- <button type="button" class="btn btn-info">Info</button> -->
					<div class="table-responsive table-scroll-quotidien">
						<table class="table table-striped table-bordered table-hover table-sm table-sticky">
							<thead>
								<tr>
									<th>Heure loc.</th>
									<th>Tempé.</th>
									<th>Humidité</th>
									<th>Td</th>
									<th>Pression</th>
									<th>Pluie/30min</th>
									<th>Int. max pluie</th>
									<th>Rafale/30min</th>
									<th>Dir. rafale</th>
									<th>Heure rafale</th>
									<?php if ($presence_uv) : ?>
									<th>UV</th>
									<?php endif; ?>
									<?php if ($presence_radiation) : ?>
									<th>Ray. sol.</th>
									<th>ET</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($tabRecapQuoti as $ts => $value) {
									echo "<tr>";
									$dt = date('d/m H\hi',$ts);
									echo "<th>$dt</th>";
									echo "<td>".$value['TempMod']."&#8239;°C</td>";
									echo "<td>".$value['HrMod']."&#8239;%</td>";
									echo "<td>".$value['TdMod']."&#8239;°C</td>";
									echo "<td>".$value['barometerMod']."&#8239;hPa</td>";
									echo "<td>".$value['rainCumulMod']."&#8239;mm</td>";
									if ($value['rainCumulMod'] == '0') {
										echo "<td></td>";
									} else {
										echo "<td>".$value['rainRateMaxMod']."&#8239;mm/h</td>";
									}
									echo "<td>".$value['windGustMaxMod']."&#8239;km/h</td>";
									if ($value['windGustMaxMod'] == '0') {
										echo "<td></td>";
										echo "<td></td>";
									} else {
										echo "<td>".$value['windGustMaxDirMod']."&#8239;°</td>";
										echo "<td>".$value['windGustMaxdtMod']."</td>";
									}
									if ($presence_uv) {
										echo "<td>".$value['UvMod']."</td>";
									}
									if ($presence_radiation) {
										echo "<td>".$value['radiationMod']."&#8239;W/m²</td>";
										if (date('i',$ts) !== '00') {
											echo "<td></td>";
										} else {
											echo "<td>".$value['EtMod']."&#8239;mm/h</td>";
										}
									}
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<p class="d-none d-lg-block source bg-light text-center">
						⇧ <?php if (isset($countTabRecapQuoti)) {echo '<span class="badge badge-success">'.$countTabRecapQuoti.' lignes</span>';} else {echo '<span class="badge badge-danger">0 lignes</span>';}?> Principaux params. de la journée ⇧
					</p>
					<p class="d-lg-none source bg-light text-right">
						⇧ <?php if (isset($countTabRecapQuoti)) {echo '<span class="badge badge-success">'.$countTabRecapQuoti.' lignes</span>';} else {echo '<span class="badge badge-danger">0 lignes</span>';}?> Principaux params. de la journée ⇨
					</p>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<h4 class="text-center">Graphiques</h4>
				</div>
			</div>


			<footer class="footer bg-light">
				<?php include 'footer.php';?>
			</footer>
		</div>
	</body>
</html>
