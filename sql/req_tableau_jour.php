<?php
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
		if (!is_null($row['dewpoint'])) {
			$dewpoint = round($row['dewpoint'],1);
		} else {
			$dewpoint = 'N/A';
		}

		if (!is_null($row['outTemp'])) {
			$temp = round($row['outTemp'],1);
		} else {
			$temp = 'N/A';
		}

		if (!is_null($row['outHumidity'])) {
			$hygro = round($row['outHumidity'],0);
		} else {
			$hygro = 'N/A';
		}

		if (!is_null($row['barometer'])) {
			$barometer = round($row['barometer'],1);
		} else {
			$barometer = 'N/A';
		}

		if (!is_null($row['windSpeed'])) {
			$wind = round($row['windSpeed'],1);
		} else {
			$wind = 'N/A';
		}

		if (!is_null($row['windGust'])) {
			$windgust = round($row['windGust'],1);
		} else {
			$windgust = 'N/A';
		}

		if ($presence_radiation){
			if (!is_null($row['radiation'])) {
				$radiation = round($row['radiation'],0);
			} else {
				$radiation = 'N/A';
			}
		};

		if ($presence_uv){
			if (!is_null($row['UV'])) {
				$uv = round($row['UV'],1);
			} else {
				$uv = 'N/A';
			}
		};

		if (!is_null($row['heatindex'])) {
			$heatindex = round($row['heatindex'],1);
		} else {
			$heatindex = 'N/A';
		}

		if (!is_null($row['windchill'])) {
			$windchill = round($row['windchill'],1);
		} else {
			$windchill = 'N/A';
		}

		if (!is_null($row['rainRate'])) {
			$rainrate = round($row['rainRate']*10,1);
		} else {
			$rainrate = 'N/A';
		}
	}

