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

		if (!is_null($row['windSpeed'])) {
			$wind = round($row['windSpeed'],1);
		} else {
			$wind = 'nd.';
		}

		if (!is_null($row['windGust'])) {
			$windgust = round($row['windGust'],1);
		} else {
			$windgust = 'nd.';
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

		if (!is_null($row['heatindex'])) {
			$heatindex = round($row['heatindex'],1);
		} else {
			$heatindex = 'nd.';
		}

		if (!is_null($row['windchill'])) {
			$windchill = round($row['windchill'],1);
		} else {
			$windchill = 'nd.';
		}

		if (!is_null($row['rainRate'])) {
			$rainrate = round($row['rainRate']*10,1);
		} else {
			$rainrate = 'nd.';
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
	 * VITESSE VENT
	 */

	// Calcul de la vitesse moyenne sur 10 minutes du vent moyen
	$sql = "SELECT AVG(windSpeed) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_wind_10 = round($row[0],1);

	// Caclul de la vitesse moyenne des rafales sur les 10 dernières minutes
	$sql = "SELECT AVG(windGust) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_windGust_10 = round($row[0],1);

	// Calcul de la vitesse moyenne sur 1 heure du vent moyen
	$sql = "SELECT AVG(windSpeed) FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_wind_1h = round($row[0],1);

	// Caclul de la vitesse moyenne des rafales sur la dernière heure
	$sql = "SELECT AVG(windGust) FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_windGust_1h = round($row[0],1);

	/*
	 * DIRECTION VENT
	 */

	/*
	 * VENT MOYEN 10 minutes
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windDir FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windDir10Array = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windDir'])) {
			$windDir10Array[] = $row['windDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windDir_10 = mean_of_angles($windDir10Array);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie nd.
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windDir_10_check = $avg_windDir_10;
		if ($avg_windDir_10_check === null || $avg_windDir_10_check == ''){
			$cardinalDir10 = 'nd.';
			$avg_windDir_10 = 'nd.';
		}
		else{
			$cardinalDir10 = wind_cardinals($avg_windDir_10);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windDir_10 = round($avg_windDir_10,1);
		}

	/*
	 * VENT MOYEN 1 heure
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windDir FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windDir1hArray = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windDir'])) {
			$windDir1hArray[] = $row['windDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windDir_1h = mean_of_angles($windDir1hArray);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie nd.
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windDir_1h_check = $avg_windDir_1h;
		if ($avg_windDir_1h_check === null || $avg_windDir_1h_check == ''){
			$cardinalDir1h = 'nd.';
			$avg_windDir_1h = 'nd.';
		}
		else{
			$cardinalDir1h = wind_cardinals($avg_windDir_1h);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windDir_1h = round($avg_windDir_1h,1);
		}

	/*
	 * VENT RAFALES 10 minutes
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windGustDir FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windGustDir10Array = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windGustDir'])) {
			$windGustDir10Array[] = $row['windGustDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windGustDir_10 = mean_of_angles($windGustDir10Array);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie nd.
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windGustDir_10_check = $avg_windGustDir_10;
		if ($avg_windGustDir_10_check === null || $avg_windGustDir_10_check == ''){
			$cardinalGustDir10 = 'nd.';
			$avg_windGustDir_10 = 'nd.';
		}
		else{
			$cardinalGustDir10 = wind_cardinals($avg_windGustDir_10);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windGustDir_10 = round($avg_windGustDir_10,1);
		}

	/*
	 * VENT RAFALES 1 heure
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windGustDir FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windGustDir1hArray = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windGustDir'])) {
			$windGustDir1hArray[] = $row['windGustDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windGustDir_1h = mean_of_angles($windGustDir1hArray);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie nd.
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windGustDir_1h_check = $avg_windGustDir_1h;
		if ($avg_windGustDir_1h_check === null || $avg_windGustDir_1h_check == ''){
			$cardinalGustDir1h = 'nd.';
			$avg_windGustDir_1h = 'nd.';
		}
		else{
			$cardinalGustDir1h = wind_cardinals($avg_windGustDir_1h);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windGustDir_1h = round($avg_windGustDir_1h,1);
		}

	/*
	 * DIRECTIONS INSTANTANÉES
	 */

	// Direction du vent moyen instant
	$sql = "SELECT windDir FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);

	$windDir_check = $row[0];
	if ($windDir_check === null || $windDir_check == ''){
		$windDir = 'nd.';
		$cardinalWindDir = 'nd.';
	}else{
		$cardinalWindDir = wind_cardinals($windDir_check);
		$windDir = round($windDir_check,1);
	}
	//
	// Direction du vent rafales instant
	$sql = "SELECT windGustDir FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);

	$windGustDir_check = $row[0];
	if ($windGustDir_check === null || $windGustDir_check == ''){
		$windGustDir = 'nd.';
		$cardinalWindGustDir = 'nd.';
	}else{
		$cardinalWindGustDir = wind_cardinals($windGustDir_check);
		$windGustDir = round($windGustDir_check,1);
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
		exit("\n");
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
		exit("\n");
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
		exit("\n");
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
		exit("\n");
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
		exit("\n");
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
		exit("\n");
	}
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$RRateMax3h = 'nd.';
		$dtRRateMax3h = 'nd.';

		if (!is_null($row['RRateMax3h'])) {
			$RRateMax3h = round($row['RRateMax3h']*10,1);
			$dtRRateMax3h = date('H\hi',$row['tsRRateMax3h']);
		}
	}


	// On récupère les valeurs max des rafales de vent
	$sql = "SELECT * FROM $db_name.archive_day_wind ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);
	if ($maxwinddir === null || $maxwinddir == '') {
		$maxwinddir = 'nd.';
		$cardinalMaxWindDir = 'nd.';
	} else {
		$cardinalMaxWindDir = wind_cardinals($maxwinddir);
	}