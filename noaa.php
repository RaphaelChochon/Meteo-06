<?php require_once __DIR__ . '/include/access_rights.php';?>
<?php require_once __DIR__ . '/config/config.php';?>
<?php require_once __DIR__ . '/sql/connect_pdo.php';?>
<?php require_once __DIR__ . '/sql/import.php';?>
<?php require_once __DIR__ . '/include/functions.php';?>
<!DOCTYPE html>
<html lang="fr-FR" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<title><?php echo $short_station_name; ?> | NOAA</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Balises META SEO pour le referencement Google, Facebook Twitter etc. -->
		<meta name="description" content="Fichiers NOAA de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>"/>
		<link rel="canonical" href="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:locale" content="fr_FR" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $short_station_name; ?> | NOAA" />
		<meta property="og:description" content="Fichiers NOAA de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta property="og:url" content="<?php if ($SSL){echo'https://';}else echo'http://';?><?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>" />
		<meta property="og:site_name" content="<?php echo $short_station_name; ?>" />
		<meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
		<meta property="og:image" content="<?php echo $url_site; ?>/img/capture_site.jpeg" />
		<meta property="og:image:type" content="image/jpeg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Fichiers NOAA de la station <?php echo $station_name; ?> <?php echo $hashtag_meteo; ?>" />
		<meta name="twitter:title" content="<?php echo $short_station_name; ?> | NOAA" />
		<meta name="twitter:site" content="<?php echo $tw_account_name; ?>" />
		<meta name="twitter:image" content="<?php echo $url_site; ?>/img/capture_site.jpg" />
		<meta name="twitter:creator" content="<?php echo $tw_account_name; ?>" />
		<!-- Fin des balises META SEO -->
		<?php include __DIR__ . '/config/favicon.php';?>
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
		<script defer src="content/bootstrap/js/popper-1.16.0.min.js"></script>
		<script defer src="content/bootstrap/js/bootstrap-4.4.1.min.js"></script>

		<!-- Font Awesome CSS -->
		<link href="content/fontawesome-5.13.0/css/all.min.css" rel="stylesheet">

		<script type="text/javascript">
			function openNoaaFileMonth(file_month){
				var yearNoaaForm = file_month.substring(0,4);
				var monthNoaaForm = file_month.substring(5,7);
				var url = "noaa.php?yr="+yearNoaaForm+"&mo="+monthNoaaForm+"#anchorReport";
				window.location.href = url;
			}
			function openNoaaFileYear(file_year){
				var url = "noaa.php?yr=";
				url = url + file_year + "#anchorReport";
				window.location.href = url;
			}
			// Get the URL variables. Source: https://stackoverflow.com/a/26744533/1177153
			function getURLvar(k) {
				var p={};
				location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v});
				return k?p[k]:p;
			}
		</script>
	</head>
	<body>
		<div class="container">
			<header>
				<?php include __DIR__ . '/header.php';?>
			</header>
			<br>
			<nav>
				<?php include __DIR__ . '/nav.php';?>
			</nav>
			<br>

			<!-- DEBUT DU CORPS DE PAGE -->
			<!-- Bannière infos -->
			<?php if ($banniere_info_active) : ?>
				<div class="alert alert-<?php echo $banniere_info_type; ?>">
					<h4 class="alert-heading"><?php echo $banniere_info_titre; ?></h4>
					<hr>
					<p class="mb-0"><?php echo $banniere_info_message; ?></p>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">Rapports climatologiques de la station au format NOAA</h3>
					<!-- START module en ligne/Hors ligne -->
					<h3 <?php if ($diff>$offline_time){echo'class="offline_station"';}echo'class="textOnlineStation text-center"';?>>
						Derniers relevés de la station le <?php echo $date; ?> à <?php echo $heure; ?>
					</h3>
					<?php if ($diff>$offline_time) : ?>
						<h4 class="textOfflineStation text-center">
							Station actuellement hors ligne depuis
							<?php echo $jours; ?> jour(s) <?php echo $heures; ?> h et <?php echo $minutes; ?> min
						</h4>
					<?php endif; ?>
					<!-- FIN module en ligne/Hors ligne -->
				</div>
			</div>

			<script>
				jQuery(document).ready(function() {
					var monthUrl = getURLvar("mo");
					var yearUrl = getURLvar("yr");
					function month2digits(month){ 
						return (month < 10 ? '0' : '') + month;
					}
					var date = new Date(); // date object
					var yearNow = date.getUTCFullYear();
					var month2digits = month2digits(date.getMonth()+1); // get month in two digits 

					if ( ( yearUrl !== undefined ) && ( monthUrl !== undefined ) ) {
						url = 'NOAA/raw/month/NOAA-'+yearUrl+'-'+monthUrl+'.txt';
					} else if ( yearUrl !== undefined ) {
						url = 'NOAA/raw/year/NOAA-'+yearUrl+'.txt';
					} else {
						url = 'NOAA/raw/month/NOAA-'+yearNow+'-'+month2digits+'.txt';
					}
					
					// Load the file into the pre
					populatePre( url );
					
					// Change the direct href link
					jQuery(".noaa_direct_link").attr( "href", url );
				});
				
				// Change the div to the right NOAA file
				// I normally use PHP for this, but JavaScript seems to work well for the skin
				// Source: https://stackoverflow.com/a/18933218/1177153
				function populatePre(url) {
					var xhr = new XMLHttpRequest();
					xhr.onload = function () {
						raw_content = this.responseText;
						updated_content = raw_content.replace('<sup>','').replace('</sup>','').replace('&deg;','');
						document.getElementById('noaa_contents').textContent = updated_content;
					};
					xhr.open('GET', url);
					xhr.send();
				}
			</script>

			<div class="row">
				<div class="col-md-12">
				<p class="text-justify">
					Vous pouvez via les listes déroulantes ci-dessous, accéder aux rapports climatologiques mensuels et annuels bruts de la station au format "NOAA". Ce sont des fichiers texte très simple qui sont mis à jours tous les quarts d'heures pour le rapport mensuel en cours
				</p>
				<br>
				<h5 class="text-center">Rapports mensuels :</h5>
				<div class="text-center">
					<select name="Month" onchange="openNoaaFileMonth(value)">
						<?php
							$path = "NOAA/raw/month";
							$blacklist = array('.','..');
							// get everything except hidden files
							$files = preg_grep('/^([^.])/', scandir($path));
							// boucle
							foreach ($files as $file) {
								if (!in_array($file, $blacklist)) {
									$properName = substr("$file", 5, 7);
									echo '<option value="',$properName,'">',$properName,'</option>';
								}
							}
						?>
						<option selected value="#">- Selectionnez le mois -</option>'
					</select>
				</div>
				<hr>
				<h5 class="text-center">Rapports annuels :</h5>
				<div class="text-center">
					<select name="Year" onchange="openNoaaFileYear(value)">
						<?php
							$path = "NOAA/raw/year";
							$blacklist = array('.','..');
							// get everything except hidden files
							$files = preg_grep('/^([^.])/', scandir($path));
							// boucle
							foreach ($files as $file) {
								if (!in_array($file, $blacklist)) {
									$properName = substr("$file", 5, 4);
									echo '<option value="',$properName,'">',$properName,'</option>';
								}
							}
						?>
						<option selected value="#">- Selectionnez l'année -</option>'
					</select>
				</div>
			</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12" id="anchorReport">
					<h5 class="text-center">Affichage du rapport :</h5>
					<p class="text-center">Ouvrir ce rapport dans une nouvelle fenêtre <a href="#" class="noaa_direct_link" target="_blank">en cliquant ici</a></p>
					<div class="bg-light p-3">
						<pre id="noaa_contents"></pre>
					</div>
				</div>
			</div>
			<hr>

			<footer class="footer bg-light">
				<?php include __DIR__ . '/footer.php';?>
			</footer>
		</div>
	</body>
</html>