<?php require_once '../config/config.php';?>
<?php require_once '../sql/connect_pdo.php';?>
<?php require_once '../sql/import.php';?>
<?php require_once '../include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | ADMIN - Extraction de données</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php include '../config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- JQUERY JS -->
		<script src="../content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="../content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="../content/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="../content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="../content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- ######### Pour un DatePicker ######### -->
		<!-- Font Awesome CSS for Tempus Dominus -->
		<link href="../content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
		<!-- Moment.js -->
		<script defer type="text/javascript" src="../content/moment/moment.js"></script>
		<script defer type="text/javascript" src="../content/moment/moment-locale-fr.js"></script>
		<!-- Tempus Dominus -->
		<script defer type="text/javascript" src="../content/tempusdominus/tempusdominus-bootstrap-4.min.js"></script>
		<link rel="stylesheet" href="../content/tempusdominus/tempusdominus-bootstrap-4.min.css" />
	</head>
	<body>
		<div class="container">
			<header>
				<?php include '../header.php';?>
			</header>
			<br>
			<nav>
				<?php include '../nav.php';?>
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

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center"> Extraction de données</h3>
				</div>
			</div>
			<!-- Texte prez -->
			<div class="row">
				<div class="col-md-12">
					<p class="text-justify">
						Cet outil vous permet de télécharger les données de la station "<?php echo $station_name; ?>" à différents pas de temps et <b>sur une période maximale de 3 mois</b>.
						<br>
						Le formulaire ci-dessous vous permet de sélectionner les paramètres que vous souhaitez, le pas de temps (1 heure, 10 minutes, ou brut (pour cette station, l'intervalle brut est actuellement de <?php echo $archive_interval; ?> minute(s))), et la période.
						<br>
						Le fichier CSV correspondant vous est proposé à la fin du processus, et reste disponible au téléchargement pendant un mois maximum.
						<br><br>
						<i>Cet outil est à destination des propriétaires pour un usage normal, et n'est pas une API de programmation pour un usage automatique.</i>
					</p>
				</div>
			</div>
			<hr class="my-4">
			<!-- Forms + docs -->
			<div class="row mb-4">
				<!-- Docs -->
				<div class="col-md-7">
					<h5 class="text-center">Documentation</h5>
					<p class="text-justify">
						Les dates de débuts et de fins sélectionnées dans le formulaire <b>doivent être exprimées en heure UTC</b>. <b>La période couverte</b> par la date de début et de fin doit être <b>inférieure ou égale à 3 mois</b>.
						<br>
						Le pas de temps au choix permet trois options :
						<ul>
							<li>
								<b>1 heure :</b> Les données sont alors agrégées chaque heure fixe (ex : hh:00). Dans le fichier CSV, les paramètres instantanés, tels que la température, l'humidité ou encore le taux de réception sont alors ceux de l'heure fixe (hh:00). Mais les valeurs <b>minimales et maximales sur l'intervalle de l'heure</b> seront aussi ajoutées dans le fichier CSV (ex : la température minimale et la température maximale seront ajoutées sous forme de deux colonnes supplémentaires, outTempMin et outTempMax).
								<br>
								Un paramètre comme la pluie sera lui <b>cumulé sur l'intervalle de l'heure</b>.
								<br>
								Un paramètre comme la rafale de vent fera alors apparaitre la <b>rafale maximale sur l'heure</b>, accompagnée de sa direction, et l'heure à laquelle elle s'est produite.
								<br><br>
								Ainsi, pour la température, l'enregistrement de <b>15h00</b> contiendra la température à 15h exact, ainsi que les valeurs min et max sur l'heure précédente, c'est-à-dire <b>de 14h01 INCLUS à 15h00 INCLUS</b>.
							</li>
							<br>
							<li>
								<b>10 minutes :</b> De la même manière que l'option précédente, tous les paramètres sont agrégés à 10 minutes (ex : hh:00, hh:10, hh:20, etc.).
							</li>
							<br>
							<li>
								<b>Brut :</b> Cette fois, ce sont les valeurs brutes telles que retransmisses par la station météo qui figureront dans le fichier CSV.
							</li>
						</ul>
						Vous pouvez sélectionner un seul paramètre météo, ou tous, selon votre besoin (en utilisant la touche Control, ou en cliquant puis glissant votre souris sur les champs).
					</p>
				</div>
				<!-- Forms -->
				<div class="col-md-5">
					<h5 class="text-center mb-3">Formulaire</h5>
					<form method="post" action="extract-data.php#csv">
						<!-- Nom de la station -->
						<div class="form-group row">
							<label for="stationName" class="col-sm-5 col-form-label">Nom de la station</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="stationName" name="stationName" value="<?php echo $station_name; ?>" disabled>
							</div>
						</div>
						<!-- Date de début -->
						<div class="form-group row">
							<label for="datetimepickerStart" class="col-sm-5 col-form-label">Date de début <span class="badge badge-primary">UTC</span></label>
							<div class="col-sm-7">
								<div class="input-group date" id="datetimepickerStart" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerStart" name="datetimepickerStart" required/>
										<div class="input-group-append" data-target="#datetimepickerStart" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										</div>
								</div>
							</div>
						</div>
						<!-- Date de fin -->
						<div class="form-group row">
							<label for="datetimepickerEnd" class="col-sm-5 col-form-label">Date de fin <span class="badge badge-primary">UTC</span></label>
							<div class="col-sm-7">
								<div class="input-group date" id="datetimepickerEnd" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerEnd" name="datetimepickerEnd" required/>
										<div class="input-group-append" data-target="#datetimepickerEnd" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										</div>
								</div>
							</div>
						</div>
						<!-- JS datetimepicker -->
						<script type="text/javascript">
							$(function () {
								var firstDay = '<?php echo $dtFisrtDay;?>';
								$('#datetimepickerStart').datetimepicker({
									locale: moment.locale('fr'),
									format: 'DD-MM-YYYY HH:mm',
									useCurrent: false,
									minDate: moment(firstDay, 'YYYY-MM-DD HH:mm'),
									maxDate: moment()
								});
								$('#datetimepickerEnd').datetimepicker({
									locale: moment.locale('fr'),
									format: 'DD-MM-YYYY HH:mm',
									minDate: moment(firstDay, 'YYYY-MM-DD HH:mm'),
									maxDate: moment()
								});
								$("#datetimepickerStart").on("change.datetimepicker", function (e) {
									$('#datetimepickerEnd').datetimepicker('minDate', e.date);
								});
								$("#datetimepickerEnd").on("change.datetimepicker", function (e) {
									$('#datetimepickerStart').datetimepicker('maxDate', e.date);
								});
							});
						</script>
						<!-- Pas de temps -->
						<div class="form-group row">
							<label for="pasDeTemps" class="col-sm-5 col-form-label">Pas de temps</label>
							<div class="col-sm-7">
								<select class="form-control" id="pasDeTemps" name="pasDeTemps" required>
									<option value="1hour">1 heure</option>
									<option value="10min">10 minutes</option>
									<option value="raw">Brut - <?php echo $archive_interval ?> minute(s)</option>
								</select>
							</div>
						</div>
						<!-- Paramètres -->
						<div class="form-group row">
							<label for="paramsMeteo" class="col-sm-5 col-form-label">Paramètres<br>(utiliser la touche Ctrl pour en sélectionner plusieurs)</label>
							<div class="col-sm-7">
								<select multiple="" size="17" class="form-control" id="paramsMeteo" name="paramsMeteo[]" required>
									<option value="outTemp">Température</option>
									<option value="outHumidity">Humidité</option>
									<option value="dewpoint">Point de rosée</option>
									<option value="barometer">Pression atmo.</option>
									<option value="rain">Cumul de précipitations</option>
									<option value="rainRate">Intensité de précipitations</option>
									<option value="UV">Indice UV</option>
									<option value="radiation">Rayonnement solaire</option>
									<option value="ET">Evapotranspiration</option>
									<option value="windGust">Rafale de vent</option>
									<option value="windSpeed">Vent moyen</option>
									<option value="inTemp">Température intérieure</option>
									<option value="inHumidity">Humidité intérieure</option>
									<option value="rxCheckPercent">Taux de réception</option>
									<option value="consBatteryVoltage">Tension de la console</option>
								</select>
							</div>
						</div>
						<!-- Validation -->
						<button type="submit" class="btn btn-primary btn-lg btn-block">Valider le formulaire</button>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?php
						// UTC
						date_default_timezone_set('UTC');

						// Controle du formulaire et mise en forme des requêtes
						if (isset($_POST['datetimepickerStart']) && isset($_POST['datetimepickerEnd'])
								&& isset($_POST['pasDeTemps']) && isset($_POST['paramsMeteo'])) {

								// Convert valeur des datetimepicker en timestamp
								$datetimepickerStart = DateTime::createFromFormat('d-m-Y H:i', $_POST['datetimepickerStart']);
								$tsStart = $datetimepickerStart->format('U');
								$datetimepickerEnd = DateTime::createFromFormat('d-m-Y H:i', $_POST['datetimepickerEnd']);
								$tsStop = $datetimepickerEnd->format('U');

								// Vérif que la requête ne dépasse pas 3 mois (93 jours)
								if ( ($tsStop - $tsStart) > (93 * 24 * 3600) ) {
									// Dépassement de la valeur
									echo '<div id="csv" class="alert alert-danger my-4" role="alert">';
										echo '<p class="text-justify">La période demandée est supérieure à 3 mois, merci de refaire votre requête pour une période inférieure.</p>';
									echo '</div>';
								} else {

									// Init du CSV
									$csvTab = prepareExtractCSV($db_handle_pdo, $_POST['pasDeTemps'], $tsStart, $tsStop);

									// Insertion du header
									$csvTab = insertHeaderCsvTab($_POST['pasDeTemps'], $_POST['paramsMeteo'], $csvTab);

									// Insertion des params instantannés
									$csvTab = extractParamsInst($db_handle_pdo,$_POST['pasDeTemps'],$tsStart, $tsStop, $_POST['paramsMeteo'], $csvTab);

									// Insertion des paramètres vent si le pas de temps n'est pas défini à brut et si on veut du vent
									if ($_POST['pasDeTemps'] !== 'raw') {
										if (in_array('windSpeed', $_POST['paramsMeteo']) || in_array('windGust', $_POST['paramsMeteo'])) {
											$csvTab = extractParamsWind($db_handle_pdo,$_POST['pasDeTemps'],$tsStart, $tsStop, $_POST['paramsMeteo'], $csvTab);
										}
									}

									// Insertion des params min, max et cumul pour les paramètres qui le nécéssitent et si le pas de temps n'est pas défini à brut
									if ($_POST['pasDeTemps'] !== 'raw') {
										$csvTab = extractParamsExtreme($db_handle_pdo,$_POST['pasDeTemps'],$tsStart, $tsStop, $_POST['paramsMeteo'], $csvTab);
									}

									// Insert dans le CSV
									$fieldsNumber = count($csvTab['header']);
									$csvFile = "./tempCSV/extract_".$station_name."_".date('Y-m-d',$tsStart)."_".date('Y-m-d',$tsStop)."_".date('Y-m-d-His').'.csv';
									$fp      = fopen($csvFile, 'w');
									fputcsv($fp,array_keys($csvTab['header']));
									foreach($csvTab as $k => $fields) {
										if (!isset($fields['dtUTC'])) continue;
										if ($k === 'header') continue;
										if ($k > $tsStop || $k < $tsStart) continue;
										$w = 0;
										foreach($csvTab['header'] as $fieldName => $_dummy) {
											$ligne = @$fields[$fieldName];
											$w ++;
											if ($w != $fieldsNumber) $ligne = $ligne.",";
											fwrite($fp, $ligne);
										}
										fwrite($fp, "\n");
									}
									$fcloseOK = fclose($fp);

									// Bouton de téléchargement
									echo '<div id="csv" class="alert alert-success my-4" role="alert">';
										echo '<p class="text-justify">La génération du fichier est terminée, vous pouvez le télécharger en cliquant sur le bouton ci-dessous.</p>';
										echo '<a role="button" class="btn btn-primary" href="'.$csvFile.'">Télécharger le fichier</a>';

										// echo '<pre>';
										// 	var_dump($_POST);
										// echo '</pre>';

										// echo '<pre>';
										// 	print_r($csvTab);
										// echo '</pre>';

									echo '</div>';
								}
						}
					?>
				</div>
			</div>
			<hr class="my-4">
			<div class="row">
				<div class="col-md-12">
					<h5 class="text-left">Vos précédentes requêtes :</h5>
					<div class="form-group row">
						<label for="extractFile" class="col-sm-3 col-form-label">Choisissez :</label>
						<div class="col-sm-9">
							<select class="form-control" id="extractFile" name="extractFile" onchange="downloadExtractFile(value)">
								<?php
									$path = "./tempCSV";
									$blacklist = array('.','..');
									// get everything except hidden files
									$files = preg_grep('/^([^.])/', scandir($path));
									// boucle
									foreach ($files as $file) {
										if (!in_array($file, $blacklist)) {
											$properName = $file; // substr("$file", 5, 7);
											echo '<option value="',$properName,'">',$properName,'</option>';
										}
									}
								?>
								<option selected value="#">- Selectionnez le fichier -</option>'
							</select>
						</div>
					</div>
					<script type="text/javascript">
						function downloadExtractFile(file){
							var url = "./tempCSV/"+file;
							window.location.href = url;
						}
					</script>
				</div>
			</div>
			<hr class="my-4">
			<footer class="footer bg-light">
				<?php include '../footer.php';?>
			</footer>
		</div>
	</body>
</html>
