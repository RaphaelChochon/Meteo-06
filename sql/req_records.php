<?php

// Date UTC
	date_default_timezone_set('UTC');

	/**
	 * Récup des Tn
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`Tn` AS `TnMin`,
								`TnDt` AS `TnMinDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `Tn` IS NOT NULL
						ORDER BY `TnMin` ASC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$TnMin = null;
				$TnMinDt = null;

				// Insert dans le tableau
				$tabTn [$ts] = array();

				if (!is_null($row['TnMin'])) {
					$TnMin = round($row['TnMin'],1);
					if (!is_null($row['TnMinDt'])) {
						$TnMinDt = date('H\hi',strtotime($row['TnMinDt']));
					}
				}

				// Insert dans le tableau
				$tabTn [$ts] ['TnMin'] = $TnMin;
				$tabTn [$ts] ['TnMinDt'] = $TnMinDt;
			}
		}

	/**
	 * Récup des Tx
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`Tx` AS `TxMax`,
								`TxDt` AS `TxMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `Tx` IS NOT NULL
						ORDER BY `TxMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$TxMax = null;
				$TxMaxDt = null;

				// Insert dans le tableau
				$tabTx [$ts] = array();

				if (!is_null($row['TxMax'])) {
					$TxMax = round($row['TxMax'],1);
					if (!is_null($row['TxMaxDt'])) {
						$TxMaxDt = date('H\hi',strtotime($row['TxMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabTx [$ts] ['TxMax'] = $TxMax;
				$tabTx [$ts] ['TxMaxDt'] = $TxMaxDt;
			}
		}

	/**
	 * Récup des amplitude max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`TempRange` AS `TempRange`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `TempRange` IS NOT NULL
						ORDER BY `TempRange` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$TempRange = null;

				// Insert dans le tableau
				$tabTempRange [$ts] = array();

				if (!is_null($row['TempRange'])) {
					$TempRange = round($row['TempRange'],1);
				}

				// Insert dans le tableau
				$tabTempRange [$ts] ['TempRange'] = $TempRange;
			}
		}

	/**
	 * Récup des RR quoti max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`RR` AS `RrMax`,
								`RRateMax` AS `RRateMax`,
								`RRateMaxDt` AS `RRateMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `RR` IS NOT NULL
						ORDER BY `RrMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$RrMax = null;
				$RRateMax = null;
				$RRateMaxDt = null;

				// Insert dans le tableau
				$tabRr [$ts] = array();

				if (!is_null($row['RrMax'])) {
					$RrMax = round($row['RrMax'],1);
					if (!is_null($row['RRateMax'])) {
						$RRateMax = round($row['RRateMax'],1);
						$RRateMaxDt = date('H\hi',strtotime($row['RRateMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabRr [$ts] ['RrMax'] = $RrMax;
				$tabRr [$ts] ['RRateMax'] = $RRateMax;
				$tabRr [$ts] ['RRateMaxDt'] = $RRateMaxDt;
			}
		}

	/**
	 * Récup des intensités max de pluie
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`RRateMax` AS `RRateMax`,
								`RRateMaxDt` AS `RRateMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `RRateMax` IS NOT NULL
						ORDER BY `RRateMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$RRateMax = null;
				$RRateMaxDt = null;

				// Insert dans le tableau
				$tabRrate [$ts] = array();

				if (!is_null($row['RRateMax'])) {
					$RRateMax = round($row['RRateMax'],1);
					if (!is_null($row['RRateMaxDt'])) {
						$RRateMaxDt = date('H\hi',strtotime($row['RRateMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabRrate [$ts] ['RRateMax'] = $RRateMax;
				$tabRrate [$ts] ['RRateMaxDt'] = $RRateMaxDt;
			}
		}

	if ($presence_radiation) {
	/**
	 * Récup du cumul ET
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`EtSum` AS `EtSum`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `EtSum` IS NOT NULL
						ORDER BY `EtSum` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$EtSum = null;

				// Insert dans le tableau
				$tabEtSum [$ts] = array();

				if (!is_null($row['EtSum'])) {
					$EtSum = round($row['EtSum'], 2);
				}

				// Insert dans le tableau
				$tabEtSum [$ts] ['EtSum'] = $EtSum;
			}
		}
	}

	/**
	 * Récup des Hr Min
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`HrMin` AS `HrMin`,
								`HrMinDt` AS `HrMinDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `HrMin` IS NOT NULL
						ORDER BY `HrMin` ASC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$HrMin = null;
				$HrMinDt = null;

				// Insert dans le tableau
				$tabHrMin [$ts] = array();

				if (!is_null($row['HrMin'])) {
					$HrMin = round($row['HrMin']);
					if (!is_null($row['HrMinDt'])) {
						$HrMinDt = date('H\hi',strtotime($row['HrMinDt']));
					}
				}

				// Insert dans le tableau
				$tabHrMin [$ts] ['HrMin'] = $HrMin;
				$tabHrMin [$ts] ['HrMinDt'] = $HrMinDt;
			}
		}

	/**
	 * Récup des Hr Max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`HrMax` AS `HrMax`,
								`HrMaxDt` AS `HrMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `HrMax` IS NOT NULL
						ORDER BY `HrMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$HrMax = null;
				$HrMaxDt = null;

				// Insert dans le tableau
				$tabHrMax [$ts] = array();

				if (!is_null($row['HrMax'])) {
					$HrMax = round($row['HrMax']);
					if (!is_null($row['HrMaxDt'])) {
						$HrMaxDt = date('H\hi',strtotime($row['HrMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabHrMax [$ts] ['HrMax'] = $HrMax;
				$tabHrMax [$ts] ['HrMaxDt'] = $HrMaxDt;
			}
		}

	/**
	 * Récup des rafales max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`windGust` AS `windGust`,
								`windGustDt` AS `windGustDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `windGust` IS NOT NULL
						ORDER BY `windGust` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$windGust = null;
				$windGustDt = null;

				// Insert dans le tableau
				$tabWindGust [$ts] = array();

				if (!is_null($row['windGust'])) {
					$windGust = round($row['windGust'],1);
					if (!is_null($row['windGustDt'])) {
						$windGustDt = date('H\hi',strtotime($row['windGustDt']));
					}
				}

				// Insert dans le tableau
				$tabWindGust [$ts] ['windGust'] = $windGust;
				$tabWindGust [$ts] ['windGustDt'] = $windGustDt;
			}
		}


	/**
	 * Récup des pt de rosée Min
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`TdMin` AS `TdMin`,
								`TdMinDt` AS `TdMinDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `TdMin` IS NOT NULL
						ORDER BY `TdMin` ASC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$TdMin = null;
				$TdMinDt = null;

				// Insert dans le tableau
				$tabTdMin [$ts] = array();

				if (!is_null($row['TdMin'])) {
					$TdMin = round($row['TdMin'], 1);
					if (!is_null($row['TdMinDt'])) {
						$TdMinDt = date('H\hi',strtotime($row['TdMinDt']));
					}
				}

				// Insert dans le tableau
				$tabTdMin [$ts] ['TdMin'] = $TdMin;
				$tabTdMin [$ts] ['TdMinDt'] = $TdMinDt;
			}
		}

	/**
	 * Récup des pt de rosée Max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`TdMax` AS `TdMax`,
								`TdMaxDt` AS `TdMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `TdMax` IS NOT NULL
						ORDER BY `TdMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$TdMax = null;
				$TdMaxDt = null;

				// Insert dans le tableau
				$tabTdMax [$ts] = array();

				if (!is_null($row['TdMax'])) {
					$TdMax = round($row['TdMax'], 1);
					if (!is_null($row['TdMaxDt'])) {
						$TdMaxDt = date('H\hi',strtotime($row['TdMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabTdMax [$ts] ['TdMax'] = $TdMax;
				$tabTdMax [$ts] ['TdMaxDt'] = $TdMaxDt;
			}
		}

	if ($presence_radiation) {
	/**
	 * Récup du rayonnement solaire Max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`RadMax` AS `RadMax`,
								`RadMaxDt` AS `RadMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `RadMax` IS NOT NULL
						ORDER BY `RadMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$RadMax = null;
				$RadMaxDt = null;

				// Insert dans le tableau
				$tabRadMax [$ts] = array();

				if (!is_null($row['RadMax'])) {
					$RadMax = round($row['RadMax'], 1);
					if (!is_null($row['RadMaxDt'])) {
						$RadMaxDt = date('H\hi',strtotime($row['RadMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabRadMax [$ts] ['RadMax'] = $RadMax;
				$tabRadMax [$ts] ['RadMaxDt'] = $RadMaxDt;
			}
		}
	}

	/**
	 * Récup pression Min
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`PrMin` AS `PrMin`,
								`PrMinDt` AS `PrMinDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `PrMin` IS NOT NULL
						ORDER BY `PrMin` ASC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$PrMin = null;
				$PrMinDt = null;

				// Insert dans le tableau
				$tabPrMin [$ts] = array();

				if (!is_null($row['PrMin'])) {
					$PrMin = round($row['PrMin'], 1);
					if (!is_null($row['PrMinDt'])) {
						$PrMinDt = date('H\hi',strtotime($row['PrMinDt']));
					}
				}

				// Insert dans le tableau
				$tabPrMin [$ts] ['PrMin'] = $PrMin;
				$tabPrMin [$ts] ['PrMinDt'] = $PrMinDt;
			}
		}

	/**
	 * Récup pression Max
	 */
		$query_string = "SELECT `dateDay` AS `dateDay`,
								`PrMax` AS `PrMax`,
								`PrMaxDt` AS `PrMaxDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `PrMax` IS NOT NULL
						ORDER BY `PrMax` DESC, `dateDay` DESC
						LIMIT $limitRecords;";
		$result       = $db_handle_pdo->query($query_string);

		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$ts = strtotime($row['dateDay']);
				$PrMax = null;
				$PrMaxDt = null;

				// Insert dans le tableau
				$tabPrMax [$ts] = array();

				if (!is_null($row['PrMax'])) {
					$PrMax = round($row['PrMax'], 1);
					if (!is_null($row['PrMaxDt'])) {
						$PrMaxDt = date('H\hi',strtotime($row['PrMaxDt']));
					}
				}

				// Insert dans le tableau
				$tabPrMax [$ts] ['PrMax'] = $PrMax;
				$tabPrMax [$ts] ['PrMaxDt'] = $PrMaxDt;
			}
		}

		if ($presence_uv) {
		/**
		 * Récup indice UV Max
		 */
			$query_string = "SELECT `dateDay` AS `dateDay`,
									`UvMax` AS `UvMax`,
									`UvMaxDt` AS `UvMaxDt`
							FROM `$db_name_climato`.`$db_table_climato`
							WHERE `UvMax` IS NOT NULL
							ORDER BY `UvMax` DESC, `dateDay` DESC
							LIMIT $limitRecords;";
			$result       = $db_handle_pdo->query($query_string);
	
			if ($result) {
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$ts = strtotime($row['dateDay']);
					$UvMax = null;
					$UvMaxDt = null;
	
					// Insert dans le tableau
					$tabUvMax [$ts] = array();
	
					if (!is_null($row['UvMax'])) {
						$UvMax = round($row['UvMax'], 1);
						if (!is_null($row['UvMaxDt'])) {
							$UvMaxDt = date('H\hi',strtotime($row['UvMaxDt']));
						}
					}
	
					// Insert dans le tableau
					$tabUvMax [$ts] ['UvMax'] = $UvMax;
					$tabUvMax [$ts] ['UvMaxDt'] = $UvMaxDt;
				}
			}
		}