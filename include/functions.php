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
?>