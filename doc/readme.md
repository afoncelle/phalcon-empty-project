##############
## Docker ##

- Installer Docker Desktop : https://www.docker.com/get-started
- Ouvrir une console et se placer dans le dossier contenant le fichier *docker-compose.yml*
- Lancer les containers (crée les images si non existantes) : `docker-compose up -d` 
- Stopper les containers : `docker-compose down` 
- Se connecter à un des containers : `winpty docker-compose exec [SERVICE] bash`
Exemple : `winpty docker-compose exec php-fpm bash`

Accès web : http://localhost:2222/

Accès phpmyadmin : http://localhost:8082/

Accès mailhog : http://localhost:2223/

Récap web : http://localhost:63342/wol/phpdocker/README.html?_ijt=f99lad4dqvjtn6ej2a5v3dgrt6

##############
## Composer ##

Composer est divisé en 2 parties. Le front dans /public/libs et le back dans /app/library
Pour éxécuter composer : 
1. Se connecter au container PHP : `winpty docker-compose exec php-fpm bash`
2. Se placer dans le bon répertoire : `cd /application/public/libs`
3. Lancer composer : `composer install`

####################
## Génération PDF ##
Pour générer les PDF de plan, le container php aura besoin de la librairie Xrender
1. apt-get update && \
    apt-get install -y --no-install-recommends zlib1g fontconfig libfreetype6 libx11-6 libxext6 libxrender1


###################
## Migrations DB ##

Utilitaire : phinx
Installeur : composer
Doc : https://book.cakephp.org/phinx/0/fr/index.html
Une fois les paquets composers installés...
 
1. Se connecter au container PHP : `winpty docker-compose exec php-fpm bash`
2. se placer dans le dossier `cd /application/app/migrations`
3. Créer une migration : `../library/bin/phinx create Users`
4. Executer les migrations : `../library/bin/phinx migrate -e dev`

####################
## Créer un model ##

1. Créer la table en DB (avec phinx)
2. A la racine du projet (document root) lancer la commande phalcon model --name=[modelName] --namespace=wo
    modelName = nom du model à créer, correspondant à la table
    wo = wedding online

#####################
## Tests unitaires ##

Utilitaire : phpunit
Installeur : composer
Rédiger les tests unitaires dans le dossier tests/Unit
Execution des tests : app/library/vendor/bin/phpunit