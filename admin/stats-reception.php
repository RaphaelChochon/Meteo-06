<?php require_once '../config/config.php';?>
<?php require_once '../sql/connect_pdo.php';?>
<?php require_once '../sql/import.php';?>
<?php require_once '../include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | ADMIN - Statistiques de réception</title>
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
		
		<!-- ######### Pour Highcharts ######### -->
		<!-- Highcharts BASE -->
		<script defer src="../vendors/highcharts/js/highcharts-8.0.4.js"></script>
		<!-- Spécifique pour HeatMap -->
		<script defer src="../vendors/highcharts/modules/heatmap-8.0.4.js"></script>
		<!-- Highcharts more et modules d'export -->
		<script defer src="../vendors/highcharts/js/highcharts-more-8.0.4.js"></script>
		<script defer src="../vendors/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="../vendors/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="../vendors/highcharts/modules/export-data-8.0.4.js"></script>
		<script defer src="../vendors/highcharts/modules/annotations-8.0.4.js"></script>
		<!-- Highcharts Boost -->
		<script defer src="../vendors/highcharts/modules/boost-8.0.4.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
		<!-- Font Awesome CSS for Tempus Dominus -->
		<link href="../vendors/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
		<!-- Moment.js -->
		<script defer type="text/javascript" src="../vendors/moment/moment.js"></script>
		<script defer type="text/javascript" src="../vendors/moment/moment-locale-fr.js"></script>
		<!-- Tempus Dominus -->
		<script defer type="text/javascript" src="../vendors/tempusdominus/tempusdominus-bootstrap-4.min.js"></script>
		<link rel="stylesheet" href="../vendors/tempusdominus/tempusdominus-bootstrap-4.min.css" />
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

			<!-- On récupère les valeurs en BDD pour peupler les graphs ci-après -->
			<?php require_once 'req_stats-reception.php'; ?>

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
					<h3 class="text-center"> Statistiques de réception</h3>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<div id="graph_rx_48h" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_rx_7j" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<div id="graph_tension_console" style="width:100%; height: 500px;"></div>
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
						START GRAPH RX 48h
					*/
					var graph_rx_48h = Highcharts.chart ('graph_rx_48h', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Stats de réception ISS->Console 48h',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_stats_rx',
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
							min: 0,
							max: 100,
							title: {
								text: 'RX (%)',
								style: {
									"color": "#ff0000",
								},
							},
							labels:{
								style: {
									"color": "#ff0000",
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
							name: 'RX 10min',
							id: 'series-rx',
							type: 'line',
							data: [<?php echo join($dataRx48h, ',') ?>],
							zones: [{
								value: 50,
								color: '#ff266e'
							},{
								value: 75,
								color: '#ffb626'
							},{
								color: '#34c400'
							}],
							tooltip: {
								valueSuffix: ' %',
							},
							visible: false
						},{
							name: 'RX Min/10min',
							type: 'line',
							data: [<?php echo join($dataRx48hMin, ',') ?>],
							zones: [{
								value: 50,
								color: '#ff266e'
							},{
								value: 75,
								color: '#ffb626'
							},{
								color: '#34c400'
							}],
							tooltip: {
								valueSuffix: ' %',
							},
						},{
							name: 'RX min/max',
							linkedTo: 'series-rx',
							type: 'errorbar',
							yAxis: 0,
							color: 'black',
							lineWidth: 0.8,
							tooltip: {
								pointFormat: 'RX min/max sur l\'intvl: {point.low}-{point.high} %)<br/>'
							},
							data: [<?php echo join($dataRx48hError, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						}],
					});
					/*
						START GRAPH RX 7 jours
					*/
					var graph_rx_7j = Highcharts.chart ('graph_rx_7j', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Stats de réception ISS->Console 7 jours',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_stats_rx',
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
							min: 0,
							max: 100,
							title: {
								text: 'RX (%)',
								style: {
									"color": "#ff0000",
								},
							},
							labels:{
								style: {
									"color": "#ff0000",
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
							name: 'RX 1h',
							id: 'series-rx',
							type: 'line',
							data: [<?php echo join($dataRx7j, ',') ?>],
							zones: [{
								value: 50,
								color: '#ff266e'
							},{
								value: 75,
								color: '#ffb626'
							},{
								color: '#34c400'
							}],
							tooltip: {
								valueSuffix: ' %',
							},
							visible: false
						},{
							name: 'RX Min/1h',
							type: 'line',
							data: [<?php echo join($dataRx7jMin, ',') ?>],
							zones: [{
								value: 50,
								color: '#ff266e'
							},{
								value: 75,
								color: '#ffb626'
							},{
								color: '#34c400'
							}],
							tooltip: {
								valueSuffix: ' %',
							},
						},{
							name: 'RX min/max',
							linkedTo: 'series-rx',
							type: 'errorbar',
							yAxis: 0,
							color: 'black',
							lineWidth: 0.8,
							tooltip: {
								pointFormat: 'RX min/max sur l\'intvl: {point.low}-{point.high} %)<br/>'
							},
							data: [<?php echo join($dataRx7jError, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						}],
					});
					/*
						START GRAPH tension console 7 jours
					*/
					var graph_tension_console = Highcharts.chart ('graph_tension_console', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Tension des piles de la console sur 7 jours',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_stats_tension',
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
							min: 0,
							max: 6,
							title: {
								text: 'Tension piles (V)',
								style: {
									"color": "#ff0000",
								},
							},
							labels:{
								style: {
									"color": "#ff0000",
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
							name: 'Tension 1h',
							type: 'line',
							data: [<?php echo join($dataTension7j, ',') ?>],
							zones: [{
								value: 4,
								color: '#ff266e'
							},{
								value: 4.5,
								color: '#ffb626'
							},{
								color: '#34c400'
							}],
							tooltip: {
								valueSuffix: ' V',
							},
							visible: false
						},{
							name: 'Tension Min/1h',
							type: 'line',
							data: [<?php echo join($dataTension7jMin, ',') ?>],
							zones: [{
								value: 4,
								color: '#ff266e'
							},{
								value: 4.5,
								color: '#ffb626'
							},{
								color: '#34c400'
							}],
							tooltip: {
								valueSuffix: ' V',
							},
						},{
							name: 'Tension min/max',
							type: 'errorbar',
							color: 'black',
							lineWidth: 0.8,
							tooltip: {
								pointFormat: 'Tension min/max sur l\'intvl: {point.low}-{point.high} V)<br/>'
							},
							data: [<?php echo join($dataTension7jError, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						}],
					});
					/*
						FIN DES GRAPHS
					*/
				});
			</script>



			<hr class="my-4">
			<footer class="footer bg-light">
				<?php include '../footer.php';?>
			</footer>
		</div>
	</body>
</html>
