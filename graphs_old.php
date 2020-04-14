<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<?php
	// CONF STATION
	// Récup du nombre d'heures en paramètres
	// On construit une liste blanche
	$valid_options = array();
	$valid_options[] = '24';
	$valid_options[] = '48';
	$valid_options[] = '72';
	$valid_options[] = '96';
	$valid_options[] = '120';
	$valid_options[] = '144';
	$valid_options[] = '168';

	// On vérifie que le paramètre existe, et est défini sinon on renvoi 48
	if (isset($_GET['last']) || !empty($_GET['last'])) {
		// On vérifie que le parmètre est dans la liste blanche sinon on renvoi 48
		if (in_array($_GET['last'], $valid_options)) {
			$last = $_GET['last'];
		} else {
			$last = '24';
		}
	} else {
		$last = '24';
	}

	require_once 'sql/req_graphs.php'

?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Graph <?php echo $last; ?> heures</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Graphiques des <?php echo $last; ?> dernières heures de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Graph <?php echo $last; ?> heures" />
		<meta property="og:description" content="Graphiques des <?php echo $last; ?> dernières heures de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Graphiques des <?php echo $last; ?> dernières heures de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Graph <?php echo $last; ?> heures" />
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="https://code.highcharts.com/css/highcharts.css" rel="stylesheet">
		<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css?v=1.1" rel="stylesheet">
		<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<!-- <script src="https://code.highcharts.com/js/highcharts-more.js"></script> -->
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>
		<!-- <script src="vendors/custom/highcharts_export-csv.js"></script> -->
		<script src="https://code.highcharts.com/modules/annotations.js"></script>
		<script src="https://code.highcharts.com/modules/boost.js"></script>
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
		<?php if ($banniere_info_active) : ?>
			<div class="alert alert-dismissible alert-<?php echo $banniere_info_type; ?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<h4><?php echo $banniere_info_titre; ?></h4>
				<p><?php echo $banniere_info_message; ?></p>
			</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-12 divCenter">
				<h4 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h4>
				<?php if ($diff>$offline_time) : ?>
					<h4 class="offline_station">Station actuellement hors ligne depuis
						<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
					</h4>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 divCenter">
				<h1>Graphiques des <?php echo $last; ?> dernières heures</h1>
				<p>Vous trouverez sur cette page les relevés de la station sur les <?php echo $last; ?> dernières heures sous forme de graphiques. Ils sont mis à jour instantanément dès qu'un enregistrement est envoyé par la station météo.<br>Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la souris ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende. Ils sont également exportables en cliquant sur le bouton au-dessus à droite de chaque graphique.</p>
				<p><b>Attention, les graphiques sont en heure UTC, donc il faut rajouter une heure l'hiver et deux heures l'été !<br>Exemple : il est actuellement <?php date_default_timezone_set('UTC'); echo date('H:i'); ?> UTC, et donc <?php date_default_timezone_set('Europe/Paris'); echo date('H:i'); ?> en France</b></p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<form action="graphs.php" method="get">
				<p><b>Visualiser les graphiques sur les dernières :</b>
					<select name='last' onchange='if(this.value != <?php echo $last; ?>) { this.form.submit(); }'>
						<option value="24" <?php if ($last == '24') { echo "selected";} ?>>24 heures</option>
						<option value="48" <?php if ($last == '48') { echo "selected";} ?>>48 heures (2j)</option>
						<option value="72" <?php if ($last == '72') { echo "selected";} ?>>72 heures (3j)</option>
						<option value="96" <?php if ($last == '96') { echo "selected";} ?>>96 heures (4j)</option>
						<option value="120" <?php if ($last == '120') { echo "selected";} ?>>120 heures (5j)</option>
						<option value="144" <?php if ($last == '144') { echo "selected";} ?>>144 heures (6j)</option>
						<option value="168" <?php if ($last == '168') { echo "selected";} ?>>168 heures (7j)</option>
					</select>
				</p>
				</form>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_temp_hygro" style="width:100%; height: 400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_pression" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_vent" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_precip" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php if ($presence_uv) : ?>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_uv" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php endif; ?>
		<?php if ($presence_radiation) : ?>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_rad" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="graph_et" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php endif; ?>

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
			$(function () {
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
							width: 2,
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
							width: 2,
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
							width: 2,
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
							width: 2,
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
							width: 2,
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
							width: 2,
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
							width: 2,
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
					}]
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
						text: 'Température et humidité des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Tn et Tx aux normes OMM',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Temperature',
						sourceHeight: '400',
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
						type: 'line',
						data: [<?php echo join($dataTemp, ',') ?>],
						boostThreshold: 20,
						zIndex: 1,
						color: '#ff0000',
						negativeColor:'#0d1cc5',
						tooltip: {
							valueSuffix: ' °C',
						}
					},{
						name: 'Humidité',
						type: 'line',
						data: [<?php echo join($dataHr, ',') ?>],
						boostThreshold: 20,
						yAxis: 1,
						color: '#3399FF',
						tooltip: {
							valueSuffix: ' %',
						}
					},{
						name: 'Point de rosée',
						type: 'line',
						data: [<?php echo join($dataTd, ',') ?>],
						boostThreshold: 20,
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
						text: 'Pression atmo. des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Pression',
						sourceHeight: '400',
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
						boostThreshold: 20,
						connectNulls: false,
						color: '#1be300',
					}]
				});
				/*
					START GRAPH VENT
				*/
				var vent = Highcharts.chart ('graph_vent', {
					chart: {
						type : 'line',
						zoomType: 'x',
					},
					title: {
						text: 'Vent des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Vent',
						sourceHeight: '400',
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
						enabled: false,
					},
					series: [{
						name: 'Vent moyen',
						type: 'area',
						lineColor: 'rgba(51,153,255,0.75)',
						fillColor: 'rgba(51,153,255,0.5)',
						data: <?php echo $dataWs ?>,
						turboThreshold: 0,
						connectNulls: false,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								if (this.dir != null) {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Direction: <b>'+this.dir+' °</b><br>'+
									'----<br>';
								} else {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
									'----<br>';
								}
							}
						}
					},{
						name: 'Rafales',
						type: 'line',
						color: 'rgba(255,0,0,0.65)',
						data: <?php echo $dataWg ?>,
						turboThreshold: 0,
						connectNulls: false,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								if (this.dir != null) {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Direction: <b>'+this.dir+' °</b><br>';
								} else {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>';
								}
							}
						}
					},{
						name: 'Direction moyenne',
						type: 'scatter',
						data: <?php echo $dataWsD ?>,
						turboThreshold: 0,
						zIndex: 1,
						yAxis: 1,
						color:'rgba(0, 25, 211,0.75)',
						marker: {
							symbol: 'circle',
							enabled: true,
							lineWidth: 0,
							radius:2,
							color:'rgba(0, 25, 211,0.75)',
						},
						visible:false,
						enableMouseTracking: false,
					},{
						name: 'Direction des rafales',
						type: 'scatter',
						data: <?php echo $dataWgD ?>,
						turboThreshold: 0,
						zIndex: 1,
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
						text: 'Précipitations des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Cumul à 6h UTC aux normes OMM',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Precipitations',
						sourceHeight: '400',
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
							useGPUTranslations: false,
							seriesThreshold:1,
					},
					series: [{
						name: 'Précipitations',
						type: 'column',
						zIndex: 1,
						data: [<?php echo join($dataRR, ',') ?>],
						boostThreshold: 20,
						connectNulls: false,
						color: '#4169e1',
						tooltip: {
							valueSuffix: ' mm',
						}
					},{
						name: 'Cumul sur <?php echo $last; ?>h',
						yAxis:1,
						type: 'line',
						zIndex: 3,
						data: [<?php echo join($dataRRCumul, ',') ?>],
						boostThreshold: 20,
						connectNulls: false,
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
						boostThreshold: 20,
						connectNulls: true,
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
						text: 'Indice UV des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> UV',
						sourceHeight: '400',
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
						boostThreshold: 20,
						connectNulls: false,
						color: '#ff7200',
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
						text: 'Rayonnement solaire des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Rayonnement solaire',
						sourceHeight: '400',
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
						connectNulls: false,
						color: '#e5d42b',
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
						text: 'Évapotranspiration des dernières <?php echo $last; ?> heures UTC',
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Évapotranspiration',
						sourceHeight: '400',
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
			});
		</script>
		<!--
			FIN SCRIPT HIGHCHARTS
		-->

		

	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>