<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../sql/connect_auth.php';

// Cookie domain
	\ini_set('session.cookie_domain', $cookieDomain);
// Cookie secure
	\ini_set('session.cookie_secure', 1);
// Connect BDD
	$auth = new \Delight\Auth\Auth($db_auth);

// Init var
	$wrongUsername = false;
	$wrongPassword = false;

// Validation du form de connexion si pas connecté
	if (isset($_POST['username']) && isset($_POST['password']) && !$auth->isLoggedIn()) {

		if (isset($_POST['remember'])) {$remember = 1;}else{$remember = 0;}
		if ($remember == 1) {
			// keep logged in for one year
			$rememberDuration = (int) (60 * 60 * 24 * 365.25);
		}
		else {
			// do not keep logged in after session ends
			$rememberDuration = null;
		}

		try {
			$auth->loginWithUsername($_POST['username'], $_POST['password'], $rememberDuration);
		}
		catch (\Delight\Auth\UnknownUsernameException $e) {
			$wrongUsername = true;
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
			$wrongPassword = true;
		}
	}

// Récup d'infos supp si connecté
	if ($auth->isLoggedIn()) {
		$userId = $auth->getUserId();

		// Récup du profil et des droits admin/équipe
		$query_string = "SELECT * FROM `users_profile` WHERE `id_user` = '$userId';";
		$result       = $db_auth->query($query_string);
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$userPrenom = $row['prenom'];
			$userNom = $row['nom'];
			if ($row['is_admin'] == 1) {
				define('USER_IS_ADMIN', true);
			}
			if ($row['is_team'] == 1) {
				define('USER_IS_TEAM', true);
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
?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | ADMIN - LOGIN</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php include __DIR__ . '/../config/favicon.php';?>
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

			<div class="row my-4">
				<div class="col-md-4 mx-auto">
					<?php if ($auth->isLoggedIn()) : ?>
						<!-- Si connecté -->
						<h4 class="text-center mb-4">Bonjour <?php echo $userPrenom." ".$userNom; ?></h4>
						<p class="text-center">
							Vous êtes bien connecté et pouvez continuer votre navigation sur l'ensemble des sites "station-météo" de l'association.
							<br><br>
							<?php
								if (defined('USER_IS_ADMIN')){
									echo '<b>Vous êtes administrateur.</b>';
								}
								echo '<br>';
								if (defined('USER_IS_TEAM')){
									echo '<b>Vous êtes membre de l\'équipe.</b>';
								}
								echo '<br>';
								if (defined('USER_IS_PROPRIO')){
									echo '<b>Vous êtes le propriétaire de cette station.</b>';
								} else {
									echo '<b>Vous n\'êtes pas le propriétaire de cette station.</b>';
								}
							?>
						</p>
						<a role="button" class="btn btn-primary btn-block my-3" href="https://auth.meteo06.fr/modif-profil.php" target="_blank"><i class="fas fa-users-cog"></i> Modifier mes infos sur auth.meteo06.fr</a>
						<div class="form-group">
							<form method="post" action="logout.php">
								<button type="submit" class="btn btn-danger btn-block"><i class="fas fa-user-slash"></i> Se déconnecter</button>
							</form>
						</div>
					<?php endif; ?>
					<?php if (!$auth->isLoggedIn()) : ?>
						<!-- Si pas connecté -->
						<div class="form-group">
							<form method="post" action="login.php">
								<h2 class="text-center">Connexion utilisateur</h2>
								<p class="text-center">
									Espace réservé aux propriétaires de station et à l'équipe de l'association.
								</p>
								<div class="form-group">
									<label class="control-label">Nom d'utilisateur</label>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-user"></i></span>
											</div>
											<input type="text" class="form-control <?php if ($wrongUsername) echo 'is-invalid'; ?>" id="username" name="username" placeholder="Nom d'utilisateur" autocomplete="username" required>
											<?php if ($wrongUsername) echo '<div class="invalid-feedback">Nom d\'utilisateur invalide</div>'; ?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Mot de passe</label>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-key"></i></span>
											</div>
											<input type="password" class="form-control <?php if ($wrongPassword) echo 'is-invalid'; ?>" id="password" name="password" placeholder="Mot de passe" autocomplete="current-password" required>
											<?php if ($wrongPassword) echo '<div class="invalid-feedback">Mot de passe invalide</div>'; ?>
										</div>
									</div>
								</div>
								<div class="clearfix">
									<label class="pull-left checkbox-inline"><input type="checkbox" id="remember" name="remember"> Se souvenir de moi</label>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block">Connexion</button>
								</div>
								<p class="text-center mt-5">
									Utilisation de <a alt="GitHub de la librairie" target="_blank" href="https://github.com/delight-im/PHP-Auth">PHP-Auth</a>, une librairie d'authentification sous licence MIT.
								</p>
							</form>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<footer class="footer bg-light">
				<?php include '../footer.php';?>
			</footer>
		</div>
	</body>
</html>
