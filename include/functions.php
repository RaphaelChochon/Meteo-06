<?php
// Tableaux jours et mois en français
	$jourFrancais      = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");
	$jourFrancaisAbrev = array("dim", "lun", "mar", "mer", "jeu", "ven", "sam");
	$moisFrancais      = Array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
	$moisFrancaisAbrev = Array("", "janv", "févr", "mars", "avr", "mai", "juin", "juill", "août", "sept", "oct", "nov", "déc");

// FONCTION arondi des minutes
	/**
	 * Round down minutes to the nearest lower interval of a DateTime object.
	 * 
	 * @param \DateTime $dateTime
	 * @param int $minuteInterval
	 * @return \DateTime
	 */
	function roundDownToMinuteInterval(\DateTime $dateTime) {
		$minuteInterval = 10;
		return $dateTime->setTime(
			$dateTime->format('H'),
			floor($dateTime->format('i') / $minuteInterval) * $minuteInterval,
			0
		);
	}

// FONCTION moyenne d'angles angulaires
	function mean_of_angles( $angles, $degrees = true ) {
		if ( $degrees ) {
			$angles = array_map("deg2rad", $angles);  // Convert to radians
		}
		$s_  = 0;
		$c_  = 0;
		$len = count( $angles );
		for ($i = 0; $i < $len; $i++) {
			$s_ += sin( $angles[$i] );
			$c_ += cos( $angles[$i] );
		}
		// $s_ /= $len;
		// $c_ /= $len;
		$mean = atan2( $s_, $c_ );
		if ( $degrees ) {
			$mean = rad2deg( $mean );  // Convert to degrees
		}
		if ($mean < 0) {
			$mean_ok = $mean + 360;
		} else {
			$mean_ok = $mean;
		}
		return $mean_ok;
	}

