<?php require_once 'config/config.php';?>
<?php require_once 'sql/connect_pdo.php';?>
<?php require_once 'sql/import.php';?>
<?php require_once 'include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Accueil</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Suivez les relevés météos en live de la station <?php echo $station_name; ?> sur ce site. Précipitations, températures, pression, pluie, graphiques, archives et webcam <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Accueil" />
		<meta property="og:description" content="Suivez les relevés météos en live de la station <?php echo $station_name; ?> sur ce site. Précipitations, températures, pression, pluie, graphiques, archives et webcam <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
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
		<!-- JQUERY JS -->
		<script defer src="vendors/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="vendors/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="vendors/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="vendors/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- <script>
			$(function () {
				$('[data-toggle="popover"]').popover()
			})
		</script> -->
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
			<?php require("sql/req_tableau_jour.php");?>

			<!-- Texte de prez -->
			<div class="row">
				<div class="col-md-12">
					<p class="text-justify">
						Bienvenue sur le site de la station météo de <?php echo $station_name; ?>. Vous y touverez les données météos de la station en direct, mais aussi des tableaux récapitulatifs sur plusieurs périodes et des graphiques. <?php if ($presence_webcam){echo'Une webcam est également disponible sur cette station <a href="webcam.php">en cliquant ici</a>';};?>
					</p>
				</div>
			</div>
			<!-- Tab 3 heures + webcam -->
			<div class="row">
				<?php if ($presence_gif) : ?>
				<div class="col-md-9">
				<?php else : ?>
				<div class="col-md-12">
				<?php endif; ?>
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
				<!-- START tableau 3 dernières heures -->
					<div class="table-responsive table-scroll">
					<table class="table table-striped table-bordered table-hover table-sm table-sticky">
						<!-- <caption class="textTabsCaption">⇧ Principaux paramètres sur les 24 dernières heures ⇧</caption> -->
						<thead>
							<tr>
								<th>Heure loc.</th>
								<th>Température</th>
								<th>Hygrométrie</th>
								<th>Pt. de rosée</th>
								<th>Pression atmo.</th>
								<?php if ($presence_uv) : ?>
								<th>Indice UV</th>
								<?php endif; ?>
								<?php if ($presence_radiation) : ?>
								<th>Ray. sol.</th>
								<th>ET</th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th class="textTabPrimary"><?php echo date('H\hi',$stop) ?></th>
								<td class="textTabPrimary"><?php echo $temp; ?>&#8239;°C</td>
								<td class="textTabPrimary"><?php echo $hygro; ?>&#8239;%</td>
								<td class="textTabPrimary"><?php echo $dewpoint; ?>&#8239;°C</td>
								<td class="textTabPrimary"><?php echo $barometer; ?>&#8239;hPa</td>
								<?php if ($presence_uv) : ?>
								<td class="textTabPrimary"><?php echo $uv; ?></td>
								<?php endif; ?>
								<?php if ($presence_radiation) : ?>
								<td class="textTabPrimary"><?php echo $radiation; ?>&#8239;W/m²</td>
								<td class="textTabPrimary"><?php echo $et; ?>&#8239;mm/h</td>
								<?php endif; ?>
							</tr>
							<?php
							foreach ($tabAccueil as $ts => $value) {
								if ($stop == $ts) continue; // Supprime si doublon
								echo "<tr>";
								$dt = date('H\hi',$ts);
								echo "<th>$dt</th>";
								echo "<td>".$value['TempMod']."&#8239;°C</td>";
								echo "<td>".$value['HrMod']."&#8239;%</td>";
								echo "<td>".$value['TdMod']."&#8239;°C</td>";
								echo "<td>".$value['barometerMod']."&#8239;hPa</td>";
								if ($presence_uv) {
								echo "<td>".$value['UvMod']."</td>";
								}
								if ($presence_radiation) {
								echo "<td>".$value['radiationMod']."&#8239;W/m²</td>";
								echo "<td>".$value['EtMod']."&#8239;mm/h</td>";
								}
								echo "</tr>";
							}
							?>
							<!--<tr>
								<th class="text-info">Mini.</th>
								<td><?php echo $mintemp; ?>&#8239;°C<br><span class="textTabsHourly">à&#8239;<?php echo $mintemptime; ?></span></td>
								<td><?php echo $minhygro; ?>&#8239;%<br><span class="textTabsHourly">à&#8239;<?php echo $minhygrotime; ?></span></td>
								<td><?php echo $mindewpoint; ?>&#8239;°C<br><span class="textTabsHourly">à&#8239;<?php echo $mindewpointtime; ?></span></td>
								<td><?php echo $minbarometer; ?>&#8239;hPa<br><span class="textTabsHourly">à&#8239;<?php echo $minbarometertime; ?></span></td>
							</tr>
							<tr>
								<th class="text-danger">Maxi.</th>
								<td><?php echo $maxtemp; ?>&#8239;°C<br><span class="textTabsHourly">à&#8239;<?php echo $maxtemptime; ?></span></td>
								<td><?php echo $maxhygro; ?>&#8239;%<br><span class="textTabsHourly">à&#8239;<?php echo $maxhygrotime; ?></span></td>
								<td><?php echo $maxdewpoint; ?>&#8239;°C<br><span class="textTabsHourly">à&#8239;<?php echo $maxdewpointtime; ?></span></td>
								<td><?php echo $maxbarometer; ?>&#8239;hPa<br><span class="textTabsHourly">à&#8239;<?php echo $maxbarometertime; ?></span></td>
							</tr>-->
						</tbody>
					</table>
				</div>
				<p class="d-none d-lg-block source bg-light text-center">
					⇧ Principaux params. sur 24h ⇧
				</p>
				<p class="d-lg-none source bg-light text-right">
					⇧ Principaux params. sur 24h ⇨
				</p>
				</div><!-- FIN tableau 3 dernières heures -->
				<!-- START Webcam -->
				<?php if ($presence_gif) : ?>
				<div class="col-md-3">
					<h3 class="text-center">Webcam</h3>
					<p class="text-center">
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
			</div>
			<br>
			<!-- Tableaux de précip, vent et radar + vigi MF et RS -->
			<div class="row">
				<div class="col-md-9">
					<!-- START tableau Précip -->
					<h3 class="text-center">Cumuls de précipitations</h3>
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
										<th>Cumul<br>(intensité&nbsp;max)</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>Intensité instantanée</th>
										<td><?php echo $rainrate; ?>&#8239;mm/h</td>
									</tr>
									<tr>
										<th>Cumul sur 3 heures gliss.</th>
										<td><?php echo $Rr3h; ?>&#8239;mm
											<?php if ($RRateMax3h != 0) {
												echo '<br>'.$RRateMax3h.'&#8239;mm/h <span class="textTabsHourly">à '.$dtRRateMax3h.'</span>';
											}?>
										</td>
									</tr>
									<tr>
										<th>Cumul 6h-6h UTC</th>
										<td><?php echo $RrTodayOMM; ?>&#8239;mm
											<?php if ($RRateMaxToday != 0) {
												echo '<br>'.$RRateMaxToday.'&#8239;mm/h <span class="textTabsHourly">à '.$dtRRateMaxToday.'</span>';
											}?>
										</td>
									</tr>
									<tr>
										<th>Cumul de la veille 6h-6h&nbsp;UTC</th>
										<td><?php echo $RrYesterdayOMM; ?>&#8239;mm
											<?php if ($RRateMaxYesterday != 0) {
												echo '<br>'.$RRateMaxYesterday.'&#8239;mm/h <span class="textTabsHourly">à '.$dtRRateMaxYesterday.'</span>';
											}?>
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
					<!-- START tableau vent -->
					<h3 class="text-center">Vent</h3>
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
									<td><b><?php echo $wind; ?> km/h</b></td>
									<td><b><?php echo $cardinalWindDir; ?> (<?php echo $windDir; ?>°)</b></td>
								</tr>
								<tr>
									<th>Moyen sur 10 min.</th>
									<td><?php echo $avg_wind_10; ?> km/h</td>
									<td><?php echo $cardinalDir10; ?> (<?php echo $avg_windDir_10; ?>°)</td>
								</tr>
								<tr>
									<th>Moyen sur 1 heure</th>
									<td><?php echo $avg_wind_1h; ?> km/h</td>
									<td><?php echo $cardinalDir1h; ?> (<?php echo $avg_windDir_1h; ?>°)</td>
								</tr>
								<tr>
									<th><b>Rafales instant.</b></th>
									<td><b><?php echo $windgust; ?> km/h</b></td>
									<td><b><?php echo $cardinalWindGustDir; ?> (<?php echo $windGustDir; ?>°)</b></td>
								</tr>
								<tr>
									<th>Rafales sur 10 min.</th>
									<td><?php echo $avg_windGust_10; ?> km/h</td>
									<td><?php echo $cardinalGustDir10; ?> (<?php echo $avg_windGustDir_10; ?>°)</td>
								</tr>
								<tr>
									<th>Rafales sur 1 heure</th>
									<td><?php echo $avg_windGust_1h; ?> km/h</td>
									<td><?php echo $cardinalGustDir1h; ?> (<?php echo $avg_windGustDir_1h; ?>°)</td>
								</tr>
								<tr>
									<th><b>Rafale max du jour</b></th>
									<td><b><?php echo $maxwind; ?> km/h à <?php echo $maxwindtime; ?></b></td>
									<td><b><?php echo $cardinalMaxWindDir; ?> (<?php echo $maxwinddir; ?>°)</b></td>
								</tr>
							</tbody>
						</table>
					<!-- END tableau vent -->
					<!-- START radar de précip -->
					<h3 class="text-center">Radar de précipitations</h3>
					<div class="img-thumbnail text-center">
						<img class="img-fluid" src="<?php echo $radar_url; ?>" alt="Image radar des précipitations">
					</div>
					<p class="source">
						Source : <a href="<?php echo $radar_source_url; ?>" target="blank"><?php echo $radar_source; ?></a>
					</p>
					<!-- END radar de précip -->
				</div>
				<div class="col-md-3 text-center">
					<!-- START VIGI MF + RS -->
					<h3 class="text-center">Vigi. Météo-France</h3>
					<?php include 'config/widget_vigi.php'; ?>
					<br><br>
					<h3>Réseaux sociaux</h3>
					<?php include 'config/res_sociaux.php';?>
					<!-- END VIGI MF + RS -->
				</div>
			</div>

			<footer class="footer bg-light">
				<?php include 'footer.php';?>
			</footer>
		</div> <!-- END du container -->
	</body>
</html>
