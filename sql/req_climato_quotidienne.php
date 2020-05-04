<?php
	// Date UTC
	date_default_timezone_set('UTC');

	// INIT
		$dataTn = array();
		$dataTmoy = array();
		$dataTx = array();
		$dataRR = array();
		$dataRrMonth = array();
		$dataRrYear = array();
		$dataTmoyReel = array();
		$dataTmoy1h = array();
		$dataTmoy3h = array();

		$rowArrayRRMonthTemp = 0;
		$rowArrayRRYearTemp = 0;

	// RECUP DATA
		$query_string = "SELECT `dateDay` AS `dateDay`,
							`Tn` AS `Tn`,
							`TnDt` AS `TnDt`,
							`TnRec` AS `TnRec`,
							`Tx` AS `Tx`,
							`TxDt` AS `TxDt`,
							`TxRec` AS `TxRec`,
							`Tmoy` AS `Tmoy`,
							`RR` AS `RR`,
							`RRateMax` AS `RRateMax`,
							`RRateMaxDt` AS `RRateMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						ORDER BY `dateDay` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				// Time
					$tsDateDay = strtotime($row['dateDay'])*1000;
				
				// Tn
					$Tn    = "null";
					$TnTs  = "null";
					$TnRec = "null";
					if (!is_null ($row['Tn'])) {
						$Tn = round($row['Tn'],1);
					}
					if (!is_null ($row['TnDt'])) {
						$TnTs = strtotime($row['TnDt'])*1000;
					}
					// Fiab
					if (!is_null ($row['TnRec'])) {
						$TnRec = $row['TnRec'];
					}
					$dataTn[] = "[$tsDateDay, $Tn]";
					$metaTn[] = "[$TnTs, $TnRec]";

				// Tx
					$Tx    = "null";
					$TxTs  = "null";
					$TxRec = "null";
					if (!is_null ($row['Tx'])) {
						$Tx = round($row['Tx'],1);
					}
					if (!is_null ($row['TxDt'])) {
						$TxTs = strtotime($row['TxDt'])*1000;
					}
					// Fiab
					if (!is_null ($row['TxRec'])) {
						$TxRec = $row['TxRec'];
					}
					$dataTx[] = "[$tsDateDay, $Tx]";
					$metaTx[] = "[$TxTs, $TxRec]";

				// Tmoy
					$Tmoy = "null";
					if (!is_null ($row['Tmoy'])) {
						$Tmoy = round($row['Tmoy'],1);
					}
					$dataTmoy[] = "[$tsDateDay, $Tmoy]";

				// RR
					$RR         = "null";
					$RRateMax   = "null";
					$RRateMaxTs = "null";
					if (!is_null ($row['RR'])) {
						$RR = round($row['RR'],1);
					}
					if (!is_null ($row['RRateMax'])) {
						$RRateMax = round($row['RRateMax'],1);
					}
					if (!is_null ($row['RRateMaxDt'])) {
						$RRateMaxTs = strtotime($row['RRateMaxDt'])*1000;
					}
					$dataRR[] = "[$tsDateDay, $RR]"; // On utilise le TxRec pour le nb de rec de la pluie
					$metaRR[] = "[$RRateMax, $RRateMaxTs, $TxRec]"; // On utilise le TxRec pour le nb de rec de la pluie

				// RR Cumul month
					$RRDtMonth = date('d', strtotime($row['dateDay']));
					if ($RRDtMonth != '01') {
						$RRincrementMonth = $rowArrayRRMonthTemp;
					} else {
						$RRincrementMonth = '0';
					}
					$rowArrayRRMonthTemp = $RRincrementMonth + $row['RR'];
					$RRMonthTemp = round($rowArrayRRMonthTemp,1);
					$dataRrMonth[] = "[$tsDateDay, $RRMonthTemp]";

				// RR Cumul year
					$RRDtYear = date('d-m', strtotime($row['dateDay']));
					if ($RRDtYear != '01-01') {
						$RRincrement = $rowArrayRRYearTemp;
					} else {
						$RRincrement = '0';
					}
					$rowArrayRRYearTemp = $RRincrement + $row['RR'];
					$RRYearTemp = round($rowArrayRRYearTemp,1);
					$dataRrYear[] = "[$tsDateDay, $RRYearTemp]";

			}
		}