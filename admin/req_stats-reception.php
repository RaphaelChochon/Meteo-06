<?php
// Date UTC
	date_default_timezone_set('UTC');

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

		$tsStart48h = $tsStop-(2*24*3600);
		$tsStart7j = $tsStop-(7*24*3600);
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
					`rxCheckPercent` AS `Rx48h`
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
		exit("Erreur.\n");
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$Rx48h = "null";
			$ts = $row['ts']*1000;

			if ($row['Rx48h'] != null) {
				$Rx48h = round($row['Rx48h'],1);
			}
			$dataRx48h[] = "[$ts, $Rx48h]";
		}
	}

// Min et max à 10 minutes pour 48h
	$query_string = "SELECT CEIL(`dateTime`/600)*600 AS `ts`,
								MIN(`rxCheckPercent`) AS `RxMin48h`,
								MAX(`rxCheckPercent`) AS `RxMax48h`
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
			$RxMin48h  = "null";
			$RxMax48h  = "null";
			$ts = $row['ts']*1000;

			if ($row['RxMin48h'] != null) {
				$RxMin48h = round($row['RxMin48h'],1);
			}
			if ($row['RxMax48h'] != null) {
				$RxMax48h = round($row['RxMax48h'],1);
			}
			$dataRx48hError[] = "[$ts, $RxMin48h, $RxMax48h]";
			$dataRx48hMin[] = "[$ts, $RxMin48h]";
		}
	}


// Brut à 1 heure pour 7 jours
	$query_string = "SELECT `dateTime` AS `ts`,
					`rxCheckPercent` AS `Rx7j`,
					`consBatteryVoltage` AS `Tension7j`
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
		exit("Erreur.\n");
	}
	if ($result) {
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$Rx7j = "null";
			$Tension7j = "null";
			$ts = $row['ts']*1000;

			if ($row['Rx7j'] != null) {
				$Rx7j = round($row['Rx7j'],1);
			}
			$dataRx7j[] = "[$ts, $Rx7j]";

			if ($row['Tension7j'] != null) {
				$Tension7j = round($row['Tension7j'],2);
			}
			$dataTension7j[] = "[$ts, $Tension7j]";
		}
	}

// Min et max à 1 heure pour 7 jours
	$query_string = "SELECT CEIL(`dateTime`/3600)*3600 AS `ts`,
								MIN(`rxCheckPercent`) AS `RxMin7j`,
								MAX(`rxCheckPercent`) AS `RxMax7j`,
								MIN(`consBatteryVoltage`) AS `TensionMin7j`,
								MAX(`consBatteryVoltage`) AS `TensionMax7j`
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
			$RxMin7j  = "null";
			$RxMax7j  = "null";
			$TensionMin7j  = "null";
			$TensionMax7j  = "null";
			$ts = $row['ts']*1000;

			if ($row['RxMin7j'] != null) {
				$RxMin7j = round($row['RxMin7j'],1);
			}
			if ($row['RxMax7j'] != null) {
				$RxMax7j = round($row['RxMax7j'],1);
			}
			$dataRx7jError[] = "[$ts, $RxMin7j, $RxMax7j]";
			$dataRx7jMin[] = "[$ts, $RxMin7j]";

			if ($row['TensionMin7j'] != null) {
				$TensionMin7j = round($row['TensionMin7j'],2);
			}
			if ($row['TensionMax7j'] != null) {
				$TensionMax7j = round($row['TensionMax7j'],2);
			}
			$dataTension7jError[] = "[$ts, $TensionMin7j, $TensionMax7j]";
			$dataTension7jMin[] = "[$ts, $TensionMin7j]";
		}
	}


?>