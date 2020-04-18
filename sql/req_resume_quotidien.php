<?php

/**
 * GENERAL
 * Préparation des dt et ts start et stop
 */

// Date UTC
	date_default_timezone_set('UTC');

// Détermine les start et stop
	$optDay_quoted = $db_handle_pdo->quote($optDay);
	$tsOptDay = strtotime($optDay); // minuit du jour selectionné
	$tsOptDayStart = $tsOptDay - (6 * 3600); // On enlève 6 heures pour tomber la veille à 18 heures
	$tsOptDayStop = $tsOptDay + ((24 + 6) * 3600); // On ajoute 24 + 6 heures pour tomber le lendemain à 6 heures

	$tsMinuit1 = $tsOptDay;
	$tsMinuit2 = ($tsMinuit1 + (24 * 3600));

	$optYesterday = date('Y-m-d', strtotime($optDay.'-1 day'));
	$optTomorrow = date('Y-m-d', strtotime($optDay.'+1 day'));

// Diff si journée en cours
	if (time() >= $tsOptDayStart && time() < $tsOptDayStop) {
		$dtOptDayStart = DateTime::createFromFormat('U', $tsOptDayStart);
		$dtNow = new DateTime(date('D, d M Y H:i'));
		$dtInterval = $dtOptDayStart->diff($dtNow);
		$intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($dtInterval)->getTimeStamp();
		$intervalInMinutes = $intervalInSeconds/60;
		$percentIntervalInMinutes = round($intervalInMinutes * 100 / 2160,0);
	}

