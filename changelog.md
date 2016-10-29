## Changelog Meteo 06

### ToDo
* Graphiques dynamiques sur 48 heures
* Graphique Highstock pour consultation des archives au dela des 48 dernières heures
* Tableau récap pour chaque mois de l'année, et pour chaque année
* Remplir la page a-propos.php (a voir comment la remplir avec le fichier de config)
* Réduire le logo en mode mobile (plutôt que d'en afficher un autre plus petit comme actuellement)



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
