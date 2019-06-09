Météo06
=======

## Préambule
Ce site a été développé dans le but de créer un point d'accès (fiable, fluide et moderne) aux données des stations météos de notre réseau. Toutes les stations de notre réseau sont équipées d'un Raspberry Pi sur lesquels est installé [Weewx](https://github.com/weewx/weewx), un logiciel permettant de se connecter à la console de la station et ainsi de récupérer les données. Nous l'avons configuré pour qu'il envoi les données sur une base de données MySQL sur un serveur externe.

## Requis
* Une station météo fonctionnant déjà avec Weewx
* Un serveur (ou le Raspberry Pi lui-même) avec installé :
    * Pour le web : Apache ou NGINX
    * Une base de données MySQL
    * PHP 7.x (PHP 5.x non testé)
* Et donc que Weewx soit connecté à cette BDD MySQL

> **Note :**
> Ce modèle de site fonctionne donc avec une connexion à une base de données alimentée par Weewx. "Out of the box" il ne fonctionnera pas avec une BDD SQLite, ni une BDD MySQL alimentée par un autre logiciel (sauf si la structure correspond exactement à celle de [Weewx, et donc de Wview](http://www.weewx.com/docs/customizing.htm#archive_database).

## Téléchargements & installation
### Méthode traditionnel via un zip
> **Note :**
> Cette méthode est très simple d'accès, mais elle ne rendra pas possible la mise à jour du site par la suite, ce qui est fort dommage...

Vous trouverez l'archive la plus récente du site dans les [releases du répertoire GitHub](https://github.com/RaphaelChochon/Meteo-06/releases). Le changelog de chaque version est disponible [ici](https://github.com/RaphaelChochon/Meteo-06/blob/master/config/changelog.md).

En cas de mise à jour, il suffit de sauvegarder les différents fichiers de configuration du site, ensuite supprimer le répertoire complet du site, et reprocéder à l'installation, pour enfin replacer les fichiers de configuration à leur emplacement. C'est une solution qui peut s'avérer laborieuse, c'est pourquoi il est préférable d'utiliser la méthode avec Git comme expliqué ci-dessous.

### Méthode "plus complexe" avec Git
> **Note :**
> Cette méthode à l'avantage de simplifier par la suite la mise à jour du site, par contre elle impose de ne modifier **QUE** les fichiers de configuration.

Avec cette méthode, il suffit de cloner le répertoire GitHub dans votre répertoire web.
Pour cela il faut avoir installer ``Git`` sur le serveur :
```
sudo apt update && sudo apt install git
```
Puis :
```
sudo git clone https://github.com/RaphaelChochon/Meteo-06.git /var/www/mon_site_meteo
```

**Le site est maintenant déployé !**

L'accès à la page d'accueil du site vous renverra normalement une erreur et c'est normal il n'est pas encore installé ni configuré.
Pour cela, il suffit de se rendre dans le répertoire ``install`` du site et d'exectuter un script d'installation qui va finir de déployer les fichiers :
```
cd /var/www/mon_site_meteo/install/
sudo sh script_install.sh
```
> **Note :**
> Il est important de se déplacer jusqu'au dossier d'installation certains chemins du script étant relatif à ce même dossier.

## Configuration

A ce stade le site est quasiment fonctionnel, il va maintenant falloir le connecter à votre base de données, changer le nom du site et quelques autres paramètres.
Toutes ces informations sont regroupées dans un seul et même fichier de configuration ``config/config.php``.

### Connexion à la BDD
La première étape consiste à connecter le site à une base de données **au format "weewx"**.
Pour cela, au début du fichier, 4 variables sont à modifier :
- ``server`` : qui est l'adresse de l'hôte de la base de données. Par exemple ``localhost`` ou une adresse IP ou une adresse web ;
- ``user`` : le nom d'utilisateur qui a accès à la BDD **en lecture seule** ! Pas besoin d'un utilisateur en écriture, ce serait un gros risque pour vos données ;
- ``pass`` : le mot de passe de cet utilisateur ;
- ``db_name`` : le nom de la base de données. Par défaut Weewx la nomme ``weewx`` ;
- ``db_table`` : Ici il s'agit du nom de la première table contenant tous les enregistrements. Par défaut Weewx la nomme ``archive``.

> **Note :**
>A cette étape si vous enregistrez votre fichier de configuration tel quel, votre site devrait pouvoir s'afficher, et afficher les derniers enregistrements disponibles.

### Configuration générale
Dans la suite du fichier de configuration se trouve de nombreux paramètres qu'il est possible de modifier comme bon vous semble. L'ensemble de ces paramètres sont suivis d'une explication (en Anglais mais facilement traduisible).

> **:exclamation: Attention :exclamation:**
> Il faut faire attention à la sémantique de ce fichier, la moindre erreur pouvant provoquer une page blanche et un message d'erreur générale.
> Il faut veiller à ce que chaque paramètre soit suivi d'un point virgule par exemple.

**Quelques notes supplémentaires :**

- Le paramètre ``url_site`` n'est pas primordiale au fonctionnement du site, mais peut ensuite à court terme nuire à la visibilité et au référencement de votre site sur les pages de résultats des différents moteurs de recherches tels que Google. Il sert à construire ce que l'on appelle l'URL canonique de chaque page ;
- Le paramètre ``date_install_station`` permet à certains calculs de s’arrêter à cette date, il est donc relativement important de l'indiquer ;
- Le paramètre ``extension_logo`` dépendra du logo de votre site, si c'est un jpg, jpeg, un png etc. (voir :green_book: [Logo](#logo)) ;
- Le paramètre ``presence_webcam`` va influencer l'affichage ou non de la page "Webcam" dans le menu du site. Pour plus de détails sur la configuration d'une ou deux webcams et du ou des timelapses, voir plus pas sur cette documentation :green_book: [Webcam](#webcam) ;
- Au paragraphe ``SONDES`` :
-- Les paramètres ``presence_uv`` et ``presence_radiation`` permettent d'indiquer si votre station disposent d'une de ces sondes ;
-- Le paramètre ``timestamp_maj_weewx_3_6_0`` ne concerne que les stations possédant les sondes UV et rayonnement solaire qui calculent l'évapotranspiration. Avant la version 3.6.0 de Weewx, l'ET calculée était inexacte ;
- Le paramètre ``enable_web_analytics``, même s'il est à ``true`` n'aura pas de grande influence sans modification du fichier concerné, pour cela pour le paragraphe de la documentation ci-dessous concernant les outils d'analyses du trafic web :green_book: [Web analytics](#webanalytics) ;
- Les paragraphes ``FACEBOOK``, ``TWITTER`` et ``HASHTAG`` concernent des paramètres SEO (référencement web) détaillés dans un paragraphe de la documentation ci-dessous :green_book: [SEO](#SEO).

### Graphiques dynamiques

### Personnalisations
#### Logo

#### Webcam

#### Webanalytics

#### SEO
