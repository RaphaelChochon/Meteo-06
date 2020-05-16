<?php
// Date UTC
	date_default_timezone_set('UTC');

	$today = strtotime('today midnight');
// On récupère le dernier enregistrement en BDD
	$query_string = "SELECT * FROM $db_table ORDER BY `dateTime` DESC LIMIT 1;";
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

		// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
		$stop=$row['dateTime'];
		$minutes10=$stop-(600);
		$start1=$stop-(3599);
		$start3h=$stop-(3*3600);
		$start12h=$stop-(12*3600);
		$start24h=$stop-(24*3600);
		$start7j=$stop-(7*24*3600);

		$today6h     = mktime(6,0,0,date("m"),date("d"), date("Y"));
		$yesterday6h = mktime(6,0,0,date("m"),date("d")-1, date("Y"));

		// On récup les dernières valeurs
		$TdNow = 'nd.';
		if (!is_null($row['dewpoint'])) {
			$TdNow = round($row['dewpoint'],1);
		}
	
		$TempNow = 'nd.';
		if (!is_null($row['outTemp'])) {
			$TempNow = round($row['outTemp'],1);
		}

		$HrNow = 'nd.';
		if (!is_null($row['outHumidity'])) {
			$HrNow = round($row['outHumidity'],0);
		}

		$PrNow = 'nd.';
		if (!is_null($row['barometer'])) {
			$PrNow = round($row['barometer'],1);
		}

		$windSpeedNow = 'nd.';
		if (!is_null($row['windSpeed'])) {
			$windSpeedNow = round($row['windSpeed'],1);
		}

		$windGustNow = 'nd.';
		if (!is_null($row['windGust'])) {
			$windGustNow = round($row['windGust'],1);
		}

		$RadNow = 'nd.';
		if ($presence_radiation){
			if (!is_null($row['radiation'])) {
				$RadNow = round($row['radiation'],0);
			}
		};

		$UvNow = 'nd.';
		if ($presence_uv){
			if (!is_null($row['UV'])) {
				$UvNow = round($row['UV'],1);
			}
		};

		$heatindexNow = 'nd.';
		if (!is_null($row['heatindex'])) {
			$heatindexNow = round($row['heatindex'],1);
		}

		$windchillNow = 'nd.';
		if (!is_null($row['windchill'])) {
			$windchillNow = round($row['windchill'],1);
		}

		$RrateNow = 'nd.';
		if (!is_null($row['rainRate'])) {
			$RrateNow = round($row['rainRate']*10,1);
		}
	}


	/**
	 * Récup des données min et max et dt pour la journée demandée
	 */
		$dateDay = date('Y-m-d');
		$query_string = "SELECT *
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` = '$dateDay';";
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
			if (!is_null($row['Tn'])) {
				$Tn = round($row['Tn'], 1);
				$TnDt = $row['TnDt'];
			}
			if (!is_null($row['TnFiab'])) {
				$TnFiab = round($row['TnFiab'], 0);
			}

			// Tx
			$Tx     = "nd.";
			$TxDt   = null;
			$TxFiab = 0;
			if (!is_null($row['Tx'])) {
				$Tx = round($row['Tx'], 1);
				$TxDt = $row['TxDt'];
			}
			if (!is_null($row['TxFiab'])) {
				$TxFiab = round($row['TxFiab'], 0);
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
			$windGustMaxDir = null;
			$windGustMaxDirCardinal = null;
			if (!is_null($row['windGust'])) {
				$windGustMax = round($row['windGust'], 1);
				$windGustMaxDt = $row['windGustDt'];
				if (!is_null($row['windGustDir'])) {
					$windGustMaxDir = round($row['windGustDir']);
					$windGustMaxDirCardinal = wind_cardinals($windGustMaxDir);
				}
			}

			// RR
			$RrAujd = "nd.";
			$RRateMaxAujd = 0;
			$RRateMaxAujdDt = null;
			if (!is_null($row['RR'])) {
				$RrAujd = round($row['RR'], 1);
			}
			if (!is_null($row['RRateMax'])) {
				$RRateMaxAujd = round($row['RRateMax'], 1);
				$RRateMaxAujdDt = $row['RRateMaxDt'];
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
		$dateYesterday = date('Y-m-d', strtotime($dateDay.'-1 day'));
		$query_string = "SELECT `dateDay`, `RR`, `RRateMax`, `RRateMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` = '$dateYesterday';";
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
			if (!is_null($row['RR'])) {
				$RrHier = round($row['RR'], 1);
			}
			if (!is_null($row['RRateMax'])) {
				$RRateMaxHier = round($row['RRateMax'], 1);
				$RRateMaxHierDt = $row['RRateMaxDt'];
			}
		}

	/*
	 * VITESSE VENT MOYEN
	 */

		// Vitesse moyenne du vent moyen sur 10 minutes
			$query_string = "SELECT AVG(`windSpeed`) AS `windSpeed`
							FROM `$db_name`.`$db_table`
							WHERE `dateTime` >= '$minutes10'
							AND `dateTime` <= '$stop';";
			$result       = $db_handle_pdo->query($query_string);
			$windSpeedAvg10min = "nd.";
			// $windGustAvg10min = "nd.";
			if ($result) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
				if (!is_null($row['windSpeed'])) {
					$windSpeedAvg10min = round($row['windSpeed'], 1);
				}
				// if (!is_null($row['windGust'])) {
				// 	$windGustAvg10min = round($row['windGust'], 1);
				// }
			}

		// Vitesse moyenne du vent moyen sur 1 heure
			$query_string = "SELECT AVG(`windSpeed`) AS `windSpeed`
							FROM `$db_name`.`$db_table`
							WHERE `dateTime` >= '$start1'
							AND `dateTime` <= '$stop';";
			$result       = $db_handle_pdo->query($query_string);
			$windSpeedAvg1h = "nd.";
			// $windGustAvg1h = "nd.";
			if ($result) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
				if (!is_null($row['windSpeed'])) {
					$windSpeedAvg1h = round($row['windSpeed'], 1);
				}
				// if (!is_null($row['windGust'])) {
				// 	$windGustAvg1h = round($row['windGust'], 1);
				// }
			}
	/*
	 * DIRECTION VENT MOYEN
	 */

		/**
		 * VENT MOYEN 10 minutes
		 */
			$windDir10Array       = array();
			$windDirAvg10min      = null;
			$windDirCardinal10min = null;
			$query_string = "SELECT `windDir`
						FROM `$db_name`.`$db_table`
						WHERE `dateTime` >= '$minutes10'
						AND `dateTime` <= '$stop';";
			$result       = $db_handle_pdo->query($query_string);
			if ($result) {
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					if (!is_null ($row['windDir'])) {
						$windDir10Array[] = $row['windDir'];
					}
					// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
					if (!empty($windDir10Array)) {
						$windDirAvg10min = round(mean_of_angles($windDir10Array));
						if (!is_null($windDirAvg10min)) {
							$windDirCardinal10min = wind_cardinals($windDirAvg10min);
						}
					}
				}
			}

		/**
		 * VENT MOYEN 1 heure
		 */
			$windDir1hArray       = array();
			$windDirAvg1h      = null;
			$windDirCardinal1h = null;
			$query_string = "SELECT `windDir`
						FROM `$db_name`.`$db_table`
						WHERE `dateTime` >= '$start1'
						AND `dateTime` <= '$stop';";
			$result       = $db_handle_pdo->query($query_string);
			if ($result) {
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					if (!is_null ($row['windDir'])) {
						$windDir1hArray[] = $row['windDir'];
					}
					// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
					if (!empty($windDir1hArray)) {
						$windDirAvg1h = round(mean_of_angles($windDir1hArray));
						if (!is_null($windDirAvg1h)) {
							$windDirCardinal1h = wind_cardinals($windDirAvg1h);
						}
					}
				}
			}

		/*
		* DIRECTIONS INSTANTANÉES vent moyen et rafale
		*/
			$query_string = "SELECT `windDir` AS `windDir`,
									`windGustDir` AS `windGustDir`
						FROM `$db_name`.`$db_table`
						ORDER BY `dateTime`
						DESC LIMIT 1;";
			$result       = $db_handle_pdo->query($query_string);
			$windDirNow             = "nd.";
			$windGustDirNow         = "nd.";
			$windDirCardinalNow     = null;
			$windGustDirCardinalNow = null;
			if ($result) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
				if (!is_null($row['windDir'])) {
					$windDirNow = round($row['windDir']);
					$windDirCardinalNow = wind_cardinals($windDirNow);
				}
				if (!is_null($row['windGustDir'])) {
					$windGustDirNow = round($row['windGustDir']);
					$windGustDirCardinalNow = wind_cardinals($windGustDirNow);
				}
			}

		/**
		 * Rafale max sur 10 min et sa direction
		 */
			$query_string = "SELECT `dateTime` AS `tsWindGustMax10min`,
									`windGust` AS `windGustMax10min`,
									`windGustDir` AS `windGustMaxDir10min`
							FROM `$db_name`.`$db_table`
							WHERE dateTime >= '$minutes10' AND dateTime <= '$stop'
							AND `windGust` = (
								SELECT MAX(`windGust`)
								FROM `$db_name`.`$db_table`
								WHERE `dateTime` >= '$minutes10' AND `dateTime` <= '$stop'
							);";
			$result       = $db_handle_pdo->query($query_string);
			$tsWindGustMax10min          = null;
			$windGustMax10min            = "nd.";
			$windGustMaxDir10min         = null;
			$windGustMaxDirCardinal10min = null;
			if ($result) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
				if (!is_null($row['windGustMax10min'])) {
					$windGustMax10min = round($row['windGustMax10min'], 1);
					$tsWindGustMax10min = $row['tsWindGustMax10min'];
					if (!is_null($row['windGustMaxDir10min'])){
						$windGustMaxDir10min = round($row['windGustMaxDir10min']);
						$windGustMaxDirCardinal10min = wind_cardinals($windGustMaxDir10min);
					}
				}
			}

		/**
		 * Rafale max sur 1 heure et sa direction
		 */
			$query_string = "SELECT `dateTime` AS `tsWindGustMax1h`,
									`windGust` AS `windGustMax1h`,
									`windGustDir` AS `windGustMaxDir1h`
							FROM `$db_name`.`$db_table`
							WHERE dateTime >= '$start1' AND dateTime <= '$stop'
							AND `windGust` = (
								SELECT MAX(`windGust`)
								FROM `$db_name`.`$db_table`
								WHERE `dateTime` >= '$start1' AND `dateTime` <= '$stop'
							);";
			$result       = $db_handle_pdo->query($query_string);
			$tsWindGustMax1h          = null;
			$windGustMax1h            = "nd.";
			$windGustMaxDir1h         = null;
			$windGustMaxDirCardinal1h = null;
			if ($result) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
				if (!is_null($row['windGustMax1h'])) {
					$windGustMax1h = round($row['windGustMax1h'], 1);
					$tsWindGustMax1h = $row['tsWindGustMax1h'];
					if (!is_null($row['windGustMaxDir1h'])){
						$windGustMaxDir1h = round($row['windGustMaxDir1h']);
						$windGustMaxDirCardinal1h = wind_cardinals($windGustMaxDir1h);
					}
				}
			}

	/*
	 * FIN DE CALCUL DE LA DIRECTION DU VENT
	 */


// Calcul du cumul de précips sur 3h
	$query_string = "SELECT SUM(`rain`) AS `Rr3h`
					FROM $db_table
					WHERE `dateTime` >= '$start3h' AND `dateTime` <= '$stop';";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$Rr3h = 'nd.';

		if (!is_null($row['Rr3h'])) {
			$Rr3h = round($row['Rr3h']*10,1);
		}
	}


// Calcul du cumul de précipitations depuis 0h locale
	$todayMidnight = strtotime('today midnight');
	$query_string = "SELECT SUM(`rain`) AS `RrTodayMidnight`
					FROM $db_table
					WHERE `dateTime` >= '$todayMidnight' AND `dateTime` <= '$stop';";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$RrTodayMidnight = 'nd.';

		if (!is_null($row['RrTodayMidnight'])) {
			$RrTodayMidnight = round($row['RrTodayMidnight']*10,1);
		}
	}

// Calcul du cumul de précipitations sur 12 heures glissantes
	$query_string = "SELECT SUM(`rain`) AS `Rr12h`
					FROM $db_table
					WHERE `dateTime` >= '$start12h' AND `dateTime` <= '$stop';";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$Rr12h = 'nd.';

		if (!is_null($row['Rr12h'])) {
			$Rr12h = round($row['Rr12h']*10,1);
		}
	}

// Calcul du cumul de précipitations sur 24 heures glissantes
	$query_string = "SELECT SUM(`rain`) AS `Rr24h`
					FROM $db_table
					WHERE `dateTime` >= '$start24h' AND `dateTime` <= '$stop';";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$Rr24h = 'nd.';

		if (!is_null($row['Rr24h'])) {
			$Rr24h = round($row['Rr24h']*10,1);
		}
	}

// Calcul du cumul de précipitations sur 7 jours glissants
	$query_string = "SELECT SUM(`rain`) AS `Rr7j`
					FROM $db_table
					WHERE `dateTime` >= '$start7j' AND `dateTime` <= '$stop';";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$Rr7j = 'nd.';

		if (!is_null($row['Rr7j'])) {
			$Rr7j = round($row['Rr7j']*10,1);
		}
	}

// Calcul de l'intensité max de précips sur 3h
	$query_string = "SELECT `dateTime` AS `tsRRateMax3h`, `rainRate` AS `RRateMax3h`
					FROM $db_table
					WHERE dateTime >= '$start3h' AND dateTime <= '$stop'
					AND `rainRate` = (
						SELECT MAX(`rainRate`)
						FROM $db_table
						WHERE `dateTime` >= '$start3h' AND `dateTime` <= '$stop'
					);";
	$result       = $db_handle_pdo->query($query_string);
	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$RRateMax3h = 'nd.';
		$RRateMax3hDt = null;

		if (!is_null($row['RRateMax3h'])) {
			$RRateMax3h = round($row['RRateMax3h']*10,1);
			$RRateMax3hDt = $row['tsRRateMax3h'];
		}
	}
