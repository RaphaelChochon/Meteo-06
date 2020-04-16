<!--
	INSERER CI-DESSOUS le contenu de la page "A propos", complètement personnalisable
	en respectant le framework Bootstrap en version 4 => http://getbootstrap.com/

	Ce qui est présent ci-après est un exemple que vous pouvez modifier.
-->

<!-- Début de la partie de présentation et de remerciements -->
<div class="row">
	<div class="col-md-12">
		<h4>Présentation/remerciements</h4>
		<!-- 2 ONGLETS par défaut, a vous de voir -->
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#station">La station</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#association">Le mot de l'asso.</a>
			</li>
		</ul>
		<div id="tabStationAsso" class="tab-content">
			<div class="tab-pane fade active show" id="station">
				<br>
				<p class="text-justify">
					Cette station s'appelle "<?php echo $station_name; ?>", et ses données sont hébergées sur ce site internet. C’est <?php echo $name_manager_footer; ?> (<a href="<?php echo $site_manager_graph; ?>" target="_blank">site Internet de l'association</a>) qui l’héberge et la maintient. C’est aussi cette association qui développe ce site, c’est donc à elle qu’il faut se plaindre si vous trouvez un bug (différents moyens de le contacter : <a href="humans.txt">humans.txt</a>).
					<br><br>
					Tout le code de ce site est hébergé sur la plateforme GitHub (<a href="https://github.com/RaphaelChochon/Meteo-06" target="_blank">ici</a>). Il est donc récupérable et réutilisable dans son entièreté ou juste morceau par morceau à condition de créditer l'auteur, de ne pas en faire une utilisation commerciale et de le partager dans les mêmes conditions si vous effectuez des modifications -> Ce(tte) œuvre est mise à disposition selon les termes de la <a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.fr" target="_blank">Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International</a>.<br>
					Le « changelog » du site permettant de suivre les nouveautés du site, les améliorations, les corrections de bugs, etc. est disponible <a href="https://github.com/RaphaelChochon/Meteo-06/blob/master/config/changelog.md" target="_blank">ici aussi</a>.
				</p>
			</div>
			<div class="tab-pane fade" id="association">
				<br>
				<p class="text-justify">
					L'association Nice Météo 06 remercie le propriétaire de cette station pour la mise à disposition de l'électricité nécessaire, la connexion internet, et son temps libre pour l'entretien, la maintenance, etc. !</p>
			</div>
		</div>
	</div>
