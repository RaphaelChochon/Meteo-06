<?php
	require_once __DIR__ . '/include/access_rights.php';
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/include/functions.php';

// UTC
	date_default_timezone_set('UTC');
// Récup des params
	if (isset($_GET['month']) || !empty($_GET['month'])) {
		$optMonth = $_GET['month'];
	} else {
		$optMonth = date('Y-m');
	}
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Climatologie mensuelle</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="<?php echo $hashtag_meteo; ?> Climatologie mensuelle de la station <?php echo $station_name; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Climatologie mensuelle" />
		<meta property="og:description" content="<?php echo $hashtag_meteo; ?> Climatologie mensuelle de la station <?php echo $station_name; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="<?php echo $hashtag_meteo; ?> Climatologie mensuelle de la station <?php echo $station_name; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Climatologie mensuelle" />
		<meta name="twitter:site" content="<?php echo $tw_account_name; ?>" />
		<meta name="twitter:image" content="<?php echo $url_site; ?>/img/capture_site.jpg" />
		<meta name="twitter:creator" content="<?php echo $tw_account_name; ?>" />
		<!-- Fin des balises META SEO -->
		<?php include __DIR__ .'/config/favicon.php';?>
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
		<link href="content/custom/custom.css?v=1.1" rel="stylesheet">
		<script defer src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<script>
			document.addEventListener('DOMContentLoaded', function () {
				$(function () {
					$('[data-toggle="tooltip"]').tooltip()
				})
			});
		</script>

		<!-- ######### Pour Highcharts ######### -->
		<!-- Highcharts BASE -->
		<script defer src="content/highcharts/js/highcharts-8.0.4.js"></script>
		<!-- Highcharts more et modules d'export -->
		<script defer src="content/highcharts/js/highcharts-more-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/exporting-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/offline-exporting-8.0.4.js"></script>
		<script defer src="content/highcharts/modules/export-data-8.0.4.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
		<!-- Font Awesome CSS for Tempus Dominus -->
		<link href="content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
		<!-- Moment.js -->
		<script defer type="text/javascript" src="content/moment/moment.js"></script>
		<script defer type="text/javascript" src="content/moment/moment-locale-fr.js"></script>
		<!-- Tempus Dominus -->
		<script defer type="text/javascript" src="content/tempusdominus/tempusdominus-bootstrap-4.min.js"></script>
		<link rel="stylesheet" href="content/tempusdominus/tempusdominus-bootstrap-4.min.css" />
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
			<?php include __DIR__ . '/sql/req_climato_mensuelle.php'; ?>

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Climatologie mensuelle</h3>
				</div>
			</div>
			<hr class="my-3">
			<div class="row align-items-center">
				<div class="col-md-4">
					<p class="text-center">
						Affichage de la climatologie pour le mois selectionné.
						Calcul des extrêmes et cumuls aux <b>normes OMM</b>.
					</p>
				</div>
				<!-- Date -->
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" id="anchorDate">
								<h5 class="text-center">Changer de mois :</h5>
								<div class="input-group date" id="dtPicker" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input text-center" data-target="#dtPicker" readonly="readonly">
									<div class="input-group-append" data-target="#dtPicker" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
						</div>
						<script type="text/javascript">
							document.addEventListener('DOMContentLoaded', function () {
								$(function () {
									var defaultDate = <?php echo $optMonth_quoted;?>;
									var firstDay = '<?php echo $firstYearMonth;?>';
									$('#dtPicker').datetimepicker({
										format: 'MMM YYYY',
										locale: moment.locale('fr'),
										minDate: moment(firstDay, 'YYYY-MM-DD'),
										maxDate: moment(),
										useCurrent: false,
										ignoreReadonly: true,
										defaultDate: moment(defaultDate, 'YYYY-MM')
									});
									$("#dtPicker").on("change.datetimepicker", function (e) {
										moment.locale('fr');
										d = moment(e.date,'MMM YYYY').format('YYYY-MM');
										var url = "./climatologie-mensuelle.php?month=" + d + "#anchorDate";
										window.location.href = url;
									});
								});
							});
						</script>
					</div>
					<div class="row mb-3">
						<div class="col text-left">
							<a role="button" class="btn btn-primary <?php if (strtotime($optLatestMonth) < strtotime($firstYearMonth)) {echo "disabled";} ?>" href="./climatologie-mensuelle.php?month=<?php echo $optLatestMonth; ?>#anchorDate"><i class="fas fa-chevron-circle-left"></i>&nbsp;<?php $mois = date('n', strtotime($optLatestMonth)); echo $moisFrancaisAbrev[$mois].' '.date('y', strtotime($optLatestMonth));?></a>
						</div>
						<div class="col text-right">
							<a role="button" class="btn btn-primary <?php if (strtotime($optNextMonth) > strtotime($lastYearMonth)) {echo "disabled";} ?>" href="./climatologie-mensuelle.php?month=<?php echo $optNextMonth; ?>#anchorDate"><?php $mois = date('n', strtotime($optNextMonth)); echo $moisFrancaisAbrev[$mois].' '.date('y', strtotime($optNextMonth));?>&nbsp;<i class="fas fa-chevron-circle-right"></i></a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<p class="text-center">
						Toutes les heures sur cette page sont indiquées en heure UTC.
						<br>
						<?php date_default_timezone_set('Europe/Paris'); echo date('H\hi'); ?> <span class="badge badge-success">loc.</span>
						 ⇨ 
						<?php date_default_timezone_set('UTC'); echo date('H\hi'); ?> <span class="badge badge-primary">UTC</span>
					</p>
				</div>
			</div>

		<!-- IF lessValue -->
		<?php if ($lessValue) : ?>
			<div class="row justify-content-md-center">
				<div class="col-md-8">
				<div class="alert alert-dismissible alert-danger">
					<h4 class="alert-heading">Oops !</h4>
					<p class="mb-0">
						Vous avez sélectionné une date pour laquelle aucune donnée n'est disponible.
						<br>
						<strong>Veuillez choisir une autre date.</strong>
					</p>
				</div>
				</div>
			</div>
		<!-- ELSE lessValue -->
		<?php else : ?>
			<hr class="my-3">
			<!-- Résumé journée -->
			<div class="row">
				<div class="col-md-12">
					<h4 class="text-center mb-4">Climato. du mois de <?php $mois = date('n', $tsOptMonth); echo $moisFrancais[$mois].' '.date('Y', $tsOptMonth);?></h4>
					<?php date_default_timezone_set('UTC'); if (time() >= $tsOptMonth && time() < strtotime(date('Y-m-t H:i:s', $tsOptMonth)) ) : ?>
						<!-- Résultats partiels car mois en cours -->
						<div class="row justify-content-md-center mb-5">
							<div class="col-md-6">
								<div class="alert alert-warning">
									<h4 class="alert-heading">
									<svg class="bi bi-exclamation-diamond" width="1em" height="1em" viewBox="0 0 16 16" fill="red" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.482 1.482 0 010-2.098L6.95.435zm1.4.7a.495.495 0 00-.7 0L1.134 7.65a.495.495 0 000 .7l6.516 6.516a.495.495 0 00.7 0l6.516-6.516a.495.495 0 000-.7L8.35 1.134z" clip-rule="evenodd"/>
										<path d="M7.002 11a1 1 0 112 0 1 1 0 01-2 0zM7.1 4.995a.905.905 0 111.8 0l-.35 3.507a.552.552 0 01-1.1 0L7.1 4.995z"/>
									</svg>
										&nbsp;Mois en cours
									</h4>
									<p class="mb-0 text-justify">
										Résultats partiels, le mois n'est pas terminé (<?php echo $percentIntervalInMinutes.'%'; ?>)
									</p>
									<div class="progress mt-2">
										<div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $percentIntervalInMinutes.'%'; ?>" aria-valuenow="<?php echo $percentIntervalInMinutes; ?>" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php if ($TnFiab <= 95 || $TxFiab <= 95) : ?>
						<!-- Problème de fiabilité -->
						<div class="row justify-content-md-center mb-5">
							<div class="col-md-6">
								<div class="alert alert-warning">
									<h4 class="alert-heading">
									<svg class="bi bi-exclamation-triangle" width="1em" height="1em" viewBox="0 0 16 16" fill="red" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M7.938 2.016a.146.146 0 00-.054.057L1.027 13.74a.176.176 0 00-.002.183c.016.03.037.05.054.06.015.01.034.017.066.017h13.713a.12.12 0 00.066-.017.163.163 0 00.055-.06.176.176 0 00-.003-.183L8.12 2.073a.146.146 0 00-.054-.057A.13.13 0 008.002 2a.13.13 0 00-.064.016zm1.044-.45a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" clip-rule="evenodd"/>
										<path d="M7.002 12a1 1 0 112 0 1 1 0 01-2 0zM7.1 5.995a.905.905 0 111.8 0l-.35 3.507a.552.552 0 01-1.1 0L7.1 5.995z"/>
									</svg>
										&nbsp;Attention, problème de fiabilité
									</h4>
									<p class="mb-0 text-justify">
										Nous avons identifié un potentiel problème pour ce mois.
										<br>
										L'indice de fiabilité d'une des valeurs suivantes est insuffisant :
										<ul>
											<li>Fiabilité Tn : <?php if ($TnFiab<=95) {echo '<span class="textOfflineStation">'.$TnFiab.'%</span>';}else{ echo '<span class="textOnlineStation">'.$TnFiab.'%</span>';}?></li>
											<li>Fiabilité Tx : <?php if ($TxFiab<=95) {echo '<span class="textOfflineStation">'.$TxFiab.'%</span>';}else{ echo '<span class="textOnlineStation">'.$TxFiab.'%</span>';}?></li>
										</ul>
										Cela peut indiquer un manque de données sur une partie du mois, et par conséquent, rendre les statistiques présentées ci-dessous incomplètes.
										<a role="button" class="btn btn-block btn-primary mt-3" href="/graphs-climatologie-fiabilite.php">Retrouvez plus de détails sur cet indice ici</a>
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- Tableau de données -->
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive table-scroll-quotidien-disabled">
						<table class="table table-striped table-bordered table-hover table-sm table-sticky text-center table-align-middle">
							<thead>
								<tr>
									<th style="width:10%">Jour</th>
									<th style="width:15%">Tn</th>
									<th style="width:15%">Tx</th>
									<th style="width:15%">T Moy.</th>
									<th style="width:15%">Amplitude</th>
									<th style="width:15%">Précipitations</th>
									<th style="width:15%">Rafale</th>
								</tr>
							</thead>
							<tbody>
								<?php
								date_default_timezone_set('UTC');
								foreach ($tabClimatoMonth as $ts => $value) {
									echo "<tr>";
									list($nomJour, $jour) = explode('-', date('w-d', $ts));
									$dt = date('D d',$ts);
									echo '<th><a target="_blank" href="/resume-quotidien.php?day='.date('Y-m-d', $ts).'">'.$jourFrancaisAbrev[$nomJour].' '.$jour.'</a></th>';

									if (!is_null(@$value['Tn'])) {
										echo '<td class="textMin';
										if ($value['TnMin'] == 1) echo ' textBold bgMin';
										echo '">'.$value['Tn'].'&#8239;°C</td>';
									} else {
										echo '<td></td>';
									}

									if (!is_null(@$value['Tx'])) {
										echo '<td class="textMax';
										if ($value['TxMax'] == 1) echo ' textBold bgMax';
										echo '">'.$value['Tx'].'&#8239;°C</td>';
									} else {
										echo '<td></td>';
									}

									if (!is_null(@$value['Tmoy'])) {
										echo '<td';
										if ($value['TmoyMin'] == 1) echo ' class="textMin textBold"';
										if ($value['TmoyMax'] == 1) echo ' class="textMax textBold"';
										echo '>'.$value['Tmoy'].'&#8239;°C</td>';
									} else {
										echo '<td></td>';
									}

									if (!is_null(@$value['TempRange'])) {
										echo '<td';
										if ($value['TrangeMin'] == 1) echo ' class="textMin textBold"';
										if ($value['TrangeMax'] == 1) echo ' class="textMax textBold"';
										echo '>'.$value['TempRange'].'&#8239;°C</td>';
									} else {
										echo '<td></td>';
									}

									if (!is_null(@$value['RR'])) {
										echo '<td class="textSum';
										if ($value['RrMax'] == 1) echo ' textBold bgMin';
										echo '">'.$value['RR'].'&#8239;mm</td>';
									} else {
										echo '<td></td>';
									}

									if (!is_null(@$value['windGust'])) {
										echo '<td class="textMax';
										if ($value['WgMax'] == 1) echo ' textBold bgMax';
										echo '">'.$value['windGust'].'&#8239;km/h</td>';
									} else {
										echo '<td></td>';
									}
									
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Tableau récap -->
			<div class="row">
				<div class="col-sm-12">
					<div class="table-responsive table-scroll-quotidien-disabled">
						<table class="table table-striped table-bordered table-hover table-sm table-sticky text-center table-align-middle">
							<thead>
								<tr>
									<th style="width:10%">#</th>
									<th style="width:15%">Tn</th>
									<th style="width:15%">Tx</th>
									<th style="width:15%">T Moy.</th>
									<th style="width:15%">Amplitude</th>
									<th style="width:15%">Précipitations</th>
									<th style="width:15%">Rafale</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>Moyenne<br>& cumul</th>
									<td></td>
									<td></td>
									<?php
										if (!is_null($TmoyAvg)) {
											echo '<td class="textBold">'.$TmoyAvg.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										echo '<td></td>';
										if (!is_null($RrSum)) {
											echo '<td class="textBold textSum">'.$RrSum.'&#8239;mm</td>';
										} else {
											echo '<td></td>';
										}
										echo '<td></td>';
									?>
								</tr>
								<tr>
									<th>Min. du mois</th>
									<?php
										if (!is_null($TnMin)) {
											echo '<td class="textMin textBold bgMin">'.$TnMin.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										if (!is_null($TxMin)) {
											echo '<td class="textMin">'.$TxMin.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										if (!is_null($TmoyMin)) {
											echo '<td class="textMin textBold">'.$TmoyMin.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										if (!is_null($TrangeMin)) {
											echo '<td class="textMin textBold">'.$TrangeMin.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										echo '<td></td>';
										echo '<td></td>';
									?>
								</tr>
								<tr>
									<th>Max. du mois</th>
									<?php
										if (!is_null($TnMax)) {
											echo '<td class="textMax">'.$TnMax.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										if (!is_null($TxMax)) {
											echo '<td class="textMax textBold bgMax">'.$TxMax.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										if (!is_null($TmoyMax)) {
											echo '<td class="textMax textBold">'.$TmoyMax.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										if (!is_null($TrangeMax)) {
											echo '<td class="textMax textBold">'.$TrangeMax.'&#8239;°C</td>';
										} else {
											echo '<td></td>';
										}
										echo '<td></td>';
										if (!is_null($WgMax)) {
											echo '<td class="textMax textBold bgMax">'.$WgMax.'&#8239;km/h</td>';
										} else {
											echo '<td></td>';
										}
									?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<hr class="my-3">
			<!-- GRAPHIQUES -->
			<div class="row my-2">
				<div class="col-md-12">
					<div id="graphTemp" style="width:100%; height: 500px;"></div>
				</div>
			</div>
			<div class="row my-2">
				<div class="col-md-12">
					<div id="graphRr" style="width:100%; height: 500px;"></div>
				</div>
			</div>

			<script>
				document.addEventListener('DOMContentLoaded', function () {
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
						xAxis: {
							type: 'datetime',
							min: <?php echo $tsOptMonth*1000; ?>, // Premier jour du mois
							max: <?php echo strtotime($latestDtOfMonth)*1000; ?>, // dernier jour du mois
							dateTimeLabelFormats: {
								day: '%e',
								month: '%e %b',
								year: '%b'
							},
							title: {
								text: 'Jour du mois'
							}
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

					// Graph tempé
					var graphTemp = Highcharts.chart ('graphTemp', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Tn et Tx du mois de <?php $mois = date('n', $tsOptMonth); echo $moisFrancais[$mois].' '.date('Y', $tsOptMonth);?>',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Association Nice Météo 06',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m',$tsOptMonth); ?>_climatoMensuelle_Temperature',
							sourceHeight: '500',
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
							tickPixelInterval: 30,
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
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							// xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							xDateFormat: '<b>%e %B %Y</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Tx',
							type: 'line',
							data: [<?php echo join($dataTx, ',') ?>],
							// boostThreshold: 20,
							color: '#FF0000',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Tmoy',
							type: 'line',
							data: [<?php echo join($dataTmoy, ',') ?>],
							// boostThreshold: 20,
							color: '#000000',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Tn',
							type: 'line',
							data: [<?php echo join($dataTn, ',') ?>],
							// boostThreshold: 20,
							color: '#0d1cc5',
							tooltip: {
								valueSuffix: ' °C',
							}
						}],
					});

					// Graph RR
					var graphRr = Highcharts.chart ('graphRr', {
						chart: {
							type : 'column',
							zoomType: 'x',
						},
						title: {
							text: 'Précipitations du mois de <?php $mois = date('n', $tsOptMonth); echo $moisFrancais[$mois].' '.date('Y', $tsOptMonth);?>',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Association Nice Météo 06',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m',$tsOptMonth); ?>_climatoMensuelle_Precipitations',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						// xAxis dans params généraux
						yAxis: [{
							// Axe 0
							// lineColor: '#4169e1',
							lineWidth: 1,
							tickPixelInterval: 30,
							title: {
								text: 'Cumul de pluie quotidien (mm)',
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
						tooltip: {
							shared: true,
							valueDecimals: 1,
							// xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							xDateFormat: '<b>%e %B %Y</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Cumul quotidien de pluie',
							type: 'column',
							data: [<?php echo join($dataRR, ',') ?>],
							// boostThreshold: 20,
							color: '#4169e1',
							tooltip: {
								valueSuffix: ' mm',
							}
						}],
					});
				});

			</script>

		<!-- FIN lessValue -->
		<?php endif; ?>

			<footer class="footer bg-light rounded">
				<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div>
	</body>
</html>