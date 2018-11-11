<?php

/*-----------------------------------------------------------
Creation de fichiers json pour highchart à partir de la DB MySQL
de Weewx - Xavier JULIE <xav@xj1.fr>
https://fortmahon.webcam/meteo.php
This program is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License
It is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY;
----
Modification and addition by Raphaël CHOCHON for
association Nice Météo 06
http://meteo06.fr
Création de fichiers json pour différents paramètres, à partir
de la BDD MySQL de Weewx. Ces fichiers JSON contiennent les
enregistrements des dernières 48 heures.
-------------------------------------------------------------
*/

// Chemin d'export des fichiers JSON
$path = "../json/";

// appel du script de config et connexion à la BDD
require_once('../config/config.php');
	$db=$db_name.".".$db_table;
	$conn = mysqli_connect($server,$user,$pass,$db_name);

// On récupère le timestamp du dernier enregistrement
$sql = "SELECT max(dateTime) FROM $db";
$query = $conn->query($sql);
$list = mysqli_fetch_array($query);

// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
$stop=$list[0];
$start48=$stop-(86400*2);


//time
$json_time = "[";
$sql = "SELECT datetime FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
	$when=$row[0];
		if(is_int($i)) {
			$timestamp=$when*1000;
			$json_time.= "$timestamp,";
		}
		$i++;
}
$json_time.="]";

//temperature
$json_temp = "[";
$sql = "SELECT datetime, IFNULL(outTemp,'null') AS outTemp FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$temp = $row[1];
		if(is_int($i)) {
			$json_temp.= "$temp,";
		}
		$i++;
}
$json_temp.="]";

//temp rose
$json_rose = "[";
$sql = "SELECT datetime, IFNULL(dewpoint,'null') AS dewpoint FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$rose = $row[1];
		if(is_int($i)) {
			$json_rose.= "$rose,";
		}
		$i++;
}
$json_rose.="]";

//barometre
$json_baro = "[";
$sql = "SELECT datetime, IFNULL(barometer,'null') AS barometer FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$barometer= $row[1];
		if (is_int($i)) {
			$json_baro.= "$barometer,";
		}
		$i++;
}
$json_baro.="]";

//hygro
$json_hygro = "[";
$sql = "SELECT datetime, IFNULL(outHumidity,'null') AS outHumidity FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$hygro= $row[1];
		if (is_int($i)) {
			$json_hygro.= "$hygro,";
		}
		$i++;
}
$json_hygro.="]";

//vent
$json_vent = "[";
$sql = "SELECT datetime, IFNULL(windSpeed,'null') AS windSpeed FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$vent=$row[1];
		if(is_int($i)) {
			$json_vent.= "$vent,";
		}
		$i++;
}
$json_vent.="]";

$json_rafales = "[";
$sql = "SELECT datetime, IFNULL(windGust,'null') AS windGust FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$vent=$row[1];
		if(is_int($i)) {
			$json_rafales.= "$vent,";
		}
		$i++;
}
$json_rafales.="]";

//precipitations
$json_precip = "[";
$sql = "SELECT datetime, IFNULL(rain,'null') AS rain FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$rain = $row[1];
		if ($rain == 'null') {
			$rain2 = 'null';
		}else{
			$rain2 = $rain * 10;
		}
		if(is_int($i)) {
			$json_precip.= "$rain2,";
		}
		$i++;
}
$json_precip.="]";

if ($presence_uv === "true"){
	//UV
	$json_uv = "[";
	$sql = "SELECT datetime, IFNULL(UV,'null') AS UV FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
	$res = $conn->query($sql);
	$i=0;
	while($row=mysqli_fetch_row($res)) {
			$uv = $row[1];
			if(is_int($i)) {
				$json_uv.= "$uv,";
			}
			$i++;
	}
	$json_uv.="]";
};

if ($presence_radiation === "true"){
	//RADIATION
	$json_radiation = "[";
	$sql = "SELECT datetime, IFNULL(radiation,'null') AS radiation FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
	$res = $conn->query($sql);
	$i=0;
	while($row=mysqli_fetch_row($res)) {
			$rad = $row[1];
			if(is_int($i)) {
				$json_radiation.= "$rad,";
			}
			$i++;
	}
	$json_radiation.="]";

	//ET
	$json_ET = "[";
	$sql = "SELECT datetime, IFNULL(ET,'null') AS ET FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
	$res = $conn->query($sql);
	$i=0;
	while($row=mysqli_fetch_row($res)) {
			$et = $row[1];
			if ($et == 'null') {
				$et2 = 'null';
			}else{
				$et2 = $et * 10;
			}
			if(is_int($i)) {
				$json_ET.= "$et2,";
			}
			$i++;
	}
	$json_ET.="]";
};

if ($presence_iss_radio === "true"){
	//rxCheck
	$json_rxCheckPercent = "[";
		$sql = "SELECT datetime, IFNULL(rxCheckPercent,'null') AS rxCheckPercent FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
		$res = $conn->query($sql);
		$i=0;
		while($row=mysqli_fetch_row($res)) {
				$rx = $row[1];
				if(is_int($i)) {
					$json_rxCheckPercent.= "$rx,";
				}
				$i++;
		}
	$json_rxCheckPercent.="]";
};

//DIRECTION VENT
$json_dir_vent = "[";
	$sql = "SELECT datetime, IFNULL(windDir,'null') AS winDir FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
	$res = $conn->query($sql);
	$i=0;
	while($row=mysqli_fetch_row($res)) {
			$dir_vent = $row[1];
			if(is_int($i)) {
				$json_dir_vent.= "$dir_vent,";
			}
			$i++;
	}
$json_dir_vent.="]";

//write files
$file = $path."time_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_time);
fclose($fp);

$file = $path."vent_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_vent);
fclose($fp);

$file = $path."rafales_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_rafales);
fclose($fp);

$file = $path."pression_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_baro);
fclose($fp);

$file = $path."hygrometrie_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_hygro);
fclose($fp);

$file = $path."temperature_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_temp);
fclose($fp);

$file = $path."rosee_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_rose);
fclose($fp);

$file = $path."precipitations_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_precip);
fclose($fp);

if ($presence_uv === "true"){
	$file = $path."uv_48h.json";
	$fp=fopen($file,'w');
	fwrite($fp,$json_uv);
	fclose($fp);
};

if ($presence_radiation === "true"){
	$file = $path."radiation_48h.json";
	$fp=fopen($file,'w');
	fwrite($fp,$json_radiation);
	fclose($fp);

	$file = $path."et_48h.json";
	$fp=fopen($file,'w');
	fwrite($fp,$json_ET);
	fclose($fp);
};

if ($presence_iss_radio === "true"){
	$file = $path."rx_48h.json";
	$fp=fopen($file,'w');
	fwrite($fp,$json_rxCheckPercent);
	fclose($fp);
};

$file = $path."dir_vent_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_dir_vent);
fclose($fp);

?>