</div>
<!-- Début de la partie infos techniques -->
<div class="row">
	<div class="col-md-12">
		<h4>Informations techniques</h4>
		<!-- 3 ONGLETS par défaut -->
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#stationMeteo">Station météo</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#webcam">Webcam</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#foudre">Détecteur de foudre</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#serveur">Serveur</a>
			</li>
		</ul>
		<div id="tabTech" class="tab-content">
			<!-- Onglet station -->
			<div class="tab-pane fade active show" id="stationMeteo">
				<br>
				<div class="row">
					<div class="col-md-6 text-justify">
						<p>
							<h5>Infos techniques :</h5>
							<b>Modèle de station :</b> <?php echo $station_model; ?>.<br>
							<b>Date d'installation :</b> <?php echo $date_install_station; ?>.<br>
							<b>Altitude de la station :</b> <?php echo $station_altitude; ?> mètres.<br>
							<b>Position de la station :</b> <?php echo $station_position; ?>.<br>
							<b>Exposition de la station :</b> <?php echo $station_exposure; ?>.<br>
							<b>Fiche technique au format PDF :</b> <a href="fiche-station.pdf">disponible ici</a><br>
						</p>
						<p>
							<h5>Emplacement :</h5>
							Elle se situe sur la commune de <?php echo $station_commune; ?> et plus précisément [PRECISION EMPLACEMENT].<br>
							[PRECISION sur L'EMPLACEMENT des SONDES].<br>
						</p>
						<p>
							<h5>Précautions particulières :</h5>
							<?php echo $station_precautions; ?>.
						</p>
						<p>
							<h5>Système :</h5>
							Ses données sont récupérées via un <a href="https://www.raspberrypi.org/" target="_blank">Raspberry Pi</a> et le logiciel <a href="http://www.weewx.com/" target="_blank">Weewx (linux)</a> toutes les <?php echo $archive_interval; ?> minutes. Elles sont ensuite envoyées au serveur de l’association dans une base de données afin d'être affichées sur ce site.</p>
						<p>
							Toutes les images peuvent être ouvertes en grand (clic-doit et "ouvrir l'image dans un nouvel onglet")
						</p>
					</div>
					<div class="col-md-6">
						<style>
							.carouselStation {
								height: 600px;
							}
							/* .carousel-img {
								width: auto;
								max-height: 100%;
								background-color: #ffffff;
								margin: 0 auto;
							} */
							.img-carousel {
								width: auto;
								max-height: 600px;
								margin: 0 auto;
							}
						</style>
						<div id="carouselStation" class="carouselStation carousel slide bg-secondary" data-ride="carousel">
							<!-- Indicators -->
							<ol class="carousel-indicators">
								<li data-target="#carouselStation" data-slide-to="0" class="active"></li>
								<li data-target="#carouselStation" data-slide-to="1"></li>
								<li data-target="#carouselStation" data-slide-to="2"></li>
								<li data-target="#carouselStation" data-slide-to="3"></li>
								<li data-target="#carouselStation" data-slide-to="4"></li>
								<li data-target="#carouselStation" data-slide-to="5"></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img src="img/a-propos/station/station0.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Zéro photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/station/station1.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Première photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/station/station2.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Deuxième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/station/station3.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Troisième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/station/station4.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Quatrième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/station/station5.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Cinquième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
							</div>
							<a class="carousel-control-prev" href="#carouselStation" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Précédente</span>
							</a>
							<a class="carousel-control-next" href="#carouselStation" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Suivante</span>
							</a>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<h4>Emplacement de la station</h4>
						<p class="text-justify">
							Il est possible de zoomer sur cette carte et de changer le fond cartographique en utilisant le bouton en haut à droite de la carte.
						</p>
						<div id="mapLocalisation" class="mapLocalisation"></div>
						<script type="text/javascript">
							//Init map
							map = L.map('mapLocalisation', {
								center         :[<?php echo $station_coord; ?>],
								zoom           : 11,
								zoomControl    : true,
							});
							// Chargement des différents fonds carto
							var MapBox = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
								attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
								maxZoom: 18,
								id: 'mapbox.streets',
								accessToken: '<?php echo $mapbox_token; ?>'
							}).addTo(map);
							var MapBoxOutdoors = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
								attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
								maxZoom: 18,
								id: 'mapbox.outdoors',
								accessToken: '<?php echo $mapbox_token; ?>'
							});
							var MapBoxSatellite = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
								attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
								maxZoom: 18,
								id: 'mapbox.satellite',
								accessToken: '<?php echo $mapbox_token; ?>'
							});
							var OSM = L.tileLayer('https://a.tile.openstreetmap.org/{z}/{x}/{y}.png',{
								maxZoom    : 18,
								attribution:'© <a href="http://osm.org/copyright" target="_blank">OpenStreetMap</a>',
							});
							// Markers de la station
							var marker = L.marker([<?php echo $station_coord; ?>]).addTo(map);
							// Options
							map.options.maxZoom = 18;
							// Control Layers
							var baseMaps = {
								"MapBox"       :MapBox,
								"Outdoors"     :MapBoxOutdoors,
								"Satellite"    :MapBoxSatellite,
								"OpenStreetMap":OSM,
							}
							L.control.layers(baseMaps, /*overlayMaps*/).addTo(map);
						</script>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-4" align="center">
						<p>Cette station fait partie du réseau StatIC de l'association Infoclimat !</p>
						<a href="http://www.infoclimat.fr" target="_blank"><img src="img/logo_static-infoclimat.gif" width="150"></a>
					</div>
					<div class="col-md-4" align="center">
						<p>Cette station fait partie du réseau de l'association ROMMA !</p>
						<a href="http://romma.fr" target="_blank"><img src="img/logo_ROMMA.png" width="150"></a>
					</div>
					<div class="col-md-4" align="center">
						<p>Cette station fait partie du réseau de l'association E-Metsys !</p>
						<a href="http://www.e-metsys.fr" target="_blank"><img src="img/logo_e-metsys.png" width="150"></a>
					</div>
				</div>
			</div>
			<!-- Onglet webcam -->
			<div class="tab-pane fade" id="webcam">
				<div class="row">
					<div class="col-md-6 text-justify">
						<br>
						<p>
							[DESCRIPTION WEBCAM]
						</p>
						<p>
							Elle prend une photo toutes les <?php echo $webcam_refresh_1; ?> minutes, à laquelle elle ajoute les données de la station météo dans un bandeau, et qu’elle envoie ensuite sur le serveur.
						</p>
						<p>
							Un tutoriel et les scripts nécessaires à cette configuration sont disponibles <a href="https://github.com/RaphaelChochon/RPI-PiCam" target="_blank">ici</a>.
						</p>
						<p>
							Enfin, au niveau du serveur, ces images sont stockées toute la journée afin de produire un timelapse le soir permettant d'observer en quelques secondes le balai des nuages des fois très esthétique, et des fois sans intérêt.
						</p>
					</div>
					<div class="col-md-6">
						<style>
							.carouselWebcam {
								height: 500px;
							}
							.img-carousel {
								width: auto;
								max-height: 500px;
								margin: 0 auto;
							}
						</style>
						<div id="carouselWebcam" class="carouselWebcam carousel slide bg-secondary" data-ride="carousel">
							<!-- Indicators -->
							<ol class="carousel-indicators">
								<li data-target="#carouselWebcam" data-slide-to="0" class="active"></li>
								<li data-target="#carouselWebcam" data-slide-to="1"></li>
								<li data-target="#carouselWebcam" data-slide-to="2"></li>
								<li data-target="#carouselWebcam" data-slide-to="3"></li>
								<li data-target="#carouselWebcam" data-slide-to="4"></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img src="img/a-propos/webcam/webcam1.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Première photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/webcam/webcam2.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Deuxième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/webcam/webcam3.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Troisième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/webcam/webcam4.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Quatrième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/webcam/webcam5.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Cinquième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
							</div>
							<a class="carousel-control-prev" href="#carouselWebcam" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Précédente</span>
							</a>
							<a class="carousel-control-next" href="#carouselWebcam" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Suivante</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<!-- Onglet Foudre -->
			<div class="tab-pane fade" id="foudre">
				<div class="row">
					<div class="col-md-6 text-justify">
						<br>
						<h4>Fonctionnement</h4>
						<p>
							Cette station est également équipée d'un détecteur de foudre Blitzortung.
							<br>
							"Blitzortung.org" est un réseau de détection des décharges électromagnétiques dans l'atmosphère (la foudre) basé sur le temps d'arrivée (TOA).
							<br>
							Le capteur TOA est développé grâce au projet Blitzortung.org. Il se compose de plusieurs détecteurs de foudre et un serveur central de traitement. Les détecteurs transmettent leurs données à des intervalles de temps courts via Internet vers le serveur. Chaques données contient l'heure exacte d'arrivée de l'impulsion foudre reçus ("sferic") et la position géographique exacte du site. Avec ces informations les positions exactes de la foudre sont calculés. La TOA (heure d'arrivée) technique de localisation de foudre est basé sur des calculs de courbes hyperboliques. Les signaux radio émis d'une décharge de foudre se déplacent à la vitesse de la lumière, environ 300 000 kilomètres par seconde. Chaque signal reçu est horodaté sur les sites de réception. Les différences d'horodatage sont utilisés pour la production d'une courbe hyperbolique.
						</p>
					</div>
					<div class="col-md-6">
						<br>
						<img src="img/a-propos/blitz/blitz-toa.jpg" class="img-thumbnail">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-justify">
						<p>
							Le point d'intersection de toutes les courbes hyperboliques définit l'emplacement de la source du signal radio. Cette position calculée est ensuite supposée être l'emplacement du coup de foudre.
							<br>
							Au moins 8 stations sont nécessaires pour définir le plus précisément possible le croisement unique des courbes hyperboliques.
						</p>
						<p>
							Pour trouver plus d'informations sur ce projet, il exite <a href="https://www.blitzortung.fr/" target="_blank">un forum Francophone</a>, et le site mère du projet est <a href="http://fr.blitzortung.org/" target="_blank">ici (blitzortung.org)</a>.
						</p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<style>
							.carouselFoudreBlitz {
								height: 700px;
							}
							.img-carousel {
								width: auto;
								max-height: 700px;
								margin: 0 auto;
							}
						</style>
						<div id="carouselFoudreBlitz" class="carouselFoudreBlitz carousel slide bg-secondary" data-ride="carousel">
							<!-- Indicators -->
							<ol class="carousel-indicators">
								<li data-target="#carouselFoudreBlitz" data-slide-to="0" class="active"></li>
								<li data-target="#carouselFoudreBlitz" data-slide-to="1"></li>
								<li data-target="#carouselFoudreBlitz" data-slide-to="2"></li>
								<li data-target="#carouselFoudreBlitz" data-slide-to="3"></li>
								<li data-target="#carouselFoudreBlitz" data-slide-to="4"></li>
								<li data-target="#carouselFoudreBlitz" data-slide-to="5"></li>
								<li data-target="#carouselFoudreBlitz" data-slide-to="6"></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img src="img/a-propos/blitz/detecteur1.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Première photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/blitz/detecteur2.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Deuxième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/blitz/detecteur3.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Troisième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/blitz/detecteur4.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Quatrième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/blitz/detecteur5.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Cinquième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/blitz/detecteur6.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>Sixième photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/a-propos/blitz/detecteur7.jpg" class="img-fluid img-carousel img-thumbnail mx-auto d-block" alt="...">
									<div class="carousel-caption d-none d-md-block">
										<h5>xx photo</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
							</div>
							<a class="carousel-control-prev" href="#carouselFoudreBlitz" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Précédente</span>
							</a>
							<a class="carousel-control-next" href="#carouselFoudreBlitz" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Suivante</span>
							</a>
						</div>
					</div>
					<div class="col-md-6 text-justify">
						<h4>Installation</h4>
						<p>
							Ci-contre quelques photos de l'installation.
						</p>
						<p>
							Les données issues de ce détecteur de foudre sont disponibles sur <a href="https://orages.meteo06.fr/" target="_blank">orages.meteo06.fr</a>.
						</p>
					</div>
				</div>
			</div>
			<!-- Onglet Serveur -->
			<div class="tab-pane fade" id="serveur">
				<div class="row">
					<div class="col-md-12 text-justify">
						<br>
						<p>
							C'est un serveur dédié Online qui héberge ce site, avec une configuration web (Nginx, PHP-FPM 7, MariaDB, etc.).</p>
						<p>
							Les données issues de la station météo sont conservées dans une base de donnée, et permettent la création d'archives sous formes de graphiques, de tableaux etc.
						</p>
						<p>
							Les images brutes de la webcam ne sont conservées que sur une journée afin de produire un timelpase tous les soirs, et ce dernier est archivé sur un autre serveur (Kimsufi KS-2).
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

