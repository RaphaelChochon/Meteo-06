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
Création de fichiers json pour les sondes intérieurs, à partir
de la BDD MySQL de Weewx. Ces fichiers JSON contiennent les
enregistrements des dernières 48 heures.
-------------------------------------------------------------
*/

// Chemin d'export des fichiers JSON
$path = "../json/indoor/";

// appel du script de config et connexion à la BDD
require_once('../config/config.php');
	$db=$db_name.".".$db_table;
	$conn = mysqli_connect($server,$user,$pass,$db_name);


// On récupère le timestamp du dernier enregistrement
$sql = "SELECT max(dateTime) FROM $db";
$query = $conn->query($sql);
$list = mysqli_fetch_array($query);

// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
$stop = $list[0];
$start48 = $stop-(86400*2);
$start7j = $stop-(86400*7);
$start30j = $stop-(86400*30);


// 48 HEURES
//time
$json_time_48h = "[";
$sql = "SELECT datetime FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
	$when=$row[0];
		if(is_int($i)) {
			$timestamp=$when*1000;
			$json_time_48h.= "$timestamp,";
		}
		$i++;
}
$json_time_48h.="]";

//temperature
$json_intemp_48h = "[";
$sql = "SELECT datetime, IFNULL(inTemp,'null') AS inTemp FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$temp = $row[1];
		if(is_int($i)) {
			$json_intemp_48h.= "$temp,";
		}
		$i++;
}
$json_intemp_48h.="]";

//hygro
$json_inhygro_48h = "[";
$sql = "SELECT datetime, IFNULL(inHumidity,'null') AS inHumidity FROM $db WHERE dateTime >= '$start48' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$hygro= $row[1];
		if (is_int($i)) {
			$json_inhygro_48h.= "$hygro,";
		}
		$i++;
}
$json_inhygro_48h.="]";


// 7 JOURS
//time
$json_time_7j = "[";
$sql = "SELECT datetime FROM $db WHERE dateTime >= '$start7j' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
	$when=$row[0];
		if(is_int($i)) {
			$timestamp=$when*1000;
			$json_time_7j.= "$timestamp,";
		}
		$i++;
}
$json_time_7j.="]";

//temperature
$json_intemp_7j = "[";
$sql = "SELECT datetime, IFNULL(inTemp,'null') AS inTemp FROM $db WHERE dateTime >= '$start7j' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$temp = $row[1];
		if(is_int($i)) {
			$json_intemp_7j.= "$temp,";
		}
		$i++;
}
$json_intemp_7j.="]";

//hygro
$json_inhygro_7j = "[";
$sql = "SELECT datetime, IFNULL(inHumidity,'null') AS inHumidity FROM $db WHERE dateTime >= '$start7j' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$hygro= $row[1];
		if (is_int($i)) {
			$json_inhygro_7j.= "$hygro,";
		}
		$i++;
}
$json_inhygro_7j.="]";


// 30 JOURS
//time
$json_time_30j = "[";
$sql = "SELECT datetime FROM $db WHERE dateTime >= '$start30j' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
	$when=$row[0];
		if(is_int($i)) {
			$timestamp=$when*1000;
			$json_time_30j.= "$timestamp,";
		}
		$i++;
}
$json_time_30j.="]";

//temperature
$json_intemp_30j = "[";
$sql = "SELECT datetime, IFNULL(inTemp,'null') AS inTemp FROM $db WHERE dateTime >= '$start30j' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$temp = $row[1];
		if(is_int($i)) {
			$json_intemp_30j.= "$temp,";
		}
		$i++;
}
$json_intemp_30j.="]";

//hygro
$json_inhygro_30j = "[";
$sql = "SELECT datetime, IFNULL(inHumidity,'null') AS inHumidity FROM $db WHERE dateTime >= '$start30j' AND dateTime <= '$stop' ORDER BY 1;";
$res = $conn->query($sql);
$i=0;
while($row=mysqli_fetch_row($res)) {
		$hygro= $row[1];
		if (is_int($i)) {
			$json_inhygro_30j.= "$hygro,";
		}
		$i++;
}
$json_inhygro_30j.="]";


//write files
//48 HEURES
$file = $path."time_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_time_48h);
fclose($fp);

$file = $path."intemp_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_intemp_48h);
fclose($fp);

$file = $path."inhygro_48h.json";
$fp=fopen($file,'w');
fwrite($fp,$json_inhygro_48h);
fclose($fp);

//7 JOURS
$file = $path."time_7j.json";
$fp=fopen($file,'w');
fwrite($fp,$json_time_7j);
fclose($fp);

$file = $path."intemp_7j.json";
$fp=fopen($file,'w');
fwrite($fp,$json_intemp_7j);
fclose($fp);

$file = $path."inhygro_7j.json";
$fp=fopen($file,'w');
fwrite($fp,$json_inhygro_7j);
fclose($fp);

//30 JOURS
$file = $path."time_30j.json";
$fp=fopen($file,'w');
fwrite($fp,$json_time_30j);
fclose($fp);

$file = $path."intemp_30j.json";
$fp=fopen($file,'w');
fwrite($fp,$json_intemp_30j);
fclose($fp);

$file = $path."inhygro_30j.json";
$fp=fopen($file,'w');
fwrite($fp,$json_inhygro_30j);
fclose($fp);
