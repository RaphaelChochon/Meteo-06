<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère le dernier enregistrement, et son datetime
	$res=mysql_query("SELECT dateTime FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$dateTime = $row[0];
	$date=date('d/m/Y',$dateTime);
	$heure=date('H\hi',$dateTime);

	// Temp mini
	$res=mysql_query("SELECT min, mintime FROM $db_name.archive_day_outTemp WHERE min = (SELECT min(min) FROM $db_name.archive_day_outTemp);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$mintemprec = round($row[0],1);
	$mintemptimerec = date('d/m/Y à H\hi',$row[1]);

	// Temp maxi
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_outTemp WHERE max = (SELECT max(max) FROM $db_name.archive_day_outTemp);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxtemprec = round($row[0],1);
	$maxtemptimerec = date('d/m/Y à H\hi',$row[1]);


	// Hygro mini
	$res=mysql_query("SELECT min, mintime FROM $db_name.archive_day_outHumidity WHERE min = (SELECT min(min) FROM $db_name.archive_day_outHumidity);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minhygrorec = round($row[0],1);
	$minhygrotimerec = date('d/m/Y à H\hi',$row[1]);

	// Hygro maxi
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_outHumidity WHERE max = (SELECT max(max) FROM $db_name.archive_day_outHumidity);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxhygrorec = round($row[0],1);
	$maxhygrotimerec = date('d/m/Y à H\hi',$row[1]);


	// Point de rosée mini
	$res=mysql_query("SELECT min, mintime FROM $db_name.archive_day_dewpoint WHERE min = (SELECT min(min) FROM $db_name.archive_day_dewpoint);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$mindewpointrec = round($row[0],1);
	$mindewpointtimerec = date('d/m/Y à H\hi',$row[1]);

	// Point de rosée maxi
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_dewpoint WHERE max = (SELECT max(max) FROM $db_name.archive_day_dewpoint);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxdewpointrec = round($row[0],1);
	$maxdewpointtimerec = date('d/m/Y à H\hi',$row[1]);


	// Pression mini (barometer)
	$res=mysql_query("SELECT min, mintime FROM $db_name.archive_day_barometer WHERE min = (SELECT min(min) FROM $db_name.archive_day_barometer);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minbarometerrec = round($row[0],1);
	$minbarometertimerec = date('d/m/Y à H\hi',$row[1]);

	// Pression maxi (barometer)
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_barometer WHERE max = (SELECT max(max) FROM $db_name.archive_day_barometer);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxbarometerrec = round($row[0],1);
	$maxbarometertimerec = date('d/m/Y à H\hi',$row[1]);


	// Rafales maxi
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_windGust WHERE max = (SELECT max(max) FROM $db_name.archive_day_windGust);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxwindgustrec = round($row[0],1);
	$maxwindgusttimerec = date('d/m/Y à H\hi',$row[1]);


if ($presence_uv == true){
	// Max UV
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_UV WHERE max = (SELECT max(max) FROM $db_name.archive_day_UV);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxuvrec = round($row[0],1);
	$maxuvtimerec = date('d/m/Y à H\hi',$row[1]);
};


if ($presence_radiation == true){
	// Max rayonnement solaire
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_radiation WHERE max = (SELECT max(max) FROM $db_name.archive_day_radiation);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxradiationrec = round($row[0],1);
	$maxradiationtimerec = date('d/m/Y à H\hi',$row[1]);

	// Max évapotranspiration
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_ET WHERE dateTime >= '$timestamp_maj_weewx_3_6_0' AND max = (SELECT max(max) FROM $db_name.archive_day_ET WHERE dateTime >= '$timestamp_maj_weewx_3_6_0');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxetrec = round($row[0],1);
	$maxettimerec = date('d/m/Y à H\hi',$row[1]);
};


	// Intensité précipitations maxi
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_rainRate WHERE max = (SELECT max(max) FROM $db_name.archive_day_rainRate);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxrainraterec = round($row[0]*10,1);
	$maxrainratetimerec = date('d/m/Y à H\hi',$row[1]);

	// Jour le plus pluvieux
	$res=mysql_query("SELECT sum, dateTime FROM $db_name.archive_day_rain WHERE sum = (SELECT max(sum) FROM $db_name.archive_day_rain);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxrainrec = round($row[0]*10,1);
	$maxraintimerec = date('d/m/Y',$row[1]);


	// Windchill mini
	$res=mysql_query("SELECT min, mintime FROM $db_name.archive_day_windchill WHERE min = (SELECT min(min) FROM $db_name.archive_day_windchill);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minwindchillrec = round($row[0],1);
	$minwindchilltimerec = date('d/m/Y à H\hi',$row[1]);


	// Heatindex maxi
	$res=mysql_query("SELECT max, maxtime FROM $db_name.archive_day_heatindex WHERE max = (SELECT max(max) FROM $db_name.archive_day_heatindex);") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxheatindexrec = round($row[0],1);
	$maxheatindextimerec = date('d/m/Y à H\hi',$row[1]);

?>
