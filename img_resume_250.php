<?php
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/include/functions.php';

	// Init
	$temp            = 'nd.';
	$Tn = 'nd.';
	$Tx = 'nd.';
	$wind            = 'nd.';
	$RR           = 'nd.';
	$RRhier           = 'nd.';
	$RRateMax     = 'nd.';
	$RRateMaxDt = 'nd.';
	$windGust         = 'nd.';
	$windGustDirCardinal      = 'nd.';
	$windGustDt     = 'nd.';

	// Récup de la tempé et du vent instantanné
	$query_string = "SELECT * FROM $db_table ORDER BY `dateTime` DESC LIMIT 1;";
	$result       = $db_handle_pdo->query($query_string);
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);

		if ( !is_null($row['outTemp']) ) {
			$temp = round($row['outTemp'],1);
		}
		if ( !is_null($row['windSpeed']) ) {
			$wind = round($row['windSpeed'],1);
		}
	}

	// Récup de params climato (cumul RR, intensité RR, rafale)
	$dateDay = date('Y-m-d'); // date du jour en cours
	$query_string = "SELECT `Tn`, `Tx`, `RR`, `RRateMax`, `RRateMaxDt`, `windGust`, `windGustDir`, `windGustDt`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` = '$dateDay';";
	$result       = $db_handle_pdo->query($query_string);
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);

		// Tn & Tx
		if ( !is_null($row['Tn']) ) {
			$Tn = round($row['Tn'],1);
		}
		if ( !is_null($row['Tx']) ) {
			$Tx = round($row['Tx'],1);
		}

		// RR
		if ( !is_null($row['RR']) ) {
			$RR = round($row['RR']*10,1);
		}

		// RRate
		if ( !is_null($row['RRateMax']) ) {
			$RRateMax = round($row['RRateMax']*10,1);
		} else {
			$RRateMax = '--';
		}
		if ( !is_null($row['RRateMaxDt']) ) {
				date_default_timezone_set("Europe/Paris");
					$RRateMaxDtTemp = $row['windGustDt'];
					$RRateMaxDt = date('H\hi', strtotime("${RRateMaxDtTemp}Z"));
				date_default_timezone_set("UTC");
		} else {
			$RRateMaxDt = '--';
		}

		// wind
		if ( !is_null($row['windGust']) ) {
			$windGust = round($row['windGust'],1);
				date_default_timezone_set("Europe/Paris");
					$windGustDtTemp = $row['windGustDt'];
					$windGustDt = date('H\hi', strtotime("${windGustDtTemp}Z"));
				date_default_timezone_set("UTC");
			if (!is_null($row['windGustDir'])) {
				$windGustDir = round($row['windGustDir'], 1);
				$windGustDirCardinal = wind_cardinals($windGustDir);
			}
		}
	}

	// Récup pluie hier
	$dateYesterday = date('Y-m-d', strtotime($dateDay.'-1 day'));
	$query_string = "SELECT `RR`
						FROM `$db_name_climato`.`$db_table_climato`
						WHERE `dateDay` = '$dateYesterday';";
	$result       = $db_handle_pdo->query($query_string);
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);

		// RR
		if ( !is_null($row['RR']) ) {
			$RRhier = round($row['RR']*10,1);
		}
	}

	header ("Content-type: image/png");
	//$image = imagecreate(250,175);
	$image = imagecreatefrompng("img/blank.png");

	$orange = imagecolorallocate($image, 255, 128, 0);
	$bleu = imagecolorallocate($image, 0, 0, 255);
	$bleuclair = imagecolorallocate($image, 156, 227, 254);
	$noir = imagecolorallocate($image, 0, 0, 0);
	$blanc = imagecolorallocate($image, 255, 255, 255);
	$green = imagecolorallocate($image, 93, 156, 49);

	$string1 = utf8_decode("Station $short_station_name");
	$string2 = utf8_decode("Le $date a $heure");
	$stringTiret = utf8_decode("---------------");
	$string3 = utf8_decode("Tempé: ".$temp." °C | Min/Max: ".$Tn."/".$Tx);
	$string4 = utf8_decode("Pluie 6h-6h: $RR mm | Hier : $RRhier mm");
	$string5 = utf8_decode("Int. max: $RRateMax mm/h a $RRateMaxDt");
	$string6 = utf8_decode("Vent: $wind km/h");
	$string7 = utf8_decode("Vent max: $windGust km/h (dir. $windGustDirCardinal) a $windGustDt");
	$string8 = utf8_decode("Association");
	$string9 = utf8_decode("Nice Météo 06");

	$font1  = 3;
	$width1 = imagefontwidth($font1) * strlen($string1);
	$height1 = imagefontheight($font1);

	$font2  = 3;
	$width2 = imagefontwidth($font2) * strlen($string2);
	$height2 = imagefontheight($font2);

	$fontTiret  = 2;
	$widthTiret = imagefontwidth($fontTiret) * strlen($stringTiret);
	$heightTiret = imagefontheight($fontTiret);

	$font3  = 2;
	$width3 = imagefontwidth($font3) * strlen($string3);
	$height3 = imagefontheight($font3);

	$font4  = 2;
	$width4 = imagefontwidth($font4) * strlen($string4);
	$height4 = imagefontheight($font4);

	$font5  = 2;
	$width5 = imagefontwidth($font5) * strlen($string5);
	$height5 = imagefontheight($font5);

	$font6  = 2;
	$width6 = imagefontwidth($font6) * strlen($string6);
	$height6 = imagefontheight($font6);

	$font7  = 2;
	$width7 = imagefontwidth($font7) * strlen($string7);
	$height7 = imagefontheight($font7);

	$font8  = 3;
	$width8 = imagefontwidth($font8) * strlen($string8);
	$height8 = imagefontheight($font8);

	$font9  = 3;
	$width9 = imagefontwidth($font9) * strlen($string9);
	$height9 = imagefontheight($font9);

	$img_w = 250;
	$img_h = 175;

	$img_w2 = 165;

	if ($diff>$offline_time)
		$test_offline = $orange;
	elseif ($diff<$offline_time)
		$test_offline = $green;

	ImageRectangle ($image, 4, 4, 246, 171, $noir);
	imagestring($image, $font1, ($img_w/2)-($width1/2), 10, $string1, $noir);
	imagestring($image, $font2, ($img_w/2)-($width2/2), 25, $string2, $test_offline);
	imagestring($image, $fontTiret, ($img_w/2)-($widthTiret/2), 35, $stringTiret, $noir);
	imagestring($image, $font3, ($img_w/2)-($width3/2), 50, $string3, $noir);
	imagestring($image, $font4, ($img_w/2)-($width4/2), 65, $string4, $noir);
	imagestring($image, $font5, ($img_w/2)-($width5/2), 80, $string5, $noir);
	imagestring($image, $font6, ($img_w/2)-($width6/2), 95, $string6, $noir);
	imagestring($image, $font7, ($img_w/2)-($width7/2), 110, $string7, $noir);
	imagestring($image, $font8, ($img_w2/2)-($width8/2), 135, $string8, $noir);
	imagestring($image, $font9, ($img_w2/2)-($width9/2), 150, $string9, $noir);

	imagepng($image);
?>