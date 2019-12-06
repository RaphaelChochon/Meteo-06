<?php
	// appel du script de connexion
	require_once("connect.php");

	// Temp mini
	$sql = "SELECT min, mintime FROM $db_name.archive_day_outTemp WHERE min = (SELECT min(min) FROM $db_name.archive_day_outTemp);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mintemprec = round($row[0],1);
	$mintemptimerec = date('d/m/Y à H\hi',$row[1]);

	// Temp maxi
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_outTemp WHERE max = (SELECT max(max) FROM $db_name.archive_day_outTemp);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxtemprec = round($row[0],1);
	$maxtemptimerec = date('d/m/Y à H\hi',$row[1]);


	// Hygro mini
	$sql = "SELECT min, mintime FROM $db_name.archive_day_outHumidity WHERE min = (SELECT min(min) FROM $db_name.archive_day_outHumidity);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minhygrorec = round($row[0],1);
	$minhygrotimerec = date('d/m/Y à H\hi',$row[1]);

	// Hygro maxi
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_outHumidity WHERE max = (SELECT max(max) FROM $db_name.archive_day_outHumidity);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxhygrorec = round($row[0],1);
	$maxhygrotimerec = date('d/m/Y à H\hi',$row[1]);


	// Point de rosée mini
	$sql = "SELECT min, mintime FROM $db_name.archive_day_dewpoint WHERE min = (SELECT min(min) FROM $db_name.archive_day_dewpoint);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mindewpointrec = round($row[0],1);
	$mindewpointtimerec = date('d/m/Y à H\hi',$row[1]);

	// Point de rosée maxi
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_dewpoint WHERE max = (SELECT max(max) FROM $db_name.archive_day_dewpoint);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxdewpointrec = round($row[0],1);
	$maxdewpointtimerec = date('d/m/Y à H\hi',$row[1]);


	// Pression mini (barometer)
	$sql = "SELECT min, mintime FROM $db_name.archive_day_barometer WHERE min = (SELECT min(min) FROM $db_name.archive_day_barometer);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minbarometerrec = round($row[0],1);
	$minbarometertimerec = date('d/m/Y à H\hi',$row[1]);

	// Pression maxi (barometer)
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_barometer WHERE max = (SELECT max(max) FROM $db_name.archive_day_barometer);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxbarometerrec = round($row[0],1);
	$maxbarometertimerec = date('d/m/Y à H\hi',$row[1]);


	// Rafales maxi
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_windGust WHERE max = (SELECT max(max) FROM $db_name.archive_day_windGust);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwindgustrec = round($row[0],1);
	$maxwindgusttimerec = date('d/m/Y à H\hi',$row[1]);


if ($presence_uv){
	// Max UV
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_UV WHERE max = (SELECT max(max) FROM $db_name.archive_day_UV);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxuvrec = round($row[0],1);
	$maxuvtimerec = date('d/m/Y à H\hi',$row[1]);
};


if ($presence_radiation){
	// Max rayonnement solaire
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_radiation WHERE max = (SELECT max(max) FROM $db_name.archive_day_radiation);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxradiationrec = round($row[0],1);
	$maxradiationtimerec = date('d/m/Y à H\hi',$row[1]);

	// Max évapotranspiration
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_ET WHERE dateTime >= '$timestamp_maj_weewx_3_6_0' AND max = (SELECT max(max) FROM $db_name.archive_day_ET WHERE dateTime >= '$timestamp_maj_weewx_3_6_0');";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxetrec = round($row[0]*10,3);
	$maxettimerec = date('d/m/Y à H\hi',$row[1]);
};


	// Intensité précipitations maxi
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_rainRate WHERE max = (SELECT max(max) FROM $db_name.archive_day_rainRate);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainraterec = round($row[0]*10,1);
	$maxrainratetimerec = date('d/m/Y à H\hi',$row[1]);

	// Jour le plus pluvieux
	$sql = "SELECT sum, dateTime FROM $db_name.archive_day_rain WHERE sum = (SELECT max(sum) FROM $db_name.archive_day_rain);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainrec = round($row[0]*10,1);
	$maxraintimerec = date('d/m/Y',$row[1]);


	// Windchill mini
	$sql = "SELECT min, mintime FROM $db_name.archive_day_windchill WHERE min = (SELECT min(min) FROM $db_name.archive_day_windchill);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minwindchillrec = round($row[0],1);
	$minwindchilltimerec = date('d/m/Y à H\hi',$row[1]);


	// Heatindex maxi
	$sql = "SELECT max, maxtime FROM $db_name.archive_day_heatindex WHERE max = (SELECT max(max) FROM $db_name.archive_day_heatindex);";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxheatindexrec = round($row[0],1);
	$maxheatindextimerec = date('d/m/Y à H\hi',$row[1]);

?>
