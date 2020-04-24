<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary"> -->
<nav class="navbar navbar-expand-xl navbar-light bg-light">
	<a class="navbar-brand" href="/">Accueil</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarPrincipale" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarPrincipale">
		<ul class="navbar-nav mr-auto">
			<?php if ($presence_webcam){
				echo '
				<li class="nav-item">
					<a class="nav-link" href="/webcam.php">Webcam</a>
				</li>
				';
			};?>
			<li class="nav-item">
				<a class="nav-link" href="/resume-quotidien.php?day=<?php echo date('Y-m-d');?>">Résumé quotidien</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/graphs.php?type=graphs&period=24h">Graphiques</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownClimato" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Climatologie
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownClimato">
					<a class="dropdown-item" href="/climatologie-mensuelle.php">Climato. mensuelle</a>
					<a class="dropdown-item" href="/climatologie-annuelle.php">Climato. annuelle</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/graphs-climatologie-globale.php">Graphs. climato. globale</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/tableau_records.php">Records de la station -> a revoir</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/noaa.php">Accès aux tableaux NOAA<br>Rapports mensuels et annuels</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownClimato" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Divers
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownClimato">
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/comparatif-moyenne.php">Comparatif de moyennes de<br>températures</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/climato-quoti-fiab.php">Indice de fiabilité de la<br>climato.</a>
				</div>
			</li>
			<?php if ($additional_menu){
				include 'config/additional_menu.php';
			};?>
			<li class="nav-item">
				<a class="nav-link" href="/a-propos.php">A propos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/">Admin</a>
			</li>
		</ul>
		<?php if ($auth->isLoggedIn()) : ?>
		<ul class="navbar-nav ml-auto justify-content-end">
			<li class="nav-item">
				<a class="nav-link" href="/admin/login.php"><i class="far fa-user-circle"></i></a>
			</li>
		</ul>
		<?php endif; ?>
	</div>
</nav>

<!-- JS NAV ACTIVE -->
<script>
	document.addEventListener('DOMContentLoaded', function () {
		$('li.active').removeClass('active');
		var pathArray = window.location.pathname.split('/');
		if (pathArray[1] === '') {
			pathArray[1] = 'index.php';
		}
		// console.log(pathArray);
		$('a[href^="/' + pathArray[1] + '"]').closest('li').addClass('active');
	});
</script>
