<?php
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
?>