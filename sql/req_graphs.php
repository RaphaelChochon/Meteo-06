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

	// appel du script de connexion
		/**
		 * @todo a remplacer par PDO
		 */
		require_once("connect.php");

	// Récupération des valeurs climatos
		$db_name_climato = "climato_station";
		$a = explode("_",$db_name);
		$aCount = count($a);
		if ($aCount == '2') {
			$db_table_climato = $a[1]."_day";
		} elseif ($aCount == '3') {
			$db_table_climato = $a[1]."_".$a[2]."_day";
		}

		$dateDay1 = date('Y-m-d',$tsStop);
		$dateDay5 = date('Y-m-d',$tsStop-(86400*7));

		$dataTn = array();
		$dataTx = array();
		$dataRRClimato = array();

		if ($result = mysqli_query($conn,
			"SELECT date_day AS dateTime, Tn AS Tn, Tn_datetime AS TnDt, Tx AS Tx, Tx_datetime AS TxDt,
			RR AS RR, RR_max_intensite AS RRmaxInt, RR_maxInt_datetime AS RRmaxIntDt
			FROM $db_name_climato.$db_table_climato
			WHERE date_day >= '$dateDay5' AND date_day <= '$dateDay1'
			ORDER BY Datetime ASC")){
			while($row = mysqli_fetch_array($result)) {
				$dateDay = $row['dateTime'];
				if ($row['Tn'] != null) { $rowArrayTn['Tn'] = round($row['Tn'],1); } else {$rowArrayTn['Tn'] = null;};
				if ($row['TnDt'] != null) { $rowArrayTn['TnDt'] = strtotime($row['TnDt'])*1000; } else {$rowArrayTn['TnDt'] = null;};
				if ($row['dateTime'] != null) { $rowArrayTn['dateDay'] = strtotime($row['dateTime'])*1000; } else {$rowArrayTn['dateDay'] = null;};

				if ($row['Tx'] != null) { $rowArrayTx['Tx'] = round($row['Tx'],1); } else {$rowArrayTx['Tx'] = null;};
				if ($row['TxDt'] != null) { $rowArrayTx['TxDt'] = strtotime($row['TxDt'])*1000; } else {$rowArrayTx['TxDt'] = null;};
				if ($row['dateTime'] != null) { $rowArrayTx['dateDay'] = strtotime($row['dateTime'])*1000; } else {$rowArrayTx['dateDay'] = null;};

				$rowArrayClim['dateDay'] = $dateDay;
				$rowArrayClim['dateDay6h'] = (strtotime($dateDay)+108000)*1000;
				if ($row['RR'] != null) { $rowArrayClim['RR'] = round($row['RR'],1); } else {$rowArrayClim['RR'] = null;};
				if ($row['RRmaxInt'] != null) { $rowArrayClim['RRmaxInt'] = round($row['RRmaxInt'],1); $rowArrayClim['RRmaxIntDt'] = strtotime($row['RRmaxIntDt'])*1000; } else {$rowArrayClim['RRmaxInt'] = null; $rowArrayClim['RRmaxIntDt'] = null; };

				array_push($dataTn,$rowArrayTn);
				array_push($dataTx,$rowArrayTx);
				array_push($dataRRClimato,$rowArrayClim);
			}
		}
		mysqli_close($conn);
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
