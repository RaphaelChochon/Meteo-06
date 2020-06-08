<?php

/**
 * GENERAL
 * Préparation des dt et ts start et stop
 */

// Date UTC
	date_default_timezone_set('UTC');

// Détermine les start et stop
	$optMonth_quoted = $db_handle_pdo->quote($optMonth);
	$tsOptMonth = strtotime($optMonth); // minuit du mois selectionné
	$dtOptMonth = date('Y-m', $tsOptMonth) ; // Y-m "protégé"
	// $tsOptMonthStart = $tsOptMonth;
	// $tsOptMonthStop = $tsOptMonth + ((24 + 6) * 3600); // On ajoute 24 + 6 heures pour tomber le lendemain à 6 heures

	$optLatestMonth = date('Y-m', strtotime('-1 month', $tsOptMonth));
	$optNextMonth = date('Y-m', strtotime('+1 month', $tsOptMonth));

	$latestDtOfMonth = date('Y-m-t', $tsOptMonth);

// Diff si journée en cours
	// if (time() >= $tsOptMonth && time() < strtotime(date('Y-m-d H:i:s', mktime(23, 59, 59, date('n', strtotime($optNextMonth)), 0, date('Y', $tsOptMonth))))) {
	if (time() >= $tsOptMonth && time() < strtotime(date('Y-m-t H:i:s', $tsOptMonth))) {
		$dtOptDayStart = DateTime::createFromFormat('U', $tsOptMonth);
		$dtNow = new DateTime(date('D, d M Y H:i'));
		$dtInterval = $dtOptDayStart->diff($dtNow);
		$intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($dtInterval)->getTimeStamp();
		$intervalInMinutes = $intervalInSeconds/60;
		$nbDayInMonth = date('d', mktime(23, 59, 59, date('n', strtotime($optNextMonth)), 0, date('Y', $tsOptMonth)));
		$percentIntervalInMinutes = round($intervalInMinutes * 100 / ($nbDayInMonth * 24 * 60), 0);
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
					WHERE `dateDay` LIKE '$dtOptMonth%';";
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
						WHERE `dateDay` LIKE '$dtOptMonth%';";
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
	 * Création du tableau avec tous les ts du mois en cours
	 */
		$tabClimatoMonth = getDatesFromRange($dtOptMonth.'-01', $latestDtOfMonth, 'P1D', true, 'U');

	/**
	 * Récup des données pour le mois demandé
	 */
		$query_string = "SELECT *
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
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
				$Tmoy        = null;
				$TmoyGraph   = "null";
				$TempRange   = null;
				$RR          = null;
				$RRGraph     = "null";
				$windGust    = null;
				$tsDateDay   = (string) strtotime($row['dateDay']);
				$tsDateDayJS = (string) strtotime($row['dateDay'])*1000;

				// Insert dans le tableau
				// $tabClimatoMonth [$tsDateDay] = array();
				$tabClimatoMonth [$tsDateDay] ['dateDay'] = $row['dateDay'];

				// Traitement des données
				// Tn
				if (!is_null($row['Tn'])) {
					$Tn = round($row['Tn'], 1);
					$TnGraph = round($row['Tn'], 1);
				}
				$tabClimatoMonth [$tsDateDay] ['Tn'] = $Tn;
				$tabClimatoMonth [$tsDateDay] ['TnMin'] = 0;
				$dataTn[] = "[$tsDateDayJS, $TnGraph]";
				
				// Tx
				if (!is_null($row['Tx'])) {
					$Tx = round($row['Tx'], 1);
					$TxGraph = round($row['Tx'], 1);
				}
				$tabClimatoMonth [$tsDateDay] ['Tx'] = $Tx;
				$tabClimatoMonth [$tsDateDay] ['TxMax'] = 0;
				$dataTx[] = "[$tsDateDayJS, $TxGraph]";

				// Range
				if (!is_null($row['TempRange'])) {
					$TempRange = round($row['TempRange'], 1);
				}
				$tabClimatoMonth [$tsDateDay] ['TempRange'] = $TempRange;
				$tabClimatoMonth [$tsDateDay] ['TrangeMin'] = 0;
				$tabClimatoMonth [$tsDateDay] ['TrangeMax'] = 0;

				// Tmoy
				if (!is_null($row['Tmoy'])) {
					$Tmoy = round($row['Tmoy'], 1);
					$TmoyGraph = round($row['Tmoy'], 1);
				}
				$tabClimatoMonth [$tsDateDay] ['Tmoy'] = $Tmoy;
				$tabClimatoMonth [$tsDateDay] ['TmoyMin'] = 0;
				$tabClimatoMonth [$tsDateDay] ['TmoyMax'] = 0;
				$dataTmoy[] = "[$tsDateDayJS, $TmoyGraph]";

				// RR
				if (!is_null($row['RR'])) {
					$RR = round($row['RR'], 1);
					$RRGraph = round($row['RR'], 1);
				}
				$tabClimatoMonth [$tsDateDay] ['RR'] = $RR;
				$tabClimatoMonth [$tsDateDay] ['RrMax'] = 0;
				$dataRR[] = "[$tsDateDayJS, $RRGraph]";

				// windGust
				if (!is_null($row['windGust'])) {
					$windGust = round($row['windGust'], 1);
				}
				$tabClimatoMonth [$tsDateDay] ['windGust'] = $windGust;
				$tabClimatoMonth [$tsDateDay] ['WgMax'] = 0;
			}
		}

	/**
	 * Récup de la Tn min
	 */
		$query_string = "SELECT `dateDay`, `Tn`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `Tn` = (
							SELECT MIN(`Tn`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
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
				$tsDateDay = (string) strtotime($row['dateDay']);
				$TnMin = null;
				if (!is_null($row['Tn'])) {
					$TnMin = round($row['Tn'], 1);
					$tabClimatoMonth [$tsDateDay] ['TnMin'] = 1;
				}
			}
		}

	/**
	 * Récup de la Tmoy min
	 */
		$query_string = "SELECT `dateDay`, `Tmoy`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `Tmoy` = (
							SELECT MIN(`Tmoy`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TmoyMin = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$tsDateDay = (string) strtotime($row['dateDay']);
				if (!is_null($row['Tmoy'])) {
					$TmoyMin = round($row['Tmoy'], 1);
					$tabClimatoMonth [$tsDateDay] ['TmoyMin'] = 1;
				}
			}
		}

	/**
	 * Récup de la TempRange min
	 */
		$query_string = "SELECT `dateDay`, `TempRange`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `TempRange` = (
							SELECT MIN(`TempRange`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TrangeMin = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$tsDateDay = (string) strtotime($row['dateDay']);
				if (!is_null($row['TempRange'])) {
					$TrangeMin = round($row['TempRange'], 1);
					$tabClimatoMonth [$tsDateDay] ['TrangeMin'] = 1;
				}
			}
		}

	/**
	 * Récup de la Tx max
	 */
		$query_string = "SELECT `dateDay`, `Tx`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `Tx` = (
							SELECT MAX(`Tx`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TxMax = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$tsDateDay = (string) strtotime($row['dateDay']);
				if (!is_null($row['Tx'])) {
					$TxMax = round($row['Tx'], 1);
					$tabClimatoMonth [$tsDateDay] ['TxMax'] = 1;
				}
				
			}
		}

	/**
	 * Récup de la Tmoy max
	 */
		$query_string = "SELECT `dateDay`, `Tmoy`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `Tmoy` = (
							SELECT MAX(`Tmoy`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TmoyMax = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$tsDateDay = (string) strtotime($row['dateDay']);
				if (!is_null($row['Tmoy'])) {
					$TmoyMax = round($row['Tmoy'], 1);
					$tabClimatoMonth [$tsDateDay] ['TmoyMax'] = 1;
				}
			}
		}
	
	/**
	 * Récup de la TempRange max
	 */
		$query_string = "SELECT `dateDay`, `TempRange`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `TempRange` = (
							SELECT MAX(`TempRange`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
						);";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$TrangeMax = null;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$tsDateDay = (string) strtotime($row['dateDay']);
				if (!is_null($row['TempRange'])) {
					$TrangeMax = round($row['TempRange'], 1);
					$tabClimatoMonth [$tsDateDay] ['TrangeMax'] = 1;
				}
			}
		}

	/**
	 * Récup des RR max
	 */
		$query_string = "SELECT `dateDay`, `RR`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `RR` = (
							SELECT MAX(`RR`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
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
				$tsDateDay = (string) strtotime($row['dateDay']);
				$tabClimatoMonth [$tsDateDay] ['RrMax'] = 1;
				$RrMax = $row['RR'];
			}
		}

	/**
	 * Récup de la rafale max
	 */
		$query_string = "SELECT `dateDay`, `windGust`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` LIKE '$dtOptMonth%'
						AND `windGust` = (
							SELECT MAX(`windGust`)
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `dateDay` LIKE '$dtOptMonth%'
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
				$tsDateDay = (string) strtotime($row['dateDay']);
				$tabClimatoMonth [$tsDateDay] ['WgMax'] = 1;
				$WgMax = $row['windGust'];
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
						WHERE `dateDay` LIKE '$dtOptMonth%';";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$TnMax = null;
			$TxMin = null;
			$TmoyAvg = null;
			$RrSum = null;

			if (!is_null($row['TnMax'])) {
				$TnMax = round($row['TnMax'], 1);
			}
			if (!is_null($row['TxMin'])) {
				$TxMin = round($row['TxMin'], 1);
			}
			if (!is_null($row['TmoyAvg'])) {
				$TmoyAvg = round($row['TmoyAvg'], 1);
			}
			if (!is_null($row['RrSum'])) {
				$RrSum = round($row['RrSum'], 1);
			}
		}

}