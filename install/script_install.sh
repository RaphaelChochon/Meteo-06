#!/bin/bash

echo
echo "== Script avec confirmation =="
echo

confirm()
{
	read -r -p "${1} [y/N] " response

	case "$response" in
		[yY][eE][sS]|[yY])
			true
			;;
		*)
			false
			;;
	esac
}

if confirm "Nous allons reconfigurer le site en vous demandant au cas par cas si vous voulez réinitialiser les fichiers de configurations à leur valeur d’origine. Cela aura pour conséquence de perdre vos personnalisations. Vous êtes prêts ?"; then
	echo "Ok nous allons commencer :"
	cd ../config/
	cp -iv config-SAMPLE.php config.php
	cp -iv json_cron-SAMPLE.sh json_cron.sh
	cp -iv json_archives_cron-SAMPLE.sh json_archives_cron.sh
	cp -iv web_analytics-SAMPLE.php web_analytics.php
	cp -iv additional_menu-SAMPLE.php additional_menu.php
	cp -iv res_sociaux-SAMPLE.php res_sociaux.php
	cp -iv widget_vigi-SAMPLE.php widget_vigi.php
	cp -iv a-propos-SAMPLE.php a-propos.php
	chmod +x json_cron.sh json_archives_cron.sh
	cd ../img/
	cp -iv logo-SAMPLE.jpg logo.jpg
else
	echo "Pas grave, nous verrons ça plus tard alors ! ;)"
fi