// Récup des xx derniers enregistrement avec modulo 30 minutes
	// On récupère le dernier enregistrement en BDD
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
					AND `dateTime` >= '$start24h'
					AND `dateTime` < '$stop'
					ORDER BY `dateTime` DESC;";
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
			$tabAccueil [$row['ts']] = array();

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
			$tabAccueil [$row['ts']] ['TempMod'] = $TempMod;
			$tabAccueil [$row['ts']] ['HrMod'] = $HrMod;
			$tabAccueil [$row['ts']] ['TdMod'] = $TdMod;
			$tabAccueil [$row['ts']] ['barometerMod'] = $barometerMod;
			$tabAccueil [$row['ts']] ['radiationMod'] = $radiationMod;
			$tabAccueil [$row['ts']] ['UvMod'] = $UvMod;
			$tabAccueil [$row['ts']] ['EtMod'] = $EtMod;
			$countTabAccueil = count($tabAccueil);
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
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windDir_10_check = $avg_windDir_10;
		if ($avg_windDir_10_check === null || $avg_windDir_10_check == ''){
			$cardinalDir10 = 'N/A';
			$avg_windDir_10 = 'N/A';
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
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windDir_1h_check = $avg_windDir_1h;
		if ($avg_windDir_1h_check === null || $avg_windDir_1h_check == ''){
			$cardinalDir1h = 'N/A';
			$avg_windDir_1h = 'N/A';
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
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windGustDir_10_check = $avg_windGustDir_10;
		if ($avg_windGustDir_10_check === null || $avg_windGustDir_10_check == ''){
			$cardinalGustDir10 = 'N/A';
			$avg_windGustDir_10 = 'N/A';
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
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windGustDir_1h_check = $avg_windGustDir_1h;
		if ($avg_windGustDir_1h_check === null || $avg_windGustDir_1h_check == ''){
			$cardinalGustDir1h = 'N/A';
			$avg_windGustDir_1h = 'N/A';
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
		$windDir = 'N/A';
		$cardinalWindDir = 'N/A';
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
		$windGustDir = 'N/A';
		$cardinalWindGustDir = 'N/A';
	}else{
		$cardinalWindGustDir = wind_cardinals($windGustDir_check);
		$windGustDir = round($windGustDir_check,1);
	}

	/*
	 * FIN DE CALCUL DE LA DIRECTION DU VENT
	 */

	// ET
	if ($presence_radiation){
		// Calcul de l'ET sur la dernière heure
		$sql = "SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime>= '$start1' AND dateTime <= '$stop';";
		$et_1h = $conn->query($sql);
		$et_1h_requ = mysqli_fetch_row($et_1h);
		$et = round($et_1h_requ[0]*10,1);

		// Calcul du cumul d'ET de la journée
		$sql = "SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime>'$today';";
		$etreq = $conn->query($sql);
		$etrequ = mysqli_fetch_row($etreq);
		$etcumul = round($etrequ[0]*10,2);
	};


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
		$Rr3h = 'N/A';

		if (!is_null($row['Rr3h'])) {
			$Rr3h = round($row['Rr3h']*10,1);
		}
	}

// Calcul du cumul de précips 6h-6h UTC (aujourd'hui)
	$query_string = "SELECT SUM(`rain`) AS `RrTodayOMM`
					FROM $db_table
					WHERE `dateTime` >= '$today6h' AND `dateTime` <= '$stop';";
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
		$RrTodayOMM = 'N/A';

		if (!is_null($row['RrTodayOMM'])) {
			$RrTodayOMM = round($row['RrTodayOMM']*10,1);
		}
	}

// Calcul du cumul de précips de la veille 6h-6h UTC
	$query_string = "SELECT SUM(`rain`) AS `RrYesterdayOMM`
					FROM $db_table
					WHERE `dateTime` >= '$yesterday6h' AND `dateTime` < '$today6h';";
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
		$RrYesterdayOMM = 'N/A';

		if (!is_null($row['RrYesterdayOMM'])) {
			$RrYesterdayOMM = round($row['RrYesterdayOMM']*10,1);
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
		$RrTodayMidnight = 'N/A';

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
		$Rr12h = 'N/A';

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
		$Rr24h = 'N/A';

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
		$Rr7j = 'N/A';

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
		$RRateMax3h = 'N/A';
		$dtRRateMax3h = 'N/A';

		if (!is_null($row['RRateMax3h'])) {
			$RRateMax3h = round($row['RRateMax3h']*10,1);
			$dtRRateMax3h = date('H\hi',$row['tsRRateMax3h']);
		}
	}

// Calcul de l'intensité max 6h-6h (aujourd'hui)
	$query_string = "SELECT `dateTime` AS `tsRRateMaxToday`, `rainRate` AS `RRateMaxToday`
					FROM $db_table
					WHERE dateTime >= '$today6h' AND dateTime <= '$stop'
					AND `rainRate` = (
						SELECT MAX(`rainRate`)
						FROM $db_table
						WHERE `dateTime` >= '$today6h' AND `dateTime` <= '$stop'
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
		$RRateMaxToday = 'N/A';
		$dtRRateMaxToday = 'N/A';

		if (!is_null($row['RRateMaxToday'])) {
			$RRateMaxToday = round($row['RRateMaxToday']*10,1);
			$dtRRateMaxToday = date('H\hi',$row['tsRRateMaxToday']);
		}
	}

// Calcul de l'intensité max de la veille 6h-6h
	$query_string = "SELECT `dateTime` AS `tsRRateMaxYesterday`, `rainRate` AS `RRateMaxYesterday`
					FROM $db_table
					WHERE dateTime >= '$yesterday6h' AND dateTime < '$today6h'
					AND `rainRate` = (
						SELECT MAX(`rainRate`)
						FROM $db_table
						WHERE `dateTime` >= '$yesterday6h' AND `dateTime` < '$today6h'
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
		$RRateMaxYesterday = 'N/A';
		$dtRRateMaxYesterday = 'N/A';

		if (!is_null($row['RRateMaxYesterday'])) {
			$RRateMaxYesterday = round($row['RRateMaxYesterday']*10,1);
			$dtRRateMaxYesterday = date('H\hi',$row['tsRRateMaxYesterday']);
		}
	}




	// On récupère les valeurs max et min de la température
	$sql = "SELECT * FROM $db_name.archive_day_outTemp ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mintemptime = date('H\hi',$row[2]);
	$mintemp = round($row[1],1);
	$maxtemp = round($row[3],1);
	$maxtemptime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de l'hygro
	$sql = "SELECT * FROM $db_name.archive_day_outHumidity ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minhygrotime = date('H\hi',$row[2]);
	$minhygro = round($row[1],1);
	$maxhygro = round($row[3],1);
	$maxhygrotime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min du pt de rosée
	$sql = "SELECT * FROM $db_name.archive_day_dewpoint ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mindewpointtime = date('H\hi',$row[2]);
	$mindewpoint = round($row[1],1);
	$maxdewpoint = round($row[3],1);
	$maxdewpointtime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de la pression
	$sql = "SELECT * FROM $db_name.archive_day_barometer ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minbarometertime = date('H\hi',$row[2]);
	$minbarometer = round($row[1],1);
	$maxbarometer = round($row[3],1);
	$maxbarometertime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min des précipitations
	$sql = "SELECT * FROM $db_name.archive_day_rainRate ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainRate = round($row[3]*10,1);
	$maxrainRatetime = date('H\hi',$row[4]);

	// On récupère les valeurs max des rafales de vent
	$sql = "SELECT * FROM $db_name.archive_day_wind ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);
	if ($maxwinddir === null || $maxwinddir == '') {
		$maxwinddir = 'N/A';
		$cardinalMaxWindDir = 'N/A';
	} else {
		$cardinalMaxWindDir = wind_cardinals($maxwinddir);
	}
	
	// UV
	if ($presence_uv){
		// Calcul de la moyenne sur 10 minutes de l'indice UV
		$sql = "SELECT AVG(UV) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$avg_UV_10 = round($row[0],1);

		// On récupère les valeurs max de l'UV
		$sql = "SELECT * FROM $db_name.archive_day_UV ORDER BY dateTime DESC LIMIT 1;";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$maxuv = round($row[3],1);
		$maxuvtime = date('H\hi',$row[4]);
	};

	// On récupère la valeur min du refroidissement éolien
	$sql = "SELECT * FROM $db_name.archive_day_windchill ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minwindchilltime = date('H\hi',$row[2]);
	$minwindchill = round($row[1],1);

	// On récupère la valeur max de l'indice de chaleur
	$sql = "SELECT * FROM $db_name.archive_day_heatindex ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxheatindex = round($row[3],1);
	$maxheatindextime = date('H\hi',$row[4]);

	// Rayonnement solaire et ET
	if ($presence_radiation){
		// Calcul de la moyenne sur 10 minutes du rayonnement solaire (radiation)
		$sql = "SELECT AVG(radiation) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$avg_radiation_10 = round($row[0],1);

		// On récupère les valeurs max du rayonnement solaire
		$sql = "SELECT * FROM $db_name.archive_day_radiation ORDER BY dateTime DESC LIMIT 1;";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$maxradiation = round($row[3],1);
		$maxradiationtime = date('H\hi',$row[4]);

		// On récupère les valeurs max de l'ET
		$sql = "SELECT * FROM $db_name.archive_day_ET ORDER BY dateTime DESC LIMIT 1;";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$maxet = round($row[3]*10,3);
		$maxettime = date('H\hi',$row[4]);
	};

?>
