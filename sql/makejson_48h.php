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
de la BDD MySQL de Weewx. Ces fichiers JSON contiennent les
enregistrements des dernières 48 heures.
-------------------------------------------------------------
*/

// Chemin d'export des fichiers JSON
$path = "../json/";

// appel du script de connexion
require("connect.php");

// On récupère le timestamp du dernier enregistrement
$sql="select max(dateTime) from $db";
$query=mysql_query($sql);
$list=mysql_fetch_array($query);

// On détermine le stop et le start de façon à récupérer dans la prochaine requête que les données des dernières xx heures
$stop=$list[0];
$start48=$stop-(86400*2);

//temperature
$json_temp = "[";
$res=mysql_query("select datetime, IFNULL(outTemp,'null') as outTemp from $db where dateTime >= '$start48' and dateTime <= '$stop' order by 1;");
$i=0;
while($row=mysql_fetch_row($res)) {
	$when=$row[0];
		$temp = $row[1];
		if(is_int($i)) {
			$timestamp=$when*1000;
			$json_temp.= "[$temp],\n";
		}
		$i++;
}
$json_temp.="]";

//temp rose
$json_rose = "[";
$res=mysql_query("select datetime,dewpoint from $db where dateTime >= '$start48' and dateTime <= '$stop' order by 1;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$when=$row[0];
		if(is_int($i)) {
			$temp = round($row[1],1);
			$timestamp=$when*1000;
			$json_rose.= "[$timestamp,$temp],\n";
		}
		$i++;
}
$json_rose.="]";

//barometre
$json_baro = "[";
$res=mysql_query("select datetime,barometer from $db where dateTime >= '$start48' and dateTime <= '$stop' order by 1;;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$data=round($row[1],1);
		if (is_int($i)) {
		$json_baro.= "[$timestamp,$data],\n";
		}
		$i++;
}
$json_baro.="]";

//hygro
$json_hygro = "[";
$res=mysql_query("select datetime,outHumidity from $db where dateTime >= '$start48' and dateTime <= '$stop' order by 1;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$data=round($row[1],0);
		if (is_int($i)) {
		$json_hygro.= "[$timestamp,$data],\n";
		}
		$i++;
}
$json_hygro.="]";

//vent
$json_vent = "[";
$res=mysql_query("select datetime,WindSpeed from $db where dateTime >= '$start48' and dateTime <= '$stop' order by 1;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$vent=round($row[1],1);
		if(is_int($i)) {
		$json_vent.= "[$timestamp,$vent],\n";
		}
		$i++;
}
$json_vent.="]";

$json_rafales = "[";
$res=mysql_query("select datetime,WindGust from $db where dateTime >= '$start48' and dateTime <= '$stop' order by 1;");
$i=0;
while($row=mysql_fetch_row($res)) {
		$timestamp=$row[0];
		$timestamp=$timestamp*1000;
		$vent=round($row[1],1);
		if(is_int($i)) {
		$json_rafales.= "[$timestamp,$vent],\n";
		}
		$i++;
}
$json_rafales.="]";


//write files
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

?>
