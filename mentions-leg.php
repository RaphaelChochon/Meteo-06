<?php include 'config.php';?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $short_station_name; ?> | Mentions légales</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
		<div class="row">
			<div class="col-md-12" align="center">
				<div class="alert alert-dismissible alert-danger">
				<h1>Réserves de responsabilités</h1>
				<h3>Toutes les données sont fournises uniquement à titre indicatif</h3>
  					<ul>
  						<li>Elles ne peuvent donc en aucun cas engager la responsabilité de quelque ordre que ce soit à l'égard des informations et données fournies dans ces pages ou de par les liens proposés vis à vis de l'éditeur, ou <?php echo $name_manager_footer; ?>.</li>
  						<li>Vous pouvez utiliser ces informations à vos seuls risques et périls. Leurs emplois dans le cadre d'une planification de voyage, d'une activité sportive, agricole ou professionnelle n'engagent que vous.</li>
  					</ul>
				</div>
			</div>
		</div>



	<footer>
		<?php include 'foot.php';?>
	</footer>
	</div>
	<link href="vendors/bootswatch-flatly/bootstrap.min.css" rel="stylesheet">
	<link href="vendors/custom/custom.css" rel="stylesheet">
	<script src="vendors/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
