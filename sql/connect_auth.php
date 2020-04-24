<?php
	try {
		$db_auth = new PDO("mysql:host=$serverAuth;dbname=$dbNameAuth", $userAuth, $passAuth);
		// Activation des erreurs PDO
		$db_auth->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $exception) {
		echo 'Échec lors de la connexion : ' . $exception->getMessage();
	}
?>
