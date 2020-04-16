<?php

// Récup des xx derniers enregistrement avec modulo 30/10 minutes
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
					WHERE `dateTime` % 1800 = 0
					AND `dateTime` >= '1586887200'
					AND `dateTime` < '1587016800'
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
	$query_string = "SELECT CEIL(`dateTime`/1800)*1800 AS `ts`,
									SUM(`rain`) AS `rainCumulMod`,
									MAX(`rainRate`) AS `rainRateMaxMod`
					FROM $db_table
					WHERE `dateTime` >= '1586887200' AND `dateTime` < '1587016800'
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

// PARAMS VENT RAFALES 1 heure
	// On sort un tableau contenant le dt "CEIL", et ensuite le dt de la rafale max, sa direction et sa vitesse
	$query_string = "SELECT CEIL(`dateTime`/1800)*1800 AS `ts`, a.`dateTime`, a.`windGust`, a.`windGustDir`
					FROM $db_table a
					INNER JOIN (
						SELECT CEIL(`dateTime`/1800)*1800 AS `dtUTC2`, MAX(`windGust`) AS `windGustMax`
						FROM $db_table
						WHERE `dateTime` >= 1586887200 AND `dateTime` < 1587016800
						GROUP BY `dtUTC2`
					) b
					ON CEIL(a.`dateTime`/1800)*1800 = b.`dtUTC2` AND b.`windGustMax` = a.`windGust`
					WHERE `dateTime` >= 1586887200 AND `dateTime` < 1587016800
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

?>