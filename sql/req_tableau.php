<?php
	// appel du script de connexion
	require("connect.php");

	mysql_select_db($db_name);

	// On récupère le dernier enregistrement, et son datetime
	$res=mysql_query("select * from $db_name.$db_table order by dateTime desc limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$dateTime = $row[0];
	$date=date('d/m/Y',$dateTime);
	$heure=date('H\hi',$dateTime);


	// On récupère les valeurs actuelles
	$dewpoint = round($row[16],1);
	$temp = round($row[7],1);
	$wind = round($row[10],1);
	$windgust = round($row[12],1);
	$windgustdir = round($row[13],2);
	$hygro = round($row[9],1);
	$barometer = round($row[3],1);
	if(!$row[21]) {$uv=0;} else {$uv=$row[21];}
	$heatindex = round($row[18],1);
	$windchill = round($row[17],1);
	$rainrate = round($row[14]*10,1);
	$today = strtotime('today midnight');
	$rain = mysql_query("select sum(rain) from $db_name.$db_table where dateTime>'$today';");
	$he = mysql_fetch_row($rain);
	$cumul = round($he[0]*10,1);

	// Calcul des précipitations 24/48/72 heures glissantes
	// On récupère le timestamp du dernier enregistrement
	$sql="select max(dateTime) from archive";
	$query=mysql_query($sql);
	$list=mysql_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
	$stop=$list[0];
	$start24=$stop-(86400);
	$start48=$stop-(86400*2);
	$start72=$stop-(86400*3);

	$rain24 = mysql_query("select sum(rain) from $db_name.$db_table where dateTime >= '$start24' and dateTime <= '$stop' order by 1;");
	$he24 = mysql_fetch_row($rain24);
	$cumul24 = round($he24[0]*10,1);

	$rain48 = mysql_query("select sum(rain) from $db_name.$db_table where dateTime >= '$start48' and dateTime <= '$stop' order by 1;");
	$he48 = mysql_fetch_row($rain48);
	$cumul48 = round($he48[0]*10,1);

	$rain72 = mysql_query("select sum(rain) from $db_name.$db_table where dateTime >= '$start72' and dateTime <= '$stop' order by 1;");
	$he72 = mysql_fetch_row($rain72);
	$cumul72 = round($he72[0]*10,1);


	// On récupère les valeurs max et min de la température
	$res = mysql_query("select * from $db_name.archive_day_outTemp order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$mintemptime = date('H\hi',$row[2]);
	$mintemp = round($row[1],1);
	$maxtemp = round($row[3],1);
	$maxtemptime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de l'hygro
	$res = mysql_query("select * from $db_name.archive_day_outHumidity order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minhygrotime = date('H\hi',$row[2]);
	$minhygro = round($row[1],1);
	$maxhygro = round($row[3],1);
	$maxhygrotime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min du pt de rosée
	$res = mysql_query("select * from $db_name.archive_day_dewpoint order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$mindewpointtime = date('H\hi',$row[2]);
	$mindewpoint = round($row[1],1);
	$maxdewpoint = round($row[3],1);
	$maxdewpointtime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de la pression
	$res = mysql_query("select * from $db_name.archive_day_barometer order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minbarometertime = date('H\hi',$row[2]);
	$minbarometer = round($row[1],1);
	$maxbarometer = round($row[3],1);
	$maxbarometertime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min des précipitations
	$res = mysql_query("select * from $db_name.archive_day_rainRate order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minrainRatetime = date('H\hi',$row[2]);
	$minrainRate = round($row[1],1);
	$maxrainRate = round($row[3],1);
	$maxrainRatetime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min des rafales de vent
	$res = mysql_query("select * from $db_name.archive_day_wind order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minwindtime = date('H\hi',$row[2]);
	$minwind = round($row[1],1);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);

	// On récupère les valeurs max et min de l'UV
	$res = mysql_query("select * from $db_name.archive_day_UV order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minuvtime = date('H\hi',$row[2]);
	$minuv = round($row[1],1);
	$maxuv = round($row[3],1);
	$maxuvtime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min du refroidissement éolien
	$res = mysql_query("select * from $db_name.archive_day_windchill order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minwindchilltime = date('H\hi',$row[2]);
	$minwindchill = round($row[1],1);

	// On récupère les valeurs max et min de l'indice de chaleur
	$res = mysql_query("select * from $db_name.archive_day_heatindex order by dateTime DESC limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$maxheatindex = round($row[3],1);
	$maxheatindextime = date('H\hi',$row[4]);

?>
