<?php
	require_once("config/config.php");
	$db=$db_name.".".$db_table;
	mysql_connect($server,$user,$pass) or die ("Erreur SQL : ".mysql_error() );
?>
