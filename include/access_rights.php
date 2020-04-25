<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../config/config.php';
	require_once __DIR__ . '/../sql/connect_auth.php';

	// Cookie domain
	\ini_set('session.cookie_domain', $cookieDomain);
	// Connect BDD
	$auth = new \Delight\Auth\Auth($db_auth);

	if ($auth->isLoggedIn()) {
		$userId = $auth->getUserId();

		// Récup du profil et des droits admin
		$query_string = "SELECT * FROM `users_profile` WHERE `id_user` = '$userId';";
		$result       = $db_auth->query($query_string);
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$userPrenom = $row['prenom'];
			$userNom = $row['nom'];
			if ($row['admin'] == 1) {
				define('USER_IS_ADMIN', true);
			}
		}

		// Récup des droits stations
		$userStationAccess = array();
		$query_string = "SELECT `id`, `station` FROM `station_access` WHERE `id_user` = '$userId';";
		$result       = $db_auth->query($query_string);
		if ($result) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if ($row['station'] != null) {
					$userStationAccess[] = $row['station'];
				}
			}
			if (in_array($db_name,$userStationAccess)) {
				define('USER_IS_PROPRIO', true);
			}
		}
	}