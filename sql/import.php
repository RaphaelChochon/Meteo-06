<?php
// On récupère le dernier enregistrement
	$query_string = "SELECT `dateTime`, `interval` FROM $db_table ORDER BY `dateTime` DESC LIMIT 1;";
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
		$archive_interval = $row['interval'];
		$tsStop = $row['dateTime'];
		$date=date('d/m/Y',$tsStop);
		$heure=date('H\hi',$tsStop);

		// Calcul pour savoir si la station est hors ligne
		$now=time();
		$diff=abs($now-$tsStop);
		$tmp = $diff;
		$secondes = $tmp % 60;
		$tmp = floor( ($tmp - $secondes) /60 );
		$minutes = $tmp % 60;
		$tmp = floor( ($tmp - $minutes)/60 );
		$heures = $tmp % 24;
		$tmp = floor( ($tmp - $heures)/24 );
		$jours = $tmp;
	}

// On va récup les années présentes en BDD pour la heatmap
	$query_string = "SELECT MIN(`dateTime`) AS `tsStartMaxWeewx`, MAX(`dateTime`) AS `tsStopMaxWeewx` FROM $db_table ORDER BY `dateTime` DESC LIMIT 1;";
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
		$firstYear = date('Y',$row['tsStartMaxWeewx']);
		$firstYearMonth = date('Y-m',$row['tsStartMaxWeewx']);
		$lastYear = date('Y',$row['tsStopMaxWeewx']);
		$lastYearMonth = date('Y-m',$row['tsStopMaxWeewx']);

		$beginY = DateTime::createFromFormat('Y', $firstYear); // Objet dt de la première année
		$endY = DateTime::createFromFormat('Y', $lastYear); // Objet dt de la dernière année
		$intervalYear = new DateInterval('P1Y');
		$eY = $endY->modify( '+1 year' );
		$yearRange = new DatePeriod($beginY, $intervalYear ,$eY); // -> On se sert de yearRange dans graphs.php pour la heatmap

		$beginM = DateTime::createFromFormat('Y-m', $firstYearMonth); // Objet dt de la première année
		$endM = DateTime::createFromFormat('Y-m', $lastYearMonth); // Objet dt de la dernière année
		$intervalMonth = new DateInterval('P1M');
		$eM = $endM->modify( '+1 month' );
		$monthRange = new DatePeriod($beginM, $intervalMonth ,$eM); // -> On se sert de yearRange dans graphs.php pour la heatmap
	}

?>
