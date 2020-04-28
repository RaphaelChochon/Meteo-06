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
		<title><?php echo $short_station_name; ?> | Comparatif moyenne</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Comparatif des moyennes pour la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Comparatif moyenne" />
		<meta property="og:description" content="Comparatif des moyennes pour la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Comparatif des moyennes pour la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Comparatif moyenne" />
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
		<!-- @ToDo Mettre en place boost sur ces graphs -->
		<!-- <script defer src="content/highcharts/modules/boost-8.0.4.js"></script> -->

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
			<?php include __DIR__ . '/sql/req_climato_quotidienne.php';?>

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
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Comparatif des méthodes de calcul pour les moyennes de températures</h3>
					<p class="text-justify">
						Le graphique ci-dessous représente des températures moyennes calculées selon plusieurs méthodes.
						<ul>
							<li>La première, la <b>Tmoy</b>, est calculée de manière traditionnelle, selon les normes de l'OMM : Tn+Tx/2.</li>
							<li>La seconde, la <b>TmoyReel</b>, est calculée en prenant toutes les valeurs de températures de 00h à 23h59 UTC du jour en cours.</li>
							<li>La troisième, la <b>Tmoy1h</b>, est calculée en prenant les températures instantanées de toutes les heures de la journée en cours (moyenne des 24 températures horaires : 1h, 2h ..., 24h).</li>
							<li>La quatrième, la <b>Tmoy3h</b>, est calculée en prenant les températures instantanées trihoraires de la journée en cours (moyenne des 8 valeurs trihoraires : 3h, 6h, 9h, 12h, 15h, 18h, 21h, 24h).</li>
						</ul>
						Peuvent également être affichées les extrêmes de la journée, et leurs heures de survenues (Tn + Tx, aux normes OMM).<br>
						Un indice de fiabilité est donné pour certains paramètres calculés. Il est exprimé en pourcentage et indique si toutes les données de la journée en question sont présentes. 100% indiquent une très bonne fiabilité, alors qu'une valeur inférieure peut indiquer un manque de données, et donc une approximation.<br><b>Cet indice représente donc principalement la fiabilité de la transmission. Il est présent afin d'aider à l'interprétation mais ne représente en aucun cas un indicateur de validation/invalidation des données affichées.</b><br><br>
							
						Les données sont mises à jour plusieurs fois par heure, mais il est important de noter que <b>les données affichées pour la journée en cours sont incomplètes et/ou provisoires</b>. Il faut attendre le lendemain à 6h UTC pour que toutes les données de la veille soit complètes.
					</p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div id="graphTempMoy" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<hr>
			<!-- <div class="row">
				<div class="col-md-12">
					<h2>Ecart Tmoy TmoyReel et Pluie</h2>
				</div>
			</div> -->

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
									// {"padding": "0.5em 1em", "color": "#333333", "background": "none", "fontSize": "11px/14px", "transition": "background 250ms, color 250ms"}
									fontSize: "9px",
									padding: "0.5em 0.5em"
								}
						}
					});
					// GRAPHS
					
					var graphTempMoy = Highcharts.chart('graphTempMoy', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Comparaison de différentes méthodes de calcul de la moyenne des températures'
						},
						subtitle: {
							text: 'Tmoy : Tn+Tx/2 | TmoyReel : Moy. de ttes les vals. de 0h à 24h UTC | Tmoy1h : Moy. des 24 vals. horaires loc. | Tmoy3h : Moy. des 8 vals. tri-horaires loc.<br>Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						credits: {
							text: '<?php echo $name_manager_graph; ?>',
							href: '<?php echo $site_manager_graph; ?>'
						},
						exporting: {
							filename: '<?php echo $short_station_name; ?> climato_tempMoy',
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
								text: 'Température moyenne (°C)',
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
							xDateFormat: '<b>%e %B %Y</b>',
							headerFormat: '<small>{point.key}</small><br>----<br>',
						},
						series: [{
							name : 'Tmoy',
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
							name : 'TmoyReel',
							type: 'spline',
							data : <?php echo $dataTmoyReel ?>,
							zIndex: 2,
							color: '#9c0ba1',
							turboThreshold: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.Fiab+'%<br/>'+
									'----<br>';
								}
							}
						},{
							name : 'Tmoy1h',
							visible: false,
							type: 'spline',
							data : <?php echo $dataTmoy1h ?>,
							zIndex: 2,
							color: '#258f0a',
							turboThreshold: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.Fiab+'%<br/>'+
									'----<br>';
								}
							}
						},{
							name : 'Tmoy3h',
							visible: false,
							type: 'spline',
							data : <?php echo $dataTmoy3h ?>,
							zIndex: 2,
							color: '#c29006',
							turboThreshold: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b><br>'+
									'<span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.Fiab+'%<br/>'+
									'----<br>';
								}
							}
						},{
							name : 'Tx',
							visible: false,
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
							name : 'Tn',
							visible: false,
							type: 'spline',
							data : <?php echo $dataTn ?>,
							zIndex: 1,
							color: '#003bff',
							turboThreshold: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									var TnDate = new Date(this.TnDt);
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' °C</b> le <b>'+("0"+TnDate.getUTCDate()).slice(-2)+'/'+("0"+(TnDate.getUTCMonth()+1)).slice(-2)+'</b> à <b>'+("0"+TnDate.getUTCHours()).slice(-2)+':'+("0"+TnDate.getUTCMinutes()).slice(-2)+' UTC</b><br/><span style="color:'+this.series.color+'">\u25CF</span> Indice de fiabilité : '+this.TnFiab+'%<br/>';
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
