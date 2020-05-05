<?php
	// Date UTC
		date_default_timezone_set('UTC');

	// INIT
		$dataFiabTn = array();
		$dataFiabTx = array();

	// RECUP DATA
		$query_string = "SELECT `dateDay` AS `dateDay`,
							`TnFiab` AS `TnFiab`,
							`TxFiab` AS `TxFiab`
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
					$TnFiab = "null";
					if (!is_null ($row['TnFiab'])) {
						$TnFiab = $row['TnFiab'];
					}
					$fiabTn[] = "[$tsDateDay, $TnFiab]";

				// Tx
					$TxFiab = "null";
					if (!is_null ($row['TxFiab'])) {
						$TxFiab = $row['TxFiab'];
					}
					$fiabTx[] = "[$tsDateDay, $TxFiab]";
			}
		}