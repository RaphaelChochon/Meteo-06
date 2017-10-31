<?php
	// appel du script de connexion
	require_once("connect.php");

	// On récupère le timestamp du dernier enregistrement
	$sql = "SELECT max(dateTime) FROM $db_name.$db_table";
	$query = $conn->query($sql);
	$list = mysqli_fetch_array($query);

	// On détermine le stop et le start de façon à récupérer dans la prochaine
	// requête que les données des 30 derniers jours
	$stop = $list[0]*1000;
	$debut = $stop-(86400*2)*1000;
	$minuit = strtotime('today midnight')*1000;
	$minuit_hier = strtotime('yesterday midnight')*1000;

?>
