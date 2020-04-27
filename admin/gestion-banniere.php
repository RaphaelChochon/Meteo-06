<?php
	require_once __DIR__ . '/../include/access_rights.php';
	if (!$auth->isLoggedIn()) {
		// Redirection
		header('Location: /admin/login.php'); 
		exit();
	}
	if (!defined('USER_IS_ADMIN') || !defined('USER_IS_TEAM')) {
		// Redirection
		header('Location: /admin/index.php'); 
		exit();
	}

	// Init
	$messageBanniere = false;

	// Connexion et récup de la bannière actuelle
	require_once __DIR__ . '/../sql/connect_pdo.php';
	$query_string = "SELECT * FROM `stations_meta`.`config_bannieres_infos` WHERE `bdd_name` = '$db_name';";
	$result       = $db_handle_pdo->query($query_string);
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$banIsEnabled = $row['enable_banniere_bool'];
		$banType = $row['banniere_type'];
		$banTitle = $row['banniere_title'];
		$banMessage = $row['banniere_message'];
	}

	// Controle du formulaire
	if (isset($_POST['type']) && isset($_POST['title']) && isset($_POST['message']) && isset($_POST['submitForm']) ) {
		// Application des toogle
		if (isset($_POST['isEnabled'])) {$IS_ENABLED = 1;}else{$IS_ENABLED = 0;}
		$req_prep = $db_handle_pdo -> prepare("UPDATE `stations_meta`.`config_bannieres_infos` SET
										`enable_banniere_bool` = :banIsEnabled,
										`banniere_type` = :banType,
										`banniere_title` = :banTitle,
										`banniere_message` = :banMessage
										WHERE `bdd_name` = '$db_name'");
		$req_prep -> bindParam('banIsEnabled', $IS_ENABLED);
		$req_prep -> bindParam('banType', $_POST['type']);
		$req_prep -> bindParam('banTitle', $_POST['title']);
		$req_prep -> bindParam('banMessage', $_POST['message']);
		$result = $req_prep -> execute();

		if (!$result) {
			// Erreur
			$messageBanniere = '
			<div class="alert alert-danger" id="message">
				<h4 class="alert-heading mt-1">Erreur !</h4>
				<p class="text-justify mb-0">
				Erreur dans la requete '.$query_string.'<br>';
			$messageBanniere .= "\nPDO::errorInfo():\n";
			$messageBanniere .= print_r($req_prep->errorInfo());
			$messageBanniere .= '
				</p>
			</div>
			';
		}
		if ($result) {
			$messageBanniere = '
			<div class="alert alert-success" id="message">
				<h4 class="alert-heading mt-1">C\'est fait !</h4>
				<p class="text-justify mb-0">
					Bannière mise à jour comme ci-dessus.
					<br>
					A vous de l\'activer ou pas.
				</p>
			</div>
			';
		}
	}

	// MAJ de la banniere
	$query_string = "SELECT * FROM `stations_meta`.`config_bannieres_infos` WHERE `bdd_name` = '$db_name';";
	$result       = $db_handle_pdo->query($query_string);
	if ($result) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$banIsEnabled = $row['enable_banniere_bool'];
		$banType = $row['banniere_type'];
		$banTitle = $row['banniere_title'];
		$banMessage = $row['banniere_message'];
	}

	require_once __DIR__ . '/../sql/import.php';
	require_once __DIR__ . '/../include/functions.php';
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | ADMIN - Gestion de la bannière</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php include '../config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- JQUERY JS -->
		<script src="../content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="../content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="../content/custom/custom.css?v=1.2" rel="stylesheet">
		<script defer src="../content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="../content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- Font Awesome CSS -->
		<link href="../content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<header>
				<?php include __DIR__ . '/../header.php';?>
			</header>
			<br>
			<nav>
				<?php include __DIR__ . '/../nav.php';?>
			</nav>
			<br>

			<!-- Si modification -->
			<?php if ($messageBanniere) : ?>

			<!-- Bannière infos prévisualisation -->
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Prévisualisation :</h3>
					<h5 class="text-center">(après validation)</h5>
					<div class="alert alert-<?php echo $banType; ?>">
						<h4 class="alert-heading"><?php echo $banTitle; ?></h4>
						<hr>
						<p class="mb-0"><?php echo $banMessage; ?></p>
					</div>
				</div>
			</div>

			<!-- Message -->
			<div class="row my-4">
				<div class="col-md-4 mx-auto">
					<?php echo $messageBanniere; ?>
				</div>
			</div>
			<!-- Redirection -->
			<div class="row">
				<div class="col-md-4 mx-auto">
					<div class="alert alert-info">
						<h4 class="alert-heading mt-1">Redirection</h4>
						<p class="text-justify mb-0">
							Vous allez être redirigé dans quelques secondes, sinon cliquez ici :
							<a role="button" class="btn btn-block btn-outline-info mt-3" href="gestion-banniere.php">Redirection</a>
							<script>
								setTimeout(function () {
									window.location.href= 'gestion-banniere.php';
								},5000);
							</script>
						</p>
					</div>
				</div>
			</div>

			<?php else : ?>

			<!-- Bannière infos -->
			<?php if ($banIsEnabled) : ?>
				<div class="alert alert-<?php echo $banType; ?>">
					<h4 class="alert-heading"><?php echo $banTitle; ?></h4>
					<hr>
					<p class="mb-0"><?php echo $banMessage; ?></p>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Gestion de la bannière</h3>
				</div>
			</div>
			<!-- Texte prez -->
			<div class="row">
				<div class="col-md-12">
					<p class="text-center">
						Activation ou modification de la bannière.
						<br>
						Attention cette bannière est publique et s'affiche sur toutes les pages du site dés la validation, si activée !
					</p>
				</div>
			</div>
			<hr class="my-4">


			<div class="row my-4">
				<div class="col-md-4 mx-auto">
					<h4 class="text-center mb-4">Bannière</h4>
					<div class="form-group">
						<form method="post" action="gestion-banniere.php#message">
							<!-- Type -->
							<div class="form-group">
								<label for="type">Type/couleur</label>
								<select class="form-control" id="type" name="type">
									<option value="">--select value--</option>
									<option <?php if ($banType == 'info') { echo 'selected';} ?> value="info">info (bleu)</option>
									<option <?php if ($banType == 'success') { echo 'selected';} ?> value="success">success (vert)</option>
									<option <?php if ($banType == 'warning') { echo 'selected';} ?> value="warning">warning (orange/jaune)</option>
									<option <?php if ($banType == 'danger') { echo 'selected';} ?> value="danger">danger (rouge)</option>
									<option <?php if ($banType == 'light') { echo 'selected';} ?> value="light">light (blanc/gris)</option>
									<option <?php if ($banType == 'secondary') { echo 'selected';} ?> value="secondary">secondary (gris Ubuntu)</option>
									<option <?php if ($banType == 'primary') { echo 'selected';} ?> value="primary">primary (orange Ubuntu)</option>
								</select>
							</div>
							<!-- Titre -->
							<div class="form-group">
								<label for="title">Titre</label>
								<textarea class="form-control" id="title" rows="3" name="title"><?php echo htmlentities($banTitle); ?></textarea>
							</div>
							<!-- Message -->
							<div class="form-group">
								<label for="message">Message (HTML autorisé seulement si vous savez ce que vous faites !)</label>
								<textarea class="form-control" id="message" rows="10" name="message"><?php echo htmlentities($banMessage); ?></textarea>
							</div>
							<hr class="my-4">
							<p class="text-justify">
								Avant d'activer, vous pouvez valider le formulaire pour ensuite prévisualiser la bannière et vérifier que cela vous convienne.
							</p>
							<!-- Activé ? -->
							<div class="form-group">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="isEnabled" name="isEnabled" <?php if ($banIsEnabled == 1) { echo 'checked';} ?>>
									<label class="custom-control-label" for="isEnabled">Activer/désactiver la bannière</label>
								</div>
							</div>
							
							<hr class="my-4">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block" name="submitForm">Enregistrer</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- Bannière infos prévisualisation -->
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Prévisualisation :</h3>
					<h5 class="text-center">(après validation)</h5>
					<div class="alert alert-<?php echo $banType; ?>">
						<h4 class="alert-heading"><?php echo $banTitle; ?></h4>
						<hr>
						<p class="mb-0"><?php echo $banMessage; ?></p>
					</div>
				</div>
			</div>

			<?php endif; ?>

			<footer class="footer bg-light">
				<?php include __DIR__ . '/../footer.php';?>
			</footer>
		</div>
	</body>
</html>
