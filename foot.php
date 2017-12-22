<div class="row">
	<div class="col-md-12 divCenter">
		<div class="well">
			<p>Maintenance de la station par <a href="<?php echo $site_manager_graph; ?>" target="blank"><?php echo $name_manager_footer; ?></a><br>
			<a href="mentions-leg.php"> Mentions légales et réserves de responsabilités</a><br>
			Pour nous contacter : <?php echo $contact_mail_footer; ?><br>
			Modèle de station : <?php echo $station_model; ?> | Altitude : <?php echo $station_altitude; ?> mètres<br>
			Position : <?php echo $station_position; ?> | Exposition : <?php echo $station_exposure; ?> | <a href="a-propos.php">+ d'infos</a><br>
			Version du site : <?php echo $version_site; ?> - <a href="https://github.com/RaphaelChochon/Meteo-06/blob/master/config/changelog.md" target="blank">Changelog</a></p>
			<a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.fr" target="_blank"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
		</div>
	</div>
</div>
<?php if ($enable_web_analytics == true){
	include 'config/web_analytics.php';
};?>
