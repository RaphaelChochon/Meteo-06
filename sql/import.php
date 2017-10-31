<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère le dernier enregistrement
	$res = $conn->query("SELECT * FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;");
	$row = mysqli_fetch_row($res);
	$archive_interval = $row[2];
	$dateTime = $row[0];
	$date=date('d/m/Y',$dateTime);
	$heure=date('H\hi',$dateTime);

	// Calcul pour savoir si la station est hors ligne
	$now=time();
	$diff=abs($now-$dateTime);
	$tmp = $diff;
	$secondes = $tmp % 60;
	$tmp = floor( ($tmp - $secondes) /60 );
	$minutes = $tmp % 60;
	$tmp = floor( ($tmp - $minutes)/60 );
	$heures = $tmp % 24;
	$tmp = floor( ($tmp - $heures)/24 );
	$jours = $tmp;

?>
