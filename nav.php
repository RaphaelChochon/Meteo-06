<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary"> -->
<nav class="navbar navbar-expand-xl navbar-light bg-light rounded">
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
					<a class="dropdown-item" href="/records.php">Records de la station</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/noaa.php">Accès aux tableaux NOAA<br>Rapports mensuels et annuels</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownClimato" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Divers
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownClimato">
					<a class="dropdown-item" href="/comparatif-moyenne.php">Comparatif de moyennes de<br>températures</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/graphs-climatologie-fiabilite.php">Indice de fiabilité de la<br>climato.</a>
				</div>
			</li>
			<?php if ($additional_menu){
				include 'config/additional_menu.php';
			};?>
			<li class="nav-item">
				<a class="nav-link" href="/a-propos.php">A propos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/">
					Admin
					<?php if ($auth->isLoggedIn()) : ?>
						<svg class="bi bi-unlock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path d="M.5 9a2 2 0 012-2h7a2 2 0 012 2v5a2 2 0 01-2 2h-7a2 2 0 01-2-2V9z"/>
							<path fill-rule="evenodd" d="M8.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
						</svg>
					<?php else : ?>
						<svg class="bi bi-lock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<rect width="11" height="9" x="2.5" y="7" rx="2"/>
							<path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
						</svg>
					<?php endif; ?>
				</a>
			</li>
		</ul>
		<?php if ($auth->isLoggedIn()) : ?>
		<ul class="navbar-nav ml-auto justify-content-end">
			<li class="nav-item">
				<a class="nav-link" href="/admin/login.php">
					<small>
						Profil
						<svg class="bi bi-person-bounding-box" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M1.5 1a.5.5 0 00-.5.5v3a.5.5 0 01-1 0v-3A1.5 1.5 0 011.5 0h3a.5.5 0 010 1h-3zM11 .5a.5.5 0 01.5-.5h3A1.5 1.5 0 0116 1.5v3a.5.5 0 01-1 0v-3a.5.5 0 00-.5-.5h-3a.5.5 0 01-.5-.5zM.5 11a.5.5 0 01.5.5v3a.5.5 0 00.5.5h3a.5.5 0 010 1h-3A1.5 1.5 0 010 14.5v-3a.5.5 0 01.5-.5zm15 0a.5.5 0 01.5.5v3a1.5 1.5 0 01-1.5 1.5h-3a.5.5 0 010-1h3a.5.5 0 00.5-.5v-3a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
						</svg>
					</small>
				</a>
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
