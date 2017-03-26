<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Graph intérieur</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<link rel="canonical" href="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<?php include 'config/favicon.php';?>
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
		eval('var time_48h = <?php include 'json/indoor/time_48h.json' ?>');
		eval('var data_intemp_48h = <?php include 'json/indoor/intemp_48h.json' ?>');
		eval('var data_inhygro_48h = <?php include 'json/indoor/inhygro_48h.json' ?>');

		eval('var time_7j = <?php include 'json/indoor/time_7j.json' ?>');
		eval('var data_intemp_7j = <?php include 'json/indoor/intemp_7j.json' ?>');
		eval('var data_inhygro_7j = <?php include 'json/indoor/inhygro_7j.json' ?>');

		eval('var time_30j = <?php include 'json/indoor/time_30j.json' ?>');
		eval('var data_intemp_30j = <?php include 'json/indoor/intemp_30j.json' ?>');
		eval('var data_inhygro_30j = <?php include 'json/indoor/inhygro_30j.json' ?>');

		<?php require_once 'sql/req_graph_48h.php' ?>
		eval('var minuit = <?php echo $minuit; ?>');
		eval('var debut = <?php echo $debut; ?>');
		eval('var minuit_hier = <?php echo $minuit_hier; ?>');
/*
	FONCTION TIME
 */
		function comArr(unitsArray) {
			var outarr = [];
			for (var i = 0; i < time_48h.length; i++) {
				outarr[i] = [time_48h[i], unitsArray[i]];
			}
			return outarr;
		}

		function comArr_7j(unitsArray) {
			var outarr = [];
			for (var i = 0; i < time_7j.length; i++) {
				outarr[i] = [time_7j[i], unitsArray[i]];
			}
			return outarr;
		}

		function comArr_30j(unitsArray) {
			var outarr = [];
			for (var i = 0; i < time_30j.length; i++) {
				outarr[i] = [time_30j[i], unitsArray[i]];
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
	START GRAPH TEMP/HYGRO 48H
 */
				var temperature = Highcharts.chart ('graph_temp_hygro', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Température et humidité intérieur des dernières 48 heures',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?>',
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
								}[this.point.series.name];
								s = s + '<br>' + '<span style="color:'+ point.series.color +'">' + point.series.name + '</span> : <b>'+Highcharts.numberFormat(point.y,1,","," ") + unit + '</b>';
							});
						return s;
						},
					},
					series: [{
						name: 'Température',
						type: 'spline',
						data: comArr(data_intemp_48h),
						connectNulls: true,
						zIndex: 1,
						color: '#ff0000',
						negativeColor:'#0d1cc5',
					},{
						name: 'Humidité',
						type: 'area',
						data: comArr(data_inhygro_48h),
						yAxis: 1,
						connectNulls: true,
						color: '#3399FF',
					}]
				});
/*
	FIN DU GRAPH 48H
 */

/*
	START GRAPH TEMP/HYGRO 7J
 */
				var temperature = Highcharts.chart ('graph_temp_hygro_7j', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Température et humidité intérieur des 7 derniers jours',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?>',
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
								}[this.point.series.name];
								s = s + '<br>' + '<span style="color:'+ point.series.color +'">' + point.series.name + '</span> : <b>'+Highcharts.numberFormat(point.y,1,","," ") + unit + '</b>';
							});
						return s;
						},
					},
					series: [{
						name: 'Température',
						type: 'spline',
						data: comArr_7j(data_intemp_7j),
						connectNulls: true,
						zIndex: 1,
						color: '#ff0000',
						negativeColor:'#0d1cc5',
					},{
						name: 'Humidité',
						type: 'area',
						data: comArr_7j(data_inhygro_7j),
						yAxis: 1,
						connectNulls: true,
						color: '#3399FF',
					}]
				});
/*
	FIN DU GRAPH 7J
 */
/*
	START GRAPH TEMP/HYGRO 30J
 */
				var temperature = Highcharts.chart ('graph_temp_hygro_30j', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Température et humidité intérieur des 30 derniers jours',
						//x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?>',
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
								}[this.point.series.name];
								s = s + '<br>' + '<span style="color:'+ point.series.color +'">' + point.series.name + '</span> : <b>'+Highcharts.numberFormat(point.y,1,","," ") + unit + '</b>';
							});
						return s;
						},
					},
					series: [{
						name: 'Température',
						type: 'spline',
						data: comArr_30j(data_intemp_30j),
						connectNulls: true,
						zIndex: 1,
						color: '#ff0000',
						negativeColor:'#0d1cc5',
					},{
						name: 'Humidité',
						type: 'area',
						data: comArr_30j(data_inhygro_30j),
						yAxis: 1,
						connectNulls: true,
						color: '#3399FF',
					}]
				});
/*
	FIN DES GRAPHS
 */
			});
		</script>
<!--
	FIN SCRIPT HIGHCHARTS
-->
		<!-- DEBUT DU SCRIPT PHP -->
		<!-- Va permettre de récupérer les dernières valeurs en BDD -->
		<?php require("sql/req_tableau_interieur.php");?>
		<!-- FIN DU SCRIPT PHP -->

		<!-- DEBUT DU CORPS DE PAGE -->
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Données des sondes intérieures </h3>
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
				<table class="table table-striped table-bordered table-responsive table-hover">
					<thead>
						<tr>
						<th>Paramètres</th>
						<th>Valeur actuelle</th>
						<th class="text-info">Mini du jour</th>
						<th class="text-danger">Maxi du jour</th>
						</tr>
					</thead>
					<tbody>
						<td>Température intérieure</td>
						<td><?php echo $inTemp; ?> °C</td>
						<td><?php echo $mintemp; ?> °C à <?php echo $mintemptime; ?></td>
						<td><?php echo $maxtemp; ?> °C à <?php echo $maxtemptime; ?></td>
					</tbody>
					<tbody>
						<td>Hygrométrie intérieure</td>
						<td><?php echo $inHumidity; ?> %</td>
						<td><?php echo $minhygro; ?> % à <?php echo $minhygrotime; ?></td>
						<td><?php echo $maxhygro; ?> % à <?php echo $maxhygrotime; ?></td>
					</tbody>
				</table>
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
				<div id="graph_temp_hygro_7j" style="width:100%; height: 400px;"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_temp_hygro_30j" style="width:100%; height: 400px;"></div>
			</div>
		</div>

	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
