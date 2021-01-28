# todolist-v2
Améliorez une application existante de ToDo &amp; Co

Réalisé en PHP 7.3.12 et Symfony 5.2.1
<hr />
<a href="https://codeclimate.com/github/glerique/todolist-v2/maintainability"><img src="https://api.codeclimate.com/v1/badges/fd44a9c7fc3acc4a7d88/maintainability" /></a>
<hr />

Installer l'application

    - Clonez le repository GitHub
    
    - Configurez vos variables d'environnement dans le fichier .env :    
      
      => DATABASE_URL=mysql://root:@127.0.0.1:3306/todolist?serverVersion=5.7 pour la base de données
      
    - Téléchargez et installez les dépendances du projet avec la commande Composer suivante : composer install
    
    - Créez la base de données en utilisant la commande suivante : php bin/console doctrine:database:create
    
    - Créez la structure de la base de données en utilisant la commande : php bin/console doctrine:migrations:migrate
    
    - Installer les fixtures pour avoir un jeu de données fictives avec la commande suivante : php bin/console doctrine:fixtures:load


<hr />

Lancer L'application
	    
    - Lancez le serveur à l'aide de la commande suivante : php -S localhost:8000 -t public
    
    - Vous pouvez désormais commencer à utiliser l'appication Bilemo sur http://localhost:8000
    
    - Vous pouvez lancer les tests unitaires et fonctionnels avec la commande : php bin/phpunit --coverage-html docs/coverage  
    

<hr />

Utilisateurs par défaut 
	    
    - Administrateur : 
        username : admin
        password : password
    
    - Utilisateur : 
        username : user1
        password : password
    
    
