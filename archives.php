<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Archives</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css" rel="stylesheet">
		<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
		<script src="https://code.highcharts.com/stock/highstock.src.js"></script>
		<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
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
<script type="text/javascript">
/*
	FONCTION JSONP
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
	var seriesOptions = [],
		yAxisOptions = [],
		// Démarrage du compteur
		seriesCounter = 0,
		// Déclaration des variables que l'on va charger
		names = ['Temperature', 'Humidite', 'Rosee'];
		// Et des options qu'on va leur attribuer (dans l'ordre !)
		types = ['spline', 'area', 'spline']; // add types
		visibles = [true, true, false];
		colors = ['#ff0000', '#3399FF', '#1c23e4'];
		negativeColors = ['#0d1cc5', '', ''];
		zIndexs = [1];
		yAxiss = [0,1,0];
/*
	FONCTION EACH
 */
	$.each(names, function(i, name) {
		$.getJSON('json/archives/jsonp.php?filename='+ name.toLowerCase() +'.json&callback=?', function (data) {
			seriesOptions[i] = {
				name: name,
				data: data,
				type: types[i],
				visible: visibles[i],
				color: colors[i],
				negativeColor: negativeColors[i],
				zIndex: zIndexs[i],
				yAxis: yAxiss[i],
				tooltip: {
					valueDecimals: 1
				},
				connectNulls: true,
			};
			// Indentation du compteur
			seriesCounter+= 1;
			if (seriesCounter === names.length) {
				createChart();
			}
		});
	});
/*
	CREATION DU GRAPH UNE FOIS LES DONNEES CHARGEES
 */
	function createChart() {
		// Create the chart
		Highcharts.stockChart('temperature', {
			chart: {
				zoomType: 'x'
			},
			rangeSelector: {
				buttons: [{
					type: 'day',
					count: 1,
					text: '1j'
				},{
					type: 'week',
					count: 1,
					text: '1w'
				},{
					type: 'month',
					count: 1,
					text: '1m'
				},{
					type: 'month',
					count: 3,
					text: '3m'
				},{
					type: 'year',
					count: 1,
					text: '1y'
				},{
					type: 'all',
					text: 'All'
				}],
				selected : 2,
			},
			title: {
				text: 'Historique des températures et humidités'
			},
			subtitle: {
				text: 'Station <?php echo $station_name; ?>',
			},
			credits: {
				text: '<?php echo $name_manager_graph; ?>',
				href: '<?php echo $site_manager_graph; ?>'
			},
			exporting: {
				filename: '<?php echo $short_station_name; ?> Temperature',
			},
			legend: {
				enabled: true,
			},
			yAxis: [{
				// Axe 0
				opposite: false,
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
			series: seriesOptions,
		});
	};
});
</script>

		<!-- DEBUT DU CORPS DE PAGE -->
		<div class="row">
			<div class="col-md-12" align="center">
				<h3>Archives de la station</h3>
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
				<p>Vous trouverez sur cette page l'historique complet des données archivées de la station. Il est préférable de consulter cette page depuis un ordinateur de bureau.<br>Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la soucis ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende.</p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" align="center">
				<div id="temperature" style="height: 400px"></div>
			</div>
		</div>



	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
