<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Graph 48 heures</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!--<link href="vendors/custom/charts.css" rel="stylesheet">-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="https://code.highcharts.com/css/highcharts.css" rel="stylesheet">
		<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css" rel="stylesheet">
		<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<!-- <script src="https://code.highcharts.com/js/highcharts-more.js"></script> -->
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
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
	<!--
		DEBUT SCRIPT HIGHCHARTS
	-->
		<script>
		eval('var time = <?php include 'json/time_48h.json' ?>');
		eval('var data_outTemp = <?php include 'json/temperature_48h.json' ?>');
		eval('var data_hygro = <?php include 'json/hygrometrie_48h.json' ?>');
		eval('var data_rosee = <?php include 'json/rosee_48h.json' ?>');
		eval('var data_pression = <?php include 'json/pression_48h.json' ?>');
		eval('var data_vent = <?php include 'json/vent_48h.json' ?>');
		eval('var data_rafales = <?php include 'json/rafales_48h.json' ?>');
		eval('var data_dir_vent = <?php include 'json/dir_vent_48h.json' ?>');
		eval('var data_precip = <?php include 'json/precipitations_48h.json' ?>');
		<?php if ($presence_uv == true) : ?>
			eval('var data_uv = <?php include 'json/uv_48h.json' ?>');
		<?php endif; ?>
		<?php if ($presence_radiation == true) : ?>
			eval('var data_rad = <?php include 'json/radiation_48h.json' ?>');
			eval('var data_et = <?php include 'json/et_48h.json' ?>');
		<?php endif; ?>

		<?php require_once 'sql/req_graph_48h.php' ?>
		eval('var minuit = <?php echo $minuit; ?>');
		eval('var debut = <?php echo $debut; ?>');
		eval('var minuit_hier = <?php echo $minuit_hier; ?>');
/*
	FONCTION TIME
 */
		function comArr(unitsArray) {
			var outarr = [];
			for (var i = 0; i < time.length; i++) {
				outarr[i] = [time[i], unitsArray[i]];
			}
			return outarr;
		}
/*
	DEBUT GRAPHS
 */
			$(function () {
				Highcharts.setOptions({
					global: {
						useUTC: false
					},
					lang: {
						months: ["Janvier "," Février "," Mars "," Avril "," Mai "," Juin "," Juillet "," Août "," Septembre "," Octobre "," Novembre "," Décembre"],
						weekdays: ["Dim "," Lun "," Mar "," Mer "," Jeu "," Ven "," Sam"],
						shortMonths: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil','Août', 'Sept', 'Oct', 'Nov', 'Déc'],
						contextButtonTitle: "Menu",
						decimalPoint: ',',
						resetZoom: 'Reset zoom',
						resetZoomTitle: 'Reset zoom à 1:1',
						downloadPNG: "Télécharger au format PNG image",
						downloadJPEG: "Télécharger au format JPEG image",
						downloadPDF: "Télécharger au format PDF document",
						downloadSVG: "Télécharger au format SVG vector image",
						printChart: "Imprimer le graphique",
						loading: "Chargement..."
					}
				});
/*
	START GRAPH TEMP/HYGRO
 */
				var temperature = Highcharts.chart ('graph_temp_hygro', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Température et humidité des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Temperature',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: [{
						// Axe 0
						//className: 'highcharts-color-0',
						//crosshair:true,
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
						//className: 'highcharts-color-1',
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
						//valueSuffix: '°C',
						valueDecimals: 1,
						formatter: function() {
							var s = '<b>'+ Highcharts.dateFormat('%e %B à %H:%M', this.x) +'</b>';
							$.each(this.points, function(i, point) {
								var unit = {
									'Température': ' °C',
									'Humidité': ' %',
									'Point de rosée': ' °C',
								}[this.point.series.name];
								s = s + '<br>' + '<span style="color:'+ point.series.color +'">' + point.series.name + '</span> : <b>'+Highcharts.numberFormat(point.y,1,","," ") + unit + '</b>';
							});
						return s;
						},
					},
					series: [{
						name: 'Température',
						type: 'spline',
						data: comArr(data_outTemp),
						connectNulls: true,
						zIndex: 1,
						color: '#ff0000',
						negativeColor:'#0d1cc5',
					},{
						name: 'Humidité',
						type: 'area',
						data: comArr(data_hygro),
						yAxis: 1,
						connectNulls: true,
						color: '#3399FF',
					},{
						name: 'Point de rosée',
						type: 'spline',
						data: comArr(data_rosee),
						connectNulls: true,
						color: '#1c23e4',
						visible: false,
					}]
				});
