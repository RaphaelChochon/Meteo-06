<?php require_once 'config/config.php';?>
<?php require_once 'sql/connect_pdo.php';?>
<?php require_once 'sql/import.php';?>
<?php require_once 'include/functions.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Mentions légales</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<?php include 'config/favicon.php';?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- JQUERY JS -->
		<script src="content/jquery/jquery-slim-3.4.1.min.js"></script>

		<!-- Bootstrap 4.4.1 -->
		<link href="content/bootstrap/css/bootswatch-united-4.4.1.min.css" rel="stylesheet">
		<link href="content/custom/custom.css?v=1.2" rel="stylesheet">
		<script src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>
	</head>
	<body>
	<div class="container">
		<header>
			<?php include 'header.php';?>
		</header>
		<br>
		<nav>
			<?php include 'nav.php';?>
		</nav>

		<!-- DEBUT DU CORPS DE PAGE -->
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-dismissible alert-danger">
					<h1 class="text-center">Réserves de responsabilités</h1>
					<h3 class="text-center">Toutes les données sont fournies uniquement à titre indicatif</h3>
						<ul>
							<li>Elles ne peuvent donc en aucun cas engager la responsabilité de quelque ordre que ce soit à l'égard des informations et données fournies dans ces pages ou de par les liens proposés vis à vis de l'éditeur, ou <?php echo $name_manager_footer; ?>.</li>
							<li>Vous pouvez utiliser ces informations à vos seuls risques et périls. Leurs emplois dans le cadre d'une planification de voyage, d'une activité sportive, agricole ou professionnelle n'engagent que vous.</li>
						</ul>
				</div>
			</div>
		</div>



	<footer class="footer bg-light">
		<?php include 'footer.php';?>
	</footer>
	</div>
	</body>
</html>
