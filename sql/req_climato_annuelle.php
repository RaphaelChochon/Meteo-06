<?php

/**
 * GENERAL
 * Préparation des dt et ts start et stop
 */

// Date UTC
	date_default_timezone_set('UTC');

// Détermine les start et stop
	$optYear_quoted = $db_handle_pdo->quote($optYear);
	$tsOptYear = strtotime($optYear.'-01'); // minuit de l'année selectionnée
	$dtOptYear = date('Y', $tsOptYear) ; // Y "protégé"

	$optLatestYear = date('Y', strtotime('-1 year', $tsOptYear));
	$optNextYear = date('Y', strtotime('+1 year', $tsOptYear));

	$latestDtOfYear = date('Y-12-31', $tsOptYear);

// Diff si journée en cours
	if (time() >= $tsOptYear && time() < strtotime(date('Y-12-t 23:59:59', $tsOptYear))) {
		$dtOptDayStart = DateTime::createFromFormat('U', $tsOptYear);
		$dtNow = new DateTime(date('D, d M Y H:i'));
		$dtInterval = $dtOptDayStart->diff($dtNow);
		$intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($dtInterval)->getTimeStamp();
		$intervalInMinutes = $intervalInSeconds/60;
		$nbDayInYear = 365;
		$percentIntervalInMinutes = round($intervalInMinutes * 100 / ($nbDayInYear * 24 * 60), 0);
	}