/**
 * VERIF COUNT DATA
 * L'objectif est de compter le nb d'enregistrement pour la date demandée
 * Si le nombre est inférieur à 10, on considère que la journée est null
 * Et on renvoit un message à l'utilisateur
 */
	$lessValue = false;
	$query_string = "SELECT COUNT(`dateTime`) AS `nbDt`
					FROM $db_table
					WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` < '$tsOptDayStop';";
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
		if ($row['nbDt'] == 0) {
			$lessValue = true;
		}
	}

// IF NOT lessValue
if (!$lessValue) {

	/**
	 * STATS OMM
	 * Calcul des Tn, Tx, min et max de chaque params et somme de pluie et ET
	 */
	
	// Tn
		$tsStop18h = $tsOptDayStart + (24*3600); // 18h le jour meme
		$query_string = "SELECT MIN(`outTemp`) AS TnDay
						FROM $db_table
						WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` < '$tsStop18h';";
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
			$TnDay = 'N/A';
			if (!is_null($row['TnDay'])) {
				$TnDay = round($row['TnDay'],1);
			}
		}

	// Tx + RR + RRate
		$tsStart6h = $tsOptDayStop - (24*3600); // 6h le jour meme
		$query_string = "SELECT MAX(`outTemp`) AS TxDay,
								SUM(`rain`) AS RrCumul,
								MAX(`rainRate`) AS RRateMax
						FROM $db_table
						WHERE `dateTime` >= '$tsStart6h' AND `dateTime` < '$tsOptDayStop';";
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
			$TxDay = 'N/A';
			$RrCumul = 'N/A';
			$RRateMax = 'N/A';
			if (!is_null($row['TxDay'])) {
				$TxDay = round($row['TxDay'],1);
			}
			if (!is_null($row['RrCumul'])) {
				$RrCumul = round($row['RrCumul']*10,1);
			}
			if (!is_null($row['RRateMax'])) {
				$RRateMax = round($row['RRateMax']*10,1);
			}
		}

	// Hr + Td + P
		$query_string = "SELECT MIN(`outHumidity`) AS HrMin,
								MAX(`outHumidity`) AS HrMax,
								MIN(`dewpoint`) AS TdMin,
								MAX(`dewpoint`) AS TdMax,
								MIN(`barometer`) AS PrMin,
								MAX(`barometer`) AS PrMax,
								MAX(`UV`) AS UvMax,
								MAX(`radiation`) AS RadMax,
								SUM(`ET`) AS EtCumul,
								MAX(`ET`) AS EtMax
						FROM $db_table
						WHERE `dateTime` >= '$tsMinuit1' AND `dateTime` < '$tsMinuit2';";
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
			$HrMin = 'N/A';
			$HrMax = 'N/A';
			$TdMin = 'N/A';
			$TdMax = 'N/A';
			$PrMin = 'N/A';
			$PrMax = 'N/A';
			$UvMax = 'N/A';
			$RadMax = 'N/A';
			$EtCumul = 'N/A';
			$EtMax = 'N/A';
			if (!is_null($row['HrMin'])) {
				$HrMin = round($row['HrMin'],0);
			}
			if (!is_null($row['HrMax'])) {
				$HrMax = round($row['HrMax'],0);
			}
			if (!is_null($row['TdMin'])) {
				$TdMin = round($row['TdMin'],1);
			}
			if (!is_null($row['TdMax'])) {
				$TdMax = round($row['TdMax'],1);
			}
			if (!is_null($row['PrMin'])) {
				$PrMin = round($row['PrMin'],1);
			}
			if (!is_null($row['PrMax'])) {
				$PrMax = round($row['PrMax'],1);
			}
			if (!is_null($row['UvMax'])) {
				$UvMax = round($row['UvMax'],1);
			}
			if (!is_null($row['RadMax'])) {
				$RadMax = round($row['RadMax'],0);
			}
			if (!is_null($row['EtCumul'])) {
				$EtCumul = round($row['EtCumul'],2);
			}
			if (!is_null($row['EtMax'])) {
				$EtMax = round($row['EtMax'],2);
			}
		}

	/**
	 * TABLEAU DATA
	 */

	// Récup des xx derniers enregistrement avec modulo 30 minutes
		// On récupère les enregistrements en BDD
		$query_string = "SELECT `dateTime` AS `ts`,
							`outTemp` AS `TempMod`,
							`outHumidity` AS `HrMod`,
							`dewpoint` AS `TdMod`,
							`barometer` AS `barometerMod`,
							`radiation` AS `radiationMod`,
							`UV` AS `UvMod`,
							`ET` AS `EtMod`
						FROM $db_table
						WHERE `dateTime` % 600 = 0
						AND `dateTime` >= '$tsOptDayStart'
						AND `dateTime` < '$tsOptDayStop'
						ORDER BY `dateTime` ASC;";
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
				$TempMod      = 'N/A';
				$HrMod        = 'N/A';
				$TdMod        = 'N/A';
				$barometerMod = 'N/A';
				$rainRateMod  = 'N/A';
				$radiationMod = 'N/A';
				$UvMod        = 'N/A';
				$row['ts'] = (string)round($row['ts']);

				// Insert dans le tableau
				$tabRecapQuoti [$row['ts']] = array();

			// Traitement des données
				// Temp
				if ($row['TempMod'] != null) {
					$TempMod = round($row['TempMod'],1);
				}

				// Humidité
				if ($row['HrMod'] != null) {
					$HrMod = round($row['HrMod'],1);
				}

				// Point de rosée
				if ($row['TdMod'] != null) {
					$TdMod = round($row['TdMod'],1);
				}

				// Barometer
				if ($row['barometerMod'] != null) {
					$barometerMod = round($row['barometerMod'],1);
				}

				// UV
				if ($presence_uv){
					if ($row['UvMod'] != null) {
						$UvMod = round($row['UvMod'],1);
					}
				}

				// Radiation & ET
				if ($presence_radiation){
					// Radiation
					if ($row['radiationMod'] != null) {
						$radiationMod = round($row['radiationMod'],0);
					}

					// ET
					if ($row['EtMod'] != null) {
						$EtMod = round($row['EtMod']*10,1);
					}
				}

				// Insert dans le tableau
				$tabRecapQuoti [$row['ts']] ['TempMod'] = $TempMod;
				$tabRecapQuoti [$row['ts']] ['HrMod'] = $HrMod;
				$tabRecapQuoti [$row['ts']] ['TdMod'] = $TdMod;
				$tabRecapQuoti [$row['ts']] ['barometerMod'] = $barometerMod;
				$tabRecapQuoti [$row['ts']] ['radiationMod'] = $radiationMod;
				$tabRecapQuoti [$row['ts']] ['UvMod'] = $UvMod;
				$tabRecapQuoti [$row['ts']] ['EtMod'] = $EtMod;
			}
		}

	// Extrèmes et cumul
		$query_string = "SELECT CEIL(`dateTime`/600)*600 AS `ts`,
										SUM(`rain`) AS `rainCumulMod`,
										MAX(`rainRate`) AS `rainRateMaxMod`
						FROM $db_table
						WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` < '$tsOptDayStop'
						GROUP BY `ts` ORDER BY `ts` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result and $debug) {
			// Erreur et debug activé
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$rainCumulMod = null;
				$rainRateMaxMod = null;
				$row['ts'] = (string)round($row['ts']);
				
				if (!is_null ($row['rainCumulMod'])) {
					$rainCumulMod = round($row['rainCumulMod']*10,1);
				}
				if (!is_null ($row['rainRateMaxMod'])) {
					$rainRateMaxMod = round($row['rainRateMaxMod']*10,1);
				}

				// Insertion dans le tableau des données
				if (isset($tabRecapQuoti [$row['ts']])) {
					$tabRecapQuoti [$row['ts']] ['rainCumulMod'] = $rainCumulMod;
					$tabRecapQuoti [$row['ts']] ['rainRateMaxMod'] = $rainRateMaxMod;
				}
			}
		}

	// Rafales max 30 minutes
		// On sort un tableau contenant le dt "CEIL", et ensuite le dt de la rafale max, sa direction et sa vitesse
		$query_string = "SELECT CEIL(`dateTime`/600)*600 AS `ts`, a.`dateTime`, a.`windGust`, a.`windGustDir`
						FROM $db_table a
						INNER JOIN (
							SELECT CEIL(`dateTime`/600)*600 AS `dtUTC2`, MAX(`windGust`) AS `windGustMax`
							FROM $db_table
							WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` < '$tsOptDayStop'
							GROUP BY `dtUTC2`
						) b
						ON CEIL(a.`dateTime`/600)*600 = b.`dtUTC2` AND b.`windGustMax` = a.`windGust`
						WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` < '$tsOptDayStop'
						ORDER BY `a`.`dateTime` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result and $debug) {
			// Erreur et debug activé
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
			exit("Erreur.\n");
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$windGustMaxMod    = null;
				$windGustMaxDirMod = null;
				$windGustMaxdtMod  = null;
				$row['ts'] = (string)round($row['ts']);
				if (!is_null ($row['windGust'])) {
					$windGustMaxMod = round($row['windGust'],1);
					if (!is_null ($row['windGustDir'])) { $windGustMaxDirMod = round($row['windGustDir'],1); }
					$windGustMaxdtMod = date('H:i',$row['dateTime']);
				}
				// Insertion dans le tableau des données
				// Sauf que notre résultat comprend des doublons (plusieurs rafales max identique dans la même heure), donc on n'insert que si la valeur n'a pas déjà été enregistrée pour cette même KEY (ts) == On garde donc seulement la première rafale max
				if (isset($tabRecapQuoti [$row['ts']]) && !isset($tabRecapQuoti [$row['ts']] ['windGustMaxMod'])) {
					$tabRecapQuoti [$row['ts']] ['windGustMaxMod'] = $windGustMaxMod;
					$tabRecapQuoti [$row['ts']] ['windGustMaxDirMod'] = $windGustMaxDirMod;
					$tabRecapQuoti [$row['ts']] ['windGustMaxdtMod'] = $windGustMaxdtMod;
				}
			}
		}

	// Taille du tableau
		$countTabRecapQuoti = count($tabRecapQuoti);



	/**
	 * GRAPHIQUES
	 */
	$cumulRR = 0;

	// Requete pour tous les params sauf le vent avec modulo de 5 minutes
		$query_string = "SELECT `dateTime` AS `ts`,
						`outTemp` AS `TempNow`,
						`outHumidity` AS `HrNow`,
						`dewpoint` AS `TdNow`,
						`barometer` AS `barometerNow`,
						`radiation` AS `radiationNow`,
						`UV` AS `UvNow`,
						`ET` AS `EtNow`
					FROM `archive`
					WHERE `dateTime` % 300 = 0
					AND `dateTime` >= '$tsOptDayStart'
					AND `dateTime` <= '$tsOptDayStop';";
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
		$query_string = "SELECT CEIL(`dateTime`/300)*300 AS `ts`, a.`dateTime`, a.`windGust`, a.`windGustDir`
						FROM $db_table a
						INNER JOIN (
							SELECT CEIL(`dateTime`/300)*300 AS `dtUTC2`, MAX(`windGust`) AS `windGustMax`
							FROM $db_table
							WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` <= '$tsOptDayStop'
							GROUP BY `dtUTC2`
						) b
						ON CEIL(a.`dateTime`/300)*300 = b.`dtUTC2` AND b.`windGustMax` = a.`windGust`
						WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` <= '$tsOptDayStop'
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
				$windGustMax5m    = "null";
				$windGustMaxDir5m = "null";
				$windGustMaxdt5m  = "null";
				$ts = $row['ts']*1000;
				if (!is_null ($row['windGust'])) {
					$windGustMax5m = round($row['windGust'],1);
					if (!is_null ($row['windGustDir'])) {
						$windGustMaxDir5m = round($row['windGustDir'],1);
						$windGustMaxdt5m = date('H\hi',$row['dateTime']);
					}
				}
				$dataWg[] = "[$ts, $windGustMax5m, $windGustMaxDir5m, '$windGustMaxdt5m']";
				$dataWgD[] = "[$ts, $windGustMaxDir5m]";
			}
		}

	// PARAMS VENT MOYEN VITESSE
		$query_string = "SELECT CEIL(`dateTime`/300)*300 AS `ts`,
										AVG(`windSpeed`) AS `windSpeedAvg5m`
						FROM $db_table WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` <= '$tsOptDayStop'
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
				$windSpeedAvg5m = "null";
				$ts = $row['ts']*1000;
				if (!is_null ($row['windSpeedAvg5m'])) {
					$windSpeedAvg5m = round($row['windSpeedAvg5m'],1);
				}
				$dataWs[] = "[$ts, $windSpeedAvg5m]";
			}
		}

	// PARAMS VENT MOYEN direction
		$query_string = "SELECT CEIL(`dateTime`/300)*300 AS `ts`,
										GROUP_CONCAT(`windDir`) AS `windDirConcat`
						FROM $db_table WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` <= '$tsOptDayStop'
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
			// Construction du tableau
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$windDirArray = null;
				$windDirAvg5minTemp = null;
				$ts = $row['ts']*1000;
				if (!is_null($row['windDirConcat'])) {
					$windDirArray[] = explode(',', $row['windDirConcat']);
				}
				// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
				if (!is_null ($windDirArray)) {
					$windDirAvg5minTemp = mean_of_angles($windDirArray['0']);
				}
				// Vérif not null
				$windDirAvg5m = "null";
				if (!is_null ($windDirAvg5minTemp)) {
					$windDirAvg5m = round($windDirAvg5minTemp,1);
				}
				$dataWsD[] = "[$ts, $windDirAvg5m]";
			}
		}

	// PARAMS MIN MAX et SUM RR
		$query_string = "SELECT CEIL(`dateTime`/300)*300 AS `ts`,
										MIN(`outTemp`) AS `TnMod`,
										MAX(`outTemp`) AS `TxMod`,
										SUM(`rain`) AS `rainCumulMod`,
										MAX(`rainRate`) AS `rainRateMaxMod`,
										MIN(`radiation`) AS `radiationMinMod`,
										MAX(`radiation`) AS `radiationMaxMod`,
										MIN(`UV`) AS `UvMinMod`,
										MAX(`UV`) AS `UvMaxMod`
						FROM $db_table WHERE `dateTime` >= '$tsOptDayStart' AND `dateTime` <= '$tsOptDayStop'
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

	// Récupération des valeurs climatos
		$db_name_climato = "climato_station";
		$a = explode("_",$db_name);
		$aCount = count($a);
		if ($aCount == '2') {
			$db_table_climato = $a[1]."_day";
		} elseif ($aCount == '3') {
			$db_table_climato = $a[1]."_".$a[2]."_day";
		}

		$dataTn = array();
		$dataTx = array();
		$dataRRClimato = array();

		$query_string = "SELECT date_day AS dateTime,
							Tn AS Tn, Tn_datetime AS TnDt,
							Tx AS Tx, Tx_datetime AS TxDt,
							RR AS RR, RR_max_intensite AS RRmaxInt, RR_maxInt_datetime AS RRmaxIntDt
						FROM $db_name_climato.$db_table_climato
						WHERE date_day = $optDay_quoted;";
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
} // fin lessValue

?>