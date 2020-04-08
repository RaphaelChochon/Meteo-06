<?php
	// appel du script de connexion
	//@@todo à remplacer par PDO
	require_once("connect.php");

	// On récupère les valeurs max et min de la température
	$sql = "SELECT * FROM $db_name.archive_day_inTemp ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mintemptime = date('H\hi',$row[2]);
	$mintemp = round($row[1],1);
	$maxtemp = round($row[3],1);
	$maxtemptime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de l'hygro
	$sql = "SELECT * FROM $db_name.archive_day_inHumidity ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minhygrotime = date('H\hi',$row[2]);
	$minhygro = round($row[1],1);
	$maxhygro = round($row[3],1);
	$maxhygrotime = date('H\hi',$row[4]);


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
		// On récupère les valeurs actuelles
		$inTemp = round($row['inTemp'],1);
		$inHumidity = round($row['inHumidity'],1);
	}

?>
