<?php
	// appel du script de connexion
	require("connect.php");

	// On récupère le dernier enregistrement, et son datetime
	$res=mysql_query("select dateTime from $db_name.$db_table order by dateTime desc limit 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$dateTime = $row[0];
	$date=date('d/m/Y',$dateTime);
	$heure=date('H\hi',$dateTime);


	// On récupère le timestamp du dernier enregistrement
	$sql="select max(dateTime) from $db_name.$db_table";
	$query=mysql_query($sql);
	$list=mysql_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine
	// requête que les données des 7 derniers jours
	$stop=$list[0];
	$start7j=$stop-(86400*7);


	// Min temp
	$res=mysql_query("select dateTime, outTemp from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and outTemp = (select min(outTemp) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$mintemp7j = round($row[1],1);
	$mintemptime7j = date('d/m/Y à H\hi',$row[0]);

	// Max temp
	$res=mysql_query("select dateTime, outTemp from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and outTemp = (select max(outTemp) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxtemp7j = round($row[1],1);
	$maxtemptime7j = date('d/m/Y à H\hi',$row[0]);


	// Min Humidité
	$res=mysql_query("select dateTime, outHumidity from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and outHumidity = (select min(outHumidity) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minhygro7j = round($row[1],1);
	$minhygrotime7j = date('d/m/Y à H\hi',$row[0]);

	// Max Humidité
	$res=mysql_query("select dateTime, outHumidity from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and outHumidity = (select max(outHumidity) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxhygro7j = round($row[1],1);
	$maxhygrotime7j = date('d/m/Y à H\hi',$row[0]);


	// Min point de rosée
	$res=mysql_query("select dateTime, dewpoint from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and dewpoint = (select min(dewpoint) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$mindewpoint7j = round($row[1],1);
	$mindewpointtime7j = date('d/m/Y à H\hi',$row[0]);

	// Max point de rosée
	$res=mysql_query("select dateTime, dewpoint from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and dewpoint = (select max(dewpoint) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxdewpoint7j = round($row[1],1);
	$maxdewpointtime7j = date('d/m/Y à H\hi',$row[0]);


	// Min pression (barometer)
	$res=mysql_query("select dateTime, barometer from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and barometer = (select min(barometer) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minbarometer7j = round($row[1],1);
	$minbarometertime7j = date('d/m/Y à H\hi',$row[0]);

	// Max pression (barometer)
	$res=mysql_query("select dateTime, barometer from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and barometer = (select max(barometer) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxbarometer7j = round($row[1],1);
	$maxbarometertime7j = date('d/m/Y à H\hi',$row[0]);


	// Max rafales
	$res=mysql_query("select dateTime, windGust from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and windGust = (select max(windGust) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxwindgust7j = round($row[1],1);
	$maxwindgusttime7j = date('d/m/Y à H\hi',$row[0]);

if ($presence_uv == true){
	// Max UV
	$res=mysql_query("select dateTime, UV from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and UV = (select max(UV) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxuv7j = round($row[1],1);
	$maxuvtime7j = date('d/m/Y à H\hi',$row[0]);
};


if ($presence_radiation == true){
	// Max rayonnement solaire
	$res=mysql_query("select dateTime, radiation from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and radiation = (select max(radiation) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxradiation7j = round($row[1],1);
	$maxradiationtime7j = date('d/m/Y à H\hi',$row[0]);

	// Max évapotranspiration
	$res=mysql_query("select dateTime, ET from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and ET = (select max(ET) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxet7j = round($row[1],1);
	$maxettime7j = date('d/m/Y à H\hi',$row[0]);

	// Cumul évapotranspiration
	$res = mysql_query("select sum(ET) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop';") or die (mysql_error());
	$etrequ = mysql_fetch_row($res);
	$cumulet7j = round($etrequ[0]*10,2);
};


	// Max précipitations
	$res=mysql_query("select dateTime, rainRate from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and rainRate = (select max(rainRate) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxrainrate7j = round($row[1],1);
	$maxrainratetime7j = date('d/m/Y à H\hi',$row[0]);

	// Cumul précipitations
	$res = mysql_query("select sum(rain) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop';");
	$rainrequ = mysql_fetch_row($res);
	$cumulrain7j = round($rainrequ[0]*10,1);


	// Min windchill
	$res=mysql_query("select dateTime, windchill from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and windchill = (select min(windchill) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minwindchill7j = round($row[1],1);
	$minwindchilltime7j = date('d/m/Y à H\hi',$row[0]);


	// Max heatindex
	$res=mysql_query("select dateTime, heatindex from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop' and heatindex = (select max(heatindex) from $db_name.$db_table where dateTime >= '$start7j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxheatindex7j = round($row[1],1);
	$maxheatindextime7j = date('d/m/Y à H\hi',$row[0]);

?>