/*
	START GRAPH pression
 */
				var pression = Highcharts.chart ('graph_pression', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Pression atmo. des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Pression',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: {
						// Axe 0
						//className: 'highcharts-color-0',
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
						//shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} :</span> <b>{point.y}</b><br/>',
						valueDecimals: 1,
						valueSuffix: ' hPa',
					},
					series: [{
						name: 'Pression',
						type: 'spline',
						data: comArr(data_pression),
						connectNulls: true,
						color: '#1be300',
						//zIndex: 1,
					}]
				});
/*
	START GRAPH VENT
 */
				var vent = Highcharts.chart ('graph_vent', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Vent des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Vent',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: [{
						// Axe 0
						//className: 'highcharts-color-0',
						//crosshair:true,
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
						categories: ['Nord','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','N-E','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','Est','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','S-E','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','Sud','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','S-O','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','Ouest','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','N-O','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''],
							endOnTick: true,
							tickInterval:45,
							minorTickInterval:45,
							title: {
							text: 'Direction moyenne',
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
						formatter: function() {
							var s = '<b>'+ Highcharts.dateFormat('%e %B à %H:%M', this.x) +'</b>';
							$.each(this.points, function(i, point) {
								var unit = {
									'Vent moyen': ' km/h',
									'Rafales': ' km/h',
									'Direction moyenne': '',
								}[this.point.series.name];
								s = s + '<br>' + '<span style="color:'+ point.series.color +'">' + point.series.name + '</span> : <b>'+Highcharts.numberFormat(point.y,1,","," ") + unit + '</b>';
							});
						return s;
						},
					},
					plotOptions: {
						series: {
							marker: {
								enabled: false
							}
						}
					},
					series: [{
						name: 'Vent moyen',
						type: 'area',
						lineColor: 'rgba(51,153,255,0.75)',
						fillColor: 'rgba(51,153,255,0.5)',
						//lineWidth: 0.5,
						//fillOpacity:0.2,
						data: comArr(data_vent),
						connectNulls: true,
						//zIndex: 1,
					},{
						name: 'Rafales',
						type: 'spline',
						color: 'rgba(255,0,0,0.65)',
						//lineWidth: '0.2',
						data: comArr(data_rafales),
						connectNulls: true,
						//zIndex: 2,
					},{
						name: 'Direction moyenne',
						type: 'scatter',
						data: comArr(data_dir_vent),
						connectNulls: true,
						zIndex: 1,
						yAxis: 1,
						color:'rgba(148,0,211,0.75)',
						marker: {
							symbol: 'circle',
							enabled: true,
							lineWidth: 0,
							radius:2,
							color:'rgba(148,0,211,0.75)',
							//fillColor: '#9400d3',
						},
						visible:false,
					}]
				});
/*
	START GRAPH precip
 */
				var precip = Highcharts.chart ('graph_precip', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Précipitations des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Precipitations',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: {
						// Axe 0
						//className: 'highcharts-color-0',
						crosshair:true,
						lineColor: '#4169e1',
						lineWidth: 1,
						min:0,
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
					},
					tooltip: {
						//shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} :</span> <b>{point.y}</b><br/>',
						valueDecimals: 1,
						valueSuffix: ' mm',
					},
					series: [{
						name: 'Précipitations',
						type: 'spline',
						data: comArr(data_precip),
						connectNulls: true,
						color: '#4169e1',
						//zIndex: 1,
					}]
				});
