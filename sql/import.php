<?php
// appel du script de connexion
	// require_once("connect_pdo.php");

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
	// appel du script de connexion

?>
