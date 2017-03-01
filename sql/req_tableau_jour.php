<?php
	// appel du script de connexion
	require_once("connect.php");

	mysql_select_db($db_name);

	// Calcul des précipitations 24/48/72 heures glissantes et cumul ET, etc.
	// On récupère le timestamp du dernier enregistrement
	$sql="SELECT max(dateTime) FROM $db_name.$db_table";
	$query=mysql_query($sql);
	$list=mysql_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
	$stop=$list[0];
	$start1=$stop-(3599);
	$start24=$stop-(86400);
	$start48=$stop-(86400*2);
	$start72=$stop-(86400*3);

	// On récupère les valeurs actuelles
	// Mais d'abord on vérifie si la valeur actuelle n'est pas null
	$dewpoint_check = $row[16];
	if($dewpoint_check == null){
		// si elle est null, alors on lui donne la valeur N/A
		$dewpoint = 'N/A';
	}else{
		// sinon on l'arrondie
		$dewpoint = round($row[16],1);
	}
	//
	$temp_check = $row[7];
	if ($temp_check == null){
		$temp = 'N/A';
	}else{
		$temp = round($row[7],1);
	}
	//
	$wind_check = $row[10];
	if ($wind_check == null){
		$wind = 'N/A';
	}else{
		$wind = round($row[10],1);
	}
	//
	$windgust_check = $row[12];
	if ($windgust_check == null){
		$windgust = 'N/A';
	}else{
		$windgust = round($row[12],1);
	}
	//
	$windgustdir_check = $row[13];
	if ($windgustdir_check == null){
		$windgustdir = 'N/A';
	}else{
		$windgustdir = round($row[13],1);
	}
	//
	$hygro_check = $row[9];
	if ($hygro_check == null){
		$hygro = 'N/A';
	}else{
		$hygro = round($row[9],1);
	}
	//
	$barometer_check = $row[3];
	if ($barometer_check == null){
		$barometer = 'N/A';
	}else{
		$barometer = round($row[3],1);
	}
	//
	$radiation_check = $row[20];
	if ($radiation_check == null){
		$radiation = 'N/A';
	}else{
		$radiation = round($row[20],1);
	}
	//
	$heatindex_check = $row[18];
	if ($heatindex_check == null){
		$heatindex = 'N/A';
	}else{
		$heatindex = round($row[18],1);
	}
	//
	$windchill_check = $row[17];
	if ($windchill_check == null){
		$windchill = 'N/A';
	}else{
		$windchill = round($row[17],1);
	}
	//
	$rainrate_check = $row[14];
	if ($rainrate_check == null){
		$rainrate = 'N/A';
	}else{
		$rainrate = round($row[14],1);
	}
	//
	$today = strtotime('today midnight');
	$rain = mysql_query("SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime>'$today';");
	$he = mysql_fetch_row($rain);
	$cumul = round($he[0]*10,1);

if ($presence_uv == true){
	$uv_check = $row[21];
	if($row[21]== null){
		$uv='N/A';
	}else{
		$uv=$row[21];
	}
};

if ($presence_radiation == true){
	//$et = round($row[19]*10,3);
	$et_1h = mysql_query("SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime>= '$start1' AND dateTime <= '$stop';");
	$et_1h_requ = mysql_fetch_row($et_1h);
	$et = round($et_1h_requ[0]*10,3);
	//
	$etreq = mysql_query("SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime>'$today';");
	$etrequ = mysql_fetch_row($etreq);
	$etcumul = round($etrequ[0]*10,2);
};

	$rain24 = mysql_query("SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start24' AND dateTime <= '$stop' ORDER BY 1;");
	$he24 = mysql_fetch_row($rain24);
	$cumul24 = round($he24[0]*10,1);

	$rain48 = mysql_query("SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;");
	$he48 = mysql_fetch_row($rain48);
	$cumul48 = round($he48[0]*10,1);

	$rain72 = mysql_query("SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start72' AND dateTime <= '$stop' ORDER BY 1;");
	$he72 = mysql_fetch_row($rain72);
	$cumul72 = round($he72[0]*10,1);


	// On récupère les valeurs max et min de la température
	$res = mysql_query("SELECT * FROM $db_name.archive_day_outTemp ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$mintemptime = date('H\hi',$row[2]);
	$mintemp = round($row[1],1);
	$maxtemp = round($row[3],1);
	$maxtemptime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de l'hygro
	$res = mysql_query("SELECT * FROM $db_name.archive_day_outHumidity ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minhygrotime = date('H\hi',$row[2]);
	$minhygro = round($row[1],1);
	$maxhygro = round($row[3],1);
	$maxhygrotime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min du pt de rosée
	$res = mysql_query("SELECT * FROM $db_name.archive_day_dewpoint ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$mindewpointtime = date('H\hi',$row[2]);
	$mindewpoint = round($row[1],1);
	$maxdewpoint = round($row[3],1);
	$maxdewpointtime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de la pression
	$res = mysql_query("SELECT * FROM $db_name.archive_day_barometer ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minbarometertime = date('H\hi',$row[2]);
	$minbarometer = round($row[1],1);
	$maxbarometer = round($row[3],1);
	$maxbarometertime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min des précipitations
	$res = mysql_query("SELECT * FROM $db_name.archive_day_rainRate ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minrainRatetime = date('H\hi',$row[2]);
	$minrainRate = round($row[1]*10,1);
	$maxrainRate = round($row[3]*10,1);
	$maxrainRatetime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min des rafales de vent
	$res = mysql_query("SELECT * FROM $db_name.archive_day_wind ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minwindtime = date('H\hi',$row[2]);
	$minwind = round($row[1],1);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);

if ($presence_uv == true){
	// On récupère les valeurs max et min de l'UV
	$res = mysql_query("SELECT * FROM $db_name.archive_day_UV ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minuvtime = date('H\hi',$row[2]);
	$minuv = round($row[1],1);
	$maxuv = round($row[3],1);
	$maxuvtime = date('H\hi',$row[4]);
};

	// On récupère les valeurs max et min du refroidissement éolien
	$res = mysql_query("SELECT * FROM $db_name.archive_day_windchill ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$minwindchilltime = date('H\hi',$row[2]);
	$minwindchill = round($row[1],1);

	// On récupère les valeurs max de l'indice de chaleur
	$res = mysql_query("SELECT * FROM $db_name.archive_day_heatindex ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$maxheatindex = round($row[3],1);
	$maxheatindextime = date('H\hi',$row[4]);

if ($presence_radiation == true){
	// On récupère les valeurs max du rayonnement solaire
	$res = mysql_query("SELECT * FROM $db_name.archive_day_radiation ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$maxradiation = round($row[3],1);
	$maxradiationtime = date('H\hi',$row[4]);

	// On récupère les valeurs max de l'ET
	$res = mysql_query("SELECT * FROM $db_name.archive_day_ET ORDER BY dateTime DESC LIMIT 1;") or die(mysql_error());
	$row = mysql_fetch_row($res);
	$maxet = round($row[3]*10,3);
	$maxettime = date('H\hi',$row[4]);
};

?>