/**
 * VERIF COUNT DATA
 * L'objectif est de compter le nb d'enregistrement pour la date demandée
 * Si le nombre est inférieur à 10, on considère que la journée est null
 * Et on renvoit un message à l'utilisateur
 */
	$lessValue = false;
	$query_string = "SELECT COUNT(`dateDay`) AS `nbDt`
					FROM `$db_name_climato`.`$db_table_climato`
					WHERE `dateDay` LIKE '$dtOptYear%';";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
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
	 * Fiabilité
	 */
		$query_string = "SELECT AVG(`TnFiab`) AS `TnFiab`, AVG(`TxFiab`) AS `TxFiab`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%';";
		$result       = $db_handle_pdo->query($query_string);
		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$TnFiab = 0;
			if (!is_null($row['TnFiab'])) {
				$TnFiab = round($row['TnFiab'], 0);
			}
			$TxFiab = 0;
			if (!is_null($row['TxFiab'])) {
				$TxFiab = round($row['TxFiab'], 0);
			}
		}

	/**
	 * Création du tableau avec les ts de chaque mois de l'année
	 */
		$tabClimatoYear = getDatesFromRange($dtOptYear.'-01-01', $latestDtOfYear, 'P1M', false, 'Y-m');

	/**
	 * Récup des données pour l'année demandée
	 */
		$query_string = "SELECT
							`dateDay` AS `dateDay`,
							MIN(`Tn`) AS `TnMin`,
							AVG(`Tn`) AS `TnAvg`,
							MAX(`Tn`) AS `TnMax`,

							MAX(`Tx`) AS `TxMax`,
							AVG(`Tx`) AS `TxAvg`,
							MIN(`Tx`) AS `TxMin`,

							MIN(`Tmoy`) AS `TmoyMin`,
							AVG(`Tmoy`) AS `TmoyAvg`,
							MAX(`Tmoy`) AS `TmoyMax`,

							SUM(`RR`) AS `RrSum`,

							MAX(`windGust`) AS `WgMax`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						GROUP BY YEAR(`dateDay`), MONTH(`dateDay`)
						ORDER BY `dateDay` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$Tn          = null;
				$TnGraph     = "null";

				$Tx          = null;
				$TxGraph     = "null";

				$TmoyMin        = null;
				$TmoyAvg        = null;
				$TmoyMax        = null;
				$TmoyGraph   = "null";

				$RrSum          = null;
				$RrSumGraph     = "null";

				$windGust    = null;

				$dtDateMonth   = date('Y-m', strtotime($row['dateDay']));
				$tsDateMonthJS = (string) strtotime($row['dateDay'])*1000;

				// Insert dans le tableau
				$tabClimatoYear [$dtDateMonth] ['dateMonth'] = date('Y-m', strtotime($row['dateDay']));

				// Traitement des données
				// TnMin
				if (!is_null($row['TnMin'])) {
					$Tn = round($row['TnMin'], 1);
					$TnGraph = round($row['TnMin'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['TnMin'] = $Tn;
				$tabClimatoYear [$dtDateMonth] ['TnMinAbs'] = 0;
				$dataTnMin[] = "[$tsDateMonthJS, $TnGraph]";
				
				// TxMax
				if (!is_null($row['TxMax'])) {
					$Tx = round($row['TxMax'], 1);
					$TxGraph = round($row['TxMax'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['TxMax'] = $Tx;
				$tabClimatoYear [$dtDateMonth] ['TxMaxAbs'] = 0;
				$dataTxMax[] = "[$tsDateMonthJS, $TxGraph]";

				// TmoyMin
				if (!is_null($row['TmoyMin'])) {
					$TmoyMin = round($row['TmoyMin'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['TmoyMin'] = $TmoyMin;
				$tabClimatoYear [$dtDateMonth] ['TmoyMinAbs'] = 0;

				// TmoyAvg
				if (!is_null($row['TmoyAvg'])) {
					$TmoyAvg = round($row['TmoyAvg'], 1);
					$TmoyGraph = round($row['TmoyAvg'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['TmoyAvg'] = $TmoyAvg;
				$dataTmoy[] = "[$tsDateMonthJS, $TmoyGraph]";

				// TmoyMax
				if (!is_null($row['TmoyMax'])) {
					$TmoyMax = round($row['TmoyMax'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['TmoyMax'] = $TmoyMax;
				$tabClimatoYear [$dtDateMonth] ['TmoyMaxAbs'] = 0;

				// RrSum
				if (!is_null($row['RrSum'])) {
					$RrSum = round($row['RrSum'], 1);
					$RrSumGraph = round($row['RrSum'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['RrSum'] = $RrSum;
				$tabClimatoYear [$dtDateMonth] ['RrMaxAbs'] = 0;
				$dataRR[] = "[$tsDateMonthJS, $RrSumGraph]";

				// windGust
				if (!is_null($row['WgMax'])) {
					$windGust = round($row['WgMax'], 1);
				}
				$tabClimatoYear [$dtDateMonth] ['windGust'] = $windGust;
				$tabClimatoYear [$dtDateMonth] ['WgMaxAbs'] = 0;
			}
		}

	/**
	 * Récup de la Tn min absolue
	 */
		$query_string = "SELECT `dateDay`, `Tn`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						AND `Tn` = (
							SELECT MIN(`Tn`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptYear%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dtDateMonth = date('Y-m', strtotime($row['dateDay']));
				$TnMinYear = null;
				if (!is_null($row['Tn'])) {
					$TnMinYear = round($row['Tn'], 1);
					$tabClimatoYear [$dtDateMonth] ['TnMinAbs'] = 1;
				}
			}
		}

	/**
	 * Récup de la Tmoy min
	 */
		$query_string = "SELECT `dateDay`, `Tmoy`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						AND `Tmoy` = (
							SELECT MIN(`Tmoy`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptYear%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TmoyMinYear = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dtDateMonth = date('Y-m', strtotime($row['dateDay']));
				if (!is_null($row['Tmoy'])) {
					$TmoyMinYear = round($row['Tmoy'], 1);
					$tabClimatoYear [$dtDateMonth] ['TmoyMinAbs'] = 1;
				}
			}
		}

	/**
	 * Récup de la Tx max absolue
	 */
		$query_string = "SELECT `dateDay`, `Tx`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						AND `Tx` = (
							SELECT MAX(`Tx`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptYear%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TxMaxYear = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dtDateMonth = date('Y-m', strtotime($row['dateDay']));
				if (!is_null($row['Tx'])) {
					$TxMaxYear = round($row['Tx'], 1);
					$tabClimatoYear [$dtDateMonth] ['TxMaxAbs'] = 1;
				}
				
			}
		}

	/**
	 * Récup de la Tmoy max absolue
	 */
		$query_string = "SELECT `dateDay`, `Tmoy`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						AND `Tmoy` = (
							SELECT MAX(`Tmoy`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptYear%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TmoyMaxYear = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dtDateMonth = date('Y-m', strtotime($row['dateDay']));
				if (!is_null($row['Tmoy'])) {
					$TmoyMaxYear = round($row['Tmoy'], 1);
					$tabClimatoYear [$dtDateMonth] ['TmoyMaxAbs'] = 1;
				}
			}
		}

	/**
	 * Récup des RR max
	 */
		$query_string = "SELECT `dateDay`, `RR`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						AND `RR` = (
							SELECT MAX(`RR`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptYear%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dtDateMonth = date('Y-m', strtotime($row['dateDay']));
				$tabClimatoYear [$dtDateMonth] ['RrMaxAbs'] = 1;
				$RrMaxYear = $row['RR'];
			}
		}

	/**
	 * Récup de la rafale max
	 */
		$query_string = "SELECT `dateDay`, `windGust`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%'
						AND `windGust` = (
							SELECT MAX(`windGust`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptYear%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$dtDateMonth = date('Y-m', strtotime($row['dateDay']));
				$tabClimatoYear [$dtDateMonth] ['WgMaxAbs'] = 1;
				$WgMaxYear = $row['windGust'];
			}
		}

	/**
	 * Récup des autres Min et max
	 */
		$query_string = "SELECT MAX(`Tn`) AS `TnMax`,
								MIN(`Tx`) AS `TxMin`,
								AVG(`Tmoy`) AS `TmoyAvg`,
								SUM(`RR`) AS `RrSum`

						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptYear%';";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$TnMaxYear = null;
			$TxMinYear = null;
			$TmoyAvgYear = null;
			$RrSumYear = null;

			if (!is_null($row['TnMax'])) {
				$TnMaxYear = round($row['TnMax'], 1);
			}
			if (!is_null($row['TxMin'])) {
				$TxMinYear = round($row['TxMin'], 1);
			}
			if (!is_null($row['TmoyAvg'])) {
				$TmoyAvgYear = round($row['TmoyAvg'], 1);
			}
			if (!is_null($row['RrSum'])) {
				$RrSumYear = round($row['RrSum'], 1);
			}
		}
	
	/**
	 * Heatmap
	 */
	if ($heatmap) {
		/**
		 * @todo transférer le calcul du SUM RR dans une table ou en cache
		 */

		// Détermination des tsStart tsStop
		$tsStartYear =strtotime($dtOptYear.'-01-01 00:00:00');
		$tsStopYear =strtotime($dtOptYear.'-12-31 23:59:59');

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

} // END lessValue