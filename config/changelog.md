## Changelog Meteo 06

### ToDo
* Graphique Highstock pour consultation des archives au dela des 48 dernières heures
* Tableau récap pour chaque mois de l'année, et pour chaque année
* Remplir la page a-propos.php (a voir comment la remplir avec le fichier de config)
* HighMaps pour les mois
* Ajout d'un export de graphs miniatures pour mettre en signature par exemple sur IC ou autres (idée Thibaut Valbonne)
* Highstock - variation des températures journalières


### 0.8.0 - 24/12/2016 - Cadeau de Noël
* Ajout d'une page "interieur.php" avec des graphiques sur 48h, 7jours et 30 jours de la température et humidité intérieur.

### 0.7.0 - 21/12/2016
* Ajout de la possibilité de changer le format d'extension du fichier logo en png, jpg ou jpeg etc.
* Fix : Remodelage de la page des webcams pour les timelapses
* Déplacement du chargement des ressources CSS et JS dans le header au profit de l'affichage et au dépens de certains benchmark (ressources appelées dans le header et non plus en fin de body)

### 0.6 - 10/12/2016
* Ajout d'une page ``web_analytics.php`` permettant d'ajouter un code de suivi sur toutes les pages du site
* Modifications pour l'ET qui est finalement retournée chaque heure directement par la VP2 et non calculée à chaque interval par Weewx - Changement du graph en colonne - Changement de l'affichage en page d'accueil
* Remodelage de la page des webcams
* Mise en params d'un onglet supplémentaire dans le menu de navigation, paramétrable via un fichier "config/additional_menu.php"
* Ajout dans le script d'install de la copie auto des fichiers SAMPLE pour faciliter la première install
* Ajout d'une définition pour l'ET via popover
* Le logo n'est maintenant chargé plus qu'une seule fois, et est réduit en CSS si affichage mobile - En modifiant l'image ``img/logo.jpg`` on peut ainsi modifier son logo et afficher ce que l'on veut.

### 0.5 - 13/11/2016
* Déplacement de l'ensemble des fichiers de configuration dans un dossier "config" pour simplifier et faciliter la configuration du site
* Correction du dossier "json" qui n'était plus créé à l'installation mais qui est indispensable
* Ajout de plusieurs favicons
* Ajout d'un système pour repérer facilement si la station est en-ligne ou hors-ligne. Si hors-ligne, affichage en rouge de la dernière date d'actualisation et d'un message avertissant depuis combien d'heures et minutes elle est hors-ligne
* Ajout du widget de vigilance Météo-France d'InfoClimat
* Ajout du radar de pluie d'InfoClimat, paramétrables via le fichier de config
* Ajout des réseaux sociaux à côté de l'image radar (Facebook et Twitter) personnalisable via un fichier à part

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
