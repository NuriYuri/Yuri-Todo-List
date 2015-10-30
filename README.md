# Yuri-Todo-List
Petit gestionnaire de TODO :3

## Description
Gestionnaire d'objectif basé sur une interface web simple utilisant bootstrap.
## Features
### Gestion de droit d'utilisateurs
Dans configuration.php
* <b>$_LOGIN_TABLE</b> associe un nom d'utilisateur à son mot de passe. (Il doit être inséré par l'administrateur et est totalement clair donc ne mettez rien de personnel ou simpliste.)
* <b>$_RIGHT_TABLE</b> associe un nom d'utilisateur à ses différents droits.

### BBCode minimaliste
Des balises BBCode converties à la volée en HTML permettent de donner un peu de style aux différents objectifs.
Je n'ai pas intégré la fermeture des balises alors attention aux mauvaises surprises :)

## Particularité
### Pas de SQL
J'avais pas trop envie de m'embêter avec le SQL alors il n'y en a pas mais attention aux hébergeurs qui n'aiment pas l'écriture de fichiers.<br/>
Si votre TODO List change d'état autant que la section Taverne d'un forum, je vous conseille de reprendre l'enregistrement et la lecture des objectifs (voir d'utiliser autre chose :d).

## Utilisation
### Configuration
Si vous désirez loguer les actions importantes des utilisateurs mettez <b>$write_log</b> à true.<br/>
Pensez à configurer <b>$_LOGIN_TABLE</b> & <b>$_RIGHT_TABLE</b>. Si les tables sont incohérentes il se peut qu'un utilisateur ne puisse pas se connecter.
Le nom de fichier TODO est contenu dans <b>$_TODO_FILENAME</b>, par défauts tous les fichiers .txt sont interdit d'accès.
#### Droits
* see : Permet de voir la liste des objectifs et accessoirement d'utiliser l'interface.
* push : permet d'ajouter un objectif.
* update : permet de mettre à jour un objectif si l'utilisateur peut ajouter des objectifs.
* pop : permet de supprimer des objectifs. (Droit utile qu'au corps décisionnel du projet.)
* clear : permet de vider la liste des objectifs si l'utilisateur peut supprimer des objectifs. (Ne donnez jamais ce droit !)
* download : permet de télécharger la version réel de la TODO List ou une version moins abrupte.

#### Traduction / Customisation
La variable $txt contient quasiment tout ce que php peut vous cracher à la gueule en terme de texte lisible et parfois stylé.<br/>
Certains fichiers sont écrits en brute dans du html, notamment tous les dialogs qui apparaissent (dossier modal).
