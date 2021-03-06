## Changelog Meteo 06

### ToDo
Voir les "issues" sur GitHub : https://github.com/RaphaelChochon/Meteo-06/issues

### 1.0.1 - 21/07/2020
* Divers fix mineurs

### 1.0.0 - 14/06/2020 - Rupture
* Migration vers Bootstrap 4.4 --> refonte graphique de l'ensemble du site
* Internalisation de nombreuses librairies
* Modif des balises SEO et og
* Toutes les données "climatologiques" du site proviennent maintenant d'une BDD à part, plus de calcul à la volée : meilleures performances

#### Refonte graphique de l'ensemble du site :
* Page d'accueil :
  * Tableaux plus compacts
  * Suppression des modules RS (bye le pistage !)
* Page des graphiques 24h/48/7j
  * Possibilité de masquer les étiquettes (annotations HighCharts dans les graphs de tempé et RR)
  * Possibilité de déplacer ces mêmes étiquettes de façon indépendantes les unes des autres
* Réorganisation du menu en fonction des nouvelles pages
* Accès facilité aux rapports NOAA (rubrique climatologie)

#### Nouvelles pages :
* Page de "résumé quotidien", permettant la consultation de statistiques à la journée. Cette page comprends aussi un tableau déroulant avec tous les enregistrements au pas de temps de 10 minutes, et des graphiques couvrant la période de 18h la veille à 6h le lendemain (UTC). Un champs date permet de changer de journée.
* Pages "Climatos" :
  * Page de climatologie mensuelle :
    * Tableaux et graphiques permettant la consultation des stats d'un mois donné (Tn et Tx de toutes les journées du mois par exemple, et caclul de la TnX, TxX, etc.)
  * Page de climatologie annuelle :
    * Tableaux et graphiques permettant la consultation des stats d'une année donnée (Tn et Tx de tous les mois de l'année par exemple, et caclul de la TnX, TxX, etc.)
  * Page de climatologie globale :
    * Accès à quelques graphiques globaux sur la vie de la station, pourra évoluer
  * Page des records :
    * Refonte de la page avec des tableaux affichant les 10 dernières valeurs records pour de nombreux paramètres (et possibilité d’en afficher jusqu’à 30)
* Pages "Admin" :
  * Une première page de connexion (utilisation du module PHP-Auth) pour autoriser ou non l'accès en fonction du type de compte (cookies avec portée sur tous les sous-domaines *.meteo06.fr et only https)
  * Un index avec des cards donnant l'accès à 4 rubriques :
    * Accès aux données des sondes intérieures (température et humidité) au travers d'un mini-tableau et de graphiques
    * Accès aux statistiques de réception entre l'ISS et la console et la tension des piles de la console (si c'est une VP2) au travers de graphiques
    * Accès à un module d'export des données au format CSV. Possibilité de choisir la période couverte (max de 3 mois), le pas de temps désiré (10 min, 1h ou brut) et les paramètres météo.
    * Pour les membres de l'équipe seulement : accès à un module de modification de la bannière du site (pour annoncer une panne par exemple)

