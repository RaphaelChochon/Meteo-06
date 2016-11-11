<?php
	/*-----------------------------------------------------------
	Ce fichier permet de faciliter la configuration du site et ainsi
	de le rendre facilement déployable pour quiconque sans avoir
	à toucher au reste du code (ou très peu).
	--------
	This file facilitates the configuration of the site and so make
	it easily deployable for anyone without affecting the rest of
	the code (or very little)
	--------
	This program is free software: you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	It is distributed in the hope that it will be useful, but
	WITHOUT ANY WARRANTY;
	--------
	Raphaël CHOCHON pour l'association Nice Météo 06
	http://nicemeteo.fr
	-------------------------------------------------------------*/
	require_once("version.php");
	$version_site = $code_version; // DO NOT MODIFY THIS. It is used to know the version of the site installed.
	/*-----------------------------------------------------------*/


	// CONNECT BDD MySQL WEEWX
	$server ="localhost"; //localhost or host adress/IP
	$user="weewx_for_example"; // User MySQL
	$pass="passe"; // password
	$db_name="weewx"; // BDD name
	$db_table="archive"; // table name

	// GENERAL CONFIG
	$station_name = "Name of my station";
	$short_station_name = "Short name";
	$station_model = "Oregon Scientific WMR200"; //Station model : Oregon Scientific WMR200, WMR88 ; Davis VP2 etc.
	$date_install_station = "01/01/1970"; // Start of the database MySQL Weewx, at format dd/mm/aaaa
	$name_manager_footer = "l'association Nice Météo 06"; // Manager name or association maintaining station
	$name_manager_graph = "Association Nice Météo 06"; // Name which appears in the charts
	$site_manager_graph = "http://nicemeteo06.fr"; // URL of site on click to the name manager in the charts
	$contact_mail_footer = "nice.meteo06[at]example.com"; // mail address to contact the station manager

	// WEBCAM
	$presence_webcam = true; // if true change header title and menu
	$webcam_url_1 = "webcam/lastsnap.jpg"; // URL or path of webcam 1 jpeg, png, etc
	$webcam_view_1 = "Vue sur Nice - Plein Sud"; // Viewing angle of the webcam 2. ex Vue sur Nice - Plein Sud

	$presence_second_webcam = false; // if true, display a second webcam on page webcam.php
									//(require $presence_webcam true)
	$webcam_url_2 = "webcam/lastsnap2.jpg"; // URL or path of webcam 2 jpeg, png, etc
	$webcam_view_2 = "Plein Nord"; // Viewing angle of the webcam 2. ex Plein Nord

	$webcam_refresh_1 = "5"; // refresh rate of webam in minutes
	$webcam_refresh_2 = $webcam_refresh_1; //Keep this value if both cameras have the same refresh rate

	// TIMELAPSE
	$timelapse_poster_url_1 = "timelapse/nice.jpg"; // URL or path of miniature timelapse 1
	$timelapse_url_1 = "timelapse/nice.webm"; // URL or path timelapse 1

	$presence_second_timelapse = false; //if true, display a second timelapse on page webcam.php
	$timelapse_poster_url_2 = ""; // URL or path of miniature timelapse 2
	$timelapse_url_2 = ""; // URL or path timelapse 2

	// ARCHIVES TIMELAPSES
	$presence_archive_timelapse = true; // if true, display a button with URL or path to the archives
	$timelapse_archive_url_1 = "archives/nice/"; // URL or path to archive 1
	$timelapse_archive_url_2 = ""; // URL or path to archive 2

	// SONDES
	$presence_uv = true; // If true, display values in the tables and charts
	$presence_radiation = false; // If true, display values in the tables and charts
	$timestamp_maj_weewx_3_6_0 = "1477605600"; // Before update Weewx 3.6.0 the calcul of ET is wrong. http://www.timestamp.fr/

?>
