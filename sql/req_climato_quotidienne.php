<?php
	$db_name_climato = "climato_station";
	$a = explode("_",$db_name);
	$aCount = count($a);
	if ($aCount == '2') {
		$db_table_climato = $a[1]."_day";
	} elseif ($aCount == '3') {
		$db_table_climato = $a[1]."_".$a[2]."_day";
	}

	$conn = mysqli_connect($server,$user,$pass,$db_name_climato);

	$dataTn = array();
	$dataTmoy = array();
	$dataTx = array();
	$dataRR = array();
	$dataRRYear = array();
	$dataTmoyReel = array();
	$dataTmoy1h = array();
	$dataTmoy3h = array();

	$rowArrayRRYearTemp = 0;

	if ($result = mysqli_query($conn,
						"SELECT date_day AS Datetime, Tn AS Tn, Tn_datetime AS TnDt, Tx AS Tx, Tx_datetime AS TxDt,
						Tmoy AS Tmoy, TmoyReel AS TmoyReel, Tmoy1h AS Tmoy1h, Tmoy3h AS Tmoy3h,
						RR AS RR, RR_max_intensite AS RRMaxIntensite, RR_maxInt_datetime AS RRMaxIntensiteDt, RRRecord AS RRRecord,
						TnRecord AS TnRecord, TxRecord AS TxRecord, TmoyReelRecord AS TmoyReelRecord, Tmoy1hRecord AS Tmoy1hRecord, Tmoy3hRecord AS Tmoy3hRecord,
						expectedRecord06 AS expectedRecordTx, expectedRecord18 AS expectedRecordTn, expectedRecord00 AS expectedRecord00
						FROM $db_name_climato.$db_table_climato
						ORDER BY Datetime ASC")){
		while ($row = mysqli_fetch_assoc($result)) {
		// Time
		$rowArrayTn['x'] = strtotime($row['Datetime'])*1000;
		// La même chose pour les autres
		$rowArrayTmoy['x'] = $rowArrayTn['x'];
		$rowArrayTx['x'] = $rowArrayTn['x'];
		$rowArrayRR['x'] = $rowArrayTn['x'];
		$rowArrayRRYear['x'] = $rowArrayTn['x'];
		$rowArrayTmoyReel['x'] = $rowArrayTn['x'];
		$rowArrayTmoy1h['x'] = $rowArrayTn['x'];
		$rowArrayTmoy3h['x'] = $rowArrayTn['x'];


		// Tn
		if ($row['Tn'] != null) {$rowArrayTn['y'] = round($row['Tn'],1); } else {$rowArrayTn['y'] = null;};
		if ($row['TnDt'] != null) {$rowArrayTn['TnDt'] = strtotime($row['TnDt'])*1000; } else {$rowArrayTn['TnDt'] = null;};
		// Tn expected record
		if ($row['TnRecord'] != null && $row['TnRecord'] != '0' && $row['expectedRecordTn'] != null && $row['expectedRecordTn'] != '0') {$rowArrayTn['TnFiab'] = round(($row['TnRecord']*100)/$row['expectedRecordTn'],1); } else {$rowArrayTn['TnFiab'] = null;};

		// Tx
		if ($row['Tx'] != null) {$rowArrayTx['y'] = round($row['Tx'],1); } else {$rowArrayTx['y'] = null;};
		if ($row['TxDt'] != null) {$rowArrayTx['TxDt'] = strtotime($row['TxDt'])*1000; } else {$rowArrayTx['TxDt'] = null;};
		// Tx expected record
		if ($row['TxRecord'] != null && $row['TxRecord'] != '0' && $row['expectedRecordTx'] != null && $row['expectedRecordTx'] != '0') {$rowArrayTx['TxFiab'] = round(($row['TxRecord']*100)/$row['expectedRecordTx'],1); } else {$rowArrayTx['TxFiab'] = null;};

		// Tmoy
		if ($row['Tmoy'] != null) {$rowArrayTmoy['y'] = round($row['Tmoy'],1); } else {$rowArrayTmoy['y'] = null;};
		
		// TmoyReel
		if ($row['TmoyReel'] != null) {$rowArrayTmoyReel['y'] = round($row['TmoyReel'],1); } else {$rowArrayTmoyReel['y'] = null;};
		// TmoyReel expected record
		if ($row['TmoyReelRecord'] != null && $row['TmoyReelRecord'] != '0' && $row['expectedRecord00'] != null && $row['expectedRecord00'] != '0') {$rowArrayTmoyReel['Fiab'] = round($row['TmoyReelRecord']*100/$row['expectedRecord00'],1); } else {$rowArrayTmoyReel['Fiab'] = null;};

		// Tmoy1h
		if ($row['Tmoy1h'] != null) {$rowArrayTmoy1h['y'] = round($row['Tmoy1h'],1); } else {$rowArrayTmoy1h['y'] = null;};
		// Tmoy1h expected record
		if ($row['Tmoy1hRecord'] != null && $row['Tmoy1hRecord'] != '0') {$rowArrayTmoy1h['Fiab'] = round($row['Tmoy1hRecord']*100/24,1); } else {$rowArrayTmoy1h['Fiab'] = null;};

		// Tmoy3h
		if ($row['Tmoy3h'] != null) {$rowArrayTmoy3h['y'] = round($row['Tmoy3h'],1); } else {$rowArrayTmoy3h['y'] = null;};
		// Tmoy3h expected record
		if ($row['Tmoy3hRecord'] != null && $row['Tmoy3hRecord'] != '0') {$rowArrayTmoy3h['Fiab'] = round($row['Tmoy3hRecord']*100/8,1); } else {$rowArrayTmoy3h['Fiab'] = null;};

		// RR
		if ($row['RR'] != null) {$rowArrayRR['y'] = round($row['RR'],1); } else {$rowArrayRR['y'] = null;};
		if ($row['RRMaxIntensite'] != null && $row['RRMaxIntensiteDt'] != null) {$rowArrayRR['RRMaxInt'] = round($row['RRMaxIntensite'],1); $rowArrayRR['RRMaxIntDt'] = strtotime($row['RRMaxIntensiteDt'])*1000;; } else {$rowArrayRR['RRMaxInt'] = null; $rowArrayRR['RRMaxIntDt'] = null;};
		// RR expected record
		if ($row['RRRecord'] != null && $row['RRRecord'] != '0' && $row['expectedRecordTx'] != null && $row['expectedRecordTx'] != '0') {$rowArrayRR['RRFiab'] = round(($row['RRRecord']*100)/$row['expectedRecordTx'],1); } else {$rowArrayRR['RRFiab'] = null;};
		// RR Cumul year
		$RRDtYear = date('d-m', strtotime($row['Datetime']));
		if ($RRDtYear != '01-01') {
			$RRincrement = $rowArrayRRYearTemp;
		} else {
			$RRincrement = '0';
		}
		$rowArrayRRYearTemp = $RRincrement + $row['RR'];
		$rowArrayRRYear['y'] = round($rowArrayRRYearTemp,1);


		array_push($dataTn,$rowArrayTn);
		array_push($dataTmoy,$rowArrayTmoy);
		array_push($dataTx,$rowArrayTx);
		array_push($dataRR,$rowArrayRR);
		array_push($dataRRYear,$rowArrayRRYear);
		array_push($dataTmoyReel,$rowArrayTmoyReel);
		array_push($dataTmoy1h,$rowArrayTmoy1h);
		array_push($dataTmoy3h,$rowArrayTmoy3h);
		}
	}

	$dataTn = json_encode($dataTn, JSON_NUMERIC_CHECK);
	$dataTmoy = json_encode($dataTmoy, JSON_NUMERIC_CHECK);
	$dataTx = json_encode($dataTx, JSON_NUMERIC_CHECK);
	$dataRR = json_encode($dataRR, JSON_NUMERIC_CHECK);
	$dataRRYear = json_encode($dataRRYear, JSON_NUMERIC_CHECK);
	$dataTmoyReel = json_encode($dataTmoyReel, JSON_NUMERIC_CHECK);
	$dataTmoy1h = json_encode($dataTmoy1h, JSON_NUMERIC_CHECK);
	$dataTmoy3h = json_encode($dataTmoy3h, JSON_NUMERIC_CHECK);











	mysqli_close($conn);
?>