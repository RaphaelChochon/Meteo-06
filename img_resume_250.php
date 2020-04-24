<?php
	require_once __DIR__ . '/config/config.php';
	require_once __DIR__ . '/sql/connect_pdo.php';
	require_once __DIR__ . '/sql/import.php';
	require_once __DIR__ . '/sql/req_last_records.php';

	header ("Content-type: image/png");
	//$image = imagecreate(200,200);
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
	$string3 = utf8_decode("Température : $temp °C");
	$string4 = utf8_decode("Pluie auj. : $cumul mm");
	$string5 = utf8_decode("Intensité max. : $maxrainRate mm/h a $maxrainRatetime");
	$string6 = utf8_decode("Vent : $wind km/h");
	$string7 = utf8_decode("Vent max. : $maxwind km/h ($maxwinddir °) a $maxwindtime");
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