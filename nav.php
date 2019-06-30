<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="./">Accueil</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php if ($presence_webcam === "true"){
					echo '<li><a href="webcam.php">Webcam</a></li>';
				};?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tableaux récap.<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="tableau_hier.php">Hier</a></li>
						<li><a href="tableau_7j.php">7 jours glissants</a></li>
						<li><a href="tableau_30j.php">30 jours glissants</a></li>
						<li><a href="tableau_mois.php">Mois par mois</a></li>
						<li><a href="tableau_annee.php">Année</a></li>
						<li><a href="tableau_records.php">Records</a></li>
					</ul>
				</li>
				<li><a href="graphs.php">Graphiques <span style="color:red;font-weight:bold;font-size:small;"><sup>New</sup></span></a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Climato. <span style="color:red;font-weight:bold;font-size:small;"><sup>New</sup></span><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="climatologie-quotidienne.php">Climato. quotidienne</a></li>
						<li><a href="comparatif-moyenne.php">Comparatif de moyennes</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Archives<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="noaa.php">Accès aux tableaux NOAA<br>Rapports mensuels et annuels</a></li>
						<li class="divider"></li>
						<li><a href="archives.php">Graphique de toutes les archives<br>Attention, lent à charger...</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<?php if ($additional_menu === "true"){
						include 'config/additional_menu.php';
					};?>
				<li>
				<li><a href="a-propos.php">A propos</a></li>
			</ul>
		</div>
	</div>
</nav>
