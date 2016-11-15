<div class="row">
	<div class="col-md-12" align="center">
		<div class="well">
			<p>Maintenance de la station par <a href="<?php echo $site_manager_graph; ?>" target="blank"><?php echo $name_manager_footer; ?></a><br>
			<a href="mentions-leg.php"> Mentions légales et réserves de responsabilités</a><br>
			Pour nous contacter : <?php echo $contact_mail_footer; ?><br>
			Modèle de station : <?php echo $station_model; ?><br>
			Version du site : <?php echo $version_site; ?></p>
		</div>
	</div>
</div>
<?php if ($enable_web_analytics == true){
	include 'config/web_analytics.php';
};?>
