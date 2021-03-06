<?php
	require_once __DIR__ . '/include/access_rights.php';
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/include/functions.php';

// UTC
	date_default_timezone_set('UTC');
// Récup des params
	if (isset($_GET['day']) || !empty($_GET['day'])) {
		$optDay = $_GET['day'];
	} else {
		$optDay = date('Y-m-d');
	}
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | Résumé quotidien</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="<?php echo $hashtag_meteo; ?> Résumé quotidien de la station <?php echo $station_name; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | Résumé quotidien" />
		<meta property="og:description" content="<?php echo $hashtag_meteo; ?> Résumé quotidien de la station <?php echo $station_name; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://'; echo $_SERVER['HTTP_HOST'].parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="<?php echo $hashtag_meteo; ?> Résumé quotidien de la station <?php echo $station_name; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | Résumé quotidien" />
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
		<link href="content/custom/custom.css?v=1.6" rel="stylesheet">
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
		<script defer src="content/highcharts/modules/annotations-8.0.4.js"></script>

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
			<?php include __DIR__ . '/sql/req_resume_quotidien.php'; ?>

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Résumé quotidien</h3>
				</div>
			</div>
			<hr class="my-3">
			<div class="row align-items-center">
				<div class="col-md-4">
					<p class="text-center">
						Affichage des statistiques pour la journée selectionnée.
						Calcul des extrêmes et cumuls aux <b>normes OMM</b>.
					</p>
				</div>
				<!-- Date -->
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" id="anchorDate">
								<h5 class="text-center">Changer de date :</h5>
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
									var defaultDate = <?php echo $optDay_quoted;?>;
									var firstDay = '<?php echo $dtFisrtDay;?>';
									$('#dtPicker').datetimepicker({
										format: 'ddd DD MMM YYYY',
										locale: moment.locale('fr'),
										minDate: moment(firstDay, 'YYYY-MM-DD'),
										maxDate: moment(),
										useCurrent: false,
										ignoreReadonly: true,
										defaultDate: moment(defaultDate, 'YYYY-MM-DD')
									});
									$("#dtPicker").on("change.datetimepicker", function (e) {
										moment.locale('fr');
										d = moment(e.date,'ddd DD MMM YYYY').format('YYYY-MM-DD');
										var url = "./resume-quotidien.php?day=" + d + "#anchorDate";
										window.location.href = url;
									});
								});
							});
						</script>
					</div>
					<div class="row mb-3">
						<div class="col text-left">
							<a role="button" class="btn btn-primary  <?php if (strtotime($optYesterday) < strtotime($dtFisrtDay)) {echo "disabled";} ?>" href="./resume-quotidien.php?day=<?php echo $optYesterday; ?>#anchorDate"><i class="fas fa-chevron-circle-left"></i>&nbsp;<?php list($nomJour, $jour, $mois) = explode('-', date('w-d-n', strtotime($optYesterday))); echo $jourFrancaisAbrev[$nomJour].' '.$jour.' '.$moisFrancaisAbrev[$mois];?></a>
						</div>
						<div class="col text-right">
							<a role="button" class="btn btn-primary <?php if (strtotime($optTomorrow) > strtotime($dtLastDay)) {echo "disabled";} ?>" href="./resume-quotidien.php?day=<?php echo $optTomorrow; ?>#anchorDate"><?php list($nomJour, $jour, $mois) = explode('-', date('w-d-n', strtotime($optTomorrow))); echo $jourFrancaisAbrev[$nomJour].' '.$jour.' '.$moisFrancaisAbrev[$mois];?>&nbsp;<i class="fas fa-chevron-circle-right"></i></a>
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
					<h4 class="text-center mb-4">Résumé du <?php echo date('d/m/Y',$tsOptDay) ?> <span style="font-size:0.7em;">en&nbsp;heures&nbsp;<span class="badge badge-primary">UTC</span></span></h4>
					<?php date_default_timezone_set('UTC'); if (time() >= $tsOptDayStart && time() < $tsOptDayStop) : ?>
						<!-- Résultats partiels car journée en cours -->
						<div class="row justify-content-md-center mb-5">
							<div class="col-md-6">
								<div class="alert alert-warning">
									<h4 class="alert-heading">
									<svg class="bi bi-exclamation-diamond" width="1em" height="1em" viewBox="0 0 16 16" fill="red" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.482 1.482 0 010-2.098L6.95.435zm1.4.7a.495.495 0 00-.7 0L1.134 7.65a.495.495 0 000 .7l6.516 6.516a.495.495 0 00.7 0l6.516-6.516a.495.495 0 000-.7L8.35 1.134z" clip-rule="evenodd"/>
										<path d="M7.002 11a1 1 0 112 0 1 1 0 01-2 0zM7.1 4.995a.905.905 0 111.8 0l-.35 3.507a.552.552 0 01-1.1 0L7.1 4.995z"/>
									</svg>
										&nbsp;Attention !
									</h4>
									<p class="mb-0 text-justify">
										Résultats partiels, la journée n'est pas terminée (<?php echo $percentIntervalInMinutes.'%'; ?>)
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
										Nous avons identifié un potentiel problème pour cette journée.
										<br>
										L'indice de fiabilité d'une des valeurs suivantes est insuffisant :
										<ul>
											<li>Fiabilité Tn : <?php if ($TnFiab<=95) {echo '<span class="textOfflineStation">'.$TnFiab.'%</span>';}else{ echo '<span class="textOnlineStation">'.$TnFiab.'%</span>';}?></li>
											<li>Fiabilité Tx : <?php if ($TxFiab<=95) {echo '<span class="textOfflineStation">'.$TxFiab.'%</span>';}else{ echo '<span class="textOnlineStation">'.$TxFiab.'%</span>';}?></li>
										</ul>
										Cela peut indiquer un manque de données sur une partie de la journée, et par conséquent, rendre les statistiques présentées ci-dessous incomplètes.
										<a role="button" class="btn btn-block btn-primary mt-3" href="/graphs-climatologie-fiabilite.php">Retrouvez plus de détails sur cet indice ici</a>
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<table class="table table-striped table-bordered table-hover table-sm table-resume-quoti">
						<tbody>
							<tr>
								<th>Tn</th>
								<td class="textMin">
									<?php echo $Tn; ?>&#8239;°C
									<?php if (!is_null($TnDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($TnDt)).'</span>';
									} ?>
									<?php
										if (is_numeric($TnPos) && $TnPos <= 30) {
											echo '<span class="badge badge-pill';
											if ($TnPos <= 10) { echo ' badge-success"'; } else { echo ' badge-light"'; }
											echo 'data-toggle="tooltip" data-placement="top" data-html="true" title="Rang de la Tn">';
											echo $TnPos;
											if ($TnPos == 1) {
												echo '<sup>&nbsp;ère</sup>';
											} else {
												echo '<sup>&nbsp;ème</sup>';
											}
											echo '</span>';
										}
									?>
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La Tn est calculée d'après la méthode officielle OMM, il s'agit donc de la <u>température minimale</u> qui s'est produite entre 18h UTC la veille et 18h UTC le jour même">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>Tx</th>
								<td class="textMax">
									<?php echo $Tx; ?>&#8239;°C
									<?php if (!is_null($TxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($TxDt)).'</span>';
									} ?>
									<?php
										if (is_numeric($TxPos) && $TxPos <= 30) {
											echo '<span class="badge badge-pill';
											if ($TxPos <= 10) { echo ' badge-success"'; } else { echo ' badge-light"'; }
											echo 'data-toggle="tooltip" data-placement="top" data-html="true" title="Rang de la Tx">';
											echo $TxPos;
											if ($TxPos == 1) {
												echo '<sup>&nbsp;ère</sup>';
											} else {
												echo '<sup>&nbsp;ème</sup>';
											}
											echo '</span>';
										}
									?>
									</span>
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La Tx est calculée d'après la méthode officielle OMM, il s'agit donc de la <u>température maximale</u> qui s'est produite entre 06h UTC le jour même et 06h UTC le lendemain">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>Tmoy</th>
								<td>
									<?php echo $Tmoy; ?>&#8239;°C
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>température moyenne</u> est donnée selon la méthode officielle OMM comme la <u>moyenne de la Tn + la Tx</u>, c'est à dire : (Tn + Tx) / 2">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>Amplitude</th>
								<td>
									<?php echo $TempRange; ?>&#8239;°C
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="L'<u>amplitude</u> de température est simplement la différence entre la Tx et la Tn, c'est à dire : Tx - Tn">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover table-sm table-resume-quoti">
						<tbody>
							<tr>
								<th>UV max</th>
								<td class="textMax">
									<?php echo $UvMax; ?>
									<?php if (!is_null($UvMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($UvMaxDt)).'</span>';
									} ?>
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="<u>Indice UV</u> maximale de la journée, entre 00h UTC et 23h59 UTC inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>Ray. sol. max</th>
								<td class="textMax">
									<?php echo $RadMax; ?>&#8239;W/m²
									<?php if (!is_null($RadMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($RadMaxDt)).'</span>';
									} ?>
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Le <u>rayonnement solaire</u> maximale de la journée, entre 00h UTC et 23h59 UTC inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>Cumul d'ET</th>
								<td class="textSum">
									<?php echo $EtSum; ?>&#8239;mm
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Le <u>cumul d'évapotranspiration</u> de la journée, entre 00h UTC et 23h59 UTC inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-sm-8">
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Params.</th>
								<th>Minimum</th>
								<th>Maximum</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									Humidité
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="L'<u>humidité relative</u> minimale et maximale de la journée, entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textMin">
									<?php echo $HrMin; ?>&#8239;%
									<?php if (!is_null($HrMinDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($HrMinDt)).'</span>';
									} ?>
								</td>
								<td class="textMax">
									<?php echo $HrMax; ?>&#8239;%
									<?php if (!is_null($HrMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($HrMaxDt)).'</span>';
									} ?>
								</td>
							</tr>
							<tr>
								<th>
									Point de rosée
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="Le <u>point de rosée</u> minimal et maximal de la journée, entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textMin">
									<?php echo $TdMin; ?>&#8239;°C
									<?php if (!is_null($TdMinDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($TdMinDt)).'</span>';
									} ?>
								</td>
								<td class="textMax">
									<?php echo $TdMax; ?>&#8239;°C
									<?php if (!is_null($TdMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($TdMaxDt)).'</span>';
									} ?>
								</td>
							</tr>
							<tr>
								<th>
									Température ressentie
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>température ressentie</u> minimale et maximale de la journée, entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textMin">
									<?php echo $windChillMin; ?>&#8239;
									<?php if (!is_null($windChillMinDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($windChillMinDt)).'</span>';
									} ?>
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La température ressentie minimale correspond au windchill, aussi appelé refroidissement éolien, ou parfois facteur vent dans le langage populaire : désigne la sensation de froid produite par le vent sur un organisme qui dégage de la chaleur, alors que la température réelle de l'air ambiant ne s'abaisse pas. (Source : Wikipedia). <b>Cette information n'a pas d'unité et ne correspond pas à une température observée</b>.">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
								<td class="textMax">
									<?php echo $heatIndexMax; ?>&#8239;
									<?php if (!is_null($heatIndexMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($heatIndexMaxDt)).'</span>';
									} ?>
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La température ressentie maximale correspond à l'humidex, c'est un indice développé aux États-Unis qui combine la température de l'air ambiant et l'humidité relative pour tenter de déterminer la perception de la température que ressent le corps humain. (Source : Wikipedia). <b>Cette information n'a pas d'unité et ne correspond pas à une température observée</b>.">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</td>
							</tr>
							<tr>
								<th>
									Pression
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>pression atmosphérique</u> minimale et maximale de la journée, entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textMin">
									<?php echo $PrMin; ?>&#8239;hPa
									<?php if (!is_null($PrMinDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($PrMinDt)).'</span>';
									} ?>
								</td>
								<td class="textMax">
									<?php echo $PrMax; ?>&#8239;hPa
									<?php if (!is_null($PrMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($PrMaxDt)).'</span>';
									} ?>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Vent</th>
								<th>Vitesse</th>
								<th>Direction</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									Rafale max
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>rafale de vent</u> maximale de la journée, entre 00h UTC et 23h59 inclus">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textMax"><?php echo $windGustMax; ?>&#8239;km/h
									<?php if (!is_null($windGustMaxDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($windGustMaxDt)).'</span>';
									} ?>
									<?php
										if (is_numeric($windGustMaxPos) && $windGustMaxPos <= 30) {
											echo '<span class="badge badge-pill';
											if ($windGustMaxPos <= 10) { echo ' badge-success"'; } else { echo ' badge-light"'; }
											echo 'data-toggle="tooltip" data-placement="top" data-html="true" title="Rang de la rafale">';
											echo $windGustMaxPos;
											if ($windGustMaxPos == 1) {
												echo '<sup>&nbsp;ère</sup>';
											} else {
												echo '<sup>&nbsp;ème</sup>';
											}
											echo '</span>';
										}
									?>
								</td>
								<td><?php echo $windGustMaxDirCardinal.' ('.$windGustMaxDir; ?>&#8239;°)</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover table-sm">
						<thead>
							<tr>
								<th>Précips.</th>
								<th><?php list($jour, $mois) = explode('-', date('d-n', strtotime($optDay))); echo $jour.' '.$moisFrancaisAbrev[$mois];?><span class="d-md-none"><br></span><span class="textTabsHourly"> (ce&nbsp;jour)</span></th>
								<th><?php list($jour, $mois) = explode('-', date('d-n', strtotime($optYesterday))); echo $jour.' '.$moisFrancaisAbrev[$mois];?><span class="d-md-none"><br></span><span class="textTabsHourly"> (veille)</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>
									Cumul de pluie
									<span class="float-right">
										<svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip" data-placement="top" data-html="true" title="La <u>cumul de précipitations</u> de la journée (et celle d'hier à côté), est calculée d'après la méthode officielle OMM, il s'agit donc de la <u>somme des précipitations</u> qui se sont produites entre 06h UTC le jour même et 06h UTC le lendemain">
											<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
											<path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
											<circle cx="8" cy="4.5" r="1"/>
										</svg>
									</span>
								</th>
								<td class="textSum">
									<?php echo $RrAujd; ?>&#8239;mm
									<?php
										if (is_numeric($RrPos)  && $RrPos <= 30) {
											echo '<span class="badge badge-pill';
											if ($RrPos <= 10) { echo ' badge-success"'; } else { echo ' badge-light"'; }
											echo 'data-toggle="tooltip" data-placement="top" data-html="true" title="Rang du cumul quotidien">';
											echo $RrPos;
											if ($RrPos == 1) {
												echo '<sup>&nbsp;ère</sup>';
											} else {
												echo '<sup>&nbsp;ème</sup>';
											}
											echo '</span>';
										}
									?>
								</td>
								<td class="textSum"><?php echo $RrHier; ?>&#8239;mm</td>
							</tr>
							<tr>
								<th>Intensité pluie max.</th>
								<td><?php echo $RRateMaxAujd; ?>&#8239;mm/h
									<?php if (!is_null($RRateMaxAujdDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($RRateMaxAujdDt)).'</span>';
									} ?>
								</td>
								<td><?php echo $RRateMaxHier; ?>&#8239;mm/h
									<?php if (!is_null($RRateMaxHierDt)) {
										echo '<span class="textTabsHourly">à&nbsp;'.date('H:i',strtotime($RRateMaxHierDt)).'</span>';
									} ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<hr class="my-4">
			<!-- Tableau de données 10 minutes -->
			<div class="row">
				<div class="col-sm-12">
					<h4 class="text-center">Tableau de données</h4>
					<p class="text-justify">
						Affichage des enregistrements de la station météo, au pas de temps de 10 minutes, de la veille à 18h UTC, au lendemain à 06h UTC.
					</p>
					<div class="table-responsive table-scroll-quotidien">
						<table class="table table-striped table-bordered table-hover table-sm table-sticky">
							<thead>
								<tr>
									<th>Heure <span class="badge badge-primary">UTC</span></th>
									<th>Tempé.</th>
									<th>Humidité</th>
									<th>Td</th>
									<th>Pression</th>
									<th>Pluie/10min</th>
									<th>Int. max pluie</th>
									<th>Rafale/10min</th>
									<th>Dir. rafale</th>
									<th>Heure rafale</th>
									<?php if ($presence_uv) : ?>
									<th>UV</th>
									<?php endif; ?>
									<?php if ($presence_radiation) : ?>
									<th>Ray. sol.</th>
									<th>ET</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php
								date_default_timezone_set('UTC');
								foreach ($tabRecapQuoti as $ts => $value) {
									echo "<tr>";
									$dt = date('d/m H\hi',$ts);
									echo "<th>$dt</th>";
									echo "<td>".$value['TempMod']."&#8239;°C</td>";
									echo "<td>".$value['HrMod']."&#8239;%</td>";
									echo "<td>".$value['TdMod']."&#8239;°C</td>";
									echo "<td>".$value['barometerMod']."&#8239;hPa</td>";
									echo "<td>".$value['rainCumulMod']."&#8239;mm</td>";
									if ($value['rainCumulMod'] == '0') {
										echo "<td></td>";
									} else {
										echo "<td>".$value['rainRateMaxMod']."&#8239;mm/h</td>";
									}
									echo "<td>".$value['windGustMaxMod']."&#8239;km/h</td>";
									if ($value['windGustMaxMod'] == '0') {
										echo "<td></td>";
										echo "<td></td>";
									} else {
										echo "<td>".$value['windGustMaxDirMod']."&#8239;°</td>";
										echo "<td>".$value['windGustMaxdtMod']."</td>";
									}
									if ($presence_uv) {
										echo "<td>".$value['UvMod']."</td>";
									}
									if ($presence_radiation) {
										echo "<td>".$value['radiationMod']."&#8239;W/m²</td>";
										if (date('i',$ts) !== '00') {
											echo "<td></td>";
										} else {
											echo "<td>".$value['EtMod']."&#8239;mm/h</td>";
										}
									}
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<p class="d-none d-lg-block source bg-light text-center">
						⇧ <?php if (isset($countTabRecapQuoti)) {echo '<span class="badge badge-info">'.$countTabRecapQuoti.' lignes</span>';} else {echo '<span class="badge badge-danger">0 lignes</span>';}?> Principaux params. de la journée ⇧
					</p>
					<p class="d-lg-none source bg-light text-right">
						⇧ <?php if (isset($countTabRecapQuoti)) {echo '<span class="badge badge-info">'.$countTabRecapQuoti.' lignes</span>';} else {echo '<span class="badge badge-danger">0 lignes</span>';}?> Principaux params. de la journée ⇨
					</p>
				</div>
			</div>
			<hr class="my-4">
			<!-- Graphiques -->
			<div class="row">
				<div class="col-sm-12">
					<h4 class="text-center">Graphiques</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_temp_hygro" style="width:100%; height: 500px;"></div>
					<div class="text-center mt-1">
						<button type="button" class="btn btn-info" id="removeAnnoTnTx">⇧ Masquer les étiquettes ⇧</button>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_pression" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_vent" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_precip" style="width:100%; height:500px;"></div>
					<div class="text-center mt-1">
						<button type="button" class="btn btn-info" id="removeAnnoRR">⇧ Masquer les étiquettes ⇧</button>
					</div>
				</div>
			</div>
			<?php if ($presence_uv) : ?>
			<hr>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_uv" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($presence_radiation) : ?>
			<hr>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_rad" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-12">
					<div id="graph_et" style="width:100%; height:500px;"></div>
				</div>
			</div>
			<?php endif; ?>
			<hr>
			<!--
				DEBUT SCRIPT HIGHCHARTS
			-->
			<script>
				// Tn & Tx Labels
				<?php
					$annotJsTClim = array();
					foreach($dataTn as $annotation){
						$dateTn = date('d/m', $annotation['dateDay']/1000);
						$annotJsTClim[] = array(
							'id' => 'anno-TnTx',
							'labels' => array(array(
								'point' => array(
									'xAxis' => 0,
									'yAxis' => 0,
									'x' => $annotation['TnDt'],
									'y' => $annotation['Tn'],
								),
								'y' => 40,
								'text' => 'Tn du '.$dateTn.' : '.$annotation['Tn'].' °C',
							)),
							'labelOptions' => array(
								'borderRadius' => 5,
								'style' => array(
									'fontSize' => '8.5px'
								)
							),
							'shapeOptions' => array(
								'fill' => 'rgba(237, 237, 237, 0.7)'
							)
						);
					}
					foreach($dataTx as $annotation){
						$dateTx = date('d/m', $annotation['dateDay']/1000);
						$annotJsTClim[] = array(
							'id' => 'anno-TnTx',
							'labels' => array(array(
								'point' => array(
									'xAxis' => 0,
									'yAxis' => 0,
									'x' => $annotation['TxDt'],
									'y' => $annotation['Tx'],
								),
								'text' => 'Tx du '.$dateTx.' : '.$annotation['Tx'].' °C',
							)),
							'labelOptions' => array(
								'borderRadius' => 5,
								'style' => array(
									'fontSize' => '8.5px'
								)
							),
							'shapeOptions' => array(
								'fill' => 'rgba(237, 237, 237, 0.7)'
							)
						);
					}
					
					// echo "var LabelsPersoT = ".json_encode($annotJsTClim, JSON_PRETTY_PRINT).";";
					echo "var LabelsPersoT = ".json_encode($annotJsTClim).";";

				// RRClimato Labels
					$annotJsRRClim = array();
					foreach($dataRr as $annotation){
						$dateRRClim = date('d/m', strtotime($annotation['dateDay']));
						if ($annotation['RRmaxInt'] != null) {
							$dateRRMaxInt = date('H:i', $annotation['RRmaxIntDt']/1000);
							$annotJsRRClim[] = array(
								'id' => 'anno-RR',
								'labels' => array(array(
									'point' => array(
										'xAxis' => 0,
										'yAxis' => 0,
										'x' => $annotation['dateDay6h'],
										'y' => 0,
									),
									'useHTML' => true,
									'text' => 'Cumul du '.$dateRRClim.' : '.$annotation['RR'].' mm<br>Int.max '.$annotation['RRmaxInt'].' mm/h à '.$dateRRMaxInt,
								)),
								'labelOptions' => array(
									'borderRadius' => 5,
									'style' => array(
										'fontSize' => '8.5px'
									)
								),
								'shapeOptions' => array(
									'fill' => 'rgba(237, 237, 237, 0.7)'
								)
							);
						} else {
							$annotJsRRClim[] = array(
								'id' => 'anno-RR',
								'labels' => array(array(
									'point' => array(
										'xAxis' => 0,
										'yAxis' => 0,
										'x' => $annotation['dateDay6h'],
										'y' => 0,
									),
									'useHTML' => true,
									'text' => 'Cumul du '.$dateRRClim.' : '.$annotation['RR'].' mm',
								)),
								'labelOptions' => array(
									'borderRadius' => 5,
									'style' => array(
										'fontSize' => '8.5px'
									)
								),
								'shapeOptions' => array(
									'fill' => 'rgba(237, 237, 237, 0.7)'
								)
							);
						}
					}
					echo "var LabelsPersoRRClim = ".json_encode($annotJsRRClim).";";
				?>

				/*
					DEBUT GRAPHS
				*/
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
						xAxis: [{
							type: 'datetime',
							dateTimeLabelFormats: {day: '%H:%M', hour: '%H:%M'},
							tickInterval: 7200*1000,
							crosshair: true,
							plotLines: [{
								value: <?php echo $tsMinuit1 * 1000;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
									align: 'right',
									style:{font: 'bold 10px sans-serif', color: 'black'},
									rotation: -90,
									y: 10,
									x: 12,
								}
							},{
								value: <?php echo $tsMinuit2 * 1000;?>,
								dashStyle: 'ShortDash',
								width: 1,
								color: 'red',
								zIndex: 1,
								label: {
									text: 'minuit UTC',
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
					/*
						START GRAPH TEMP/HYGRO
					*/
					var temperature = Highcharts.chart ('graph_temp_hygro', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Température et humidité du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Tn et Tx aux normes OMM',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?>_Temperature',
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
							opposite: true,
							min:0,
							max: 100,
							tickPixelInterval: 30,
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
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
							// debug: {
							// 	showSkipSummary: true,
							// 	timeSeriesProcessing: true,
							// 	timeBufferCopy: true,
							// }
						},
						series: [{
							name: 'Température',
							id: 'series-temp',
							type: 'line',
							data: [<?php echo join($dataTemp, ',') ?>],
							// boostThreshold: 20,
							zIndex: 2,
							color: '#ff0000',
							negativeColor:'#0d1cc5',
							tooltip: {
								valueSuffix: ' °C',
							}
						},{
							name: 'Température min/max',
							linkedTo: 'series-temp',
							type: 'errorbar',
							yAxis: 0,
							color: '#ff0000',
							lineWidth: 1.2,
							tooltip: {
								pointFormat: 'Temp. min/max sur l\'intvl: {point.low}-{point.high}°C)<br/>'
							},
							zIndex: 20,
							data: [<?php echo join($dataTnTx, ',') ?>],
							showInLegend: true,
							visible: false,
							includeInDataExport : true
						},{
							name: 'Humidité',
							type: 'line',
							data: [<?php echo join($dataHr, ',') ?>],
							// boostThreshold: 20,
							yAxis: 1,
							color: '#3399FF',
							tooltip: {
								valueSuffix: ' %',
							}
						},{
							name: 'Point de rosée',
							type: 'line',
							data: [<?php echo join($dataTd, ',') ?>],
							// boostThreshold: 20,
							color: '#1c23e4',
							visible: false,
							tooltip: {
								valueSuffix: ' °C',
							}
						}],
						annotations: LabelsPersoT
					});
					/*
						START GRAPH pression
					*/
					var pression = Highcharts.chart ('graph_pression', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Pression atmo. du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?>_Pression',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
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
							shared: true,
							valueDecimals: 1,
							valueSuffix: ' hPa',
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
							// debug: {
							// 	showSkipSummary: true,
							// 	timeSeriesProcessing: true,
							// 	timeBufferCopy: true,
							// }
						},
						series: [{
							name: 'Pression',
							type: 'line',
							data: [<?php echo join($dataBaro, ',') ?>],
							// boostThreshold: 20,
							connectNulls: false,
							color: '#1be300',
						}]
					});
					/*
						START GRAPH VENT
					*/
					var data_wg = [<?php echo join($dataWg, ',') ?>];
					var vent = Highcharts.chart ('graph_vent', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Vent du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?>_Vent',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: [{
							// Axe 0
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
							categories: ['N (0°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','NE (45°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','E (90°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','SE (135°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','S (180°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','SO (225°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','O (270°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','NO (315°)','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','N (0°)'],
								endOnTick: true,
								tickInterval:45,
								minorTickInterval:45,
								title: {
								text: 'Direction du vent moyen',
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
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							headerFormat: '<small>{point.key}</small><br>----<br>',
						},
						plotOptions: {
							series: {
								marker: {
									enabled: false
								}
							}
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Rafales max.',
							type: 'line',
							color: 'rgba(255,0,0,0.65)',
							data: data_wg,
							// boostThreshold: 20,
							zIndex: 20,
							tooltip: {
								useHTML: true,
								pointFormatter: function(){
									if (this.y != 0) {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
										'<span style="color:'+this.series.color+'">\u25CF</span> Direction: <b>'+data_wg[this.index][2]+' °</b><br>'+
										'<span style="color:'+this.series.color+'">\u25CF</span> à <b>'+data_wg[this.index][3]+'</b><br>'+
										'----<br>';
									} else {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>'+
										'----<br>';
									}
								},
							}
						},{
							name: 'Direction des rafales',
							type: 'scatter',
							data: [<?php echo join($dataWgD, ',') ?>],
							// boostThreshold: 20,
							zIndex: 40,
							yAxis: 1,
							color:'rgba(148,0,28,0.75)',
							marker: {
								symbol: 'circle',
								enabled: true,
								lineWidth: 0,
								radius:2,
								color:'rgba(148,0,28,0.75)',
							},
							visible:false,
							enableMouseTracking: false,
						},{
							name: 'Vent moyen',
							type: 'line',
							color: 'rgba(51,153,255,0.75)',
							data: [<?php echo join($dataWs, ',') ?>],
							// boostThreshold: 20,
							zIndex: 10,
							tooltip: {
								useHTML: true,
								pointFormatter: function(){
									return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' km/h</b><br>';
								},
							}
						},{
							name: 'Direction moy. du vent moy.',
							type: 'scatter',
							data: [<?php echo join($dataWsD, ',') ?>],
							// boostThreshold: 20,
							zIndex: 30,
							yAxis: 1,
							color:'rgba(148,0,211,0.75)',
							marker: {
								symbol: 'circle',
								enabled: true,
								lineWidth: 0,
								radius:2,
								color:'rgba(148,0,211,0.75)',
							},
							visible:false,
							enableMouseTracking: false,
						}]
					});
					/*
						START GRAPH precip
					*/
					var precip = Highcharts.chart ('graph_precip', {
						chart: {
							type : 'area',
							zoomType: 'x',
						},
						title: {
							text: 'Précipitations du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres | Cumul à 6h UTC aux normes OMM',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?>_Precipitations',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: [{
							// Axe 0
							crosshair:true,
							lineColor: '#4169e1',
							lineWidth: 1,
							min:0,
							softMin:1,
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
						},{
							// Axe 1 Cumul pluie
							opposite: true,
							crosshair:true,
							lineColor: '#3d4147',
							min: 0,
							title: {
								text: 'Cumul précips. (mm)',
								style: {
									"color": "#3d4147",
								},
							},
							labels:{
								style: {
									"color": "#3d4147",
								},
							},
						},{
							// Axe 2 intensité pluie
							opposite: true,
							crosshair:true,
							lineColor: '#6883d9',
							min: 0,
							title: {
								text: 'Intensité précips. (mm)',
								style: {
									"color": "#6883d9",
								},
							},
							labels:{
								style: {
									"color": "#6883d9",
								},
							},
						}],
						tooltip: {
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Précipitations',
							type: 'column',
							zIndex: 1,
							data: [<?php echo join($dataRR, ',') ?>],
							// boostThreshold: 20,
							color: '#4169e1',
							tooltip: {
								valueSuffix: ' mm',
							}
						},{
							name: 'Cumul/5min',
							yAxis:1,
							type: 'line',
							zIndex: 3,
							data: [<?php echo join($dataRRCumul, ',') ?>],
							// boostThreshold: 20,
							color: '#3d4147',
							tooltip: {
								valueSuffix: ' mm',
							}
						},{
							name: 'Intensité',
							yAxis:2,
							visible: false,
							type: 'line',
							zIndex: 2,
							color: '#6883d9',
							data: [<?php echo join($dataRRate, ',') ?>],
							// boostThreshold: 20,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									if (this.y != 0) {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm/h</b><br>';
									} else {
										return '';
									}
								}
							}
						}],
						annotations: LabelsPersoRRClim
					});

					<?php if ($presence_uv) : ?>
					/*
						START GRAPH UV
					*/
					var uv = Highcharts.chart ('graph_uv', {
						chart: {
							type : 'area',
							zoomType: 'x',
							panning: true,
							panKey: 'shift'
						},
						title: {
							text: 'Indice UV du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?>_UV',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
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
							shared: true,
							valueDecimals: 1,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Indice UV',
							type: 'area',
							data: [<?php echo join($dataUV, ',') ?>],
							// boostThreshold: 20,
							connectNulls: false,
							color: '#ff7200',
						},{
							name: 'Indice UV min/max',
							type: 'errorbar',
							// yAxis: 0,
							color: '#ff7200',
							data: [<?php echo join($dataUvMinMax, ',') ?>],
							// boostThreshold: 20,
							tooltip: {
								pointFormat: 'Indice UV min/max sur l\'intvl: {point.low} - {point.high})<br/>'
							},
							zIndex: 10,
							showInLegend: true,
							visible: false
						}]
					});
					<?php endif; ?>

					<?php if ($presence_radiation) : ?>
					/*
						START GRAPH RADIATION
					*/
					var rad = Highcharts.chart ('graph_rad', {
						chart: {
							type : 'area',
							zoomType: 'x',
						},
						title: {
							text: 'Rayonnement solaire du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?> Rayonnement solaire',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
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
							shared: true,
							valueDecimals: 0,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
							valueSuffix: ' W/m²',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Rayonnement solaire',
							type: 'area',
							data: [<?php echo join($dataRadiation, ',') ?>],
							// boostThreshold: 20,
							color: '#e5d42b',
						},{
							name: 'Rayonnement sol. min/max',
							type: 'errorbar',
							// yAxis: 0,
							color: '#e5d42b',
							data: [<?php echo join($dataRadiationMinMax, ',') ?>],
							// boostThreshold: 20,
							tooltip: {
								pointFormat: 'Rad. min/max sur l\'intvl: {point.low} - {point.high})<br/>'
							},
							zIndex: 10,
							showInLegend: true,
							visible: false
						}]
					});
					/*
						START GRAPH ET
					*/
					var et = Highcharts.chart ('graph_et', {
						chart: {
							type : 'line',
							zoomType: 'x',
						},
						title: {
							text: 'Évapotranspiration du <?php echo date('d/m/Y',$tsOptDay) ?> en heure UTC',
						},
						subtitle: {
							text: 'Station <?php echo $station_name; ?> | Altitude : <?php echo $station_altitude; ?> mètres',
						},
						exporting: {
							filename: '<?php echo $short_station_name."_".date('Y-m-d',$tsOptDay); ?>_Évapotranspiration',
							sourceHeight: '500',
							sourceWidth: '1200',
							csv: {
								itemDelimiter:';',
								decimalPoint:'.'
							},
						},
						yAxis: {
							// Axe 0
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
							shared: true,
							valueDecimals: 3,
							xDateFormat: '<b>%e %B à %H:%M UTC</b>',
						},
						boost: {
							enabled:false,
							useGPUTranslations: false,
							seriesThreshold:1,
						},
						series: [{
							name: 'Évapotranspiration',
							type: 'column',
							data: [<?php echo join($dataET, ',') ?>],
							color: '#e5d42b',
							pointPadding: 0,
							groupPadding: 0,
							borderWidth: 0,
							shadow: false,
							borderWidth: 0,
							tooltip: {
								useHTML: true,
								pointFormatter: function () {
									if (this.y != 0) {
										return '<span style="color:'+this.series.color+'">\u25CF</span> '+this.series.name+': <b>'+this.y+' mm/heure</b><br>';
									} else {
										return '';
									}
								}
							}
						}]
					});
					<?php endif; ?>
					/*
						FIN DES GRAPHS
					*/
					$("#removeAnnoTnTx").click(function() {
						var l = temperature.annotations.length;
						for(var i = l-1; i >= 0; i-=1) {
							temperature.removeAnnotation(temperature.annotations[i]);
						}
					});
					$("#removeAnnoRR").click(function() {
						var l = precip.annotations.length;
						for(var i = l-1; i >= 0; i-=1) {
							precip.removeAnnotation(precip.annotations[i]);
						}
					});
				});
				
			</script>
			<!--
				FIN SCRIPT HIGHCHARTS
			-->

		<!-- FIN lessValue -->
		<?php endif; ?>

			<footer class="footer bg-light rounded">
				<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div>
	</body>
</html>