<?php if ($presence_uv == true) : ?>
/*
	START GRAPH UV
 */
				var uv = Highcharts.chart ('graph_uv', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Indice UV des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> UV',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: {
						// Axe 0
						//className: 'highcharts-color-0',
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
						//shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} :</span> <b>{point.y}</b><br/>',
						valueDecimals: 1,
						//valueSuffix: '',
					},
					series: [{
						name: 'Indice UV',
						type: 'area',
						data: comArr(data_uv),
						connectNulls: true,
						color: '#ff7200',
						//zIndex: 1,
					}]
				});
<?php endif; ?>

<?php if ($presence_radiation == true) : ?>
/*
	START GRAPH RADIATION
 */
				var rad = Highcharts.chart ('graph_rad', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Rayonnement solaire des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Rayonnement solaire',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: {
						// Axe 0
						//className: 'highcharts-color-0',
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
						//shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} :</span> <b>{point.y}</b><br/>',
						valueDecimals: 1,
						valueSuffix: ' W/m²',
					},
					series: [{
						name: 'Rayonnement solaire',
						type: 'area',
						data: comArr(data_rad),
						connectNulls: true,
						color: '#e5d42b',
						//zIndex: 1,
					}]
				});
/*
	START GRAPH ET
 */
				var et = Highcharts.chart ('graph_et', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Évapotranspiration des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						//x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> Évapotranspiration',
						//scale: 2,
					},
					xAxis: [{
						type: 'datetime',
						dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
						tickInterval: 7200*1000,
						crosshair: true,
						plotBands: [{
							color: '#e0ffff',
							from: minuit_hier,
							to: minuit,
						}],
						plotLines: [{
							value: minuit,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							label: {
								text: 'minuit',
								align: 'right',
								style:{font: 'bold 10px sans-serif', color: 'black'},
								rotation: -90,
								y: 10,
								x: 12,
							}
						},{
							value: minuit_hier,
							dashStyle: 'ShortDash',
							width: 2,
							color: 'red',
							zIndex: 1,
							label: {
								text: 'minuit',
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
					yAxis: {
						// Axe 0
						//className: 'highcharts-color-0',
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
						//shared: true,
						pointFormat: '<span style="color:{series.color}">{series.name} :</span> <b>{point.y}</b><br/>',
						valueDecimals: 3,
						valueSuffix: ' mm/heure',
					},
					series: [{
						name: 'Évapotranspiration',
						type: 'column',
						data: comArr(data_et),
						//connectNulls: true,
						color: '#e5d42b',
						//zIndex: 1,
						pointPadding: 0,
						groupPadding: 0,
						borderWidth: 0,
						shadow: false,
						borderWidth: 10,
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

		<!-- DEBUT DU CORPS DE PAGE -->
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Graphiques des 48 dernières heures</h3>
				<h4 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h4>
				<?php if ($diff>$offline_time) : ?>
					<h4 class="offline_station">Station actuellement hors ligne depuis
						<?php echo $heures; ?> h et <?php echo $minutes; ?> min
					</h4>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<p>Vous trouverez sur cette page les relevés de la station sur les 48 dernières heures sous forme de graphiques. Ils sont mis à jour toutes les 5 minutes.<br>Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la souris ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende. Ils sont également exportables en cliquant sur le bouton au-dessus à droite de chaque graphique.</p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_temp_hygro" style="width:100%; height: 400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_pression" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_vent" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_precip" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php if ($presence_uv == true) : ?>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_uv" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php endif; ?>
		<?php if ($presence_radiation == true) : ?>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_rad" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_et" style="width:100%; height:400px;"></div>
			</div>
		</div>
		<?php endif; ?>

	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
