<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère le dernier enregistrement
	$res=mysql_query("SELECT * FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$archive_interval = $row[2];
	$dateTime = $row[0];
	$date=date('d/m/Y',$dateTime);
	$heure=date('H\hi',$dateTime);
?>
