<?php require_once 'config/config.php';?>
<?php require_once 'sql/connect_pdo.php';?>
<?php require_once 'sql/import.php';?>
<?php require_once 'include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Climatologie quotidienne</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Climatologie quotidienne de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Climatologie quotidienne" />
		<meta property="og:description" content="Climatologie quotidienne de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Climatologie quotidienne de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Climatologie quotidienne" />
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

		<?php require_once 'sql/req_climato_quotidienne.php';?>

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
			<div class="col-md-12 ">
				<h1 class="divCenter">Climatologie quotidienne</h1>
				<p>Vous trouverez sur cette page la climatologie quotidienne de la station sous forme de graphiques. <br>Vous pouvez zoomer sur une zone spécifique, faire apparaitre une infobulle au passage de la souris ou au clic sur mobile, et afficher/masquer un paramètre météo en cliquant sur son intitulé dans la légende. Ils sont également exportables en cliquant sur le bouton au-dessus à droite de chaque graphique.</p>
				<h3 class="divCenter">Note importante :</h3>
				<p>Les données affichées sur ces graphiques sont calculées aux normes OMM, c'est à dire :
					<ul>
						<li>Tx : Pour le jour J, température maximale de 6h jour J, à 6h J+1 (UTC)</li>
						<li>Tn : Pour le jour J, température minimale de 18h J-1, à 18h jour J (UTC)</li>
						<li>Précipitations : Cumul de pluie pour le jour J de 6h jour J, à 6h J+1 (UTC)</li>
					</ul>
					Un indice de fiabilité est donné pour certains paramètres calculés. Il est exprimé en pourcentage et indique si toutes les données de la journée en question sont présentes. 100% indiquent une très bonne fiabilité, alors qu'une valeur inférieure peut indiquer un manque de données, et donc une approximation.<br><b>Cet indice représente donc principalement la fiabilité de la transmission. Il est présent afin d'aider à l'interprétation mais ne représente en aucun cas un indicateur de validation/invalidation des données affichées.</b><br><br>
					
					Les données sont mises à jour plusieurs fois par heure, mais il est important de noter que <b>les données affichées pour la journée en cours sont incomplètes et/ou provisoires</b>. Il faut attendre le lendemain à 6h UTC pour que toutes les données de la veille soit complètes.
				</p>
			</div>
		</div>
		<?php if ($presence_old_climato) : ?>
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
				<h2>Températures</h2>
				<div id="graphTemp" style="width:100%; height: 500px;"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 divCenter">
				<h2>Précipitations</h2>
				<div id="graphRR" style="width:100%; height: 500px;"></div>
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
				var graphTemp = Highcharts.chart('graphTemp', {
					chart: {
						type : 'line',
						zoomType: 'x',
					},
					title: {
						text: 'Température maxi, moyenne et mini au pas de temps quotidien'
					},
					subtitle: {
						text: 'Tn et Tx aux normes OMM, température moyenne : Tn+Tx/2 | UTC<br>Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> climato_temp',
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
							text: 'Température (°C)',
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
						name : 'Température maxi. (Tx)',
						type: 'spline',
						data : <?php echo $dataTx ?>,
						zIndex: 1,
						color: '#ff0000',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								var TxDate = new Date(this.TxDt);
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b> le <b>'+("0"+TxDate.getUTCDate()).slice(-2)+'/'+("0"+(TxDate.getUTCMonth()+1)).slice(-2)+'</b> à <b>'+("0"+TxDate.getUTCHours()).slice(-2)+':'+("0"+TxDate.getUTCMinutes()).slice(-2)+' UTC</b><br/><span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.TxFiab+'%<br/>'+
								'----<br>';
							}
						}
					},{
						name : 'Température moyenne. (Tmoy)',
						type: 'spline',
						data : <?php echo $dataTmoy ?>,
						zIndex: 1,
						color: '#292a2d',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b><br>'+
								'----<br>';
							}
						}
					},{
						name : 'Température mini. (Tn)',
						type: 'spline',
						data : <?php echo $dataTn ?>,
						zIndex: 1,
						color: '#003bff',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							// pointFormat : '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} °C</b><br/>',
							pointFormatter: function () {
								var TnDate = new Date(this.TnDt);
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b> le <b>'+("0"+TnDate.getUTCDate()).slice(-2)+'/'+("0"+(TnDate.getUTCMonth()+1)).slice(-2)+'</b> à <b>'+("0"+TnDate.getUTCHours()).slice(-2)+':'+("0"+TnDate.getUTCMinutes()).slice(-2)+' UTC</b><br/><span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.TnFiab+'%<br/>';
							}
						}
					}]
				});
				var graphRR = Highcharts.chart('graphRR', {
					chart: {
						type : 'line',
						zoomType: 'x',
					},
					title: {
						text: 'Cumul de précipitations au pas de temps quotidien, et cumul annuel'
					},
					subtitle: {
						text: 'Précipitations aux normes OMM | UTC<br>Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
					},
					credits: {
						text: '<?php echo $name_manager_graph; ?>',
						href: '<?php echo $site_manager_graph; ?>'
					},
					exporting: {
						filename: '<?php echo $short_station_name; ?> climato_rr',
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
						lineColor: '#2175fc',
						lineWidth: 1,
						tickInterval: 10,
						title: {
							text: 'Cumul quotidien (mm)',
							style: {
								"color": "#2175fc",
							},
						},
						labels:{
							style: {
								"color": "#2175fc",
							},
						},
					},{
						// Axe 1
						opposite: true,
						lineColor: '#2c469c',
						lineWidth: 1,
						title: {
							text: 'Cumul annuel (mm)',
							style: {
								"color": "#2c469c",
							},
						},
						labels:{
							style: {
								"color": "#2c469c",
							},
						},
					}],
					tooltip: {
						shared: true,
						valueDecimals: 1,
						xDateFormat: '<b>%e %B %Y</b>',
					},
					series: [{
						name : 'Cumul quotidien',
						type: 'column',
						data : <?php echo $dataRR ?>,
						zIndex: 1,
						color: '#2175fc',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								var RRMaxIntDt = new Date(this.RRMaxIntDt);
								if (this.RRMaxInt != null) {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Intensité max. de '+this.RRMaxInt+' mm/h'+
									' le <b>'+("0"+RRMaxIntDt.getUTCDate()).slice(-2)+'/'+("0"+(RRMaxIntDt.getUTCMonth()+1)).slice(-2)+'</b> à <b>'+("0"+RRMaxIntDt.getUTCHours()).slice(-2)+':'+("0"+RRMaxIntDt.getUTCMinutes()).slice(-2)+' UTC</b><br/>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.RRFiab+'%<br/>'+
									'----<br>';
								} else {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.RRFiab+'%<br/>'+
									'----<br>';
								}
							}
						}
					},{
						name : 'Cumul mensuel',
						type: 'spline',
						data : <?php echo $dataRRMonth ?>,
						zIndex: 2,
						yAxis: 1,
						color: '#486cb0',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm</b><br>';
							}
						}
					},{
						name : 'Cumul annuel',
						type: 'spline',
						data : <?php echo $dataRRYear ?>,
						zIndex: 3,
						yAxis: 1,
						color: '#39404d',
						turboThreshold: 0,
						tooltip: {
							useHTML: true,
							pointFormatter: function () {
								return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm</b><br>';
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
