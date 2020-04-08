<?php
	// appel du script de connexion
	// @@todo à remplacer par PDO
	require_once("connect.php");

	// On récupère le timestamp du dernier enregistrement
	$sql = "SELECT max(dateTime) FROM $db_name.$db_table";
	$query = $conn->query($sql);
	$list = mysqli_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine
	// requête que les données des 30 derniers jours
	$stop = $list[0];
	$start30j = $stop-(86400*30);


	// Min temp
	$sql = "SELECT dateTime, outTemp FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND outTemp = (SELECT min(outTemp) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mintemp30j = round($row[1],1);
	$mintemptime30j = date('d/m/Y à H\hi',$row[0]);

	// Max temp
	$sql = "SELECT dateTime, outTemp FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND outTemp = (SELECT max(outTemp) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxtemp30j = round($row[1],1);
	$maxtemptime30j = date('d/m/Y à H\hi',$row[0]);


	// Min Humidité
	$sql = "SELECT dateTime, outHumidity FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND outHumidity = (SELECT min(outHumidity) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minhygro30j = round($row[1],1);
	$minhygrotime30j = date('d/m/Y à H\hi',$row[0]);

	// Max Humidité
	$sql = "SELECT dateTime, outHumidity FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND outHumidity = (SELECT max(outHumidity) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxhygro30j = round($row[1],1);
	$maxhygrotime30j = date('d/m/Y à H\hi',$row[0]);


	// Min point de rosée
	$sql = "SELECT dateTime, dewpoint FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND dewpoint = (SELECT min(dewpoint) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mindewpoint30j = round($row[1],1);
	$mindewpointtime30j = date('d/m/Y à H\hi',$row[0]);

	// Max point de rosée
	$sql = "SELECT dateTime, dewpoint FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND dewpoint = (SELECT max(dewpoint) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxdewpoint30j = round($row[1],1);
	$maxdewpointtime30j = date('d/m/Y à H\hi',$row[0]);


	// Min pression (barometer)
	$sql = "SELECT dateTime, barometer FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND barometer = (SELECT min(barometer) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minbarometer30j = round($row[1],1);
	$minbarometertime30j = date('d/m/Y à H\hi',$row[0]);

	// Max pression (barometer)
	$sql = "SELECT dateTime, barometer FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND barometer = (SELECT max(barometer) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxbarometer30j = round($row[1],1);
	$maxbarometertime30j = date('d/m/Y à H\hi',$row[0]);


	// Max rafales
	$sql = "SELECT dateTime, windGust FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND windGust = (SELECT max(windGust) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwindgust30j = round($row[1],1);
	$maxwindgusttime30j = date('d/m/Y à H\hi',$row[0]);

if ($presence_uv){
	// Max UV
	$sql = "SELECT dateTime, UV FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND UV = (SELECT max(UV) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxuv30j = round($row[1],1);
	$maxuvtime30j = date('d/m/Y à H\hi',$row[0]);
};


if ($presence_radiation){
	// Max rayonnement solaire
	$sql = "SELECT dateTime, radiation FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND radiation = (SELECT max(radiation) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxradiation30j = round($row[1],1);
	$maxradiationtime30j = date('d/m/Y à H\hi',$row[0]);

	// Max évapotranspiration
	$sql = "SELECT dateTime, ET FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND dateTime >= '$timestamp_maj_weewx_3_6_0' AND ET = (SELECT max(ET) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND dateTime >= '$timestamp_maj_weewx_3_6_0');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxet30j = round($row[1]*10,3);
	$maxettime30j = date('d/m/Y à H\hi',$row[0]);

	// Cumul évapotranspiration
	$sql = "SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND dateTime >= '$timestamp_maj_weewx_3_6_0';";
	$res = $conn->query($sql);
	$etrequ = mysqli_fetch_row($res);
	$cumulet30j = round($etrequ[0]*10,2);
};


	// Max précipitations
	$sql = "SELECT dateTime, rainRate FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND rainRate = (SELECT max(rainRate) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainrate30j = round($row[1]*10,1);
	$maxrainratetime30j = date('d/m/Y à H\hi',$row[0]);

	// Cumul précipitations
	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$rainrequ = mysqli_fetch_row($res);
	$cumulrain30j = round($rainrequ[0]*10,1);


	// Min windchill
	$sql = "SELECT dateTime, windchill FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND windchill = (SELECT min(windchill) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minwindchill30j = round($row[1],1);
	$minwindchilltime30j = date('d/m/Y à H\hi',$row[0]);


	// Max heatindex
	$sql = "SELECT dateTime, heatindex FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop' AND heatindex = (SELECT max(heatindex) FROM $db_name.$db_table WHERE dateTime >= '$start30j' AND dateTime <= '$stop');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxheatindex30j = round($row[1],1);
	$maxheatindextime30j = date('d/m/Y à H\hi',$row[0]);

?>
