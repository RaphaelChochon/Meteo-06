<?php
	require_once __DIR__ . '/include/access_rights.php';
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/include/functions.php';
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Indice de fiabilité de la climatologie</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="<?php echo $hashtag_meteo; ?> Indice de fiabilité de la climatologie de la station <?php echo $station_name; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Indice de fiabilité de la climatologie" />
		<meta property="og:description" content="<?php echo $hashtag_meteo; ?> Indice de fiabilité de la climatologie de la station <?php echo $station_name; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="<?php echo $hashtag_meteo; ?> Indice de fiabilité de la climatologie de la station <?php echo $station_name; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Indice de fiabilité de la climatologie" />
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
		<script defer src="content/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/export-data-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/boost-8.0.4.js"></script>

		<!-- Font Awesome CSS -->
		<link href="../content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
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

			<!-- On récupère les valeurs en BDD pour peupler les tableaux ci-après -->
			<?php include __DIR__ . '/sql/req_graphs-climatologie-fiabilite.php';?>

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
				<div class="col-md-12 ">
					<h3 class="text-center">Fiabilité de la climatologie quotidienne</h3>
					<p class="text-justify">
						Vous trouverez sur cette page un indice de fiabilité de la climatologie quotidienne de la station sous forme de graphiques. Il est exprimé en pourcentage et indique si toutes les données de la journée en question sont présentes. 100% indiquent une très bonne fiabilité, alors qu'une valeur inférieure peut indiquer un manque de données, et donc une approximation.<br><b>Cet indice représente donc principalement la fiabilité de la transmission. Il est présent afin d'aider à l'interprétation mais ne représente en aucun cas un indicateur de validation/invalidation des données affichées.</b>
					</p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<h4 class="text-center">Indice de fiabilité</h4>
					<div id="graphFiab" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr>

			<!--
				DEBUT SCRIPT HIGHCHARTS
			-->
			<script>
				document.addEventListener('DOMContentLoaded', function () {
					// GLOBAL
					Highcharts.setOptions({
						global: {
							useUTC: true
						},
						lang: {
							months: ["Janvier "," Février "," Mars "," Avril "," Mai "," Juin "," Juillet "," Août "," Septembre "," Octobre "," Novembre "," Décembre"],
							weekdays: ["Dim "," Lun "," Mar "," Mer "," Jeu "," Ven "," Sam"],
							shortMonths: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil','Août', 'Sept', 'Oct', 'Nov', 'Déc'],
							contextButtonTitle: "Menu",
							decimalPoint: ',',
							resetZoom: 'Reset zoom',
							resetZoomTitle: 'Reset zoom à 1:1',
							downloadPNG: "Télécharger au format PNG",
							downloadJPEG: "Télécharger au format JPEG",
							downloadPDF: "Télécharger au format PDF",
							downloadSVG: "Télécharger au format SVG",
							downloadCSV: "Télécharger les données<br>dans un fichier CSV",
							downloadXLS: "Télécharger les données<br>dans un fichier XLS (Excel)",
							printChart: "Imprimer le graphique",
							viewData: "Afficher/masquer les données brut sous forme<br>d'un tableau ci-dessous (BETA)",
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
					// GRAPHS
					var graphFiab = Highcharts.chart('graphFiab', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Fiabilité des mesures de températures'
						},
						subtitle: {
							text: 'Fiabilité Tn, et Tx | UTC<br>Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | <?php echo $name_manager_graph; ?>',
						},
						credits: {
							text: '<?php echo $name_manager_graph; ?>',
							href: '<?php echo $site_manager_graph; ?>'
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> climato_fiab',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.',
							},
						},
						xAxis: [{
							type: 'datetime',
							dateTimeLabelFormats: {day: '%e'},
							tickInterval: 24*3600*1000, // 1 jour
						},{ // Axe esclave
							type: 'datetime',
							linkedTo: 0,
							tickInterval: 120*3600*1000, // 5 jours environ
							labels: {
								align:"center",
								formatter: function () {
									return Highcharts.dateFormat('%b', this.value);
								},
								style:{
									fontSize: "8px",
								},
							}
						}],
						yAxis: [{
							// Axe 0
							lineColor: '#FF0000',
							lineWidth: 1,
							tickInterval: 10,
							title: {
								text: 'Fiabilité (%)',
								style: {
									"color": "#000000",
								},
							},
							labels:{
								style: {
									"color": "#000000",
								},
							},
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							// xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							xDateFormat: '<b>%e %B %Y</b>',
						},
						boost: {
							enabled:true,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name : 'Fiabilité Tn',
							type: 'line',
							data : [<?php echo join($fiabTn, ',') ?>],
							boostThreshold: 20,
							zIndex: 1,
							color: '#003bff',
							turboThreshold: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' %</b><br>'+
									'----<br>';
								}
							}
						},{
							name : 'Fiabilité Tx',
							type: 'line',
							data : [<?php echo join($fiabTx, ',') ?>],
							boostThreshold: 20,
							zIndex: 1,
							color: '#ff0000',
							turboThreshold: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' %</b><br>';
								}
							}
						}]
					});
				});
			</script>
			
			<!--
				FIN SCRIPT HIGHCHARTS
			-->

			<footer class="footer bg-light">
				<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div>
	</body>
</html>
