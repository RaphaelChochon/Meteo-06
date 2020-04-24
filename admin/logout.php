<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../config/config.php';
	require_once __DIR__ . '/../sql/connect_auth.php';

	// Connect BDD
	$auth = new \Delight\Auth\Auth($db_auth);

	if ($auth->isLoggedIn()) {

		// Logout
		$auth->logOut();
		$auth->destroySession();

		// Redirection
		header('Location: login.php'); 
		exit();
	} else {
		// Redirection
		header('Location: login.php'); 
		exit();
	}
?>