#### Modif au niveau des calculs/données
* Page d'accueil :
  * Affichage des heures en locale (seule page du site ou l'heure locale persiste)
  * Récup des min, max et cumul depuis la BDD climato_day
  * Norme OMM pour tous les calculs
* Page des graphiques 24h/48h/7j
  * Pas de temps de 5 min, 10 min ou 1 heure en fonction de la période demandée : allégement des données à charger côté client et donc meilleure fluidité
  * Améliorations au niveau des étiquettes (annotations)
* Pages de climatologie (mensuelle, annuelle, globale) : Requêtes directement dans la BDD climato_* de la station (amélioration des performanes)
* Pages de climatologie globale, comparatif de moyennes de tempé, et indice de fiabilité : Activation du mode Boost de HighCharts

### 0.15.2 - 03/09/2019
* Désactivation de la connection entre les valeurs ``null`` sur les graphiques de la page ``graphs.php``
* Ajout de deux nouvelles options horaires sur la page ``graphs.php``: 144h (6 jours) et 168h (7 jours)
* Correction d'une erreur sur le cumul d'ETP de la journée d'hier (merci PC2V)

### 0.15.1 - 07/07/2019
* Ajout du cumul mensuel sur le graphique de climatologie quotidienne des précipitations
* Modification de quelques ``zIndex`` dans les graphiques
* Agrandissement de la taille de police des labels de Tn, Tx et RR dans les graphiques horaires
* Réparation des pages ``interieur.php``, qu'il faudra également refondre...
* Ajout de deux axes Y supplémentaires sur le graphique des précipitations de la page ``graphs.php`` pour le cumul, et l’intensité des précipitations.
* Fix #14 : Ajout de la bannière d'information sur toutes les pages du site

### 0.15.0 - 30/06/2019
* Refonte de la page des graphiques sur 48h qui devient ``graphs.php`` :
    * Différents pas de temps disponibles de 24h à 120h
    * Ajout des Tn, Tx et cumul de pluie aux normes OMM sous forme d'annotations sur les graphiques
    * Refonte de l'appel aux données, qui ne passe plus par des fichiers JSON pré-générés
* Ajout d'une page de climatologie quotidienne avec graphiques
* Ajout d'une page de comparatif des différentes moyennes calculées

### 0.14.4. - 09/06/2019
* Correction dans l'affichage en page d'accueil des moyennes des directions du vent (affichage de N/A si NULL ou chaine vide)
* Divers correctifs mineurs
* Ajout d'un lien "partenaire" dans le titre via la balise ``site_manager_link`` paramétrable dans le fichier de config

### 0.14.3 - 09/12/2018
* Remplacement des "==" par "==="
* Modification dans la liste des stations du réseau

### 0.14.2 - 06/10/2018
* Diverses corrections de bugs mineurs
* Modification des tooltip des graphiques 48 heures pour adapter la couleur automatiquement
* Passage à l'UTC pour les graphiques 48 heures

### 0.14.1 - 09/04/2018
* Fix typo page d'accueil

### 0.14.0 - 18/03/2018
* Fix direction du vent
    * Calcul des directions moyennes sur 10 min rectifiées
    * Ajout du calcul de la direction moyenne sur 1 heure
    * Modification de la page d'accueil pour afficher les vitesses et direction du vent dans un tableau séparé

### 0.13.3 - 08/03/2018
* Fix temperature max sur la page de la journée d'hier

### 0.13.2 - 22/12/2017
* Révision de la page "A propos"
    * Ajout d'une carte de localisation de la station
    * Inclus l'ajout de deux nouveaux paramètres de config : ``$station_coord`` & ``$mapbox_token``
* Ajout de la licence Creative Commons 4.0 International (CC BY-NC-SA 4.0)
    * Attribution
    * Pas d’Utilisation Commerciale
    * Partage dans les Mêmes Conditions

### 0.13.1 - 19/12/2017
* Révision de la page "A propos"
    * Ajout de la position et de l'exposition sous le conseil de P. CARREGA (``$station_position`` & ``$station_exposure``)
    * Ajout  d'un champ de "précautions particulières" vis à vis des données (``$station_precautions``) permettant d'indiquer si une possible sur ou sous estimation en fonction des conditions d'installations de la station est possible
    * Ajout d'un champ "commune de la station" pour indiquer la commune d'installation de la station (``$station_commune``)
* Modification mineure du fichier ``humans.txt``

### 0.13.0 - 18/12/2017
* Adaptation connexion MySQLi pour la compatibilité PHP 7

### 0.12.1 - 30/07/2017
* Ajout pour les VP2 (disposant du rayonnement solaire - solution encore bancale, à revoir!) d'un graphique de qualité de réception (à partir de la valeur ``rxCheckPercent``) sur la page ``interieur.php`` (#6)
* Changement du sample de menu additionnel (ajout de Nice Pessicart VP2 et de Vence en prévision...)
* Quelques modifications mineures au niveau du script d'installation ``install/script_install.sh``

### 0.12.0 - 30/05/2017
* Ajout de la possibilité d'exporter les données des graphiques 48 heures en CSV, XLS, ou simplement d'afficher les données brutes dans un tableau directement sous le graphique.
* Modification du code source du plugin ``export-data`` de Highcharts pour laisser la possibilité de masquer le tableau une fois qu'il est affiché sous le graphique, en recliquant sur le bouton. Voir => https://github.com/highcharts/export-csv/issues/102

### 0.11.1 - 14/05/2017
* Ajout des moyennes sur 10min pour le vent, les UV et le rayonnement
* Ajout des directions du vent en texte en + des degrès (N, NNO, NO, etc.)
* Ouverture des rapports TXT NOAA dans un nouvel onglet

### 0.11.0 - 05/04/2017
* Rédaction du readme avec premières étapes d'installation, à finir !
* Ajout d'une redirection des tableaux récaps mois/mois et année/année vers les rapports NOAA
* Mise en conformité avec le W3C
* Retouches mineures du fichier de config

### 0.10.0 - 28/03/2017
* Ajout d'un exemple pour la page "A propos" de la station, avec intégration d'onglets et de "carousel" Bootstrap pour intégrer quelques photos.

### 0.9.11 - 26/03/2017 bis
* Correction image de partage Twitter
* Ajout d'un fichier ``robots.txt``
* Ajout de ``noindex`` et ``nofollow`` sur la page ``interieur.php`` (http://robots-txt.com/meta-robots/)
* Ajout ``humans.txt``
* Alignement des paramètres dans le fichier ``config-SAMPLE.php``, bien + propre et + accessible. Envisager de le faire systématiquement pour les fichiers CSS etc... (Rappel : Ctrl+Alt+A pour ST3)

### 0.9.10 - 26/03/2017
* Correctif og:url (debug : https://developers.facebook.com/tools/debug/)
* Support du SSL pour URL canonique et og:url en ajoutant un paramètre dans le fichier de config pour savoir si le site est en HTTPS ou pas (``$SSL``)
* Redimensionnement de l'image de partage et ajout des balises META ``og:image:type``, ``og:image:width`` et ``og:image:height`` pour précharger l'image au moment du partage fb/tw etc. (https://developers.facebook.com/docs/sharing/best-practices/#precaching)

### 0.9.9 - 25/03/2017 bis
* Correctif des URLs canoniques qui étaient fausses (http://www.scriptol.fr/scripts/canonical.php)

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
