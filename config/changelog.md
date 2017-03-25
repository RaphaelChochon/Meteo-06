## Changelog Meteo 06

### ToDo
* Tableau récap pour chaque mois de l'année, et pour chaque année
* Proposer un SAMPLE plus fourni pour la page a propos avec par exemple une petite librairie permettant d'afficher des photos de manière pratique et jolie
* HighMaps pour les mois
* Ajout d'un export de graphs miniatures pour mettre en signature par exemple sur IC ou autres (idée Thibaut Valbonne)
* Highstock - variation des températures journalières
* Faire une page avec graphiques rxcheckpourcent etc
* "Mode scientifique" avec vitesse vent en m/s etc.

### 0.9.9 - 25/03/2017 bis
* Correctif des URLs canoniques qui étaient fausses

### 0.9.8 - 25/03/2017
* Modification de la largeur et de la hauteur de l'image à l'exportation des graphs 48h (1200*400px)
* Ajout de différentes balises META pour aider le SEO et les partages Facebook et Twitter sur toutes les pages.
* Ajout de paramètres dans le fichier de config pour supporter les nouvelles META (``$fb_app_id``, ``$tw_account_name``, ``$hashtag_meteo``, ``$url_site``)
* Modification du header afin de suppr la duplication du doctype

### 0.9.7 - 16/03/2017
* Ajout de l'altitude dans le bas de page et dans les graphiques
* Ajout des jours dans le mode offline de la station
* Suppression de la décimale pour le graph 48h du rayonnement solaire

### 0.9.6 - 14/03/2017
* Ajout d'un répertoire ``NOAA`` pour stocker les fichiers txt générés par Weewx (rapports climato mensuelles et annuelles)
* Ajout d'une page ``noaa.php`` permettant de consulter la liste des fichiers NOAA générés par Weewx, et d'y accéder

### 0.9.5 - 13/03/2017
* Ajout des précipitations sur 1, 3, 6 et 12 heures
* Réagencement des précipitations dans deux onglets différents pour prendre moins de place
* Correctif de l'ET de la page des records
* Divers correctifs concernant l'affichage des N/A (UV et rainRate)

### 0.9.4 - 01/03/2017
* Correctif d'une coquille dans ``img_resume_250.php``
* Affichage de la mention 'N/A' sur le tableau des valeurs actuelles quand un problème survient au niveau de la station et qu'elle renvoit des valeurs 'null' en BdD

### 0.9.3 - 06/02/2017
* Correctif d'une coquille dans ``sql/import.php``
* Ajout du widget de vigilance de MF dans un fichier à part qui est donc complètement personnalisable
* Ajout du contenu de la page ``a-propos.php`` dans une autre page ``config/a-propos.php`` la rendant elle aussi complètement personnalisable en fonction des besoins. Cela facilitera les modifications vu que cette page est spécifique à chaque station
* Petite modification dans le menu additionnel

### 0.9.2 - 31/01/2017
* Correctif de petites coquilles dans le texte
* Ajout d'un tableau des dernières valeurs des sondes intérieures
* Ajout d'une image générée en PHP de 250*175px intégrant un (très) bref résumé des valeurs de la station. Peut être intégrée n'importe ou via une balise html img (but d'intégration dans la map via une popup)

### 0.9.1 - 06/01/2017
* Ajout de la station de Valbonne dans le menu additionnel
* Changement du message d'avertissement à l'attention des utilisateurs mobiles pour la page d'archive (graphique très long à charger et non adapté à la consultation mobile)

### 0.9.0 - 02/01/2017
* Graphique Highstock pour consultation de toutes les archives de la station

### 0.8.1 - 02/01/2017
* Fix ``.gitignore`` du dossier img qui causait problème si changement du logo

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
