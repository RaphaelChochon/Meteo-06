<?php
	require_once("config/config.php");
	$db=$db_name.".".$db_table;
	$conn = mysqli_connect($server,$user,$pass,$db_name);
?>