// Position cardinale du vent (en texte plutôt qu'en degrés)
	function wind_cardinals($deg) {
		$cardinalDirections = array(
			'N'   => array(348.75, 361),
			'N2'   => array(0, 11.25),
			'NNE' => array(11.25, 33.75),
			'NE'  => array(33.75, 56.25),
			'ENE' => array(56.25, 78.75),
			'E'   => array(78.75, 101.25),
			'ESE' => array(101.25, 123.75),
			'SE'  => array(123.75, 146.25),
			'SSE' => array(146.25, 168.75),
			'S'   => array(168.75, 191.25),
			'SSW' => array(191.25, 213.75),
			'SW'  => array(213.75, 236.25),
			'WSW' => array(236.25, 258.75),
			'W'   => array(258.75, 281.25),
			'WNW' => array(281.25, 303.75),
			'NW'  => array(303.75, 326.25),
			'NNW' => array(326.25, 348.75)
		);
		foreach ($cardinalDirections as $dir => $angles) {
			if ($deg >= $angles[0] && $deg < $angles[1]) {
				$cardinal = str_replace("2", "", $dir);
			}
		}
		return $cardinal;
	};


	/**
	 * prepareExtractCSV
	 * Préparation à l'extraction en créant un tableau d'objet.
	 * 
	 * @param object $db_handle_pdo Connexion SQL PDO
	 * @param string $interval Intervalle souhaité (1hour, 10min, raw)
	 * @param int $tsStart Timestamp de départ INCLUS
	 * @param int $tsStop Timestamp de fin INCLUS
	 * 
	 * @return array $csvTab = [$ts] => [dtUTC]
	 */
	function prepareExtractCSV($db_handle_pdo, $interval, $tsStart, $tsStop) {
		global $db_table;
		if ($interval == 'raw') {$mod = 1; } elseif ($interval == '10min') {$mod = 600; } elseif ($interval == '1hour') {$mod = 3600; }
		$query_string = "SELECT CEIL(`dateTime`/$mod)*$mod AS `ts`
						FROM $db_table
						WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop
						GROUP BY `ts` ORDER BY `ts` ASC;";
		$result       = $db_handle_pdo->query($query_string);

		if (!$result) {
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$row['ts'] = (string)round($row['ts']);
				// Insertion dans le tableau des date time UTC en tant que key
				$csvTab [$row['ts']] = array();
				$csvTab [$row['ts']] ['dtUTC'] = date('Y-m-d H:i:s',$row['ts']);
			}
			return $csvTab;
		}
	}


	/**
	 * insertHeaderCsvTab
	 * Insertion du header dans $csvTab.
	 * 
	 * @param string $interval Intervalle souhaité (1hour, 10min, raw)
	 * @param array $paramsMeteo Tableau contenant les champs/parametre météo à récup
	 * @param array $csvTab Tableau d'objet contenant déjà des timestamp en key (généré par la fonction prepareExtractCSV)
	 * 
	 * @return array $csvTab = [header] => [value]
	 */
	function insertHeaderCsvTab($interval, $paramsMeteo, $csvTab) {
		global $presence_uv,$presence_radiation;
		// Interval == $mod
		if ($interval === 'raw') {
			$mod = 1;
		} elseif ($interval === '10min') {
			$mod = 600;
		} elseif ($interval === '1hour') {
			$mod = 3600;
		}

		// header
		$csvTab ['header'] ['dtUTC'] = null;
		if (in_array('outTemp',$paramsMeteo)) {
			$csvTab ['header'] ['outTemp'] = null;
		}
		if (in_array('outTemp',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['outTempMin'] = null;
			$csvTab ['header'] ['outTempMax'] = null;
		}

		if (in_array('outHumidity',$paramsMeteo)) {
			$csvTab ['header'] ['outHumidity'] = null;
		}
		if (in_array('outHumidity',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['outHumidityMin'] = null;
			$csvTab ['header'] ['outHumidityMax'] = null;
		}

		if (in_array('dewpoint',$paramsMeteo)) {
			$csvTab ['header'] ['dewpoint'] = null;
		}
		if (in_array('dewpoint',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['dewpointMin'] = null;
			$csvTab ['header'] ['dewpointMax'] = null;
		}

		if (in_array('barometer',$paramsMeteo)) {
			$csvTab ['header'] ['barometer'] = null;
		}
		if (in_array('barometer',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['barometerMin'] = null;
			$csvTab ['header'] ['barometerMax'] = null;
		}

		if (in_array('rain',$paramsMeteo) && $mod === 1) {
			$csvTab ['header'] ['rain'] = null;
		}
		if (in_array('rain',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['rainSum'] = null;
		}
		if (in_array('rainRate',$paramsMeteo)) {
			$csvTab ['header'] ['rainRate'] = null;
		}
		if (in_array('rainRate',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['rainRateMax'] = null;
		}

		if (in_array('windSpeed',$paramsMeteo)) {
			$csvTab ['header'] ['windSpeed'] = null;
			$csvTab ['header'] ['windDir'] = null;
		}
		if (in_array('windGust',$paramsMeteo)) {
			$csvTab ['header'] ['windGust'] = null;
			$csvTab ['header'] ['windGustDir'] = null;
		}
		if (in_array('windGust',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['windGustDt'] = null;
		}

		if ($presence_uv) {
			if (in_array('UV',$paramsMeteo)) {
				$csvTab ['header'] ['UV'] = null;
			}
			if (in_array('UV',$paramsMeteo) && $mod !== 1) {
				$csvTab ['header'] ['UvMax'] = null;
			}
		}
		if ($presence_radiation) {
			if (in_array('radiation',$paramsMeteo)) {
				$csvTab ['header'] ['radiation'] = null;
			}
			if (in_array('radiation',$paramsMeteo) && $mod !== 1) {
				$csvTab ['header'] ['radiationMax'] = null;
			}
			if (in_array('ET',$paramsMeteo)) {
				$csvTab ['header'] ['ET'] = null;
			}
		}
		if (in_array('inTemp',$paramsMeteo)) {
			$csvTab ['header'] ['inTemp'] = null;
		}
		if (in_array('inTemp',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['inTempMin'] = null;
			$csvTab ['header'] ['inTempMax'] = null;
		}
		if (in_array('inHumidity',$paramsMeteo)) {
			$csvTab ['header'] ['inHumidity'] = null;
		}
		if (in_array('inHumidity',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['inHumidityMin'] = null;
			$csvTab ['header'] ['inHumidityMax'] = null;
		}
		if (in_array('rxCheckPercent',$paramsMeteo)) {
			$csvTab ['header'] ['rxCheckPercent'] = null;
		}
		if (in_array('rxCheckPercent',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['rxCheckPercentMin'] = null;
		}
		if (in_array('consBatteryVoltage',$paramsMeteo)) {
			$csvTab ['header'] ['consBatteryVoltage'] = null;
		}
		if (in_array('consBatteryVoltage',$paramsMeteo) && $mod !== 1) {
			$csvTab ['header'] ['consBatteryVoltageMin'] = null;
		}

		return $csvTab;
	}



	/**
	 * extractParamsInst
	 * Extraction des paramètres météo instantanés tels que la température, l'humidité, etc.
	 * 
	 * @param object $db_handle_pdo Connexion SQL PDO
	 * @param string $interval Intervalle souhaité (1hour, 10min, raw)
	 * @param int $tsStart Timestamp de départ INCLUS
	 * @param int $tsStop Timestamp de fin INCLUS
	 * @param array $paramsMeteo Tableau contenant les champs/parametre météo à récup
	 * @param array $csvTab Tableau d'objet contenant déjà des timestamp en key (généré par la fonction prepareExtractCSV)
	 * 
	 * @return array $csvTab = [$row['ts']] ['outTemp'] = $outTemp ...
	 */
	function extractParamsInst($db_handle_pdo, $interval, $tsStart, $tsStop, $paramsMeteo, $csvTab) {
		global $db_table,$presence_radiation,$presence_uv;

		// Interval == $mod
		if ($interval === 'raw') {
			$mod = 1;
		} elseif ($interval === '10min') {
			$mod = 600;
		} elseif ($interval === '1hour') {
			$mod = 3600;
		}

		// Préparation de la requête
		$query_string = "SELECT `dateTime` AS `ts`";
		if (in_array('outTemp',$paramsMeteo)) {
			$query_string .= ", `outTemp` AS `outTemp`";
		}
		if (in_array('outHumidity',$paramsMeteo)) {
			$query_string .= ", `outHumidity` AS `outHumidity`";
		}
		if (in_array('dewpoint',$paramsMeteo)) {
			$query_string .= ", `dewpoint` AS `dewpoint`";
		}
		if (in_array('barometer',$paramsMeteo)) {
			$query_string .= ", `barometer` AS `barometer`";
		}
		if (in_array('rain',$paramsMeteo) && $mod === 1) {
			$query_string .= ", `rain` AS `rain`";
		}
		if (in_array('rainRate',$paramsMeteo)) {
			$query_string .= ", `rainRate` AS `rainRate`";
		}
		if ($presence_uv) {
			if (in_array('UV',$paramsMeteo)) {
				$query_string .= ", `UV` AS `UV`";
			}
		}
		if ($presence_radiation) {
			if (in_array('radiation',$paramsMeteo)) {
				$query_string .= ", `radiation` AS `radiation`";
			}
			if (in_array('ET',$paramsMeteo)) {
				$query_string .= ", `ET` AS `ET`";
			}
		}

		if (in_array('inTemp',$paramsMeteo)) {
			$query_string .= ", `inTemp` AS `inTemp`";
		}
		if (in_array('inHumidity',$paramsMeteo)) {
			$query_string .= ", `inHumidity` AS `inHumidity`";
		}
		if (in_array('rxCheckPercent',$paramsMeteo)) {
			$query_string .= ", `rxCheckPercent` AS `rxCheckPercent`";
		}
		if (in_array('consBatteryVoltage',$paramsMeteo)) {
			$query_string .= ", `consBatteryVoltage` AS `consBatteryVoltage`";
		}

		if (in_array('windSpeed',$paramsMeteo) && $mod === 1) {
			$query_string .= ", `windSpeed` AS `windSpeed`";
			$query_string .= ", `windDir` AS `windDir`";
		}
		if (in_array('windGust',$paramsMeteo) && $mod === 1) {
			$query_string .= ", `windGust` AS `windGust`";
			$query_string .= ", `windGustDir` AS `windGustDir`";
		}

		$query_string .=" FROM $db_table
						WHERE `dateTime` % $mod = 0
						AND `dateTime` >= '$tsStart'
						AND `dateTime` <= '$tsStop';";
		$result       = $db_handle_pdo->query($query_string);
		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$row['ts'] = (string)round($row['ts']);

				// Traitement des données
				if (in_array('outTemp',$paramsMeteo)) {
					$outTemp = null;
					if (!is_null ($row['outTemp'])) {
						$outTemp = round($row['outTemp'],1);
					}
					$csvTab [$row['ts']] ['outTemp'] = $outTemp;
				}
				if (in_array('outHumidity',$paramsMeteo)) {
					$outHumidity = null;
					if (!is_null ($row['outHumidity'])) {
						$outHumidity = round($row['outHumidity']);
					}
					$csvTab [$row['ts']] ['outHumidity'] = $outHumidity;
				}
				if (in_array('dewpoint',$paramsMeteo)) {
					$dewpoint = null;
					if (!is_null ($row['dewpoint'])) {
						$dewpoint = round($row['dewpoint'],1);
					}
					$csvTab [$row['ts']] ['dewpoint'] = $dewpoint;
				}
				if (in_array('barometer',$paramsMeteo)) {
					$barometer = null;
					if (!is_null ($row['barometer'])) {
						$barometer = round($row['barometer'],1);
					}
					$csvTab [$row['ts']] ['barometer'] = $barometer;
				}
				if (in_array('rain',$paramsMeteo) && $mod === 1) {
					$rain = null;
					if (!is_null ($row['rain'])) {
						$rain = round($row['rain']*10,1);
					}
					$csvTab [$row['ts']] ['rain'] = $rain;
				}
				if (in_array('rainRate',$paramsMeteo)) {
					$rainRate = null;
					if (!is_null ($row['rainRate'])) {
						$rainRate = round($row['rainRate']*10,1);
					}
					$csvTab [$row['ts']] ['rainRate'] = $rainRate;
				}
				if ($presence_uv) {
					if (in_array('UV',$paramsMeteo)) {
						$UV = null;
						if (!is_null ($row['UV'])) {
							$UV = round($row['UV'],1);
						}
						$csvTab [$row['ts']] ['UV'] = $UV;
					}
				}
				if ($presence_radiation) {
					if (in_array('radiation',$paramsMeteo)) {
						$radiation = null;
						if (!is_null ($row['radiation'])) {
							$radiation = round($row['radiation'],0);
						}
						$csvTab [$row['ts']] ['radiation'] = $radiation;
					}
					if (in_array('ET',$paramsMeteo)) {
						$ET = null;
						if (!is_null ($row['ET'])) {
							$ET = round($row['ET'],2);
						}
						$csvTab [$row['ts']] ['ET'] = $ET;
					}
				}

				if (in_array('inTemp',$paramsMeteo)) {
					$inTemp = null;
					if (!is_null ($row['inTemp'])) {
						$inTemp = round($row['inTemp'],1);
					}
					$csvTab [$row['ts']] ['inTemp'] = $inTemp;
				}
				if (in_array('inHumidity',$paramsMeteo)) {
					$inHumidity = null;
					if (!is_null ($row['inHumidity'])) {
						$inHumidity = round($row['inHumidity'],1);
					}
					$csvTab [$row['ts']] ['inHumidity'] = $inHumidity;
				}
				if (in_array('rxCheckPercent',$paramsMeteo)) {
					$rxCheckPercent = null;
					if (!is_null ($row['rxCheckPercent'])) {
						$rxCheckPercent = round($row['rxCheckPercent'],2);
					}
					$csvTab [$row['ts']] ['rxCheckPercent'] = $rxCheckPercent;
				}
				if (in_array('consBatteryVoltage',$paramsMeteo)) {
					$consBatteryVoltage = null;
					if (!is_null ($row['consBatteryVoltage'])) {
						$consBatteryVoltage = round($row['consBatteryVoltage'],2);
					}
					$csvTab [$row['ts']] ['consBatteryVoltage'] = $consBatteryVoltage;
				}

				if (in_array('windSpeed',$paramsMeteo) && $mod === 1) {
					$windSpeed = null;
					if (!is_null ($row['windSpeed'])) {
						$windSpeed = round($row['windSpeed'],1);
					}
					$csvTab [$row['ts']] ['windSpeed'] = $windSpeed;

					$windDir = null;
					if (!is_null ($row['windDir'])) {
						$windDir = round($row['windDir'],1);
					}
					$csvTab [$row['ts']] ['windDir'] = $windDir;
				}
				if (in_array('windGust',$paramsMeteo) && $mod === 1) {
					$windGust = null;
					if (!is_null ($row['windGust'])) {
						$windGust = round($row['windGust'],1);
					}
					$csvTab [$row['ts']] ['windGust'] = $windGust;

					$windGustDir = null;
					if (!is_null ($row['windGustDir'])) {
						$windGustDir = round($row['windGustDir'],1);
					}
					$csvTab [$row['ts']] ['windGustDir'] = $windGustDir;
				}
			}
			return $csvTab;
		}
	}


	/**
	 * extractParamsWind
	 * Extraction des paramètres météo VENT.
	 * 
	 * @param object $db_handle_pdo Connexion SQL PDO
	 * @param string $interval Intervalle souhaité (1hour, 10min, raw)
	 * @param int $tsStart Timestamp de départ INCLUS
	 * @param int $tsStop Timestamp de fin INCLUS
	 * @param array $paramsMeteo Tableau contenant les champs/parametre météo à récup
	 * @param array $csvTab Tableau d'objet contenant déjà des timestamp en key (généré par la fonction prepareExtractCSV)
	 * 
	 * @return array $csvTab = [$row['ts']] ['windGust'] = $windGust ...
	 */
	function extractParamsWind($db_handle_pdo, $interval, $tsStart, $tsStop, $paramsMeteo, $csvTab) {
		global $db_table;

		// Interval == $mod
		if ($interval === 'raw') {
			exit;
		} elseif ($interval === '10min') {
			$mod = 600;
		} elseif ($interval === '1hour') {
			$mod = 3600;
		}

		// Préparation de la requête pour les rafales
		if (in_array('windGust',$paramsMeteo)) {
			$query_string = "SELECT CEIL(`dateTime`/$mod)*$mod AS `ts`, a.`dateTime`, a.`windGust`, a.`windGustDir`
					FROM $db_table a
					INNER JOIN (
						SELECT CEIL(`dateTime`/$mod)*$mod AS `dtUTC2`, MAX(`windGust`) AS `windGustMax`
						FROM $db_table
						WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop
						GROUP BY `dtUTC2`
					) b
					ON CEIL(a.`dateTime`/$mod)*$mod = b.`dtUTC2` AND b.`windGustMax` = a.`windGust`
					WHERE `dateTime` >= $tsStart AND `dateTime` <= $tsStop
					ORDER BY `a`.`dateTime` ASC;";
			$result       = $db_handle_pdo->query($query_string);
			if (!$result) {
				// Erreur
				echo "Erreur dans la requete ".$query_string."\n";
				echo "\nPDO::errorInfo():\n";
				print_r($db_handle_pdo->errorInfo());
			}
			if ($result) {
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$row['ts'] = (string)round($row['ts']);

					$windGust    = null;
					$windGustDir = null;
					$windGustDt  = null;

					// Traitement des données
					if (!is_null ($row['windGust'])) {
						$windGust = round($row['windGust'],1);
						if (!is_null ($row['windGustDir'])) {
							$windGustDir = round($row['windGustDir'],1);
							$windGustDt = date('Y-m-d H:i:s',$row['dateTime']);
						}
					}
					// Insertion dans le tableau des données
					// Sauf que notre résultat comprend des doublons (plusieurs rafales max identique dans la même heure), donc on n'insert que si la valeur n'a pas déjà été enregistrée pour cette même KEY (ts) == On garde donc seulement la première rafale max
					if (!isset($csvTab [$row['ts']] ['windGust'])) {
						$csvTab [$row['ts']] ['windGust'] = $windGust;
						$csvTab [$row['ts']] ['windGustDir'] = $windGustDir;
						$csvTab [$row['ts']] ['windGustDt'] = $windGustDt;
					}
				}
			}
		} // fin du windGust
		if (in_array('windSpeed',$paramsMeteo)) {
			// Vitesse
			$query_string = "SELECT CEIL(`dateTime`/$mod)*$mod AS `ts`,
									AVG(`windSpeed`) AS `windSpeed`
							FROM $db_table
							WHERE `dateTime` >= $tsStart
							AND `dateTime` <= $tsStop
							GROUP BY `ts` ORDER BY `ts` ASC;";
			$result       = $db_handle_pdo->query($query_string);

			// Result
			if (!$result) {
				// Erreur
				echo "Erreur dans la requete ".$query_string."\n";
				echo "\nPDO::errorInfo():\n";
				print_r($db_handle_pdo->errorInfo());
			}
			if ($result) {
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$row['ts'] = (string)round($row['ts']);
					$windSpeed = null;
					if (!is_null ($row['windSpeed'])) {
						$windSpeed = round($row['windSpeed'],1);
					}
					$csvTab [$row['ts']] ['windSpeed'] = $windSpeed;
				}
			}
			// Direction moyenne
			$query_string = "SELECT CEIL(`dateTime`/$mod)*$mod AS `ts`,
									GROUP_CONCAT(`windDir`) AS `windDirConcat`
							FROM $db_table
							WHERE `dateTime` >= $tsStart
							AND `dateTime` <= $tsStop
							GROUP BY `ts` ORDER BY `ts` ASC;";
			$result       = $db_handle_pdo->query($query_string);
			if (!$result) {
				echo "Erreur dans la requete ".$query_string."\n";
				echo "\nPDO::errorInfo():\n";
				print_r($db_handle_pdo->errorInfo());
			}
			if ($result) {
				// Construction du tableau
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$windDirArray = null;
					$windDirAvg10minTemp = null;
					$row['ts'] = (string)round($row['ts']);
					if (!is_null($row['windDirConcat'])) {
						$windDirArray[] = explode(',', $row['windDirConcat']);
					}
					// Calcul de la moyenne avec la fonction `mean_of_angles` et le tableau
					if (!is_null ($windDirArray)) {
						$windDirAvg10minTemp = mean_of_angles($windDirArray['0']);
					}
					// Vérif not null
					$windDir = null;
					if (!is_null ($windDirAvg10minTemp)) {
						$windDir = round($windDirAvg10minTemp,1);
					}
					// Insertion dans le tableau CSV
					$csvTab [$row['ts']] ['windDir'] = $windDir;
				}
			}
		} // fin du windSpeed
		return $csvTab;
	}


	/**
	 * extractParamsExtreme
	 * Extraction des paramètres météo extrêmes et cumul comme les Tn et Tx sur l'intervalle ou le cumul de pluie sur l'intervalle, etc.
	 * 
	 * @param object $db_handle_pdo Connexion SQL PDO
	 * @param string $interval Intervalle souhaité (1hour, 10min, raw)
	 * @param int $tsStart Timestamp de départ INCLUS
	 * @param int $tsStop Timestamp de fin INCLUS
	 * @param array $paramsMeteo Tableau contenant les champs/parametre météo à récup
	 * @param array $csvTab Tableau d'objet contenant déjà des timestamp en key (généré par la fonction prepareExtractCSV)
	 * 
	 * @return array $csvTab = [$row['ts']] ['outTempMin'] = $outTempMin ...
	 */
	function extractParamsExtreme($db_handle_pdo, $interval, $tsStart, $tsStop, $paramsMeteo, $csvTab) {
		global $db_table, $presence_radiation, $presence_uv;

		// Interval == $mod
		if ($interval === 'raw') {
			exit;
		} elseif ($interval === '10min') {
			$mod = 600;
		} elseif ($interval === '1hour') {
			$mod = 3600;
		}

		// Préparation de la requête
		$query_string = "SELECT CEIL(`dateTime`/$mod)*$mod AS `ts`";
		if (in_array('outTemp',$paramsMeteo)) {
			$query_string .= ", MIN(`outTemp`) AS `outTempMin`";
			$query_string .= ", MAX(`outTemp`) AS `outTempMax`";
		}
		if (in_array('outHumidity',$paramsMeteo)) {
			$query_string .= ", MIN(`outHumidity`) AS `outHumidityMin`";
			$query_string .= ", MAX(`outHumidity`) AS `outHumidityMax`";
		}
		if (in_array('dewpoint',$paramsMeteo)) {
			$query_string .= ", MIN(`dewpoint`) AS `dewpointMin`";
			$query_string .= ", MAX(`dewpoint`) AS `dewpointMax`";
		}
		if (in_array('barometer',$paramsMeteo)) {
			$query_string .= ", MIN(`barometer`) AS `barometerMin`";
			$query_string .= ", MAX(`barometer`) AS `barometerMax`";
		}
		if (in_array('rain',$paramsMeteo)) {
			$query_string .= ", SUM(`rain`) AS `rainSum`";
		}
		if (in_array('rainRate',$paramsMeteo)) {
			$query_string .= ", MAX(`rainRate`) AS `rainRateMax`";
		}
		if ($presence_uv) {
			if (in_array('UV',$paramsMeteo)) {
				$query_string .= ", MAX(`UV`) AS `UvMax`";
			}
		}
		if ($presence_radiation) {
			if (in_array('radiation',$paramsMeteo)) {
				$query_string .= ", MAX(`radiation`) AS `radiationMax`";
			}
		}

		if (in_array('inTemp',$paramsMeteo)) {
			$query_string .= ", MIN(`inTemp`) AS `inTempMin`";
			$query_string .= ", MAX(`inTemp`) AS `inTempMax`";
		}
		if (in_array('inHumidity',$paramsMeteo)) {
			$query_string .= ", MIN(`inHumidity`) AS `inHumidityMin`";
			$query_string .= ", MAX(`inHumidity`) AS `inHumidityMax`";
		}
		if (in_array('rxCheckPercent',$paramsMeteo)) {
			$query_string .= ", MIN(`rxCheckPercent`) AS `rxCheckPercentMin`";
		}
		if (in_array('consBatteryVoltage',$paramsMeteo)) {
			$query_string .= ", MIN(`consBatteryVoltage`) AS `consBatteryVoltageMin`";
		}

		$query_string .=" FROM $db_table
						WHERE `dateTime` >= '$tsStart'
						AND `dateTime` <= '$tsStop'
						GROUP BY `ts` ORDER BY `ts` ASC;";
		$result       = $db_handle_pdo->query($query_string);
		if (!$result) {
			// Erreur
			echo "Erreur dans la requete ".$query_string."\n";
			echo "\nPDO::errorInfo():\n";
			print_r($db_handle_pdo->errorInfo());
		}
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$row['ts'] = (string)round($row['ts']);

				// Traitement des données
				if (in_array('outTemp',$paramsMeteo)) {
					$outTempMin = null;
					if (!is_null ($row['outTempMin'])) {
						$outTempMin = round($row['outTempMin'],1);
					}
					$csvTab [$row['ts']] ['outTempMin'] = $outTempMin;

					$outTempMax = null;
					if (!is_null ($row['outTempMax'])) {
						$outTempMax = round($row['outTempMax'],1);
					}
					$csvTab [$row['ts']] ['outTempMax'] = $outTempMax;
				}
				if (in_array('outHumidity',$paramsMeteo)) {
					$outHumidityMin = null;
					if (!is_null ($row['outHumidityMin'])) {
						$outHumidityMin = round($row['outHumidityMin'],1);
					}
					$csvTab [$row['ts']] ['outHumidityMin'] = $outHumidityMin;

					$outHumidityMax = null;
					if (!is_null ($row['outHumidityMax'])) {
						$outHumidityMax = round($row['outHumidityMax'],1);
					}
					$csvTab [$row['ts']] ['outHumidityMax'] = $outHumidityMax;
				}
				if (in_array('dewpoint',$paramsMeteo)) {
					$dewpointMin = null;
					if (!is_null ($row['dewpointMin'])) {
						$dewpointMin = round($row['dewpointMin'],1);
					}
					$csvTab [$row['ts']] ['dewpointMin'] = $dewpointMin;

					$dewpointMax = null;
					if (!is_null ($row['dewpointMax'])) {
						$dewpointMax = round($row['dewpointMax'],1);
					}
					$csvTab [$row['ts']] ['dewpointMax'] = $dewpointMax;
				}
				if (in_array('barometer',$paramsMeteo)) {
					$barometerMin = null;
					if (!is_null ($row['barometerMin'])) {
						$barometerMin = round($row['barometerMin'],1);
					}
					$csvTab [$row['ts']] ['barometerMin'] = $barometerMin;

					$barometerMax = null;
					if (!is_null ($row['barometerMax'])) {
						$barometerMax = round($row['barometerMax'],1);
					}
					$csvTab [$row['ts']] ['barometerMax'] = $barometerMax;
				}
				if (in_array('rain',$paramsMeteo)) {
					$rainSum = null;
					if (!is_null ($row['rainSum'])) {
						$rainSum = round($row['rainSum']*10,1);
					}
					$csvTab [$row['ts']] ['rainSum'] = $rainSum;
				}
				if (in_array('rainRate',$paramsMeteo)) {
					$rainRateMax = null;
					if (!is_null ($row['rainRateMax'])) {
						$rainRateMax = round($row['rainRateMax']*10,1);
					}
					$csvTab [$row['ts']] ['rainRateMax'] = $rainRateMax;
				}
				if ($presence_uv) {
					if (in_array('UV',$paramsMeteo)) {
						$UvMax = null;
						if (!is_null ($row['UvMax'])) {
							$UvMax = round($row['UvMax'],1);
						}
						$csvTab [$row['ts']] ['UvMax'] = $UvMax;
					}
				}
				if ($presence_radiation) {
					if (in_array('radiation',$paramsMeteo)) {
						$radiationMax = null;
						if (!is_null ($row['radiationMax'])) {
							$radiationMax = round($row['radiationMax'],0);
						}
						$csvTab [$row['ts']] ['radiationMax'] = $radiationMax;
					}
				}
				if (in_array('inTemp',$paramsMeteo)) {
					$inTempMin = null;
					if (!is_null ($row['inTempMin'])) {
						$inTempMin = round($row['inTempMin'],1);
					}
					$csvTab [$row['ts']] ['inTempMin'] = $inTempMin;

					$inTempMax = null;
					if (!is_null ($row['inTempMax'])) {
						$inTempMax = round($row['inTempMax'],1);
					}
					$csvTab [$row['ts']] ['inTempMax'] = $inTempMax;
				}
				if (in_array('inHumidity',$paramsMeteo)) {
					$inHumidityMin = null;
					if (!is_null ($row['inHumidityMin'])) {
						$inHumidityMin = round($row['inHumidityMin'],1);
					}
					$csvTab [$row['ts']] ['inHumidityMin'] = $inHumidityMin;

					$inHumidityMax = null;
					if (!is_null ($row['inHumidityMax'])) {
						$inHumidityMax = round($row['inHumidityMax'],1);
					}
					$csvTab [$row['ts']] ['inHumidityMax'] = $inHumidityMax;
				}
				if (in_array('rxCheckPercent',$paramsMeteo)) {
					$rxCheckPercentMin = null;
					if (!is_null ($row['rxCheckPercentMin'])) {
						$rxCheckPercentMin = round($row['rxCheckPercentMin'],2);
					}
					$csvTab [$row['ts']] ['rxCheckPercentMin'] = $rxCheckPercentMin;
				}
				if (in_array('consBatteryVoltage',$paramsMeteo)) {
					$consBatteryVoltageMin = null;
					if (!is_null ($row['consBatteryVoltageMin'])) {
						$consBatteryVoltageMin = round($row['consBatteryVoltageMin'],2);
					}
					$csvTab [$row['ts']] ['consBatteryVoltageMin'] = $consBatteryVoltageMin;
				}
			}
		}
		return $csvTab;
	}
?>