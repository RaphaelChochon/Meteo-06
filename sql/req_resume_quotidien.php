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

	$dataRr = array();

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
		if ($row['nbDt'] <= 1) {
			$lessValue = true;
		}
	}

// IF NOT lessValue
if (!$lessValue) {

	/**
	 * Récup des données pour la journée demandée
	 */
		$query_string = "SELECT *
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` = $optDay_quoted;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);

			// Tn
			$Tn     = "nd.";
			$TnDt   = null;
			$TnFiab = 0;
			// Annotations HC
			            $dataTn    = array();
			$rowArrayTn['Tn']      = null;
			$rowArrayTn['TnDt']    = null;
			$rowArrayTn['dateDay'] = strtotime($row['dateDay'])*1000;

			if (!is_null($row['Tn'])) {
				$Tn = round($row['Tn'], 1);
				$TnDt = $row['TnDt'];
				$rowArrayTn['Tn'] = $row['Tn']; // Annotations HC
				$rowArrayTn['TnDt'] = strtotime($row['TnDt'])*1000; // Annotations HC
			}
			if (!is_null($row['TnFiab'])) {
				$TnFiab = round($row['TnFiab'], 0);
			}
			array_push($dataTn,$rowArrayTn); // Annotations HC


			// Rang/Position de la Tn
			$resultTnRang = false;
			if (is_numeric($Tn)) {
				$query_string = "SELECT COUNT(`Tn`) AS `TnRang`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `Tn` <= ($Tn+1e-6);";
				$resultTnRang       = $db_handle_pdo->query($query_string);
			}
			$TnPos = "nd.";
			if ($resultTnRang) {
				$rowTnRang = $resultTnRang->fetch(PDO::FETCH_ASSOC);
				$TnPos = $rowTnRang['TnRang'];
			}

			// Tx
			$Tx     = "nd.";
			$TxDt   = null;
			$TxFiab = 0;
			// Annotations HC
			$dataTx    = array();
			$rowArrayTx['Tx']      = null;
			$rowArrayTx['TxDt']    = null;
			$rowArrayTx['dateDay'] = strtotime($row['dateDay'])*1000;

			if (!is_null($row['Tx'])) {
				$Tx = round($row['Tx'], 1);
				$TxDt = $row['TxDt'];
				$rowArrayTx['Tx'] = $row['Tx']; // Annotations HC
				$rowArrayTx['TxDt'] = strtotime($row['TxDt'])*1000; // Annotations HC
			}
			if (!is_null($row['TxFiab'])) {
				$TxFiab = round($row['TxFiab'], 0);
			}
			array_push($dataTx,$rowArrayTx); // Annotations HC

			// Rang/Position de la Tx
			$resultTxRang = false;
			if (is_numeric($Tx)) {
				$query_string = "SELECT COUNT(`Tx`) AS `TxRang`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `Tx` >= ($Tx-1e-6);";
				$resultTxRang       = $db_handle_pdo->query($query_string);
			}
			$TxPos = "nd.";
			if ($resultTxRang) {
				$rowTxRang = $resultTxRang->fetch(PDO::FETCH_ASSOC);
				$TxPos = $rowTxRang['TxRang'];
			}

			// Tmoy
			$Tmoy = "nd.";
			if (!is_null($row['Tmoy'])) {
				$Tmoy = round($row['Tmoy'], 1);
			}

			// Amplitude
			$TempRange = "nd.";
			if (!is_null($row['TempRange'])) {
				$TempRange = round($row['TempRange'], 1);
			}

			// UvMax
			$UvMax = "nd.";
			$UvMaxDt = null;
			if (!is_null($row['UvMax'])) {
				$UvMax = round($row['UvMax'], 1);
				$UvMaxDt = $row['UvMaxDt'];
			}

			// Rayonnement solaire
			$RadMax = "nd.";
			$RadMaxDt = null;
			if (!is_null($row['RadMax'])) {
				$RadMax = round($row['RadMax'], 0);
				$RadMaxDt = $row['RadMaxDt'];
			}

			// Hr
			$HrMin = "nd.";
			$HrMax = "nd.";
			$HrMinDt = null;
			$HrMaxDt = null;
			if (!is_null($row['HrMin'])) {
				$HrMin = round($row['HrMin'], 0);
				$HrMinDt = $row['HrMinDt'];
			}
			if (!is_null($row['HrMax'])) {
				$HrMax = round($row['HrMax'], 0);
				$HrMaxDt = $row['HrMaxDt'];
			}

			// Td
			$TdMin = "nd.";
			$TdMax = "nd.";
			$TdMinDt = null;
			$TdMaxDt = null;
			if (!is_null($row['TdMin'])) {
				$TdMin = round($row['TdMin'], 1);
				$TdMinDt = $row['TdMinDt'];
			}
			if (!is_null($row['TdMax'])) {
				$TdMax = round($row['TdMax'], 1);
				$TdMaxDt = $row['TdMaxDt'];
			}

			// Pression
			$PrMin = "nd.";
			$PrMax = "nd.";
			$PrMinDt = null;
			$PrMaxDt = null;
			if (!is_null($row['PrMin'])) {
				$PrMin = round($row['PrMin'], 1);
				$PrMinDt = $row['PrMinDt'];
			}
			if (!is_null($row['PrMax'])) {
				$PrMax = round($row['PrMax'], 1);
				$PrMaxDt = $row['PrMaxDt'];
			}

			// Tempé ressentie
			$windChillMin = "nd.";
			$windChillMinDt = null;
			$heatIndexMax = "nd.";
			$heatIndexMaxDt = null;
			if (!is_null($row['windChillMin'])) {
				$windChillMin = round($row['windChillMin'], 1);
				$windChillMinDt = $row['windChillMinDt'];
			}
			if (!is_null($row['heatIndexMax'])) {
				$heatIndexMax = round($row['heatIndexMax'], 1);
				$heatIndexMaxDt = $row['heatIndexMaxDt'];
			}

			// Rafale
			$windGustMax = "nd.";
			$windGustMaxDt = null;
			$windGustMaxDir = "nd.";
			$windGustMaxDirCardinal = "nd.";
			if (!is_null($row['windGust'])) {
				$windGustMax = round($row['windGust'], 1);
				$windGustMaxDt = $row['windGustDt'];
				if (!is_null($row['windGustDir'])) {
					$windGustMaxDir = round($row['windGustDir'], 1);
					$windGustMaxDirCardinal = wind_cardinals($windGustMaxDir);
				}
			}

			// Rang/Position de la rafale
			$resultWindGustRang = false;
			if (is_numeric($windGustMax)) {
				$query_string = "SELECT COUNT(`windGust`) AS `windGustRang`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `windGust` >= ($windGustMax-1e-6);";
				$resultWindGustRang       = $db_handle_pdo->query($query_string);
			}
			$windGustMaxPos = "nd.";
			if ($resultWindGustRang) {
				$rowWindGustRang = $resultWindGustRang->fetch(PDO::FETCH_ASSOC);
				$windGustMaxPos = $rowWindGustRang['windGustRang'];
			}

			// RR
			$RrAujd = "nd.";
			$RRateMaxAujd = 0;
			$RRateMaxAujdDt = null;
			// Annotations HC
			$rowArrayRr['RR'] = null;
			$rowArrayRr['RRmaxInt'] = null;
			$rowArrayRr['RRmaxIntDt'] = null;
			$rowArrayRr['dateDay'] = $row['dateDay'];
			$rowArrayRr['dateDay6h'] = (strtotime($row['dateDay'])+108000)*1000;
			if (!is_null($row['RR'])) {
				$RrAujd = round($row['RR'], 1);
				$rowArrayRr['RR'] = round($row['RR'],1); // Annotations HC
			}
			if (!is_null($row['RRateMax'])) {
				$RRateMaxAujd = round($row['RRateMax'], 1);
				$RRateMaxAujdDt = $row['RRateMaxDt'];
				$rowArrayRr['RRmaxInt'] = round($row['RRateMax'],1); // Annotations HC
				$rowArrayRr['RRmaxIntDt'] = strtotime($row['RRateMaxDt'])*1000; // Annotations HC
			}
			array_push($dataRr,$rowArrayRr); // Annotations HC

			// Rang/Position du cumul RR du jour
			$resultRrRang = false;
			if (is_numeric($RrAujd) && $RrAujd > 0) {
				$query_string = "SELECT COUNT(`RR`) AS `RrRang`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `RR` >= ($RrAujd-1e-6)
						AND `RR` != 0
						AND `RR` IS NOT NULL;";
				$resultRrRang       = $db_handle_pdo->query($query_string);
			}
			$RrPos = "nd.";
			if ($resultRrRang) {
				$rowRrRang = $resultRrRang->fetch(PDO::FETCH_ASSOC);
				$RrPos = $rowRrRang['RrRang'];
			}

			// ET
			$EtSum = "nd.";
			if (!is_null($row['EtSum'])) {
				$EtSum = round($row['EtSum'], 2);
			}
		}

	/**
	 * Récup de la pluie d'hier
	 */
		$query_string = "SELECT `dateDay`, `RR`, `RRateMax`, `RRateMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` = '$optYesterday';";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			// RR hier
			$RrHier = "nd.";
			$RRateMaxHier = 0;
			$RRateMaxHierDt = null;
			// Annotations HC
			$rowArrayRr['RR'] = null;
			$rowArrayRr['RRmaxInt'] = null;
			$rowArrayRr['RRmaxIntDt'] = null;
			$rowArrayRr['dateDay'] = $row['dateDay'];
			$rowArrayRr['dateDay6h'] = (strtotime($row['dateDay'])+108000)*1000;
			if (!is_null($row['RR'])) {
				$RrHier = round($row['RR'], 1);
				$rowArrayRr['RR'] = round($row['RR'],1); // Annotations HC
			}
			if (!is_null($row['RRateMax'])) {
				$RRateMaxHier = round($row['RRateMax'], 1);
				$RRateMaxHierDt = $row['RRateMaxDt'];
				$rowArrayRr['RRmaxInt'] = round($row['RRateMax'],1); // Annotations HC
				$rowArrayRr['RRmaxIntDt'] = strtotime($row['RRateMaxDt'])*1000; // Annotations HC
			}
			array_push($dataRr,$rowArrayRr); // Annotations HC
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
				
				if ($presence_uv){
					$tabRecapQuoti [$row['ts']] ['UvMod'] = $UvMod;
				}
				
				if ($presence_radiation){
					$tabRecapQuoti [$row['ts']] ['radiationMod'] = $radiationMod;
					$tabRecapQuoti [$row['ts']] ['EtMod'] = $EtMod;
				}
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
				// Si le ts est inférieur à 6h de la journée alors = 0
				$ts6h = $tsOptDay + (6 * 60 * 60); // 6h le jour meme
				if ( ($ts/1000) < $ts6h) {
					$cumulRR = 0;
				}
				$dataRR[] = "[$ts, $rainCumulMod]";
				$dataRRCumul[] = "[$ts, $cumulRR]";

				//RRate
				if (!is_null ($row['rainRateMaxMod'])) {
					$rainRateMaxMod = round($row['rainRateMaxMod']*10,1);
				}
				$dataRRate[] = "[$ts, $rainRateMaxMod]";

				//Radiation
				if ($presence_radiation) {
					if (!is_null ($row['radiationMinMod'])) {
						$radiationMinMod = round($row['radiationMinMod'],0);
					}
					if (!is_null ($row['radiationMaxMod'])) {
						$radiationMaxMod = round($row['radiationMaxMod'],0);
					}
					$dataRadiationMinMax[] = "[$ts, $radiationMinMod, $radiationMaxMod]";
				}

				//UV
				if ($presence_uv) {
					if (!is_null ($row['UvMinMod'])) {
						$UvMinMod = round($row['UvMinMod'],1);
					}
					if (!is_null ($row['UvMaxMod'])) {
						$UvMaxMod = round($row['UvMaxMod'],1);
					}
					$dataUvMinMax[] = "[$ts, $UvMinMod, $UvMaxMod]";
				}
			}
		}
} // fin lessValue

?>