<?php
	// appel du script de connexion
	require_once("connect.php");


	// On récupère le timestamp du dernier enregistrement
	$sql = "SELECT max(dateTime) FROM $db_name.$db_table";
	$query = $conn->query($sql);
	$list = mysqli_fetch_array($query);

	// On récupère les valeurs actuelles
	$inTemp = round($row[6],1);
	$inHumidity = round($row[8],1);

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

?>
