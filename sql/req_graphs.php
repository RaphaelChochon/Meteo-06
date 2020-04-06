<?php
	// Date UTC
	date_default_timezone_set('UTC');

	// appel du script de connexion
	require_once("connect.php");

	// On récupère le timestamp du dernier enregistrement
	$sql = "SELECT MAX(dateTime) FROM $db";
	$query = $conn->query($sql);
	$list = mysqli_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
	$stop = $list[0];
	if ($last == '24') {
		$start = $stop-(86400);
	} elseif ($last == '48') {
		$start = $stop-(86400*2);
	} elseif ($last == '72') {
		$start = $stop-(86400*3);
	} elseif ($last == '96') {
		$start = $stop-(86400*4);
	} elseif ($last == '120') {
		$start = $stop-(86400*5);
	} elseif ($last == '144') {
		$start = $stop-(86400*6);
	} elseif ($last == '168') {
		$start = $stop-(86400*7);
	} else {
		exit;
	}
	$minuit = strtotime('today midnight')*1000;
	$minuit_hier = strtotime('yesterday midnight')*1000;
	$minuit_3 = strtotime('-2 day midnight')*1000;
	$minuit_4 = strtotime('-3 day midnight')*1000;
	$minuit_5 = strtotime('-4 day midnight')*1000;
	$minuit_6 = strtotime('-5 day midnight')*1000;
	$minuit_7 = strtotime('-6 day midnight')*1000;
	$minuit_8 = strtotime('-7 day midnight')*1000;

	$dataWs = array();
	$dataWg = array();
	$dataWsD = array();
	$dataWgD = array();

	$cumulRR = 0;

	if ($result = mysqli_query($conn,
						"SELECT dateTime AS dateTime, outTemp AS outTemp, outHumidity AS outHumidity, dewpoint AS dewpoint,
						barometer AS barometer, windSpeed AS windSpeed, windDir AS windDir, windGust AS windGust, windGustDir AS windGustDir,
						rain AS rain, rainRate AS rainRate, UV AS UV, radiation AS radiation, ET AS ET
						FROM $db
						WHERE dateTime >= '$start' AND dateTime <= '$stop'
						ORDER BY dateTime ASC")){
		while ($row = mysqli_fetch_assoc($result)) {
		// Time
		$dt = $row['dateTime']*1000;
		$rowArrayTemp['x'] = $row['dateTime']*1000;
		$rowArrayWs['x'] = $rowArrayTemp['x'];
		$rowArrayWg['x'] = $rowArrayTemp['x'];
		$rowArrayWsD['x'] = $rowArrayTemp['x'];
		$rowArrayWgD['x'] = $rowArrayTemp['x'];

		// Temp
		$Temp = "null";
		if ($row['outTemp'] != null) {
			$Temp = round($row['outTemp'],1);
		}
		$dataTemp[] = "[$dt, $Temp]";

		// Humidité
		$hr = "null";
		if ($row['outHumidity'] != null) {
			$hr = round($row['outHumidity'],1);
		}
		$dataHr[] = "[$dt, $hr]";

		// Point de rosée
		$dewPoint = "null";
		if ($row['dewpoint'] != null) {
			$dewPoint = round($row['dewpoint'],1);
		}
		$dataTd[] = "[$dt, $dewPoint]";

		// Barometer
		$barometer = "null";
		if ($row['barometer'] != null) {
			$barometer = round($row['barometer'],1);
		}
		$dataBaro[] = "[$dt, $barometer]";

		// Wind speed
		if ($row['windSpeed'] != null) {$rowArrayWs['y'] = round($row['windSpeed'],1); } else {$rowArrayWs['y'] = null;};
		if ($row['windGust'] != null) {$rowArrayWg['y'] = round($row['windGust'],1); } else {$rowArrayWg['y'] = null;};

		// Wind dir
		if ($row['windDir'] != null) {$rowArrayWsD['y'] = round($row['windDir'],1); $rowArrayWs['dir'] = round($row['windDir'],1); } else {$rowArrayWsD['y'] = null; $rowArrayWs['dir'] = null;};
		if ($row['windGustDir'] != null) {$rowArrayWgD['y'] = round($row['windGustDir'],1); $rowArrayWg['dir'] = round($row['windGustDir'],1); } else {$rowArrayWgD['y'] = null; $rowArrayWg['dir'] = null;};

		// Rain
		$RR = "null";
		if ($row['rain'] != null) {
			$RR = round($row['rain']*10,1);
		}
		$dataRR[] = "[$dt, $RR]";

		// RainRate
		$RRate = "null";
		if ($row['rainRate'] != null) {
			$RRate = round($row['rainRate']*10,1);
		}
		$dataRRate[] = "[$dt, $RRate]";

		$RRincrement = $cumulRR;
		$cumulRR = round($RRincrement + ($row['rain']*10),1);
		$dataRRCumul[] = "[$dt, $cumulRR]";

		// UV
		if ($presence_uv){
			$UV = "null";
			if ($row['UV'] != null) {
				$UV = round($row['UV'],1);
			}
			$dataUV[] = "[$dt, $UV]";
		}

		// Radiation & ET
		if ($presence_radiation){
			// Radiation
			$Rad = "null";
			if ($row['radiation'] != null) {
				$Rad = round($row['radiation'],0);
			}
			$dataRadiation[] = "[$dt, $Rad]";

			// ET
			$ET = "null";
			if ($row['ET'] != null) {
				$ET = round($row['ET']*10,1);
			}
			$dataET[] = "[$dt, $ET]";
		}

		array_push($dataWs,$rowArrayWs);
		array_push($dataWg,$rowArrayWg);
		array_push($dataWsD,$rowArrayWsD);
		array_push($dataWgD,$rowArrayWgD);
		}
	}

	$dataWs = json_encode($dataWs, JSON_NUMERIC_CHECK);
	$dataWg = json_encode($dataWg, JSON_NUMERIC_CHECK);
	$dataWsD = json_encode($dataWsD, JSON_NUMERIC_CHECK);
	$dataWgD = json_encode($dataWgD, JSON_NUMERIC_CHECK);

	// Récupération des valeurs climatos
	$db_name_climato = "climato_station";
	$a = explode("_",$db_name);
	$aCount = count($a);
	if ($aCount == '2') {
		$db_table_climato = $a[1]."_day";
	} elseif ($aCount == '3') {
		$db_table_climato = $a[1]."_".$a[2]."_day";
	}

	$dateDay1 = date('Y-m-d',$stop);
	$dateDay5 = date('Y-m-d',$stop-(86400*7));

	$dataTn = array();
	$dataTx = array();
	$dataRRClimato = array();

	if ($result = mysqli_query($conn,
		"SELECT date_day AS dateTime, Tn AS Tn, Tn_datetime AS TnDt, Tx AS Tx, Tx_datetime AS TxDt,
		RR AS RR, RR_max_intensite AS RRmaxInt, RR_maxInt_datetime AS RRmaxIntDt
		FROM $db_name_climato.$db_table_climato
		WHERE date_day >= '$dateDay5' AND date_day <= '$dateDay1'
		ORDER BY Datetime ASC")){
		while($row = mysqli_fetch_array($result)) {
			$dateDay = $row['dateTime'];
			if ($row['Tn'] != null) { $rowArrayTn['Tn'] = round($row['Tn'],1); } else {$rowArrayTn['Tn'] = null;};
			if ($row['TnDt'] != null) { $rowArrayTn['TnDt'] = strtotime($row['TnDt'])*1000; } else {$rowArrayTn['TnDt'] = null;};
			if ($row['dateTime'] != null) { $rowArrayTn['dateDay'] = strtotime($row['dateTime'])*1000; } else {$rowArrayTn['dateDay'] = null;};

			if ($row['Tx'] != null) { $rowArrayTx['Tx'] = round($row['Tx'],1); } else {$rowArrayTx['Tx'] = null;};
			if ($row['TxDt'] != null) { $rowArrayTx['TxDt'] = strtotime($row['TxDt'])*1000; } else {$rowArrayTx['TxDt'] = null;};
			if ($row['dateTime'] != null) { $rowArrayTx['dateDay'] = strtotime($row['dateTime'])*1000; } else {$rowArrayTx['dateDay'] = null;};

			$rowArrayClim['dateDay'] = $dateDay;
			$rowArrayClim['dateDay6h'] = (strtotime($dateDay)+108000)*1000;
			if ($row['RR'] != null) { $rowArrayClim['RR'] = round($row['RR'],1); } else {$rowArrayClim['RR'] = null;};
			if ($row['RRmaxInt'] != null) { $rowArrayClim['RRmaxInt'] = round($row['RRmaxInt'],1); $rowArrayClim['RRmaxIntDt'] = strtotime($row['RRmaxIntDt'])*1000; } else {$rowArrayClim['RRmaxInt'] = null; $rowArrayClim['RRmaxIntDt'] = null; };

			array_push($dataTn,$rowArrayTn);
			array_push($dataTx,$rowArrayTx);
			array_push($dataRRClimato,$rowArrayClim);
		}
	}
	mysqli_close($conn);

?>
