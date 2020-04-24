<?php require_once __DIR__ . '/include/access_rights.php';?>
<?php require_once __DIR__ . '/config/config.php';?>
<?php require_once __DIR__ . '/sql/connect_pdo.php';?>
<?php require_once __DIR__ . '/sql/import.php';?>
<?php require_once __DIR__ . '/include/functions.php';?>
<?php
// Récup des params
	$optType = array('graphs','month','heatmap');
	$optPeriod = array('24h','48h','7j');

	if (isset($_GET['type']) || !empty($_GET['type'])) {
		if (in_array($_GET['type'], $optType)) {
			$graphType = $_GET['type'];
			if ($graphType == 'graphs') {
				if (isset($_GET['period']) || !empty($_GET['period'])) {
					if (in_array($_GET['period'], $optPeriod)) {
						$period = $_GET['period'];
					} else {
						$period = '24h';
					}
				} else {
					$period = '24h';
				}
			}
			elseif ($graphType == 'heatmap') {
				// insert dans optPeriod les années possibles
				$optPeriod = array();
				// $yearRange est calculé dans sql/import.php
				foreach($yearRange as $year){
					$optPeriod[] = $year->format("Y");
				}

				if (isset($_GET['period']) || !empty($_GET['period'])) {
					if (in_array($_GET['period'], $optPeriod)) {
						$period = $_GET['period'];
					} else {
						$period = $lastYear;
					}
				} else {
					$period = $lastYear;
				}
			}
			elseif ($graphType == 'month') {
				// insert dans optPeriod les années possibles
				$optPeriod = array();
				// $yearRange est calculé dans sql/import.php
				foreach($monthRange as $month){
					$optPeriod[] = $month->format('Y-m');
				}

				if (isset($_GET['period']) || !empty($_GET['period'])) {
					if (in_array($_GET['period'], $optPeriod)) {
						$period = $_GET['period'];
					} else {
						$period = $lastYearMonth;
					}
				} else {
					$period = $lastYearMonth;
				}
			}
		} else {
			$graphType = 'graphs';
			$period = '24h';
		}
	} else {
		$graphType = 'graphs';
		$period = '24h';
	}

	
	// print_r($optPeriod);
	

	// appel du script de requete
	include __DIR__ . '/sql/req_graphs.php';

