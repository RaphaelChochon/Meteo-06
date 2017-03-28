<!--
	INSERER CI-DESSOUS le contenu de la page "A propos", complètement personnalisable
	en respectant le framework Bootstrap => http://getbootstrap.com/

	Ce qui est présent ici est un exemple que vous pouvez choisir de complètement modifier
	cependant au vu du travail que représente la conception et le développement d'un tel
	site, il serait sympa de garder une mention à l'association Nice Météo06 et Raphaël
	CHOCHON qui développe et maintient ce projet ;) Merci, et bon code !
-->
<!-- Affichage de la date et l'heure de dernière MAJ des données -->
<div class="row">
	<div class="col-md-12" align="center">
		<h3 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="online_station"';?>>Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?></h3>
		<?php if ($diff>$offline_time) : ?>
			<h4 class="offline_station">Station actuellement hors ligne depuis
				<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
			</h4>
		<?php endif; ?>
	</div>
</div>
<!-- Début de la partie de présentation et de remerciements -->
<div class="row">
	<div class="col-md-12">
		<h4><b>Présentation/remerciements</b></h4>
		<!-- 2 ONGLETS par défaut, a vous de voir -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#station" data-toggle="tab" aria-expanded="true">Le mot de la station</a></li>
			<li class=""><a href="#asso" data-toggle="tab" aria-expanded="false">Le mot de l'asso.</a></li>
		</ul>
		<div id="myTabContent" class="tab-content">
			<!-- Onglet station -->
			<div class="tab-pane fade active in" id="station">
				<p>Bonjour, je suis une station météo, on m'appelle "<?php echo $station_name; ?>" et mes données sont hébergées sur ce site Internet. Ce dernier me permet de me connecter à mes utilisateurs en leur fournissant tous les paramètres météos que je mesure en direct. C'est <?php echo $name_manager_footer; ?> (<a href="<?php echo $site_manager_graph; ?>" target="_blank">site Internet de l'association</a>) qui s'occupe de moi et héberge ce site.<br>C'est aussi cette association et plus particulièrement Raphaël CHOCHON qui développe ce site, c'est donc la plus part du temps à lui qu'il faut se plaindre si vous trouvez un bug (pour le contacter : contact [at] meteo06.fr). Tout son travail est hébergé sur la plateforme GitHub (<a href="https://github.com/RaphaelChochon/Meteo-06" target="_blank">ici</a>) et donc tout le code disponible est réutilisable. Le « changelog » du site permettant de suivre les nouveautés du site, les améliorations, les corrections de bugs, etc. est disponible <a href="https://github.com/RaphaelChochon/Meteo-06/blob/master/config/changelog.md" target="_blank">ici aussi</a>.</p>
			</div>
			<!-- Onglet asso -->
			<div class="tab-pane fade" id="asso">
				<p>L'association Nice Météo 06 remercie le propriétaire de cette station pour la mise à disposition de l'électricité nécessaire, la connexion internet, et son temps libre pour l'entretien, la maintenance, etc. !</p>
			</div>
		</div>
	</div>
</div>
<!-- Début de la partie infos techniques -->
<div class="row">
	<div class="col-md-12">
		<h4><b>Infos techniques</b></h4>
		<!-- 3 ONGLETS par défaut -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#stationmeteo" data-toggle="tab" aria-expanded="true">Station</a></li>
			<li class=""><a href="#webcam" data-toggle="tab" aria-expanded="false">Webcam</a></li>
			<li class=""><a href="#serveur" data-toggle="tab" aria-expanded="false">Serveur</a></li>
		</ul>
		<div id="techniques" class="tab-content">
			<!-- Onglet stationmeteo -->
			<div class="tab-pane fade active in" id="stationmeteo">
				<br>
				<div class="col-md-6">
					<p>La station météo est une <?php echo $station_model; ?>.<br>
					Elle est installée depuis le <?php echo $date_install_station; ?>.<br>
					Elle se situe sur la commune de [NOM de la COMMUNE] et plus précisément [PRECISION EMPLACEMENT] à <?php echo $station_altitude; ?> mètres d'altitude.</p>
					<p>Ses données sont récupérées via un <a href="https://www.raspberrypi.org/" target="_blank">Raspberry Pi</a> et le logiciel <a href="http://www.weewx.com/" target="_blank">Weewx (linux)</a> toutes les <?php echo $archive_interval; ?> minutes. Elles sont ensuite envoyées au serveur de l’association dans une base de données MySQL afin d'être affichée sur ce site.</p>
					<p>[PRECISION sur L'EMPLACEMENT des SONDES]</p>
					<p>Toutes les images peuvent être ouvertes en grand (clic-doit et "ouvrir l'image dans un nouvel onglet")</p>
				</div>
				<div class="col-md-6">
				<style>.carousel-img{width:auto;height:350px;max-height:350px;background-color:#5f666d;color:white;}</style>
					<div id="carouselStation" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
							<li data-target="#carouselStation" data-slide-to="0" class="active"></li>
							<li data-target="#carouselStation" data-slide-to="1"></li>
							<li data-target="#carouselStation" data-slide-to="2"></li>
						</ol>
						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<div class="item active">
								<img src="img/a-propos/station1.jpg" alt="" class="img-responsive img-thumbnail carousel-img" style="margin:0px auto;">
								<div class="carousel-caption">
									<h3>Titre</h3>
									<p>Courte description</p>
								</div>
							</div>

							<div class="item">
								<img src="img/a-propos/station2.jpg" alt="" class="img-responsive img-thumbnail carousel-img" style="margin:0px auto;">
								<div class="carousel-caption">
									<h3>Titre</h3>
									<p>Courte description</p>
								</div>
							</div>

							<div class="item">
								<img src="img/a-propos/station3.jpg" alt="" class="img-responsive img-thumbnail carousel-img" style="margin:0px auto;">
								<div class="carousel-caption">
									<h3>Titre</h3>
									<p>Courte description</p>
								</div>
							</div>
						</div>
						<!-- Left and right controls -->
						<a class="left carousel-control" href="#carouselStation" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Précédente</span>
						</a>
						<a class="right carousel-control" href="#carouselStation" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Suivante</span>
						</a>
					</div>
				</div>
			</div>
			<!-- Onglet webcam -->
			<div class="tab-pane fade" id="webcam">
				<br>
				<div class="col-md-6">
					<p>[DESCRIPTION WEBCAM]</p>
					<p>Elle prend une photo toutes les <?php echo $webcam_refresh_1; ?> minutes, à laquelle elle ajoute les données de la station météo dans un bandeau, et qu’elle envoie ensuite sur le serveur.</p>
					<p>Un tutoriel et les scripts nécessaires à cette configuration sont disponibles <a href="https://github.com/RaphaelChochon/RPI-PiCam" target="_blank">ici</a>.</p>
					<p>Enfin, au niveau du serveur, ces images sont stockées toute la journée afin de produire un timelapse le soir permettant d'observer en quelques secondes le balai des nuages des fois très esthétique, et des fois sans intérêt.</p>
				</div>
				<div class="col-md-6">
				<style>.carousel-img{width:auto;height:350px;max-height:350px;background-color:#5f666d;color:white;}</style>
					<div id="carouselWebcam" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
							<li data-target="#carouselWebcam" data-slide-to="0" class="active"></li>
							<li data-target="#carouselWebcam" data-slide-to="1"></li>
						</ol>
						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<div class="item active">
								<img src="img/a-propos/webcam1.jpg" alt="" class="img-responsive img-thumbnail carousel-img" style="margin:0px auto;">
								<div class="carousel-caption">
									<h3>Titre</h3>
								</div>
							</div>

							<div class="item">
								<img src="img/a-propos/webcam2.jpg" alt="" class="img-responsive img-thumbnail carousel-img" style="margin:0px auto;">
								<div class="carousel-caption">
									<h3>Titre</h3>
								</div>
							</div>

						</div>
						<!-- Left and right controls -->
						<a class="left carousel-control" href="#carouselWebcam" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Précédente</span>
						</a>
						<a class="right carousel-control" href="#carouselWebcam" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Suivante</span>
						</a>
					</div>
				</div>

			</div>
			<!-- Onglet serveur -->
			<div class="tab-pane fade" id="serveur">
				<br>
				<p>C'est un serveur OVH qui héberge ce site, avec une configuration classique pour le web (Apache2, PHP-FPM, MySQL, etc.).</p>
				<p>Les données issues de la station météo sont conservées dans une base de donnée, et permettent la création d'archives sous formes de graphiques, de tableaux etc.</p>
				<p>Les images brutes de la webcam ne sont conservées que sur une journée afin de produire un timelpase tous les soirs, et ce dernier est archivé sur un autre serveur (Kimsufi).</p>
			</div>
		</div>
	</div>
</div>
