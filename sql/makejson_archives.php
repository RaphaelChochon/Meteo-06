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
http://nicemeteo.fr
Création de fichiers json pour différents paramètres, à partir
de la BDD MySQL de Weewx. Ces fichiers JSON contiennent TOUS les
enregistrements de la BDD.
-------------------------------------------------------------
*/

// Chemin d'export des fichiers JSON
$path = "../json/archives/";

// appel du script de config et connexion à la BDD
require_once('../config/config.php');
	$db=$db_name.".".$db_table;
	mysql_connect($server,$user,$pass) or die ("Erreur SQL : ".mysql_error() );

//TEMPERATURE
$json_temp = "[";
$res=mysql_query("SELECT datetime, IFNULL(outTemp,'null') AS outTemp FROM $db ORDER BY dateTime ASC;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$temp = $row[1];
		if ($temp == 'null') {
			$temp2 = 'null';
		}else{
			$temp2 = round($temp,1);
		}
		if(is_int($i)) {
			$json_temp.= "[$timestamp,$temp2],\n";
		}
		$i++;
}
$json_temp.="]";

//ROSEE
$json_rosee = "[";
$res=mysql_query("SELECT datetime, IFNULL(dewpoint,'null') AS dewpoint FROM $db ORDER BY dateTime ASC;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$rosee = $row[1];
		if ($rosee == 'null') {
			$rosee = 'null';
		}else{
			$rosee = round($rosee,1);
		}
		if(is_int($i)) {
			$json_rosee.= "[$timestamp,$rosee],\n";
		}
		$i++;
}
$json_rosee.="]";

//HYGRO
$json_hygro = "[";
$res=mysql_query("SELECT datetime, IFNULL(outHumidity,'null') AS outHumidity FROM $db ORDER BY dateTime ASC;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$hygro = $row[1];
		if ($hygro == 'null') {
			$hygro = 'null';
		}else{
			$hygro = round($hygro,1);
		}
		if(is_int($i)) {
			$json_hygro.= "[$timestamp,$hygro],\n";
		}
		$i++;
}
$json_hygro.="]";


//write files
$file = $path."temperature.json";
$fp=fopen($file,'w');
fwrite($fp,$json_temp);
fclose($fp);

$file = $path."rosee.json";
$fp=fopen($file,'w');
fwrite($fp,$json_rosee);
fclose($fp);

$file = $path."humidite.json";
$fp=fopen($file,'w');
fwrite($fp,$json_hygro);
fclose($fp);

?>
