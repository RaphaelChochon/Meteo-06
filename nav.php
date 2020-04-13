<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary"> -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="./">Accueil</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarPrincipale" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarPrincipale">
		<ul class="navbar-nav mr-auto">
			<?php if ($presence_webcam){
				echo '
				<li class="nav-item">
					<a class="nav-link" href="webcam.php">Webcam</a>
				</li>
				';
			};?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTabRecap" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Résumés
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownTabRecap">
					<a class="dropdown-item" href="resume-quotidien.php">Résumé quotidien</a>
					<a class="dropdown-item" href="resume-mensuel.php">Résumé mensuel</a>
					<a class="dropdown-item" href="tableau_hier.php">Hier ?</a>
					<a class="dropdown-item" href="tableau_7j.php">7 jours glissants ?</a>
					<a class="dropdown-item" href="tableau_30j.php">30 jours glissants ?</a>
					<a class="dropdown-item" href="tableau_records.php">Records de la station</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="noaa.php">Accès aux tableaux NOAA<br>Rapports mensuels et annuels</a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="graphs.php">Graphiques</a>
				<!-- <a class="nav-link" href="graphs.php">Graphiques<span style="color:red;font-weight:bold;font-size:small;"><sup> New</sup></span></a> -->
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownClimato" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Graphs. climato.
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownClimato">
					<a class="dropdown-item" href="climatologie-quotidienne.php">Climato. quotidienne</a>
					<a class="dropdown-item" href="comparatif-moyenne.php">Comparatif de moyennes de-<br>-températures</a>
					<a class="dropdown-item" href="climato-quoti-fiab.php">Indice de fiabilité de la-<br>-climato.</a>
				</div>
			</li>
			<!-- <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownClimato" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Archives
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownClimato">
					<a class="dropdown-item" href="noaa.php">Accès aux tableaux NOAA<br>Rapports mensuels et annuels</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="archives.php">Graphique de toutes les archives<br>Attention, lent à charger...</a>
				</div>
			</li> -->
			<?php if ($additional_menu){
				include 'config/additional_menu.php';
			};?>
			<li class="nav-item">
				<a class="nav-link" href="a-propos.php">A propos</a>
			</li>
			
		</ul>
	</div>
</nav>

<!-- JS NAV ACTIVE -->
<script>
	// $(document).ready(function() {
		$('li.active').removeClass('active');
		var pathArray = window.location.pathname.split('/');
		// console.log(pathArray);
		$('a[href="' + pathArray[1] + '"]').closest('li').addClass('active');
	// });
</script>
