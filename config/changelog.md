## Changelog Meteo 06

### ToDo
* Graphique Highstock pour consultation des archives au dela des 48 dernières heures
* Tableau récap pour chaque mois de l'année, et pour chaque année
* Remplir la page a-propos.php (a voir comment la remplir avec le fichier de config)
* Réduire le logo en mode mobile (plutôt que d'en afficher un autre plus petit comme actuellement)
* Mettre le logo en paramètre afin de pouvoir personnaliser le site en fonction de la loc
* Favicon
* Piwik en params

### 0.4 - 12/11/2016
* Ajout du jour le plus pluvieux dans les records
* Changement du système de numéro de version du site
* Récupération du pas de temps de l'archivage dans la colonne "interval" en BDD plutot que de la rentrer manuellement dans le fichier de conf
* Ajout d'une page tableau récap pour la veille "tableau_hier.php"
* Ajout en paramètres de nom et de l'URL de l'asso qui apparait dans les crédits des graphiques
* Ajout de quelques paramètres supplémentaires dans le makejson_48h
* Ajout de graphiques highcharts sur 48 heures (Température-Humidité, Pression, Vent, Précipitations, UV, Rayonnement solaire, Évapotranspiration)
* Ajout plotBands et plotLines à minuit sur graph de temp
* Bannière informative en page d'accueil en paramètres, activable et modifiable à souhait.

### 0.3 - 29/10/2016
* Ajout de paramètres UV et rayonnement dans le fichier de conf, selon si la station est équipée de la sonde ou non
* Ajout de l'évapotranspiration mais nécéssite Weewx en version minimum 3.6.0 (OK pour Clans en 3.6.1), sinon le cumul journalier est complètement aberrant
* Ajout du modèle de station dans les params et dans le footer
* Ajout des pages récaps 7 et 30 jours
* Ajout du paramètre de date de l'installation de la station (de la mise en service de la BDD MySQL Weewx)
* Ajout du paramètre de date de MAJ vers Weewx 3.6.0. Avant cette date l'ET était mal calculée et est donc inutilisable. On inclus donc ce paramètre dans les requetes des tableaux récaps 7, 30 jours etc... pour ne pas prendre en compte les valeurs avant cette date.
* Ajout de la page record

### 0.2 - 17/10/2016
* Ajout du fichier de configuration (config.php) incluant les paramètres généraux du site et les paramètres de connexion à la BDD MySQL de Weewx.

### 0.1 - 12/10/2016
* Première mise en place du site pour la station de Nice Nord
* Tableau récap sur la journée en cours en page d'accueil (script PHP qui va piocher dans la BDD Weewx à chaque appel de la page).
* Intégration de header, nav et footer via php include
* Ajout d'une page webcam.php affichant la dernière image de la webcam, et le timelapse de la veille