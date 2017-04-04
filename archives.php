<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Archives</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Archives de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Archives" />
		<meta property="og:description" content="Archives de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Archives de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Archives" />
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
		<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
		<link href="vendors/custom/custom.css" rel="stylesheet">
		<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
		<script src="https://code.highcharts.com/stock/highstock.src.js"></script>
		<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	</head>
	<body>
	<script type="text/javascript">
		$('#ajax-loading').hide();
	</script>
	<div class="container">
		<header>
			<?php include 'header.php';?>
		</header>
		<br>
		<nav>
			<?php include 'nav.php';?>
		</nav>
<!-- DEBUT DU CORPS DE PAGE -->
		<div class="row">
			<div class="col-md-12 divCenter">
				<h3>Archives de la station</h3>
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
				<p>Vous trouverez sur cette page l'historique complet des données archivées de la station.<br>Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la soucis ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende.<br><b>Il est préférable de consulter cette page depuis un ordinateur de bureau. Sur mobile le graphique pourrait ne pas apparaître</b></p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<div id="ajax-loading">
					<p>Le chargement des données est en cours et peut être relativement long.<br>Patientez quelques secondes...</p>
					<img src="img/loading.gif" alt="loading" />
				</div>
				<div id="container" style="height: 500px"></div>
			</div>
		</div>
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
		//ids = ['1','2','3','4','5','6,','7']
		names = ['Temperature', 'Humidite', 'Rosee', 'Pression', 'Vent', 'Rafales', 'Direction','Precipitations-Jour'];
		// Et des options qu'on va leur attribuer (dans l'ordre !)
		types = ['spline', 'area', 'spline', 'spline', 'spline', 'spline', 'scatter', 'column']; // add types
		visibles = [true, true, false, true, false, false, false, true];
		colors = ['#ff0000', '#3399FF', '#1c23e4', '#1be300', 'rgba(109, 128, 147, 0.75)', 'rgba(18, 21, 25, 0.75)', 'rgba(148,0,211,0.75)', '#4169e1'];
		negativeColors = ['#0d1cc5', '', '', '', '', '', '', ''];
		zIndexs = [1,,,,1,1,1];
		yAxiss = [0,1,0,2,3,3,4,5];
/*
	FONCTION EACH
 */
	$.each(names, function(i, name) {
		$('#ajax-loading').show();
		$.getJSON('json/archives/jsonp.php?filename='+ name.toLowerCase() +'.json&callback=?', function (data) {
			seriesOptions[i] = {
				//id: ids[i],
				name: name,
				data: data,
				type: types[i],
				visible: visibles[i],
				color: colors[i],
				negativeColor: negativeColors[i],
				zIndex: zIndexs[i],
				yAxis: yAxiss[i],
				tooltip: {
					valueDecimals: 1,
				},
				connectNulls: true,
			};
			// Indentation du compteur
			seriesCounter+= 1;
			if (seriesCounter === names.length) {
				$('#ajax-loading').hide();
				createChart();
			}
		});
	});
/*
	CREATION DU GRAPH UNE FOIS LES DONNEES CHARGEES
 */
	function createChart() {
		// Create the chart
		Highcharts.stockChart('container', {
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
				text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
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
			},{
				// Axe 2
				opposite: true,
				//min:0,
				//max: 100,
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
			},{
				// Axe 3
				opposite: false,
				//min:0,
				//max: 100,
				lineColor: 'rgb(109, 128, 147)',
				lineWidth: 1,
				title: {
					text: 'Vitesse vent (km/h)',
					style: {
						"color": "rgb(109, 128, 147)",
					},
				},
				labels:{
					style: {
						"color": "rgb(109, 128, 147)",
					},
				},
			},{
				// Axe 4
				opposite:false,
				reversed:true,
				max : 360,
				min: 0,
				lineColor: '#9400d3',
				lineWidth: 1,
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
			},{
				// Axe 5
				opposite:true,
				crosshair:true,
				lineColor: '#4169e1',
				lineWidth: 1,
				//min:0,
				title: {
					text: 'Précipitations Journ. (mm)',
					style: {
						"color": "#4169e1",
					},
				},
				labels:{
					style: {
						"color": "#4169e1",
					},
				},
			}],
			series: seriesOptions,
			plotOptions: {
				scatter: {
					marker: {
						symbol: 'circle',
						enabled: true,
						lineWidth: 0,
						radius:2,
						color:'rgba(148,0,211,0.75)',
					},
				},
			},
		});
	};
});
</script>
	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	</body>
</html>
