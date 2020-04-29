<?php
// Date UTC
	date_default_timezone_set('UTC');

// On détermine tsStop et tsStart
	$query_string = "SELECT `dateTime`, `inTemp`, `inHumidity` FROM $db_table ORDER BY `dateTime` DESC LIMIT 1;";
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

		$inTempNow = round($row['inTemp'],1);
		$inHumidityNow = round($row['inHumidity'],1);

		// Arrondi du datetime Stop
		$datetimeStop = new DateTime();
		$datetimeStop->setTimestamp($tsStop);
		$dtStop = roundDownToMinuteInterval($datetimeStop);

		$dtStop = $dtStop->format("d-m-Y H:i:s");
		$tsStop = strtotime($dtStop);

		$tsStart48h = $tsStop-(2*24*3600);
		$tsStart7j = $tsStop-(7*24*3600);
		$tsStart30j = $tsStop-(30*24*3600);
	}
	
// Minuit
	$minuit = strtotime('today midnight')*1000;
	$minuit_hier = strtotime('yesterday midnight')*1000;
	$minuit_3 = strtotime('-2 day midnight')*1000;
	$minuit_4 = strtotime('-3 day midnight')*1000;
	$minuit_5 = strtotime('-4 day midnight')*1000;
	$minuit_6 = strtotime('-5 day midnight')*1000;
	$minuit_7 = strtotime('-6 day midnight')*1000;
	$minuit_8 = strtotime('-7 day midnight')*1000;


// Brut à 10 minutes pour 48h
	$query_string = "SELECT `dateTime` AS `ts`,
					`inTemp` AS `inTemp48h`,
					`inHumidity` AS `inHumidity48h`
				FROM `archive`
				WHERE `dateTime` % 600 = 0
				AND `dateTime` >= $tsStart48h
				AND `dateTime` <= $tsStop;";
	$result       = $db_handle_pdo->query($query_string);

	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$inTemp48h = "null";
			$inHumidity48h = "null";
			$ts = $row['ts']*1000;

			if ($row['inTemp48h'] != null) {
				$inTemp48h = round($row['inTemp48h'],1);
			}
			$dataInTemp48h[] = "[$ts, $inTemp48h]";

			if ($row['inHumidity48h'] != null) {
				$inHumidity48h = round($row['inHumidity48h'],1);
			}
			$dataInHumidity48h[] = "[$ts, $inHumidity48h]";
		}
	}

// Min et max à 10 minutes pour 48h
	$query_string = "SELECT CEIL(`dateTime`/600)*600 AS `ts`,
								MIN(`inTemp`) AS `inTemp48hMin`,
								MAX(`inTemp`) AS `inTemp48hMax`
					FROM $db_table
					WHERE `dateTime` >= $tsStart48h
					AND `dateTime` <= $tsStop
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
			$inTemp48hMin  = "null";
			$inTemp48hMax  = "null";
			$ts = $row['ts']*1000;

			if ($row['inTemp48hMin'] != null) {
				$inTemp48hMin = round($row['inTemp48hMin'],1);
			}
			if ($row['inTemp48hMax'] != null) {
				$inTemp48hMax = round($row['inTemp48hMax'],1);
			}
			$dataInTemp48hError[] = "[$ts, $inTemp48hMin, $inTemp48hMax]";
		}
	}


// 1h pour graph 7 jours
	$query_string = "SELECT `dateTime` AS `ts`,
					`inTemp` AS `inTemp7j`,
					`inHumidity` AS `inHumidity7j`
				FROM `archive`
				WHERE `dateTime` % 3600 = 0
				AND `dateTime` >= $tsStart7j
				AND `dateTime` <= $tsStop;";
	$result       = $db_handle_pdo->query($query_string);

	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$inTemp7j = "null";
			$inHumidity7j = "null";
			$ts = $row['ts']*1000;

			if ($row['inTemp7j'] != null) {
				$inTemp7j = round($row['inTemp7j'],1);
			}
			$dataInTemp7j[] = "[$ts, $inTemp7j]";

			if ($row['inHumidity7j'] != null) {
				$inHumidity7j = round($row['inHumidity7j'],1);
			}
			$dataInHumidity7j[] = "[$ts, $inHumidity7j]";
		}
	}

// Min et max à 1 heure pour 7 jours
	$query_string = "SELECT CEIL(`dateTime`/3600)*3600 AS `ts`,
								MIN(`inTemp`) AS `inTemp7jMin`,
								MAX(`inTemp`) AS `inTemp7jMax`
					FROM $db_table
					WHERE `dateTime` >= $tsStart7j
					AND `dateTime` <= $tsStop
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
			$inTemp7jMin  = "null";
			$inTemp7jMax  = "null";
			$ts = $row['ts']*1000;

			if ($row['inTemp7jMin'] != null) {
				$inTemp7jMin = round($row['inTemp7jMin'],1);
			}
			if ($row['inTemp7jMax'] != null) {
				$inTemp7jMax = round($row['inTemp7jMax'],1);
			}
			$dataInTemp7jError[] = "[$ts, $inTemp7jMin, $inTemp7jMax]";
		}
	}

// 3h pour graph 30 jours
	$query_string = "SELECT `dateTime` AS `ts`,
					`inTemp` AS `inTemp30j`,
					`inHumidity` AS `inHumidity30j`
				FROM `archive`
				WHERE `dateTime` % 10800 = 0
				AND `dateTime` >= $tsStart30j
				AND `dateTime` <= $tsStop;";
	$result       = $db_handle_pdo->query($query_string);

	if (!$result) {
		// Erreur
		echo "Erreur dans la requete ".$query_string."\n";
		echo "\nPDO::errorInfo():\n";
		print_r($db_handle_pdo->errorInfo());
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$inTemp30j = "null";
			$inHumidity30j = "null";
			$ts = $row['ts']*1000;

			if ($row['inTemp30j'] != null) {
				$inTemp30j = round($row['inTemp30j'],1);
			}
			$dataInTemp30j[] = "[$ts, $inTemp30j]";

			if ($row['inHumidity30j'] != null) {
				$inHumidity30j = round($row['inHumidity30j'],1);
			}
			$dataInHumidity30j[] = "[$ts, $inHumidity30j]";
		}
	}

// Min et max à 10 minutes pour 48h
	$query_string = "SELECT CEIL(`dateTime`/10800)*10800 AS `ts`,
								MIN(`inTemp`) AS `inTemp30jMin`,
								MAX(`inTemp`) AS `inTemp30jMax`
					FROM $db_table
					WHERE `dateTime` >= $tsStart30j
					AND `dateTime` <= $tsStop
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
			$inTemp30jMin  = "null";
			$inTemp30jMax  = "null";
			$ts = $row['ts']*1000;

			if ($row['inTemp30jMin'] != null) {
				$inTemp30jMin = round($row['inTemp30jMin'],1);
			}
			if ($row['inTemp30jMax'] != null) {
				$inTemp30jMax = round($row['inTemp30jMax'],1);
			}
			$dataInTemp30jError[] = "[$ts, $inTemp30jMin, $inTemp30jMax]";
		}
	}

