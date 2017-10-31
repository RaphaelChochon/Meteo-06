<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère le timestamp du dernier enregistrement
	$sql = "SELECT * FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	// On récupère les valeurs actuelles
	// Mais d'abord on vérifie si la valeur actuelle n'est pas null
	$temp_check = $row[7];
	if ($temp_check == null){
		// si elle est null, alors on lui donne la valeur N/A
		$temp = 'N/A';
	}else{
		// sinon on l'arrondie
		$temp = round($row[7],1);
	}
	//
	$wind_check = $row[10];
	if ($wind_check == null){
		$wind = 'N/A';
	}else{
		$wind = round($row[10],1);
	}

	// On récupère les valeurs max et min des précipitations
	$sql = "SELECT * FROM $db_name.archive_day_rainRate ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainRate = round($row[3]*10,1);
	$maxrainRatetime = date('H\hi',$row[4]);

	// PLUVIO
	// $sql="SELECT max(dateTime) FROM $db_name.$db_table";
	// $query = $conn->query($sql);
	$today = strtotime('today midnight');
	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime>'$today';";
	$rain = $conn->query($sql);
	$he = mysqli_fetch_row($rain);
	$cumul = round($he[0]*10,1);

	// On récupère les valeurs max et min des rafales de vent
	$sql = "SELECT * FROM $db_name.archive_day_wind ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);

?>