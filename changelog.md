## Changelog Meteo 06

### ToDo
* Graphiques dynamiques sur 48 heures
* Graphique Highstock pour consultation des archives au dela des 48 dernières heures
* Tableau récap (comme sur la page d'accueil) des 7 derniers jours glissants, 30 jours glissants et 365 jours glissants
* Tableau récap pour chaque mois de l'année, et pour chaque année
* Remplir la page a-propos.php (a voir comment la remplir avec le fichier de config)
* Réduire le logo en mode mobile (plutôt que d'en afficher un autre plus petit comme actuellement)



### 0.3 -
* Ajout de paramètres UV et rayonnement dans le fichier de conf, selon si la station est équipée de la sonde ou non
* Ajout de l'évapotranspiration, mais problème avec son calcul sur la journée

### 0.2 - 17/10/2016
* Ajout du fichier de configuration (config.php) incluant les paramètres généraux du site et les paramètres de connexion à la BDD MySQL de Weewx.

### 0.1 - 12/10/2016
* Première mise en place du site pour la station de Nice Nord
* Tableau récap sur la journée en cours en page d'accueil (script PHP qui va piocher dans la BDD Weewx à chaque appel de la page).
* Intégration de header, nav et footer via php include
* Ajout d'une page webcam.php affichant la dernière image de la webcam, et le timelapse de la veille
