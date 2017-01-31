<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère le timestamp du dernier enregistrement
	$res=mysql_query("SELECT * FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	// On récupère les valeurs actuelles
	$temp = round($row[7],1);
	$wind = round($row[10],1);

	// On récupère les valeurs max et min des précipitations
	$res = mysql_query("SELECT * FROM $db_name.archive_day_rainRate ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$maxrainRate = round($row[3]*10,1);
	$maxrainRatetime = date('H\hi',$row[4]);

	// PLUVIO
	$sql="SELECT max(dateTime) FROM $db_name.$db_table";
	$query=mysql_query($sql);
	$today = strtotime('today midnight');
	$rain = mysql_query("SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime>'$today';");
	$he = mysql_fetch_row($rain);
	$cumul = round($he[0]*10,1);

	// On récupère les valeurs max et min des rafales de vent
	$res = mysql_query("SELECT * FROM $db_name.archive_day_wind ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);

?>