<?php require_once 'config.php';?>
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
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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

		function comArr(unitsArray) {
			var outarr = [];
			for (var i = 0; i < time.length; i++) {
				outarr[i] = [time[i], unitsArray[i]];
			}
			return outarr;
		}




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
	START GRAPH
 */
				var temperature = Highcharts.chart ('graph_temp', {
					chart: {
						type : 'line',
						zoomType: 'x',
						//alignTicks: false,
					},
					title: {
						text: 'Température et humidité des dernières 48 heures',
						x: -20 //center
					},
					subtitle: {
						text: 'Station <?php echo $station_name; ?>',
						x: -20
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					xAxis: {
						type: 'datetime',
						//categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'Température (°C)'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#FF0000'
						}]
					},
					tooltip: {
						valueSuffix: '°C'
					},
					series: [{
						name: 'Température',
						type: 'spline',
						data: comArr(data_outTemp)
					}]
				});
			});








		</script>

		<!--
			FIN SCRIPT HIGHCHARTS
		-->







		<!-- DEBUT DU CORPS DE PAGE -->
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="graph_temp" style="width:100%; height:400px;"></div>
			</div>
		</div>



	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
	<link href="vendors/custom/custom.css" rel="stylesheet">
	<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
