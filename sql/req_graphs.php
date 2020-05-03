<?php
// Date UTC
	date_default_timezone_set('UTC');

if ($graphType == 'graphs') {
	// On détermine tsStop et tsStart
		$query_string = "SELECT `dateTime` FROM $db_table ORDER BY `dateTime` DESC LIMIT 1;";
		$result       = $db_handle_pdo->query($query_string);
		
		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("\n");
		}
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			// stop = dernier relevé dispo en BDD en timestamp Unix
			$tsStop = $row['dateTime'];

			// Arrondi du datetime Stop
			$datetimeStop = new DateTime();
			$datetimeStop->setTimestamp($tsStop);
			$dtStop = roundDownToMinuteInterval($datetimeStop);

			$dtStop = $dtStop->format("d-m-Y H:i:s");
			$tsStop = strtotime($dtStop);

			if ($period == '24h') {
				$tsStart = $tsStop-(24*3600);
				$textPeriod = '24 heures';
				$moduloSql = 300;
			} elseif ($period == '48h') {
				$tsStart = $tsStop-(2*24*3600);
				$textPeriod = '48 heures';
				$moduloSql = 600;
			} elseif ($period == '7j') {
				$tsStart = $tsStop-(7*24*3600);
				$textPeriod = '7 jours';
				$moduloSql = 3600;
			}

			$dtStart = date('d-m-Y H:i:s',$tsStart);

		}

	// 
		$minuit = strtotime('today midnight')*1000;
		$minuit_hier = strtotime('yesterday midnight')*1000;
		$minuit_3 = strtotime('-2 day midnight')*1000;
		$minuit_4 = strtotime('-3 day midnight')*1000;
		$minuit_5 = strtotime('-4 day midnight')*1000;
		$minuit_6 = strtotime('-5 day midnight')*1000;
		$minuit_7 = strtotime('-6 day midnight')*1000;
		$minuit_8 = strtotime('-7 day midnight')*1000;

		$dataWs = array();
		$dataWg = array();
		$dataWsD = array();
		$dataWgD = array();

		$cumulRR = 0;

	// Requete pour tous les params sauf le vent | 24/48h et 7 jours
		$query_string = "SELECT `dateTime` AS `ts`,
						`outTemp` AS `TempNow`,
						`outHumidity` AS `HrNow`,
						`dewpoint` AS `TdNow`,
						`barometer` AS `barometerNow`,
						`radiation` AS `radiationNow`,
						`UV` AS `UvNow`,
						`ET` AS `EtNow`
					FROM `archive`
					WHERE `dateTime` % $moduloSql = 0
					AND `dateTime` >= $tsStart
					AND `dateTime` <= $tsStop;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$TempNow      = "null";
				$HrNow        = "null";
				$TdNow        = "null";
				$barometerNow = "null";
				$rainRateNow  = "null";
				$radiationNow = "null";
				$UvNow        = "null";
				$EtNow        = "null";
				$ts = $row['ts']*1000;

			// Traitement des données
				// Temp
				if ($row['TempNow'] != null) {
					$TempNow = round($row['TempNow'],1);
				}
				$dataTemp[] = "[$ts, $TempNow]";

				// Humidité
				if ($row['HrNow'] != null) {
					$HrNow = round($row['HrNow'],1);
				}
				$dataHr[] = "[$ts, $HrNow]";

				// Point de rosée
				if ($row['TdNow'] != null) {
					$TdNow = round($row['TdNow'],1);
				}
				$dataTd[] = "[$ts, $TdNow]";

				// Barometer
				if ($row['barometerNow'] != null) {
					$barometerNow = round($row['barometerNow'],1);
				}
				$dataBaro[] = "[$ts, $barometerNow]";

				// UV
				if ($presence_uv){
					if ($row['UvNow'] != null) {
						$UvNow = round($row['UvNow'],1);
					}
					$dataUV[] = "[$ts, $UvNow]";
				}

				// Radiation & ET
				if ($presence_radiation){
					// Radiation
					if ($row['radiationNow'] != null) {
						$radiationNow = round($row['radiationNow'],0);
					}
					$dataRadiation[] = "[$ts, $radiationNow]";

					// ET
					if ($row['EtNow'] != null) {
						$EtNow = round($row['EtNow']*10,1);
					}
					$dataET[] = "[$ts, $EtNow]";
				}
			}
		}

	// PARAMS VENT RAFALES
		// On sort un tableau contenant le dt "CEIL", et ensuite le dt de la rafale max, sa direction et sa vitesse
		$query_string = "SELECT CEIL(`dateTime`/$moduloSql)*$moduloSql AS `ts`, a.`dateTime`, a.`windGust`, a.`windGustDir`
						FROM $db_table a
						INNER JOIN (
							SELECT CEIL(`dateTime`/$moduloSql)*$moduloSql AS `dtUTC2`, MAX(`windGust`) AS `windGustMax`
							FROM $db_table
							WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop
							GROUP BY `dtUTC2`
						) b
						ON CEIL(a.`dateTime`/$moduloSql)*$moduloSql = b.`dtUTC2` AND b.`windGustMax` = a.`windGust`
						WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop
						ORDER BY `a`.`dateTime` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$windGustMax10m    = "null";
				$windGustMaxDir10m = "null";
				$windGustMaxdt10m  = "null";
				$ts = $row['ts']*1000;
				if (!is_null ($row['windGust'])) {
					$windGustMax10m = round($row['windGust'],1);
					if (!is_null ($row['windGustDir'])) {
						$windGustMaxDir10m = round($row['windGustDir'],1);
						$windGustMaxdt10m = date('H\hi',$row['dateTime']);
					}
				}
				$dataWg[] = "[$ts, $windGustMax10m, $windGustMaxDir10m, '$windGustMaxdt10m']";
				$dataWgD[] = "[$ts, $windGustMaxDir10m]";
			}
		}

	// PARAMS VENT MOYEN VITESSE
		$query_string = "SELECT CEIL(`dateTime`/$moduloSql)*$moduloSql AS `ts`,
										AVG(`windSpeed`) AS `windSpeedAvg10m`
						FROM $db_table WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop GROUP BY `ts` ORDER BY `ts` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$windSpeedAvg10m = "null";
				$ts = $row['ts']*1000;
				if (!is_null ($row['windSpeedAvg10m'])) {
					$windSpeedAvg10m = round($row['windSpeedAvg10m'],1);
				}
				$dataWs[] = "[$ts, $windSpeedAvg10m]";
			}
		}

	// PARAMS VENT MOYEN direction
		$query_string = "SELECT CEIL(`dateTime`/$moduloSql)*$moduloSql AS `ts`,
										GROUP_CONCAT(`windDir`) AS `windDirConcat`
						FROM $db_table WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop GROUP BY `ts` ORDER BY `ts` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			// Construction du tableau
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$windDirArray = null;
				$windDirAvg10minTemp = null;
				$ts = $row['ts']*1000;
				if (!is_null($row['windDirConcat'])) {
					$windDirArray[] = explode(',', $row['windDirConcat']);
				}
				// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
				if (!is_null ($windDirArray)) {
					$windDirAvg10minTemp = mean_of_angles($windDirArray['0']);
				}
				// Vérif not null
				$windDirAvg10m = "null";
				if (!is_null ($windDirAvg10minTemp)) {
					$windDirAvg10m = round($windDirAvg10minTemp,1);
				}
				$dataWsD[] = "[$ts, $windDirAvg10m]";
			}
		}

	// PARAMS MIN MAX et SUM RR
		$query_string = "SELECT CEIL(`dateTime`/$moduloSql)*$moduloSql AS `ts`,
										MIN(`outTemp`) AS `TnMod`,
										MAX(`outTemp`) AS `TxMod`,
										SUM(`rain`) AS `rainCumulMod`,
										MAX(`rainRate`) AS `rainRateMaxMod`,
										MIN(`radiation`) AS `radiationMinMod`,
										MAX(`radiation`) AS `radiationMaxMod`,
										MIN(`UV`) AS `UvMinMod`,
										MAX(`UV`) AS `UvMaxMod`
						FROM $db_table WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop GROUP BY `ts` ORDER BY `ts` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$TnMod  = "null";
				$TxMod  = "null";
				$rainCumulMod = "null";
				$rainRateMaxMod = "null";
				$radiationMinMod = "null";
				$radiationMaxMod = "null";
				$UvMinMod = "null";
				$UvMaxMod = "null";
				$ts = $row['ts']*1000;

				//Tn
				if (!is_null ($row['TnMod'])) {
					$TnMod = round($row['TnMod'],1);
				}

				// Tx
				if (!is_null ($row['TxMod'])) {
					$TxMod = round($row['TxMod'],1);
				}
				$dataTnTx[] = "[$ts, $TnMod, $TxMod]";

				// RR + cumulRR
				if (!is_null ($row['rainCumulMod'])) {
					$rainCumulMod = round($row['rainCumulMod']*10,1);
					$RRincrement = $cumulRR;
					$cumulRR = $RRincrement + $rainCumulMod;
				}
				$dataRR[] = "[$ts, $rainCumulMod]";
				$dataRRCumul[] = "[$ts, $cumulRR]";

				//RRate
				if (!is_null ($row['rainRateMaxMod'])) {
					$rainRateMaxMod = round($row['rainRateMaxMod']*10,1);
				}
				$dataRRate[] = "[$ts, $rainRateMaxMod]";

				//Radiation
				if (!is_null ($row['radiationMinMod'])) {
					$radiationMinMod = round($row['radiationMinMod'],0);
				}
				if (!is_null ($row['radiationMaxMod'])) {
					$radiationMaxMod = round($row['radiationMaxMod'],0);
				}
				$dataRadiationMinMax[] = "[$ts, $radiationMinMod, $radiationMaxMod]";

				//UV
				if (!is_null ($row['UvMinMod'])) {
					$UvMinMod = round($row['UvMinMod'],1);
				}
				if (!is_null ($row['UvMaxMod'])) {
					$UvMaxMod = round($row['UvMaxMod'],1);
				}
				$dataUvMinMax[] = "[$ts, $UvMinMod, $UvMaxMod]";
			}
		}


	// CLIMATO
		$dateDay1 = date('Y-m-d',$tsStop);
		$dateDay5 = date('Y-m-d',$tsStop-(86400*7));

		$dataTn = array();
		$dataTx = array();
		$dataRRClimato = array();

		$query_string = "SELECT `dateDay` AS `dateDay`,
							`Tn` AS `Tn`,
							`TnDt` AS `TnDt`,
							`Tx` AS `Tx`,
							`TxDt` AS `TxDt`,
							`RR` AS `RR`,
							`RRateMax` AS `RRateMax`,
							`RRateMaxDt` AS `RRateMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` >= '$dateDay5' AND `dateDay` <= '$dateDay1'
						ORDER BY `dateDay` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dateDay = $row['dateDay'];

				// Tn
				$rowArrayTn['Tn'] = null;
				$rowArrayTn['TnDt'] = null;
				$rowArrayTn['dateDay'] = null;
				if ( !is_null($row['Tn']) ) {
					$rowArrayTn['Tn'] = round($row['Tn'],1);
				}
				if ( !is_null($row['TnDt']) ) { 
					$rowArrayTn['TnDt'] = strtotime($row['TnDt'])*1000;
				}
				if ( !is_null($row['dateDay']) ) {
					$rowArrayTn['dateDay'] = strtotime($row['dateDay'])*1000;
				}

				// Tx
				$rowArrayTx['Tx'] = null;
				$rowArrayTx['TxDt'] = null;
				$rowArrayTx['dateDay'] = null;
				if ( !is_null($row['Tx']) ) {
					$rowArrayTx['Tx'] = round($row['Tx'],1);
				}
				if ( !is_null($row['TxDt']) ) {
					$rowArrayTx['TxDt'] = strtotime($row['TxDt'])*1000;
				}
				if ( !is_null($row['dateDay']) ) {
					$rowArrayTx['dateDay'] = strtotime($row['dateDay'])*1000;
				}

				// RR
				$RowArrayRr['dateDay'] = $dateDay;
				$RowArrayRr['dateDay6h'] = (strtotime($dateDay) + (30 * 60 * 60)) * 1000;
				$RowArrayRr['RR'] = null;
				$RowArrayRr['RRmaxInt'] = null;
				$RowArrayRr['RRmaxIntDt'] = null;
				if ( !is_null($row['RR']) ) {
					$RowArrayRr['RR'] = round($row['RR'],1);
				}
				if ( !is_null($row['RRateMax']) ) {
					$RowArrayRr['RRmaxInt'] = round($row['RRateMax'],1);
					$RowArrayRr['RRmaxIntDt'] = strtotime($row['RRateMaxDt'])*1000;
				}

				array_push($dataTn,$rowArrayTn);
				array_push($dataTx,$rowArrayTx);
				array_push($dataRRClimato,$RowArrayRr);
			}
		}
}
elseif ($graphType == 'heatmap') {
	/**
	 * @todo transférer le calcul du SUM RR dans une table ou en cache
	 */

	// Détermination des tsStart tsStop
	$tsStartYear =strtotime($period.'-01-01 00:00:00');
	$tsStopYear =strtotime($period.'-12-31 23:59:59');

	// Récup des données de température et humidité
	$query_string = "SELECT `dateTime` AS `ts`,
					`outTemp` AS `TempHourly`,
					`outHumidity` AS `HumidityHourly`,
					`dewpoint` AS `DewPointHourly`
					FROM $db_table
					WHERE `dateTime` >= $tsStartYear AND `dateTime` <= $tsStopYear 
					AND (`dateTime`%3600)=0
					ORDER BY `ts` ASC;";
	$result       = $db_handle_pdo->query($query_string);
	
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
		exit("\n");
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$ts = strtotime(date('Y-m-d',$row['ts']))*1000;
			$heureHourly = date('G',$row['ts']); // heure au format 24h de 0 à 23
			$TempHourly        = "null";
			$HumidityHourly        = "null";
			$DewPointHourly        = "null";

			// Traitement des données
			if ($row['TempHourly'] != null) {
				$TempHourly = round($row['TempHourly'],1);
			}
			$dataTempHourly[] = "[$ts, $heureHourly, $TempHourly]";

			if ($row['HumidityHourly'] != null) {
				$HumidityHourly = round($row['HumidityHourly'],1);
			}
			$dataHumidityHourly[] = "[$ts, $heureHourly, $HumidityHourly]";

			if ($row['DewPointHourly'] != null) {
				$DewPointHourly = round($row['DewPointHourly'],1);
			}
			$dataDewPointHourly[] = "[$ts, $heureHourly, $DewPointHourly]";
		}
	}

	// Récup des données de précipitations
	$query_string = "SELECT CEIL(`dateTime`/3600)*3600 AS `ts`,
						SUM(`rain`) AS `rainCumulHourly`
						FROM $db_table
						WHERE `dateTime` >= $tsStartYear AND `dateTime` <= $tsStopYear
						GROUP BY `ts` ORDER BY `ts` ASC;";
	$result       = $db_handle_pdo->query($query_string);

	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
		exit("Erreur.\n");
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$ts = strtotime(date('Y-m-d',$row['ts']))*1000;
			$heureRrHourly = date('G',$row['ts']); // heure au format 24h de 0 à 23
			$RrHourly        = "null";

			// Traitement des données
			if ($row['rainCumulHourly'] != null) {
				$RrHourly = round($row['rainCumulHourly']*10,1);
			}
			$dataRrHourly[] = "[$ts, $heureRrHourly, $RrHourly]";
		}
	}

}
?>
