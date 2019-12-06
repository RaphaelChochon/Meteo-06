<?php require_once 'config/config.php';?>
<?php require_once 'sql/import.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Fiabilité de la climatologie quotidienne</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Fiabilité de la climatologie quotidienne de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Fiabilité de la climatologie quotidienne" />
		<meta property="og:description" content="Fiabilité de la climatologie quotidienne de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL==true){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Fiabilité de la climatologie quotidienne de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Fiabilité de la climatologie quotidienne" />
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
		<!--<link href="vendors/custom/charts.css" rel="stylesheet">-->
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

		<?php require_once 'sql/req_climato_quoti_fiab.php';?>

		<!-- DEBUT DU CORPS DE PAGE -->
		<?php if ($banniere_info_active === "true") : ?>
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
			<div class="col-md-12 ">
				<h1 class="divCenter">Fiabilité de la climatologie quotidienne</h1>
				<p>Vous trouverez sur cette page un indice de fiabilité de la climatologie quotidienne de la station sous forme de graphiques. Il est exprimé en pourcentage et indique si toutes les données de la journée en question sont présentes. 100% indiquent une très bonne fiabilité, alors qu'une valeur inférieure peut indiquer un manque de données, et donc une approximation.<br><b>Cet indice représente donc principalement la fiabilité de la transmission. Il est présent afin d'aider à l'interprétation mais ne représente en aucun cas un indicateur de validation/invalidation des données affichées.</b><br><br> <br>Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la souris ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende. Ils sont également exportables en cliquant sur le bouton au-dessus à droite de chaque graphique.</p>
			</div>
		</div>
		<?php if ($presence_old_climato === "true") : ?>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<p>Note : cette station dispose également d'une série de données antérieures. Elle n'a pas été fusionnée avec celles-ci pour ne pas corrompre son homogénéité (matériel différent, ou changement d'emplacement). Cependant elle est toujours <a href="<?php echo "$url_old_climato"; ?>" target="_blank"> disponible à la consultation ici</a>.</p>
			</div>
		</div>
		<?php endif; ?>
		<hr>
		<div class="row">
			<div class="col-md-12 divCenter">
				<h2>Indice de fiabilité</h2>
				<div id="graphFiab" style="width:100%; height: 500px;"></div>
			</div>
		</div>

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
					}
				});
				// GRAPHS
				var graphFiab = Highcharts.chart('graphFiab', {
					chart: {
						type : 'line',
						zoomType: 'x',
					},
					title: {
						text: 'Fiabilité des mesures de températures et de précipitations'
					},
					subtitle: {
						text: 'Fiabilité Tn, Tx, RR | UTC<br>Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
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
					series: [{
						name : 'Fiabilité Tx',
						type: 'spline',
						data : <?php echo $dataFiabTx ?>,
						zIndex: 1,
						color: '#ff0000',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' %</b><br>'+
								'----<br>';
							}
						}
					},{
						name : 'Fiabilité Tn',
						type: 'spline',
						data : <?php echo $dataFiabTn ?>,
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
						name : 'Fiabilité RR',
						type: 'spline',
						data : <?php echo $dataFiabRR ?>,
						zIndex: 1,
						color: '#292a2d',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' %</b><br>'+
								'----<br>';
							}
						}
					}]
				});
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
