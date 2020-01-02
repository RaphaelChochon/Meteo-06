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
	https://meteo06.fr
	-------------------------------------------------------------*/
	require_once("version.php");
	$version_site = $code_version; // DO NOT MODIFY THIS. It is used to know the version of the site installed.
	/*-----------------------------------------------------------*/


	// CONNECT BDD MySQL WEEWX
	$server  = "localhost"; //localhost or host adress/IP
	$user    = "weewx_for_example"; // User MySQL
	$pass    = "passe"; // password
	$db_name = "weewx"; // BDD name
	$db_table= "archive"; // table name

	// GENERAL CONFIG
	$station_name        = "Name of my station";
	$short_station_name  = "Short name";
	$station_commune     = "NA"; // Name of the municipality of installation of the station, example : Nice, Paris, Marseille, etc.
	$station_coord       = "43.710173, 7.261953"; // Geographical coordinates of the station
	$url_site            = "https://myweathersite.fr"; // URL of the site WITHOUT the slash at the end ('subdomain.domain.tld' for example). This is important for SEO tools (google referencing for example)
	$SSL                 = true; // If the site is in https (SSL on) then it's true, otherwise write false
	$station_model       = "Oregon Scientific WMR200"; //Station model : Oregon Scientific WMR200, WMR88 ; Davis VP2 etc.
	$station_altitude    = "NA"; // Elevation/altitude of the station in meters
	$station_position    = "NA"; // Position of the station, example : mountain peak, hill, slope, valley background, plateau/lowland etc.
	$station_exposure    = "NA"; // Exposure of the station, example : South, South West, etc.
	$station_precautions = "Pas de précautions particulières"; // Special precautions with regard to data delivered from this station, example : overestimation, under estimation, etc.
	$date_install_station= "01/01/1970"; // Start of the database MySQL Weewx, at format dd/mm/aaaa
	$name_manager_footer = "l'association Nice Météo 06"; // Manager name or association maintaining station
	$name_manager_graph  = "Association Nice Météo 06"; // Name which appears in the charts
	$site_manager_graph  = "http://nicemeteo06.fr"; // URL of site on click to the name manager in the charts
	$site_manager_link   = ""; // Link of partner/manager for title for example
	$contact_mail_footer = "nice.meteo06[at]example.com"; // mail address to contact the station manager
	$extension_logo      = "jpg"; // Logo extension format : "png" or "jpg" or "jpeg" etc.

	// WEBCAM
	$presence_webcam       = true; // if true change header title and menu
	$webcam_url_1          = "webcam/lastsnap.jpg"; // URL or path of webcam 1 jpeg, png, etc
	$webcam_view_1         = "Vue sur Nice - Plein Sud"; // Viewing angle of the webcam 2. ex Vue sur Nice - Plein Sud

	$presence_second_webcam= false; // if true, display a second webcam on page webcam.php
									//(require $presence_webcam true)
	$webcam_url_2          = "webcam/lastsnap2.jpg"; // URL or path of webcam 2 jpeg, png, etc
	$webcam_view_2         = "Plein Nord"; // Viewing angle of the webcam 2. ex Plein Nord

	$webcam_refresh_1      = "5"; // refresh rate of webam in minutes
	$webcam_refresh_2      = $webcam_refresh_1; //Keep this value if both cameras have the same refresh rate

	// TIMELAPSE
	$timelapse_poster_url_1   = "timelapse/nice.jpg"; // URL or path of miniature timelapse 1
	$timelapse_url_1          = "timelapse/nice.webm"; // URL or path timelapse 1

	$presence_second_timelapse= false; //if true, display a second timelapse on page webcam.php
	$timelapse_poster_url_2   = ""; // URL or path of miniature timelapse 2
	$timelapse_url_2          = ""; // URL or path timelapse 2

	// ARCHIVES TIMELAPSES
	$presence_archive_timelapse= true; // if true, display a button with URL or path to the archives
	$timelapse_archive_url_1   = "archives/nice/"; // URL or path to archive 1
	$timelapse_archive_url_2   = ""; // URL or path to archive 2

	// GIF
	$presence_gif = false;                   // if true, diplay GIF at index.php
	$gif_url      = "webcam/animation.gif";  // URL or path of GIF
	$gif_time     = 4;                       // Time display in GIF, in hours

	// SONDES
	$presence_iss_radio       = true; // If true, display values Rx percent in charts of interieur.php
	$presence_uv              = true; // If true, display values in the tables and charts
	$presence_radiation       = false; // If true, display values in the tables and charts
	$timestamp_maj_weewx_3_6_0= "1477605600"; // Before update Weewx 3.6.0 the calcul of ET is wrong. http://www.timestamp.fr/

	// CLIMATO
	$presence_old_climato = $row_sites['presence_old_climato']; // If true, display message and URL for old climato
	$url_old_climato      = $row_sites['url_old_climato'];

	// WEB ANALYTIC
	$enable_web_analytics = false; // REQUIRES the file "config/web_analytics.php" to be created, otherwise it will cause an error! => Insert the tracking code inside (PIWIK or GOOGLE ANALYTICS)

	// ADDITIONAL MENU
	$additional_menu = true; // If enabled, activates an additional menu in the navigation bar, linked to the file "config/additional_menu.php"

	// OFFLINE STATION
	$offline_time= "600"; // Time in seconds after which the station is declared offline - Default 10 min

	// BANNIERE INFO
	$banniere_info_active = true; // enable banniere
	$banniere_info_type   = "warning"; // change color banniere. Value possible : danger/warning/success/info => http://bootswatch.com/flatly/
	$banniere_info_titre  = "Site en cours de déploiement"; // title
	$banniere_info_message= "Ce site est en cours de déploiement, il se peut donc que vous rencontriez encore quelques bugs.";

	// RADAR
	$radar_url       = "//www.infoclimat.fr/api/UzBUfgU%2FBzJWflZgBTwHZFwuBW4AZ1Z%2BAmlbNVV8ADFWPFVgVmUFNAYwBTNTZwloUz5dPAdjV2FTMg%3D%3D/radar/sud_est?4f360c88e3aabaf99aeb8edfecc08542"; // URL of radar
	$radar_source    = "InfoClimat"; // source radar
	$radar_source_url= "http://www.infoclimat.fr/cartes-meteo-temps-reel-images-satellites-infrarouge-visible-haute-resolution.html?i=radar-sud_est"; // URL source radar

	// TOKEN MapBox
	$mapbox_token = "your.token.mapbox"; // Token for MapBox

	// FACEBOOK
	$fb_app_id = ""; // Facebook application ID

	// TWITTER
	$tw_account_name = ""; // Twitter account name WITH arobas (@)

	// HASHTAG
	$hashtag_meteo = ""; // Hashtag to the choice you want to put forward in META SEO tags Facebook and company ("#Météo06" for example). Not required

?>
