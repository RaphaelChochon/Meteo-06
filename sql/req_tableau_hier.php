<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère les timestamp correspondant à la journée d'hier
	$yesterday = strtotime('last day midnight');
	$stophier = $yesterday+(86399);

	// On les rend lisible par l'humain pour les afficher
	$yesterday_human = date('d/m/Y à H\hi',$yesterday);
	$stophier_human = date('d/m/Y à H\hi',$stophier);


	// Min temp
	$sql = "SELECT dateTime, outTemp FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND outTemp = (SELECT min(outTemp) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mintemphier = round($row[1],1);
	$mintemptimehier = date('d/m/Y à H\hi',$row[0]);

	// Max temp
	$sql = "SELECT dateTime, outTemp FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND outTemp = (SELECT max(outTemp) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxtemphier = round($row[1],1);
	$maxtemptimehier = date('d/m/Y à H\hi',$row[0]);

	// Min Humidité
	$sql = "SELECT dateTime, outHumidity FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND outHumidity = (SELECT min(outHumidity) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minhygrohier = round($row[1],1);
	$minhygrotimehier = date('d/m/Y à H\hi',$row[0]);

	// Max Humidité
	$sql = "SELECT dateTime, outHumidity FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND outHumidity = (SELECT max(outHumidity) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxhygrohier = round($row[1],1);
	$maxhygrotimehier = date('d/m/Y à H\hi',$row[0]);

	// Min point de rosée
	$sql = "SELECT dateTime, dewpoint FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND dewpoint = (SELECT min(dewpoint) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mindewpointhier = round($row[1],1);
	$mindewpointtimehier = date('d/m/Y à H\hi',$row[0]);

	// Max point de rosée
	$sql = "SELECT dateTime, dewpoint FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND dewpoint = (SELECT max(dewpoint) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxdewpointhier = round($row[1],1);
	$maxdewpointtimehier = date('d/m/Y à H\hi',$row[0]);

	// Min pression (barometer)
	$sql = "SELECT dateTime, barometer FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND barometer = (SELECT min(barometer) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minbarometerhier = round($row[1],1);
	$minbarometertimehier = date('d/m/Y à H\hi',$row[0]);

	// Max pression (barometer)
	$sql = "SELECT dateTime, barometer FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND barometer = (SELECT max(barometer) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxbarometerhier = round($row[1],1);
	$maxbarometertimehier = date('d/m/Y à H\hi',$row[0]);

	// Max rafales
	$sql = "SELECT dateTime, windGust FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND windGust = (SELECT max(windGust) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwindgusthier = round($row[1],1);
	$maxwindgusttimehier = date('d/m/Y à H\hi',$row[0]);

if ($presence_uv === "true"){
	// Max UV
	$sql = "SELECT dateTime, UV FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND UV = (SELECT max(UV) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxuvhier = round($row[1],1);
	$maxuvtimehier = date('d/m/Y à H\hi',$row[0]);
};

if ($presence_radiation === "true"){
	// Max rayonnement solaire
	$sql = "SELECT dateTime, radiation FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND radiation = (SELECT max(radiation) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxradiationhier = round($row[1],1);
	$maxradiationtimehier = date('d/m/Y à H\hi',$row[0]);

	// Max évapotranspiration
	$sql = "SELECT dateTime, ET FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND dateTime >= '$timestamp_maj_weewx_3_6_0' AND ET = (SELECT max(ET) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND dateTime >= '$timestamp_maj_weewx_3_6_0');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxethier = round($row[1]*10,3);
	$maxettimehier = date('d/m/Y à H\hi',$row[0]);

	// Cumul évapotranspiration
	$sql = "SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND dateTime >= '$timestamp_maj_weewx_3_6_0';";
	$res = $conn->query($sql);
	$etrequ = mysqli_fetch_row($res);
	$cumulethier = round($etrequ[0]*10,2);
};

	// Max précipitations
	$sql = "SELECT dateTime, rainRate FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND rainRate = (SELECT max(rainRate) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainratehier = round($row[1]*10,1);
	$maxrainratetimehier = date('d/m/Y à H\hi',$row[0]);

	// Cumul précipitations
	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier';";
	$res = $conn->query($sql);
	$rainrequ = mysqli_fetch_row($res);
	$cumulrainhier = round($rainrequ[0]*10,1);

	// Min windchill
	$sql = "SELECT dateTime, windchill FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND windchill = (SELECT min(windchill) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minwindchillhier = round($row[1],1);
	$minwindchilltimehier = date('d/m/Y à H\hi',$row[0]);

	// Max heatindex
	$sql = "SELECT dateTime, heatindex FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier' AND heatindex = (SELECT max(heatindex) FROM $db_name.$db_table WHERE dateTime >= '$yesterday' AND dateTime <= '$stophier');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxheatindexhier = round($row[1],1);
	$maxheatindextimehier = date('d/m/Y à H\hi',$row[0]);

?>
