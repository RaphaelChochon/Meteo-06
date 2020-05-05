<?php
	// Date UTC
		date_default_timezone_set('UTC');

	// INIT
		$dataTn = array();
		$dataTmoy = array();
		$dataTx = array();
		$dataTmoyReel = array();
		$dataTmoy1h = array();
		$dataTmoy3h = array();

		$rowArrayRRMonthTemp = 0;
		$rowArrayRRYearTemp = 0;

	// RECUP DATA
		$query_string = "SELECT `dateDay` AS `dateDay`,
							`Tn` AS `Tn`,
							`TnDt` AS `TnDt`,
							`TnFiab` AS `TnFiab`,
							`Tx` AS `Tx`,
							`TxDt` AS `TxDt`,
							`TxFiab` AS `TxFiab`,
							`Tmoy` AS `Tmoy`,
							`TmoyReel` AS `TmoyReel`,
							`Tmoy1h` AS `Tmoy1h`,
							`Tmoy3h` AS `Tmoy3h`
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
					$TnFiab = "null";
					if (!is_null ($row['Tn'])) {
						$Tn = round($row['Tn'],1);
					}
					if (!is_null ($row['TnDt'])) {
						$TnTs = strtotime($row['TnDt'])*1000;
					}
					// Fiab
					if (!is_null ($row['TnFiab'])) {
						$TnFiab = $row['TnFiab'];
					}
					$dataTn[] = "[$tsDateDay, $Tn]";
					$metaTn[] = "[$TnTs, $TnFiab]";

				// Tx
					$Tx    = "null";
					$TxTs  = "null";
					$TxFiab = "null";
					if (!is_null ($row['Tx'])) {
						$Tx = round($row['Tx'],1);
					}
					if (!is_null ($row['TxDt'])) {
						$TxTs = strtotime($row['TxDt'])*1000;
					}
					// Fiab
					if (!is_null ($row['TxFiab'])) {
						$TxFiab = $row['TxFiab'];
					}
					$dataTx[] = "[$tsDateDay, $Tx]";
					$metaTx[] = "[$TxTs, $TxFiab]";

				// Tmoy
					$Tmoy = "null";
					if (!is_null ($row['Tmoy'])) {
						$Tmoy = round($row['Tmoy'],1);
					}
					$dataTmoy[] = "[$tsDateDay, $Tmoy]";

				// TmoyReel
					$TmoyReel = "null";
					if (!is_null ($row['TmoyReel'])) {
						$TmoyReel = round($row['TmoyReel'],1);
					}
					$dataTmoyReel[] = "[$tsDateDay, $TmoyReel]";

				// Tmoy1h
					$Tmoy1h = "null";
					if (!is_null ($row['Tmoy1h'])) {
						$Tmoy1h = round($row['Tmoy1h'],1);
					}
					$dataTmoy1h[] = "[$tsDateDay, $Tmoy1h]";

				// Tmoy1h
					$Tmoy3h = "null";
					if (!is_null ($row['Tmoy3h'])) {
						$Tmoy3h = round($row['Tmoy3h'],1);
					}
					$dataTmoy3h[] = "[$tsDateDay, $Tmoy3h]";
			}
		}
