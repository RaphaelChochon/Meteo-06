<?php
	// appel du script de connexion
	require_once("connect.php");

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
	// requête que les données des 30 derniers jours
	$stop=$list[0];
	$start30j=$stop-(86400*30);


	// Min temp
	$res=mysql_query("select dateTime, outTemp from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and outTemp = (select min(outTemp) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$mintemp30j = round($row[1],1);
	$mintemptime30j = date('d/m/Y à H\hi',$row[0]);

	// Max temp
	$res=mysql_query("select dateTime, outTemp from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and outTemp = (select max(outTemp) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxtemp30j = round($row[1],1);
	$maxtemptime30j = date('d/m/Y à H\hi',$row[0]);


	// Min Humidité
	$res=mysql_query("select dateTime, outHumidity from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and outHumidity = (select min(outHumidity) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minhygro30j = round($row[1],1);
	$minhygrotime30j = date('d/m/Y à H\hi',$row[0]);

	// Max Humidité
	$res=mysql_query("select dateTime, outHumidity from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and outHumidity = (select max(outHumidity) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxhygro30j = round($row[1],1);
	$maxhygrotime30j = date('d/m/Y à H\hi',$row[0]);


	// Min point de rosée
	$res=mysql_query("select dateTime, dewpoint from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and dewpoint = (select min(dewpoint) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$mindewpoint30j = round($row[1],1);
	$mindewpointtime30j = date('d/m/Y à H\hi',$row[0]);

	// Max point de rosée
	$res=mysql_query("select dateTime, dewpoint from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and dewpoint = (select max(dewpoint) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxdewpoint30j = round($row[1],1);
	$maxdewpointtime30j = date('d/m/Y à H\hi',$row[0]);


	// Min pression (barometer)
	$res=mysql_query("select dateTime, barometer from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and barometer = (select min(barometer) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minbarometer30j = round($row[1],1);
	$minbarometertime30j = date('d/m/Y à H\hi',$row[0]);

	// Max pression (barometer)
	$res=mysql_query("select dateTime, barometer from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and barometer = (select max(barometer) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxbarometer30j = round($row[1],1);
	$maxbarometertime30j = date('d/m/Y à H\hi',$row[0]);


	// Max rafales
	$res=mysql_query("select dateTime, windGust from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and windGust = (select max(windGust) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxwindgust30j = round($row[1],1);
	$maxwindgusttime30j = date('d/m/Y à H\hi',$row[0]);

if ($presence_uv == true){
	// Max UV
	$res=mysql_query("select dateTime, UV from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and UV = (select max(UV) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxuv30j = round($row[1],1);
	$maxuvtime30j = date('d/m/Y à H\hi',$row[0]);
};


if ($presence_radiation == true){
	// Max rayonnement solaire
	$res=mysql_query("select dateTime, radiation from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and radiation = (select max(radiation) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxradiation30j = round($row[1],1);
	$maxradiationtime30j = date('d/m/Y à H\hi',$row[0]);

	// Max évapotranspiration
	$res=mysql_query("select dateTime, ET from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and ET = (select max(ET) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxet30j = round($row[1],1);
	$maxettime30j = date('d/m/Y à H\hi',$row[0]);

	// Cumul évapotranspiration
	$res = mysql_query("select sum(ET) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop';") or die (mysql_error());
	$etrequ = mysql_fetch_row($res);
	$cumulet30j = round($etrequ[0]*10,2);
};


	// Max précipitations
	$res=mysql_query("select dateTime, rainRate from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and rainRate = (select max(rainRate) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxrainrate30j = round($row[1],1);
	$maxrainratetime30j = date('d/m/Y à H\hi',$row[0]);

	// Cumul précipitations
	$res = mysql_query("select sum(rain) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop';");
	$rainrequ = mysql_fetch_row($res);
	$cumulrain30j = round($rainrequ[0]*10,1);


	// Min windchill
	$res=mysql_query("select dateTime, windchill from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and windchill = (select min(windchill) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$minwindchill30j = round($row[1],1);
	$minwindchilltime30j = date('d/m/Y à H\hi',$row[0]);


	// Max heatindex
	$res=mysql_query("select dateTime, heatindex from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop' and heatindex = (select max(heatindex) from $db_name.$db_table where dateTime >= '$start30j' and dateTime <= '$stop');") or die (mysql_error());
	$row = mysql_fetch_row($res);
	$maxheatindex30j = round($row[1],1);
	$maxheatindextime30j = date('d/m/Y à H\hi',$row[0]);

?>