?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Graphiques</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Graphiques de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Graphiques" />
		<meta property="og:description" content="Graphiques de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Graphiques de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Graphiques" />
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
		<link href="content/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- Highcharts -->
		<script defer src="content/highcharts/js/highcharts-8.0.4.js"></script>
		<?php if ($graphType == 'heatmap') : ?>
			<script defer src="content/highcharts/modules/heatmap-8.0.4.js"></script>
		<?php endif; ?>
		<script defer src="content/highcharts/js/highcharts-more-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/export-data-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/annotations-8.0.4.js"></script>
		<?php if ($graphType == 'heatmap') : ?>
			<script defer src="content/highcharts/modules/boost-8.0.4.js"></script>
		<?php endif; ?>

		<!-- Font Awesome CSS -->
		<link href="content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">

		<?php if ($graphType == 'month') : ?>
			<!-- Moment.js -->
			<script type="text/javascript" src="content/moment/moment.js"></script>
			<script type="text/javascript" src="content/moment/moment-locale-fr.js"></script>
			<!-- Tempus Dominus -->
			<script type="text/javascript" src="content/tempusdominus/tempusdominus-bootstrap-3.min.js"></script>
			<link rel="stylesheet" href="content/tempusdominus/tempusdominus-bootstrap-3.min.css" />
		<?php endif; ?>
	</head>
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
			<!-- Boutons -->
			<hr>
			<div class="row" id="anchorButtons">
				<div class="col-sm-12 text-center">
					<p class="text-center">
						Choisir un type de graphique :
					</p>
					<a role="button" class="btn btn-primary" href="./graphs.php?type=graphs&period=24h#anchorButtons">24 heures</a>
					<a role="button" class="btn btn-primary" href="./graphs.php?type=graphs&period=48h#anchorButtons">48 heures</a>
					<a role="button" class="btn btn-primary" href="./graphs.php?type=graphs&period=7j#anchorButtons">7 jours</a>
					<div class="btn-group">
						<a href="./graphs.php?type=heatmap&period=<?php echo $lastYear;?>#anchorButtons" class="btn btn-primary">Cartes annuelles</a>
						<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="sr-only"> </span>
						</button>
						<div class="dropdown-menu">
							<?php
								foreach($yearRange as $year){
									echo '<a class="dropdown-item" href="./graphs.php?type=heatmap&period='.$year->format("Y").'#anchorButtons">'.$year->format("Y").'</a>';
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<hr>
		<!-- DEBUT type=graphs -->
		<?php if ($graphType == 'graphs') : ?>
			<!-- Texte -->
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Graphiques <?php echo $textPeriod;?></h3>
					<p class="text-justify">
						Vous trouverez sur cette page les relevés de la station sous forme de graphiques. Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la souris ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende. Ils sont également exportables en cliquant sur le bouton au-dessus à droite de chaque graphique.
					</p>
					<p class="text-justify">
						<b>Attention, les graphiques sont en heure UTC, donc il faut rajouter une heure l'hiver et deux heures l'été !<br>Exemple : il est actuellement <?php date_default_timezone_set('UTC'); echo date('H:i'); ?> UTC, et donc <?php date_default_timezone_set('Europe/Paris'); echo date('H:i'); ?> en France</b>
					</p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_temp_hygro" style="width:100%; height: 500px;"></div>
					<div class="text-center mt-1">
						<button type="button" class="btn btn-info" id="removeAnnoTnTx">⇧ Masquer les étiquettes ⇧</button>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_pression" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_vent" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_precip" style="width:100%; height:500px;"></div>
					<div class="text-center mt-1">
						<button type="button" class="btn btn-info" id="removeAnnoRR">⇧ Masquer les étiquettes ⇧</button>
					</div>
				</div>
			</div>
			<?php if ($presence_uv) : ?>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_uv" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($presence_radiation) : ?>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_rad" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graph_et" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<?php endif; ?>
			<hr>

			<!--
				DEBUT SCRIPT HIGHCHARTS
			-->
			<script>

				// Tn & Tx Labels
				<?php
					$annotJsTClim = array();
					foreach($dataTn as $annotation){
						$dateTn = date('d/m', $annotation['dateDay']/1000);
						$annotJsTClim[] = array(
							'id' => 'anno-TnTx',
							'labels' => array(array(
								'point' => array(
									'xAxis' => 0,
									'yAxis' => 0,
									'x' => $annotation['TnDt'],
									'y' => $annotation['Tn'],
								),
								'y' => 40,
								'text' => 'Tn du '.$dateTn.' : '.$annotation['Tn'].' °C',
							)),
							'labelOptions' => array(
								'borderRadius' => 5,
								'style' => array(
									'fontSize' => '8.5px'
								)
							),
							'shapeOptions' => array(
								'fill' => 'rgba(237, 237, 237, 0.7)'
							)
						);
					}
					foreach($dataTx as $annotation){
						$dateTx = date('d/m', $annotation['dateDay']/1000);
						$annotJsTClim[] = array(
							'id' => 'anno-TnTx',
							'labels' => array(array(
								'point' => array(
									'xAxis' => 0,
									'yAxis' => 0,
									'x' => $annotation['TxDt'],
									'y' => $annotation['Tx'],
								),
								'text' => 'Tx du '.$dateTx.' : '.$annotation['Tx'].' °C',
							)),
							'labelOptions' => array(
								'borderRadius' => 5,
								'style' => array(
									'fontSize' => '8.5px'
								)
							),
							'shapeOptions' => array(
								'fill' => 'rgba(237, 237, 237, 0.7)'
							)
						);
					}
					
					// echo "var LabelsPersoT = ".json_encode($annotJsTClim, JSON_PRETTY_PRINT).";";
					echo "var LabelsPersoT = ".json_encode($annotJsTClim).";";
				?>

				// RRClimato Labels
				<?php
					$annotJsRRClim = array();
					foreach($dataRRClimato as $annotation){
						$dateRRClim = date('d/m', strtotime($annotation['dateDay']));
						if ($annotation['RRmaxInt'] != null) {
							$dateRRMaxInt = date('H:i', $annotation['RRmaxIntDt']/1000);
							$annotJsRRClim[] = array(
								'id' => 'anno-RR',
								'labels' => array(array(
									'point' => array(
										'xAxis' => 0,
										'yAxis' => 0,
										'x' => $annotation['dateDay6h'],
										'y' => 0,
									),
									'useHTML' => true,
									'text' => $dateRRClim.' : '.$annotation['RR'].' mm<br>Int.max '.$annotation['RRmaxInt'].' mm/h à '.$dateRRMaxInt,
								)),
								'labelOptions' => array(
									'borderRadius' => 5,
									'style' => array(
										'fontSize' => '8.5px'
									)
								),
								'shapeOptions' => array(
									'fill' => 'rgba(237, 237, 237, 0.7)'
								)
							);
						} else {
							$annotJsRRClim[] = array(
								'id' => 'anno-RR',
								'labels' => array(array(
									'point' => array(
										'xAxis' => 0,
										'yAxis' => 0,
										'x' => $annotation['dateDay6h'],
										'y' => 0,
									),
									'useHTML' => true,
									'text' => $dateRRClim.' : '.$annotation['RR'].' mm',
								)),
								'labelOptions' => array(
									'borderRadius' => 5,
									'style' => array(
										'fontSize' => '8.5px'
									)
								),
								'shapeOptions' => array(
									'fill' => 'rgba(237, 237, 237, 0.7)'
								)
							);
						}
					}
					echo "var LabelsPersoRRClim = ".json_encode($annotJsRRClim).";";
				?>

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
						START GRAPH TEMP/HYGRO
					*/
					var temperature = Highcharts.chart ('graph_temp_hygro', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Température et humidité <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Tn et Tx aux normes OMM',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> Temperature',
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
								text: 'Température et pt de rosée (°C)',
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
							useGPUTranslations: false,
							seriesThreshold:1,
							// debug: {
							// 	showSkipSummary: true,
							// 	timeSeriesProcessing: true,
							// 	timeBufferCopy: true,
							// }
						},
						series: [{
							name: 'Température',
							id: 'series-temp',
							type: 'line',
							data: [<?php echo join($dataTemp, ',') ?>],
							// boostThreshold: 20,
							zIndex: 2,
							color: '#ff0000',
							negativeColor:'#0d1cc5',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Température min/max',
							linkedTo: 'series-temp',
							type: 'errorbar',
							yAxis: 0,
							color: '#ff0000',
							lineWidth: 1.2,
							tooltip: {
								pointFormat: 'Temp. min/max sur l\'intvl: {point.low}-{point.high}°C)<br/>'
							},
							zIndex: 20,
							data: [<?php echo join($dataTnTx, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						},{
							name: 'Humidité',
							type: 'line',
							data: [<?php echo join($dataHr, ',') ?>],
							// boostThreshold: 20,
							yAxis: 1,
							color: '#3399FF',
							tooltip: {
								valueSuffix: ' %',
							}
						},{
							name: 'Point de rosée',
							type: 'line',
							data: [<?php echo join($dataTd, ',') ?>],
							// boostThreshold: 20,
							color: '#1c23e4',
							visible: false,
							tooltip: {
								valueSuffix: ' °C',
							}
						}],
						annotations: LabelsPersoT
					});
					/*
						START GRAPH pression
					*/
					var pression = Highcharts.chart ('graph_pression', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Pression atmo. <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> Pression',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
							crosshair:true,
							lineColor: '#1be300',
							lineWidth: 1,
							title: {
								text: 'Pression (hPa)',
								style: {
									"color": "#1be300",
								},
							},
							labels:{
								style: {
									"color": "#1be300",
								},
							},
						},
						tooltip: {
							shared: true,
							valueDecimals: 1,
							valueSuffix: ' hPa',
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
							// debug: {
							// 	showSkipSummary: true,
							// 	timeSeriesProcessing: true,
							// 	timeBufferCopy: true,
							// }
						},
						series: [{
							name: 'Pression',
							type: 'line',
							data: [<?php echo join($dataBaro, ',') ?>],
							// boostThreshold: 20,
							connectNulls: false,
							color: '#1be300',
						}]
					});
					/*
						START GRAPH VENT
					*/
					var data_wg = [<?php echo join($dataWg, ',') ?>];
					var vent = Highcharts.chart ('graph_vent', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Vent <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> Vent',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: [{
							// Axe 0
							lineColor: '#3399FF',
							lineWidth: 1,
							min:0,
							title: {
								text: 'Vitesse (km/h)',
								style: {
									"color": "#3399FF",
								},
							},
							labels:{
								style: {
									"color": "#3399FF",
								},
							},
						},{
							opposite:true,
							reversed:true,
							max : 360,
							min: 0,
							categories: ['N (0°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','NE (45°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','E (90°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','SE (135°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','S (180°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','SO (225°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','O (270°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','NO (315°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','N (0°)'],
								endOnTick: true,
								tickInterval:45,
								minorTickInterval:45,
								title: {
								text: 'Direction du vent moyen',
									style: {
										"color": "#9400d3",
									},
								},
								labels:{
									style: {
										"color": "#9400d3",
									},
								},
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							headerFormat: '<small>{point.key}</small><br>----<br>',
						},
						plotOptions: {
							series: {
								marker: {
									enabled: false
								}
							}
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Rafales max.',
							type: 'line',
							color: 'rgba(255,0,0,0.65)',
							data: data_wg,
							// boostThreshold: 20,
							zIndex: 20,
							tooltip: {
								useHTML: true,
								pointFormatter: function(){
									if (this.y != 0) {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
										'<span style="color:'+this.series.color+'">\u25CF</span> Direction: <b>'+data_wg[this.index][2]+' °</b><br>'+
										'<span style="color:'+this.series.color+'">\u25CF</span> à <b>'+data_wg[this.index][3]+'</b><br>'+
										'----<br>';
									} else {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
										'----<br>';
									}
								},
							}
						},{
							name: 'Direction des rafales',
							type: 'scatter',
							data: [<?php echo join($dataWgD, ',') ?>],
							// boostThreshold: 20,
							zIndex: 40,
							yAxis: 1,
							color:'rgba(148,0,28,0.75)',
							marker: {
								symbol: 'circle',
								enabled: true,
								lineWidth: 0,
								radius:2,
								color:'rgba(148,0,28,0.75)',
							},
							visible:false,
							enableMouseTracking: false,
						},{
							name: 'Vent moyen',
							type: 'line',
							color: 'rgba(51,153,255,0.75)',
							data: [<?php echo join($dataWs, ',') ?>],
							// boostThreshold: 20,
							zIndex: 10,
							tooltip: {
								useHTML: true,
								pointFormatter: function(){
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>';
								},
							}
						},{
							name: 'Direction moy. du vent moy.',
							type: 'scatter',
							data: [<?php echo join($dataWsD, ',') ?>],
							// boostThreshold: 20,
							zIndex: 30,
							yAxis: 1,
							color:'rgba(148,0,211,0.75)',
							marker: {
								symbol: 'circle',
								enabled: true,
								lineWidth: 0,
								radius:2,
								color:'rgba(148,0,211,0.75)',
							},
							visible:false,
							enableMouseTracking: false,
						}]
					});
					/*
						START GRAPH precip
					*/
					var precip = Highcharts.chart ('graph_precip', {
						chart: {
							type : 'area',
							zoomType: 'x',
						},
						title: {
							text: 'Précipitations <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Cumul à 6h UTC aux normes OMM',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> Precipitations',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: [{
							// Axe 0
							crosshair:true,
							lineColor: '#4169e1',
							lineWidth: 1,
							min:0,
							softMin:1,
							title: {
								text: 'Précipitations (mm)',
								style: {
									"color": "#4169e1",
								},
							},
							labels:{
								style: {
									"color": "#4169e1",
								},
							},
						},{
							// Axe 1 Cumul pluie
							opposite: true,
							crosshair:true,
							lineColor: '#3d4147',
							min: 0,
							title: {
								text: 'Cumul précips. (mm)',
								style: {
									"color": "#3d4147",
								},
							},
							labels:{
								style: {
									"color": "#3d4147",
								},
							},
						},{
							// Axe 2 intensité pluie
							opposite: true,
							crosshair:true,
							lineColor: '#6883d9',
							min: 0,
							title: {
								text: 'Intensité précips. (mm)',
								style: {
									"color": "#6883d9",
								},
							},
							labels:{
								style: {
									"color": "#6883d9",
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
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Précipitations',
							type: 'column',
							zIndex: 1,
							data: [<?php echo join($dataRR, ',') ?>],
							// boostThreshold: 20,
							color: '#4169e1',
							tooltip: {
								valueSuffix: ' mm',
							}
						},{
							name: 'Cumul <?php echo $textPeriod; ?>',
							yAxis:1,
							type: 'line',
							zIndex: 3,
							data: [<?php echo join($dataRRCumul, ',') ?>],
							// boostThreshold: 20,
							color: '#3d4147',
							tooltip: {
								valueSuffix: ' mm',
							}
						},{
							name: 'Intensité',
							yAxis:2,
							visible: false,
							type: 'line',
							zIndex: 2,
							color: '#6883d9',
							data: [<?php echo join($dataRRate, ',') ?>],
							// boostThreshold: 20,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									if (this.y != 0) {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm/h</b><br>';
									} else {
										return '';
									}
								}
							}
						}],
						annotations: LabelsPersoRRClim
					});

					<?php if ($presence_uv) : ?>
					/*
						START GRAPH UV
					*/
					var uv = Highcharts.chart ('graph_uv', {
						chart: {
							type : 'area',
							zoomType: 'x',
							panning: true,
							panKey: 'shift'
						},
						title: {
							text: 'Indice UV <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> UV',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
							crosshair:true,
							lineColor: '#ff7200',
							lineWidth: 1,
							min:0,
							title: {
								text: 'Indice UV',
								style: {
									"color": "#ff7200",
								},
							},
							labels:{
								style: {
									"color": "#ff7200",
								},
							},
						},
						tooltip: {
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Indice UV',
							type: 'area',
							data: [<?php echo join($dataUV, ',') ?>],
							// boostThreshold: 20,
							connectNulls: false,
							color: '#ff7200',
						},{
							name: 'Indice UV min/max',
							type: 'errorbar',
							// yAxis: 0,
							color: '#ff7200',
							data: [<?php echo join($dataUvMinMax, ',') ?>],
							// boostThreshold: 20,
							tooltip: {
								pointFormat: 'Indice UV min/max sur l\'intvl: {point.low} - {point.high})<br/>'
							},
							zIndex: 10,
							showInLegend: true,
							visible: false
						}]
					});
					<?php endif; ?>

					<?php if ($presence_radiation) : ?>
					/*
						START GRAPH RADIATION
					*/
					var rad = Highcharts.chart ('graph_rad', {
						chart: {
							type : 'area',
							zoomType: 'x',
						},
						title: {
							text: 'Rayonnement solaire <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> Rayonnement solaire',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
							crosshair:true,
							lineColor: '#e5d42b',
							lineWidth: 1,
							min:0,
							title: {
								text: 'Rayonnement solaire (W/m²)',
								style: {
									"color": "#e5d42b",
								},
							},
							labels:{
								style: {
									"color": "#e5d42b",
								},
							},
						},
						tooltip: {
							shared: true,
							valueDecimals: 0,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							valueSuffix: ' W/m²',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Rayonnement solaire',
							type: 'area',
							data: [<?php echo join($dataRadiation, ',') ?>],
							// boostThreshold: 20,
							color: '#e5d42b',
						},{
							name: 'Rayonnement sol. min/max',
							type: 'errorbar',
							// yAxis: 0,
							color: '#e5d42b',
							data: [<?php echo join($dataRadiationMinMax, ',') ?>],
							// boostThreshold: 20,
							tooltip: {
								pointFormat: 'Rad. min/max sur l\'intvl: {point.low} - {point.high})<br/>'
							},
							zIndex: 10,
							showInLegend: true,
							visible: false
						}]
					});
					/*
						START GRAPH ET
					*/
					var et = Highcharts.chart ('graph_et', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Évapotranspiration <?php echo $textPeriod; ?> UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> Évapotranspiration',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
							crosshair:true,
							lineColor: '#e5d42b',
							lineWidth: 1,
							min:0,
							title: {
								text: 'Évapotranspiration (mm/h)',
								style: {
									"color": "#e5d42b",
								},
							},
							labels:{
								style: {
									"color": "#e5d42b",
								},
							},
						},
						tooltip: {
							shared: true,
							valueDecimals: 3,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Évapotranspiration',
							type: 'column',
							data: [<?php echo join($dataET, ',') ?>],
							color: '#e5d42b',
							pointPadding: 0,
							groupPadding: 0,
							borderWidth: 0,
							shadow: false,
							borderWidth: 10,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									if (this.y != 0) {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm/heure</b><br>';
									} else {
										return '';
									}
								}
							}
						}]
					});
					<?php endif; ?>
					/*
						FIN DES GRAPHS
					*/
					$("#removeAnnoTnTx").click(function() {
						var l = temperature.annotations.length;
						for(var i = l-1; i >= 0; i-=1) {
							temperature.removeAnnotation(temperature.annotations[i]);
						}
						// temperature.addAnnotation(LabelsPersoT);
						// temperature.update({
						// 	series: temperature.series,
						// });
					});
					$("#removeAnnoRR").click(function() {
						var l = precip.annotations.length;
						for(var i = l-1; i >= 0; i-=1) {
							precip.removeAnnotation(precip.annotations[i]);
						}
					});
				});
				
			</script>
			<!--
				FIN SCRIPT HIGHCHARTS
			-->
		<?php endif; ?>

		<?php if ($graphType == 'heatmap') : ?>
			<!-- Texte -->
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Cartes pour l'année <?php echo $period;?></h3>
					<p class="text-justify">
						Cette représentation est une synthèse horaire de l'année, permettant par exemple de se rendre compte en un coup d'œil des grandes périodes de froid, de chaud, ou de pluie intense.
					</p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="heatMapTempHourly" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="heatMapHrHourly" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="heatMapTdHourly" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="heatMapRainHourly" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr>
			<script>
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
						}
					});
					var heatMapTempHourly = Highcharts.chart('heatMapTempHourly', {
						chart: {
							type : 'heatmap',
							zoomType: 'xy',
						},
						title: {
							text: 'Température horaire <?php echo $period; ?>',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Heures UTC',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_TemperatureHoraire_<?php echo $period; ?>',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						boost: {
							useGPUTranslations: true
						},
						xAxis: {
							type: 'datetime',
							min: Date.UTC(<?php echo $period; ?>, 0, 1),
							max: Date.UTC(<?php echo $period; ?>, 11, 31, 23, 59, 59),
							labels: {
								align: 'left',
								x: 5,
								y: 14,
								format: '{value:%B}' // long month
							},
							showLastLabel: false,
							tickLength: 16
						},
						yAxis: {
							title: {
								text: null
							},
							labels: {
								format: '{value}h'
							},
							minPadding: 0,
							maxPadding: 0,
							startOnTick: false,
							endOnTick: false,
							tickPositions: [0, 6, 12, 18, 24],
							tickWidth: 1,
							min: 0,
							max: 23,
							reversed: true
						},
						colorAxis: {
							stops: [
								[0, '#6d30cf'], //-10
								[0.1, '#3060cf'], //-5
								[0.2, '#c7e9ad'],//-0
								[0.3, '#ffffbf'],//+0
								[0.7, '#fdae61'],
								[0.8, '#f70707'],
								[0.9, '#ff2491'],//+40
								[1, '#ed1fa8']//+40
							],
							min: -10,
							max: 40,
							startOnTick: false,
							endOnTick: false,
							labels: {
								format: '{value} °C'
							}
						},
						series: [{
							data: [<?php echo join($dataTempHourly, ',') ?>],
							boostThreshold: 100,
							borderWidth: 0,
							nullColor: '#ffffff',
							colsize: 24 * 36e5, // one day
							tooltip: {
								headerFormat: 'Température<br/>',
								pointFormat: '{point.x:%e %b %Y} {point.y}:00: <b>{point.value} °C</b>'
							},
						}]
					});

					var heatMapHrHourly = Highcharts.chart('heatMapHrHourly', {
						chart: {
							type : 'heatmap',
							zoomType: 'xy',
						},
						title: {
							text: 'Humidité horaire <?php echo $period; ?>',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Heures UTC',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_HumiditeHoraire_<?php echo $period; ?>',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						boost: {
							useGPUTranslations: true
						},
						xAxis: {
							type: 'datetime',
							min: Date.UTC(<?php echo $period; ?>, 0, 1),
							max: Date.UTC(<?php echo $period; ?>, 11, 31, 23, 59, 59),
							labels: {
								align: 'left',
								x: 5,
								y: 14,
								format: '{value:%B}' // long month
							},
							showLastLabel: false,
							tickLength: 16
						},
						yAxis: {
							title: {
								text: null
							},
							labels: {
								format: '{value}h'
							},
							minPadding: 0,
							maxPadding: 0,
							startOnTick: false,
							endOnTick: false,
							tickPositions: [0, 6, 12, 18, 24],
							tickWidth: 1,
							min: 0,
							max: 23,
							reversed: true
						},
						colorAxis: {
							stops: [
								[0, '#ffffbf'],
								[0.4, '#bae7ff'],
								[0.6, '#82d4ff'],
								[0.9, '#3060cf'],
								[1, '#e14aff']
							],
							min: 0,
							max: 100,
							startOnTick: false,
							endOnTick: false,
							labels: {
								format: '{value} %'
							}
						},
						series: [{
							data: [<?php echo join($dataHumidityHourly, ',') ?>],
							boostThreshold: 100,
							borderWidth: 0,
							nullColor: '#ffffff',
							colsize: 24 * 36e5, // one day
							tooltip: {
								headerFormat: 'Humidité<br/>',
								pointFormat: '{point.x:%e %b %Y} {point.y}:00: <b>{point.value} %</b>'
							},
						}]
					});

					var heatMapTdHourly = Highcharts.chart('heatMapTdHourly', {
						chart: {
							type : 'heatmap',
							zoomType: 'xy',
						},
						title: {
							text: 'Point de rosée horaire <?php echo $period; ?>',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Heures UTC',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_Point-de-rosee_Horaire_<?php echo $period; ?>',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						boost: {
							useGPUTranslations: true
						},
						xAxis: {
							type: 'datetime',
							min: Date.UTC(<?php echo $period; ?>, 0, 1),
							max: Date.UTC(<?php echo $period; ?>, 11, 31, 23, 59, 59),
							labels: {
								align: 'left',
								x: 5,
								y: 14,
								format: '{value:%B}' // long month
							},
							showLastLabel: false,
							tickLength: 16
						},
						yAxis: {
							title: {
								text: null
							},
							labels: {
								format: '{value}h'
							},
							minPadding: 0,
							maxPadding: 0,
							startOnTick: false,
							endOnTick: false,
							tickPositions: [0, 6, 12, 18, 24],
							tickWidth: 1,
							min: 0,
							max: 23,
							reversed: true
						},
						colorAxis: {
							stops: [
								[0.05, '#e14aff'], // violet
								[0.15, '#e886f7'], // rose
								[0.25, '#372bba'], // bleu marine
								[0.45, '#42a4ff'], // bleu clair
								[0.55, '#34e36c'], // vert
								[0.75, '#e5fc30'], // jaune moyen
								[0.85, '#fcae30'], // orange
								[0.9,  '#fc4f30'], // rouge
								[1,    '#ff0000'] // rouge foncé
							],
							min: -20,
							max: 30,
							startOnTick: false,
							endOnTick: false,
							labels: {
								format: '{value} °C'
							}
						},
						series: [{
							data: [<?php echo join($dataDewPointHourly, ',') ?>],
							boostThreshold: 100,
							borderWidth: 0,
							nullColor: '#ffffff',
							colsize: 24 * 36e5, // one day
							tooltip: {
								headerFormat: 'Point de rosée<br/>',
								pointFormat: '{point.x:%e %b %Y} {point.y}:00: <b>{point.value} °C</b>'
							},
						}]
					});

					var heatMapRainHourly = Highcharts.chart('heatMapRainHourly', {
						chart: {
							type : 'heatmap',
							zoomType: 'xy',
						},
						title: {
							text: 'Précipitations horaire <?php echo $period; ?>',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Heures UTC',
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?>_PrecipsCumulHoraires_<?php echo $period; ?>',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						boost: {
							useGPUTranslations: true
						},
						xAxis: {
							type: 'datetime',
							min: Date.UTC(<?php echo $period; ?>, 0, 1),
							max: Date.UTC(<?php echo $period; ?>, 11, 31, 23, 59, 59),
							labels: {
								align: 'left',
								x: 5,
								y: 14,
								format: '{value:%B}' // long month
							},
							showLastLabel: false,
							tickLength: 16
						},
						yAxis: {
							title: {
								text: null
							},
							labels: {
								format: '{value}h'
							},
							minPadding: 0,
							maxPadding: 0,
							startOnTick: false,
							endOnTick: false,
							tickPositions: [0, 6, 12, 18, 24],
							tickWidth: 1,
							min: 0,
							max: 23,
							reversed: true
						},
						colorAxis: {
							stops: [
								[0, '#d9d9d9'],
								[0.01, '#bbdcfc'],
								[0.25, '#73baff'],
								[0.5, '#3346ff'],
								[0.75, '#8f33ff'],
								[1, '#af28c9']
							],
							min: 0,
							max: 30,
							startOnTick: false,
							endOnTick: false,
							labels: {
								format: '{value} mm'
							}
						},
						series: [{
							data: [<?php echo join($dataRrHourly, ',') ?>],
							boostThreshold: 100,
							borderWidth: 0,
							nullColor: '#ffffff',
							colsize: 24 * 36e5, // one day
							tooltip: {
								headerFormat: 'Précipitations<br/>',
								pointFormat: '{point.x:%e %b %Y} {point.y}:00: <b>{point.value} mm</b>'
							},
						}]
					});
				});
			</script>
		<?php endif; ?> <!-- FIN heatmap -->

			<footer class="footer bg-light">
				<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div>
	</body>
</html>
