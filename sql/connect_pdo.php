<?php
	$db=$db_name.".".$db_table;
	try {
		$db_handle_pdo = new PDO("mysql:host=$server;dbname=$db_name", $user, $pass);
		// Activation des erreurs PDO
		$db_handle_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// mode de fetch par défaut : FETCH_ASSOC / FETCH_OBJ / FETCH_BOTH
		//$db_handle_pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	} catch (PDOException $exception) {
		echo 'Échec lors de la connexion : ' . $exception->getMessage();
	}
?>
