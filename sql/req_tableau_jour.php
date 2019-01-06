<?php
	// appel du script de connexion
	require_once("connect.php");

	//mysql_select_db($db_name);

	// Calcul des précipitations 24/48/72 heures glissantes et cumul ET, etc.
	// On récupère le timestamp du dernier enregistrement
	$sql="SELECT max(dateTime) FROM $db_name.$db_table";
	$query = $conn->query($sql);
	$list=mysqli_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
	$stop=$list[0];
	$minutes10=$stop-(600);
	$start1=$stop-(3599);
	$start3=$stop-(10800);
	$start6=$stop-(21600);
	$start12=$stop-(43200);
	$start24=$stop-(86400);
	$start48=$stop-(86400*2);
	$start72=$stop-(86400*3);

	// On récupère les valeurs actuelles simple
	// Mais d'abord on vérifie si la valeur actuelle n'est pas null
	$dewpoint_check = $row[16];
	if($dewpoint_check == null){
		// si elle est null, alors on lui donne la valeur N/A
		$dewpoint = 'N/A';
	}else{
		// sinon on l'arrondie (eventuellement) et on l'affiche
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
	if ($presence_radiation === "true"){
		$radiation_check = $row[20];
		if ($radiation_check == null){
			$radiation = 'N/A';
		}else{
			$radiation = round($row[20],1);
		}
	};
	//
	if ($presence_uv === "true"){
		$uv_check = $row[21];
		if($row[21]== null){
			$uv='N/A';
		}else{
			$uv=$row[21];
		}
	};
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
		$rainrate = round($row[14]*10,1);
	}
	//
	// Calcul du cumul de précipitations de la journée
	$today = strtotime('today midnight');
	$rain_sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime>'$today';";
	$rain = $conn->query($rain_sql);
	$he = mysqli_fetch_row($rain);
	$cumul = round($he[0]*10,1);

	/*
	 * VITESSE VENT
	 */

	// Calcul de la vitesse moyenne sur 10 minutes du vent moyen
	$sql = "SELECT AVG(windSpeed) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_wind_10 = round($row[0],1);

	// Caclul de la vitesse moyenne des rafales sur les 10 dernières minutes
	$sql = "SELECT AVG(windGust) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_windGust_10 = round($row[0],1);

	// Calcul de la vitesse moyenne sur 1 heure du vent moyen
	$sql = "SELECT AVG(windSpeed) FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_wind_1h = round($row[0],1);

	// Caclul de la vitesse moyenne des rafales sur la dernière heure
	$sql = "SELECT AVG(windGust) FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$avg_windGust_1h = round($row[0],1);

	/*
	 * DIRECTION VENT
	 */


	// Dans un premier temps, on définit une fonction qui permettra de calculer
	// la moyenne de plusieurs angles en degrées
	function mean_of_angles( $angles, $degrees = true ) {
		if ( $degrees ) {
			$angles = array_map("deg2rad", $angles); // Convert to radians
		}
		$s_ = 0;
		$c_ = 0;
		$len = count( $angles );
		for ($i = 0; $i < $len; $i++) {
			$s_ += sin( $angles[$i] );
			$c_ += cos( $angles[$i] );
		}
		// $s_ /= $len;
		// $c_ /= $len;
		$mean = atan2( $s_, $c_ );
		if ( $degrees ) {
			$mean = rad2deg( $mean ); // Convert to degrees
		}
		if ($mean < 0) {
			$mean_ok = $mean + 360;
		} else {
			$mean_ok = $mean;
		}
		return $mean_ok;
	}
	// Maintenant, on définit une fonction qui servira à nous donner
	// la position cardinale du vent (en texte plutôt qu'en degrés)
	function wind_cardinals($deg) {
		$cardinalDirections = array(
			'N' => array(348.75, 361),
			'N2' => array(0, 11.25),
			'NNE' => array(11.25, 33.75),
			'NE' => array(33.75, 56.25),
			'ENE' => array(56.25, 78.75),
			'E' => array(78.75, 101.25),
			'ESE' => array(101.25, 123.75),
			'SE' => array(123.75, 146.25),
			'SSE' => array(146.25, 168.75),
			'S' => array(168.75, 191.25),
			'SSW' => array(191.25, 213.75),
			'SW' => array(213.75, 236.25),
			'WSW' => array(236.25, 258.75),
			'W' => array(258.75, 281.25),
			'WNW' => array(281.25, 303.75),
			'NW' => array(303.75, 326.25),
			'NNW' => array(326.25, 348.75)
		);
		foreach ($cardinalDirections as $dir => $angles) {
			if ($deg >= $angles[0] && $deg < $angles[1]) {
				$cardinal = str_replace("2", "", $dir);
			}
		}
		return $cardinal;
	};

	/*
	 * VENT MOYEN 10 minutes
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windDir FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windDir10Array = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windDir'])) {
			$windDir10Array[] = $row['windDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windDir_10 = mean_of_angles($windDir10Array);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windDir_10_check = $avg_windDir_10;
		if ($avg_windDir_10_check === null || $avg_windDir_10_check == ''){
			$cardinalDir10 = 'N/A';
			$avg_windDir_10 = 'N/A';
		}
		else{
			$cardinalDir10 = wind_cardinals($avg_windDir_10);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windDir_10 = round($avg_windDir_10,1);
		}

	/*
	 * VENT MOYEN 1 heure
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windDir FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windDir1hArray = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windDir'])) {
			$windDir1hArray[] = $row['windDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windDir_1h = mean_of_angles($windDir1hArray);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windDir_1h_check = $avg_windDir_1h;
		if ($avg_windDir_1h_check === null || $avg_windDir_1h_check == ''){
			$cardinalDir1h = 'N/A';
			$avg_windDir_1h = 'N/A';
		}
		else{
			$cardinalDir1h = wind_cardinals($avg_windDir_1h);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windDir_1h = round($avg_windDir_1h,1);
		}

	/*
	 * VENT RAFALES 10 minutes
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windGustDir FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windGustDir10Array = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windGustDir'])) {
			$windGustDir10Array[] = $row['windGustDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windGustDir_10 = mean_of_angles($windGustDir10Array);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windGustDir_10_check = $avg_windGustDir_10;
		if ($avg_windGustDir_10_check === null || $avg_windGustDir_10_check == ''){
			$cardinalGustDir10 = 'N/A';
			$avg_windGustDir_10 = 'N/A';
		}
		else{
			$cardinalGustDir10 = wind_cardinals($avg_windGustDir_10);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windGustDir_10 = round($avg_windGustDir_10,1);
		}

	/*
	 * VENT RAFALES 1 heure
	 */

	// Récupération des valeurs de direction du vent moyen des 10 dernières minutes
	$sql = "SELECT windGustDir FROM $db_name.$db_table WHERE dateTime>='$start1' AND dateTime <= '$stop';";

	// Requete + mise en tableau de la réponse
	$windGustDir1hArray = array();
	foreach ($conn->query($sql) as $row) {
		// le `!is_null` permet de vérifier qu'il n'y est pas de valeur NULL dans le calcul
		// sinon c'était pris en comme une direction à 0 degrés
		if (!is_null ($row['windGustDir'])) {
			$windGustDir1hArray[] = $row['windGustDir'];
		}
	}

	// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
	$avg_windGustDir_1h = mean_of_angles($windGustDir1hArray);

	// Maintenant on vérifie que la moyenne ne soit pas NULL
	// si elle est NULL ou si la chaine est vide, on renvoie N/A
	// sinon on execute la fonction pour la convertir en position cardinale
	$avg_windGustDir_1h_check = $avg_windGustDir_1h;
		if ($avg_windGustDir_1h_check === null || $avg_windGustDir_1h_check == ''){
			$cardinalGustDir1h = 'N/A';
			$avg_windGustDir_1h = 'N/A';
		}
		else{
			$cardinalGustDir1h = wind_cardinals($avg_windGustDir_1h);
			// Enfin, on arrondi la moyenne en degrés avec une seule décimale
			$avg_windGustDir_1h = round($avg_windGustDir_1h,1);
		}

	/*
	 * DIRECTIONS INSTANTANÉES
	 */

	// Direction du vent moyen instant
	$sql = "SELECT windDir FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);

	$windDir_check = $row[0];
	if ($windDir_check === null || $windDir_check == ''){
		$windDir = 'N/A';
		$cardinalWindDir = 'N/A';
	}else{
		$cardinalWindDir = wind_cardinals($windDir_check);
		$windDir = round($windDir_check,1);
	}
	//
	// Direction du vent rafales instant
	$sql = "SELECT windGustDir FROM $db_name.$db_table ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);

	$windGustDir_check = $row[0];
	if ($windGustDir_check === null || $windGustDir_check == ''){
		$windGustDir = 'N/A';
		$cardinalWindGustDir = 'N/A';
	}else{
		$cardinalWindGustDir = wind_cardinals($windGustDir_check);
		$windGustDir = round($windGustDir_check,1);
	}

	/*
	 * FIN DE CALCUL DE LA DIRECTION DU VENT
	 */

	// ET
	if ($presence_radiation === "true"){
		// Calcul de l'ET sur la dernière heure
		$sql = "SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime>= '$start1' AND dateTime <= '$stop';";
		$et_1h = $conn->query($sql);
		$et_1h_requ = mysqli_fetch_row($et_1h);
		$et = round($et_1h_requ[0]*10,3);

		// Calcul du cumul d'ET de la journée
		$sql = "SELECT sum(ET) FROM $db_name.$db_table WHERE dateTime>'$today';";
		$etreq = $conn->query($sql);
		$etrequ = mysqli_fetch_row($etreq);
		$etcumul = round($etrequ[0]*10,2);
	};


	// Calcul des précipitations cumulées sur différents pas de temps
	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start1' AND dateTime <= '$stop' ORDER BY 1;";
	$rain1 = $conn->query($sql);
	$he1 = mysqli_fetch_row($rain1);
	$cumul1 = round($he1[0]*10,1);

	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start3' AND dateTime <= '$stop' ORDER BY 1;";
	$rain3 = $conn->query($sql);
	$he3 = mysqli_fetch_row($rain3);
	$cumul3 = round($he3[0]*10,1);

	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start6' AND dateTime <= '$stop' ORDER BY 1;";
	$rain6 = $conn->query($sql);
	$he6 = mysqli_fetch_row($rain6);
	$cumul6 = round($he6[0]*10,1);

	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start12' AND dateTime <= '$stop' ORDER BY 1;";
	$rain12 = $conn->query($sql);
	$he12 = mysqli_fetch_row($rain12);
	$cumul12 = round($he12[0]*10,1);

	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start24' AND dateTime <= '$stop' ORDER BY 1;";
	$rain24 = $conn->query($sql);
	$he24 = mysqli_fetch_row($rain24);
	$cumul24 = round($he24[0]*10,1);

	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
	$rain48 = $conn->query($sql);
	$he48 = mysqli_fetch_row($rain48);
	$cumul48 = round($he48[0]*10,1);

	$sql = "SELECT sum(rain) FROM $db_name.$db_table WHERE dateTime >= '$start72' AND dateTime <= '$stop' ORDER BY 1;";
	$rain72 = $conn->query($sql);
	$he72 = mysqli_fetch_row($rain72);
	$cumul72 = round($he72[0]*10,1);


	// On récupère les valeurs max et min de la température
	$sql = "SELECT * FROM $db_name.archive_day_outTemp ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mintemptime = date('H\hi',$row[2]);
	$mintemp = round($row[1],1);
	$maxtemp = round($row[3],1);
	$maxtemptime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de l'hygro
	$sql = "SELECT * FROM $db_name.archive_day_outHumidity ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minhygrotime = date('H\hi',$row[2]);
	$minhygro = round($row[1],1);
	$maxhygro = round($row[3],1);
	$maxhygrotime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min du pt de rosée
	$sql = "SELECT * FROM $db_name.archive_day_dewpoint ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$mindewpointtime = date('H\hi',$row[2]);
	$mindewpoint = round($row[1],1);
	$maxdewpoint = round($row[3],1);
	$maxdewpointtime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min de la pression
	$sql = "SELECT * FROM $db_name.archive_day_barometer ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minbarometertime = date('H\hi',$row[2]);
	$minbarometer = round($row[1],1);
	$maxbarometer = round($row[3],1);
	$maxbarometertime = date('H\hi',$row[4]);

	// On récupère les valeurs max et min des précipitations
	$sql = "SELECT * FROM $db_name.archive_day_rainRate ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxrainRate = round($row[3]*10,1);
	$maxrainRatetime = date('H\hi',$row[4]);

	// On récupère les valeurs max des rafales de vent
	$sql = "SELECT * FROM $db_name.archive_day_wind ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxwind = round($row[3],1);
	$maxwindtime = date('H\hi',$row[4]);
	$maxwinddir = round($row[9],2);
	if ($maxwinddir === null || $maxwinddir == '') {
		$maxwinddir = 'N/A';
		$cardinalMaxWindDir = 'N/A';
	} else {
		$cardinalMaxWindDir = wind_cardinals($maxwinddir);
	}
	
	// UV
	if ($presence_uv === "true"){
		// Calcul de la moyenne sur 10 minutes de l'indice UV
		$sql = "SELECT AVG(UV) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$avg_UV_10 = round($row[0],1);

		// On récupère les valeurs max de l'UV
		$sql = "SELECT * FROM $db_name.archive_day_UV ORDER BY dateTime DESC LIMIT 1;";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$maxuv = round($row[3],1);
		$maxuvtime = date('H\hi',$row[4]);
	};

	// On récupère la valeur min du refroidissement éolien
	$sql = "SELECT * FROM $db_name.archive_day_windchill ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$minwindchilltime = date('H\hi',$row[2]);
	$minwindchill = round($row[1],1);

	// On récupère la valeur max de l'indice de chaleur
	$sql = "SELECT * FROM $db_name.archive_day_heatindex ORDER BY dateTime DESC LIMIT 1;";
	$res = $conn->query($sql);
	$row = mysqli_fetch_row($res);
	$maxheatindex = round($row[3],1);
	$maxheatindextime = date('H\hi',$row[4]);

	// Rayonnement solaire et ET
	if ($presence_radiation === "true"){
		// Calcul de la moyenne sur 10 minutes du rayonnement solaire (radiation)
		$sql = "SELECT AVG(radiation) FROM $db_name.$db_table WHERE dateTime>='$minutes10' AND dateTime <= '$stop';";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$avg_radiation_10 = round($row[0],1);

		// On récupère les valeurs max du rayonnement solaire
		$sql = "SELECT * FROM $db_name.archive_day_radiation ORDER BY dateTime DESC LIMIT 1;";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$maxradiation = round($row[3],1);
		$maxradiationtime = date('H\hi',$row[4]);

		// On récupère les valeurs max de l'ET
		$sql = "SELECT * FROM $db_name.archive_day_ET ORDER BY dateTime DESC LIMIT 1;";
		$res = $conn->query($sql);
		$row = mysqli_fetch_row($res);
		$maxet = round($row[3]*10,3);
		$maxettime = date('H\hi',$row[4]);
	};

?>
