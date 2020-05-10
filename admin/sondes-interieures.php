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
		<title><?php echo $short_station_name; ?> | ADMIN - Sondes intérieures</title>
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
		<script src="../content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="../content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="../content/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="../content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="../content/bootstrap/js/bootstrap-4.4.1.min.js"></script>
		
		<!-- ######### Pour Highcharts ######### -->
		<!-- Highcharts BASE -->
		<script defer src="../content/highcharts/js/highcharts-8.0.4.js"></script>
		<!-- Spécifique pour HeatMap -->
		<script defer src="../content/highcharts/modules/heatmap-8.0.4.js"></script>
		<!-- Highcharts more et modules d'export -->
		<script defer src="../content/highcharts/js/highcharts-more-8.0.4.js"></script>
		<script defer src="../content/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="../content/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="../content/highcharts/modules/export-data-8.0.4.js"></script>
		<script defer src="../content/highcharts/modules/annotations-8.0.4.js"></script>
		<!-- Highcharts Boost -->
		<script defer src="../content/highcharts/modules/boost-8.0.4.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
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
			<!-- Vérif des droits d'accès -->
			<?php if ( !(defined('USER_IS_ADMIN') || defined('USER_IS_TEAM') || defined('USER_IS_PROPRIO')) ) :?>
			<div class="row">
				<div class="col-md-6 mx-auto">
					<div class="alert alert-danger">
						<h4 class="alert-heading mt-1">Au mauvais endroit...</h4>
						<p class="text-justify mb-0">
							<strong>Oops !</strong> Il semblerait que vous n'ayez pas les droits nécessaires pour accéder à cette page.
						</p>
					</div>
				</div>
			</div>
			<?php else :?>

			<!-- DEBUT DU CORPS DE PAGE -->
			<!-- Bannière infos -->
			<?php if ($banniere_info_active) : ?>
				<div class="alert alert-<?php echo $banniere_info_type; ?>">
					<h4 class="alert-heading"><?php echo $banniere_info_titre; ?></h4>
					<hr>
					<p class="mb-0"><?php echo $banniere_info_message; ?></p>
				</div>
			<?php endif; ?>

			<!-- On récupère les valeurs en BDD pour peupler les graphs ci-après -->
			<?php include __DIR__ . '/req_sondes-interieures.php'; ?>

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
					<h3 class="text-center">Données intérieures</h3>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Paramètre</th>
								<th>Valeur actuelle</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Température intérieure</th>
								<td><?php echo $inTempNow; ?>&#8239;°C</td>
							</tr>
							<tr>
								<th>Himidité intérieure</th>
								<td><?php echo $inHumidityNow; ?>&#8239;%</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<div id="graph_temp_48h" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_temp_7j" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<div id="graph_temp_30j" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<script>
				/*
					DEBUT GRAPHS
				*/
				document.addEventListener('DOMContentLoaded', function () {
					Highcharts.setOptions({
						global: {
							useUTC: true
						},
						lang: {
							months: ["Janvier "," Février "," Mars "," Avril "," Mai "," Juin "," Juillet "," Août "," Septembre "," Octobre "," Novembre "," Décembre"],
							weekdays: ["Dim "," Lun "," Mar "," Mer "," Jeu "," Ven "," Sam"],
							shortMonths: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil','Août', 'Sept', 'Oct', 'Nov', 'Déc'],
							contextButtonTitle: "Menu",
							decimalPoint: '.',
							resetZoom: 'Reset zoom',
							resetZoomTitle: 'Reset zoom à 1:1',
							downloadPNG: "Télécharger au format PNG",
							downloadJPEG: "Télécharger au format JPEG",
							downloadPDF: "Télécharger au format PDF",
							downloadSVG: "Télécharger au format SVG",
							downloadCSV: "Télécharger les données<br>dans un fichier CSV",
							downloadXLS: "Télécharger les données<br>dans un fichier XLS (Excel)",
							printChart: "Imprimer le graphique",
							viewFullscreen: "Afficher en plein écran",
							viewData: "Afficher les données brut sous forme<br>d'un tableau ci-dessous (BETA)",
							loading: "Chargement des données en cours..."
						},
						chart: {
							resetZoomButton: {
								position: {
									align: 'left', // by default
									// verticalAlign: 'top', // by default
									x: 30,
									// y: -30
								}
							}
						},
						navigation: {
								menuItemStyle: {
									fontSize: "9px",
									padding: "0.5em 0.5em"
								}
						},
						credits: {
							enabled: false
						},
						xAxis: [{
							type: 'datetime',
							dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
							tickInterval: 7200*1000,
							crosshair: true,
							plotBands: [{
								color: '#f2f5f5',
								from: <?php echo $minuit_hier;?>,
								to: <?php echo $minuit;?>,
							},{
								color: '#f2f5f5',
								from: <?php echo $minuit_4;?>,
								to: <?php echo $minuit_3;?>,
							},{
								color: '#f2f5f5',
								from: <?php echo $minuit_6;?>,
								to: <?php echo $minuit_5;?>,
							},{
								color: '#f2f5f5',
								from: <?php echo $minuit_8;?>,
								to: <?php echo $minuit_7;?>,
							}],
							plotLines: [{
								value: <?php echo $minuit;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $minuit_hier;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $minuit_3;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $minuit_4;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $minuit_5;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $minuit_6;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $minuit_7;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							}],
						},{ // Axe esclave
							type: 'datetime',
							linkedTo: 0,
							//opposite: true,
							tickInterval: 7200 * 1000 * 8,
							labels: {
								align:"center",
								formatter: function () {
									return Highcharts.dateFormat('%a %e %b', this.value);
								},
								style:{
									fontSize: "8px",
								},
							}
						}],
						plotOptions: {
							series: {
								states: {
									hover: {
										enabled: true,
										lineWidthPlus: 0 // désactive le highlighting des series
									}
								}
							}
						}
					});
					/*
						START GRAPH inTemp 48h
					*/
					var graph_temp_48h = Highcharts.chart ('graph_temp_48h', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Température et humidité intérieure sur 48h',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_inTemp_48h',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						// xAxis dans params généraux
						yAxis: [{
							// Axe 0
							lineColor: '#FF0000',
							lineWidth: 1,
							tickPixelInterval: 30,
							title: {
								text: 'Température (°C)',
								style: {
									"color": "#ff0000",
								},
							},
							labels:{
								style: {
									"color": "#ff0000",
								},
							},
						},{
							// Axe 1
							opposite: true,
							min:0,
							max: 100,
							tickPixelInterval: 30,
							lineColor: '#3399FF',
							lineWidth: 1,
							title: {
								text: 'Humidité (%)',
								style: {
									"color": "#3399FF",
								},
							},
							labels:{
								style: {
									"color": "#3399FF",
								},
							},
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
						},
						series: [{
							name: 'Température 10min',
							type: 'line',
							data: [<?php echo join($dataInTemp48h, ',') ?>],
							zIndex: 2,
							color: '#ff0000',
							negativeColor:'#0d1cc5',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Température min/max',
							type: 'errorbar',
							yAxis: 0,
							color: '#ff0000',
							lineWidth: 1.2,
							tooltip: {
								pointFormat: 'Temp. min/max sur l\'intvl: {point.low}-{point.high}°C)<br/>'
							},
							zIndex: 20,
							data: [<?php echo join($dataInTemp48hError, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						},{
							name: 'Humidité',
							type: 'line',
							data: [<?php echo join($dataInHumidity48h, ',') ?>],
							yAxis: 1,
							color: '#3399FF',
							tooltip: {
								valueSuffix: ' %',
							}
						}],
					});
					/*
						START GRAPH inTemp 7j
					*/
					var graph_temp_7j = Highcharts.chart ('graph_temp_7j', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Température et humidité intérieure sur 7 jours',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_inTemp_7j',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						// xAxis dans params généraux
						yAxis: [{
							// Axe 0
							lineColor: '#FF0000',
							lineWidth: 1,
							tickPixelInterval: 30,
							title: {
								text: 'Température (°C)',
								style: {
									"color": "#ff0000",
								},
							},
							labels:{
								style: {
									"color": "#ff0000",
								},
							},
						},{
							// Axe 1
							opposite: true,
							min:0,
							max: 100,
							tickPixelInterval: 30,
							lineColor: '#3399FF',
							lineWidth: 1,
							title: {
								text: 'Humidité (%)',
								style: {
									"color": "#3399FF",
								},
							},
							labels:{
								style: {
									"color": "#3399FF",
								},
							},
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
						},
						series: [{
							name: 'Température 1h',
							type: 'line',
							data: [<?php echo join($dataInTemp7j, ',') ?>],
							zIndex: 2,
							color: '#ff0000',
							negativeColor:'#0d1cc5',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Température min/max',
							type: 'errorbar',
							yAxis: 0,
							color: '#ff0000',
							lineWidth: 1.2,
							tooltip: {
								pointFormat: 'Temp. min/max sur l\'intvl: {point.low}-{point.high}°C)<br/>'
							},
							zIndex: 20,
							data: [<?php echo join($dataInTemp7jError, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						},{
							name: 'Humidité 1h',
							type: 'line',
							data: [<?php echo join($dataInHumidity7j, ',') ?>],
							yAxis: 1,
							color: '#3399FF',
							tooltip: {
								valueSuffix: ' %',
							}
						}],
					});
					/*
						START GRAPH inTemp 30j
					*/
					var graph_temp_30j = Highcharts.chart ('graph_temp_30j', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Température et humidité intérieure sur 30 jours',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_inTemp_30j',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						xAxis: [{
							type: 'datetime',
							dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
							tickInterval: 7200*1000,
							crosshair: true,
							plotBands: [{
							}],
							plotLines: [{
							}],
						},{ // Axe esclave
							type: 'datetime',
							linkedTo: 0,
							//opposite: true,
							tickInterval: 7200 * 1000 * 8,
							labels: {
								align:"center",
								formatter: function () {
									return Highcharts.dateFormat('%a %e %b', this.value);
								},
								style:{
									fontSize: "8px",
								},
							},
						}],
						yAxis: [{
							// Axe 0
							lineColor: '#FF0000',
							lineWidth: 1,
							tickPixelInterval: 30,
							title: {
								text: 'Température (°C)',
								style: {
									"color": "#ff0000",
								},
							},
							labels:{
								style: {
									"color": "#ff0000",
								},
							},
						},{
							// Axe 1
							opposite: true,
							min:0,
							max: 100,
							tickPixelInterval: 30,
							lineColor: '#3399FF',
							lineWidth: 1,
							title: {
								text: 'Humidité (%)',
								style: {
									"color": "#3399FF",
								},
							},
							labels:{
								style: {
									"color": "#3399FF",
								},
							},
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
						},
						series: [{
							name: 'Température 3h',
							type: 'line',
							data: [<?php echo join($dataInTemp30j, ',') ?>],
							zIndex: 2,
							color: '#ff0000',
							negativeColor:'#0d1cc5',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Température min/max',
							type: 'errorbar',
							yAxis: 0,
							color: '#ff0000',
							lineWidth: 1.2,
							tooltip: {
								pointFormat: 'Temp. min/max sur l\'intvl: {point.low}-{point.high}°C)<br/>'
							},
							zIndex: 20,
							data: [<?php echo join($dataInTemp30jError, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						},{
							name: 'Humidité 3h',
							type: 'line',
							data: [<?php echo join($dataInHumidity30j, ',') ?>],
							yAxis: 1,
							color: '#3399FF',
							tooltip: {
								valueSuffix: ' %',
							}
						}],
					});
					/*
						FIN DES GRAPHS
					*/
				});
			</script>



			<hr class="my-4">
			<!-- Fin de vérif des droits proprios -->
			<?php endif; ?>
			<footer class="footer bg-light rounded">
				<?php include __DIR__ . '/../footer.php';?>
			</footer>
		</div>
	</body>
</html>
