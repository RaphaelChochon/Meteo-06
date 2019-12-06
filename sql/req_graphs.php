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

	$dataTemp = array();
	$dataHr = array();
	$dataTd = array();
	$dataBaro = array();
	$dataWs = array();
	$dataWg = array();
	$dataWsD = array();
	$dataWgD = array();
	$dataRR = array();
	$dataRRate = array();
	$dataRRCumul = array();

	if ($presence_uv){
		$dataUV = array();
	}
	if ($presence_radiation){
		$dataRadiation = array();
		$dataET = array();
	}

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
		$rowArrayTemp['x'] = $row['dateTime']*1000;
		$rowArrayHr['x'] = $rowArrayTemp['x'];
		$rowArrayTd['x'] = $rowArrayTemp['x'];
		$rowArrayBaro['x'] = $rowArrayTemp['x'];
		$rowArrayWs['x'] = $rowArrayTemp['x'];
		$rowArrayWg['x'] = $rowArrayTemp['x'];
		$rowArrayWsD['x'] = $rowArrayTemp['x'];
		$rowArrayWgD['x'] = $rowArrayTemp['x'];
		$rowArrayRR['x'] = $rowArrayTemp['x'];
		$rowArrayRRate['x'] = $rowArrayTemp['x'];
		$rowArrayRRCumul['x'] = $rowArrayTemp['x'];

		if ($presence_uv){
			$rowArrayUV['x'] = $rowArrayTemp['x'];
		}
		if ($presence_radiation){
			$rowArrayRadiation['x'] = $rowArrayTemp['x'];
			$rowArrayET['x'] = $rowArrayTemp['x'];
		}
		

		// TempOut
		if ($row['outTemp'] != null) {$rowArrayTemp['y'] = round($row['outTemp'],1); } else {$rowArrayTemp['y'] = null;};
		if ($row['outHumidity'] != null) {$rowArrayHr['y'] = round($row['outHumidity'],1); } else {$rowArrayHr['y'] = null;};
		if ($row['dewpoint'] != null) {$rowArrayTd['y'] = round($row['dewpoint'],1); } else {$rowArrayTd['y'] = null;};

		// Barometer
		if ($row['barometer'] != null) {$rowArrayBaro['y'] = round($row['barometer'],1); } else {$rowArrayBaro['y'] = null;};

		// Wind speed
		if ($row['windSpeed'] != null) {$rowArrayWs['y'] = round($row['windSpeed'],1); } else {$rowArrayWs['y'] = null;};
		if ($row['windGust'] != null) {$rowArrayWg['y'] = round($row['windGust'],1); } else {$rowArrayWg['y'] = null;};

		// Wind dir
		if ($row['windDir'] != null) {$rowArrayWsD['y'] = round($row['windDir'],1); $rowArrayWs['dir'] = round($row['windDir'],1); } else {$rowArrayWsD['y'] = null; $rowArrayWs['dir'] = null;};
		if ($row['windGustDir'] != null) {$rowArrayWgD['y'] = round($row['windGustDir'],1); $rowArrayWg['dir'] = round($row['windGustDir'],1); } else {$rowArrayWgD['y'] = null; $rowArrayWg['dir'] = null;};

		// Rain
		if ($row['rain'] != null) {$rowArrayRR['y'] = round($row['rain']*10,1); } else {$rowArrayRR['y'] = null;};
		if ($row['rainRate'] != null) {$rowArrayRRate['y'] = round($row['rainRate']*10,1); } else {$rowArrayRRate['y'] = null;};
		$RRincrement = $cumulRR;
		$cumulRR = $RRincrement + ($row['rain']*10);
		$rowArrayRRCumul['y'] = round($cumulRR,1);

		// UV
		if ($presence_uv){
			if ($row['UV'] != null) {$rowArrayUV['y'] = round($row['UV'],1); } else {$rowArrayUV['y'] = null;};
		}

		// Radiation & ET
		if ($presence_radiation){
			// Radiation
			if ($row['radiation'] != null) {$rowArrayRadiation['y'] = round($row['radiation'],0); } else {$rowArrayRadiation['y'] = null;};

			// ET
			if ($row['ET'] != null) {$rowArrayET['y'] = round($row['ET']*10,1); } else {$rowArrayET['y'] = null;};
		}

		array_push($dataTemp,$rowArrayTemp);
		array_push($dataHr,$rowArrayHr);
		array_push($dataTd,$rowArrayTd);
		array_push($dataBaro,$rowArrayBaro);
		array_push($dataWs,$rowArrayWs);
		array_push($dataWg,$rowArrayWg);
		array_push($dataWsD,$rowArrayWsD);
		array_push($dataWgD,$rowArrayWgD);
		array_push($dataRR,$rowArrayRR);
		array_push($dataRRate,$rowArrayRRate);
		array_push($dataRRCumul,$rowArrayRRCumul);

		if ($presence_uv){
			array_push($dataUV,$rowArrayUV);
		}
		if ($presence_radiation){
			array_push($dataRadiation,$rowArrayRadiation);
			array_push($dataET,$rowArrayET);
		}
		}
	}

	$dataTemp = json_encode($dataTemp, JSON_NUMERIC_CHECK);
	$dataHr = json_encode($dataHr, JSON_NUMERIC_CHECK);
	$dataTd = json_encode($dataTd, JSON_NUMERIC_CHECK);
	$dataBaro = json_encode($dataBaro, JSON_NUMERIC_CHECK);
	$dataWs = json_encode($dataWs, JSON_NUMERIC_CHECK);
	$dataWg = json_encode($dataWg, JSON_NUMERIC_CHECK);
	$dataWsD = json_encode($dataWsD, JSON_NUMERIC_CHECK);
	$dataWgD = json_encode($dataWgD, JSON_NUMERIC_CHECK);
	$dataRR = json_encode($dataRR, JSON_NUMERIC_CHECK);
	$dataRRate = json_encode($dataRRate, JSON_NUMERIC_CHECK);
	$dataRRCumul = json_encode($dataRRCumul, JSON_NUMERIC_CHECK);

	if ($presence_uv){
		$dataUV = json_encode($dataUV, JSON_NUMERIC_CHECK);
	}
	if ($presence_radiation){
		$dataRadiation = json_encode($dataRadiation, JSON_NUMERIC_CHECK);
		$dataET = json_encode($dataET, JSON_NUMERIC_CHECK);
	}

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
			if ($row['Tx'] != null) { $rowArrayTx['Tx'] = round($row['Tx'],1); } else {$rowArrayTx['Tx'] = null;};
			if ($row['TxDt'] != null) { $rowArrayTx['TxDt'] = strtotime($row['TxDt'])*1000; } else {$rowArrayTx['TxDt'] = null;};

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
