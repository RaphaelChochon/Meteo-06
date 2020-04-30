<?php
	// Date UTC
	date_default_timezone_set('UTC');

	$db_name_climato = "climato_station";
	$a = explode("_",$db_name);
	$aCount = count($a);
	if ($aCount == '2') {
		$db_table_climato = $a[1]."_day";
	} elseif ($aCount == '3') {
		$db_table_climato = $a[1]."_".$a[2]."_day";
	}

	// @@todo à remplacer par PDO
	$conn = mysqli_connect($server,$user,$pass,$db_name_climato);

	$dataFiabTn = array();
	$dataFiabTx = array();
	$dataFiabRR = array();

	$rowArrayRRMonthTemp = 0;
	$rowArrayRRYearTemp = 0;

	if ($result = mysqli_query($conn,
						"SELECT date_day AS Datetime, RRRecord AS RRRecord, TnRecord AS TnRecord, TxRecord AS TxRecord,
						expectedRecord06 AS expectedRecordTx, expectedRecord18 AS expectedRecordTn, expectedRecord00 AS expectedRecord00
						FROM $db_name_climato.$db_table_climato
						ORDER BY Datetime ASC")){
		while ($row = mysqli_fetch_assoc($result)) {
		// Time
		$rowArrayFiabTn['x'] = strtotime($row['Datetime'])*1000;
		// La même chose pour les autres
		$rowArrayFiabTx['x'] = $rowArrayFiabTn['x'];
		$rowArrayFiabRR['x'] = $rowArrayFiabTn['x'];


		// Tn Fiab
		if ($row['TnRecord'] != null && $row['TnRecord'] != '0' && $row['expectedRecordTn'] != null && $row['expectedRecordTn'] != '0') {$rowArrayFiabTn['y'] = round(($row['TnRecord']*100)/$row['expectedRecordTn'],1); } else {$rowArrayFiabTn['y'] = null;};

		// Tx Fiab
		if ($row['TxRecord'] != null && $row['TxRecord'] != '0' && $row['expectedRecordTx'] != null && $row['expectedRecordTx'] != '0') {$rowArrayFiabTx['y'] = round(($row['TxRecord']*100)/$row['expectedRecordTx'],1); } else {$rowArrayFiabTx['y'] = null;};

		// RR Fiab
		if ($row['RRRecord'] != null && $row['RRRecord'] != '0' && $row['expectedRecordTx'] != null && $row['expectedRecordTx'] != '0') {$rowArrayFiabRR['y'] = round(($row['RRRecord']*100)/$row['expectedRecordTx'],1); } else {$rowArrayFiabRR['y'] = null;};

		array_push($dataFiabTn,$rowArrayFiabTn);
		array_push($dataFiabTx,$rowArrayFiabTx);
		array_push($dataFiabRR,$rowArrayFiabRR);
		}
	}

	$dataFiabTn = json_encode($dataFiabTn, JSON_NUMERIC_CHECK);
	$dataFiabTx = json_encode($dataFiabTx, JSON_NUMERIC_CHECK);
	$dataFiabRR = json_encode($dataFiabRR, JSON_NUMERIC_CHECK);

	mysqli_close($conn);
?>