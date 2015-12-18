# Modification des fichiers php.ini dans PHP et Apache
Il faut modifier les fichiers php.ini dans le dossier PHP et Apache car tmbd a besoin du module curl

`C:\wamp\bin\apache\apache2.4.9\bin\php.ini`

`C:\wamp\bin\php\php5.5.12`

Enlever le `;` devant la ligne `extension=php_curl.dll`

Chercher la ligne Le temps max d'exécution du PHP doit être augmenter à 7200 `max_execution_time = 7200` Le temps max de parse des données doit être augmenter à 120 par sécurité `max_input_time = 120` Le capacité mémoire max doit être monté à 1024 Mo `memory_limit = 1280M`

# Marche à suivre pour l'archive
Exéctuez zwamp.exe qui est à la racine Ensuite ouvrir le navigateur et entrer l'url **localhost** ou **127.0.0.1**

# Modification du fichier project_root.php
De base il n'est pas utile de modifier le chemin d'accès (de base `define('ROOT_PATH', 'Z:/web');`) mais si l'utilisateur décide de changer de chemin, il doit le spécifier dans le fichier `project_root.php`
