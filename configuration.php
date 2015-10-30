<?php
//---
// Gestionnaire de Todo List pour Pokémon Gemme
// Ecrit par Nuri Yuri
//   configuration : Configuration du la todo list
//---

// Tableau des login mot de passe
$_LOGIN_TABLE = array(
  'admin' => 'password_admin', 
  'user1' => 'password_user',
  );
// Tableau des droits (il doit contenir les même clefs que $_LOGIN_TABLE)
$_RIGHT_TABLE = array(
  'admin' => array('see','push','pop','update','clear','download'), 
  'user1' => array('see'),
  );

// Variable indiquant le nom de la list à éditer
$_TODO_FILENAME = 'TODO.txt';

// Variables relative à la session
$session_login = 'login';
$session_password = 'password';

// Variable activant l'écriture le log des différentes actions importantes
$write_log = false;

//---
// Ici réside la configuration des chaines utilisés par le système
//---
$txt = array(
  'app_title' => 'TODO List Manager',
  'time_format' => '%d/%m/%Y %H:%M:%S',
  'todo_manage' => 'Gestion des objectifs',
  'add_button' => '<button class="btn btn-primary" onclick="todo_push()">Ajouter</button>',
  'update_button' => '<button class="btn btn-success" onclick="todo_update()">Mettre à jour</button>',
  'pop_button' => '<button class="btn btn-warning" onclick="todo_pop()">Supprimer</button>',
  'delete_all_button' => '<button class="btn btn-danger" onclick="todo_clear()">Tout effacer</button>',
  'download_button' => '<a class="btn btn-success" href="?action=download">Télécharger</a>',
  'parsed_download_button' => '<a class="btn btn-primary" href="?action=download&parsed">Téléchargement en version lisible</a>',
  'see_button' => '<a class="btn btn-success" href="?action=see">Voir les objectifs</a>',
  'cant_see' => 'Vous n\'êtes pas autorisés à voir les objectifs.',
  'error' => 'Erreur',
  'global_error' => 'Erreur générale',
  'error_class' => 'warning',
  'fatal_error' => 'Erreur fatale',
  'fatal_error_class' => 'danger',
  'undefined_action' => 'Cette action n\'existe pas.',
  'logoff' => 'Se déconnecter',
  'todo_done' => '<done>',
  'todo_important' => '<imp>',
  'value_done' => 'done',
  'value_important' => 'important',
  'see' => array(
    'title' => 'TODO',
    'head' => 'Reste à faire...',
    'class' => 'primary',
    'right_error' => '<p class="error_description">Vous ne possédez pas le droit de lire la TODO List.</p>',
    'end_tag' => '">',
    'todo_done' => '<li class="todo_done" id="todo_',
    'todo_important' => '<li class="todo_important" id="todo_',
    'todo' => '<li class="todo" id="todo_',
    'todo_title' => '" title="#',
    'todo_list_end' => '</li>',
    'ul_beg' => '<ul id="todo_list" title="',
    'ul_end' => '</ul>',
    ),
  'push' => array(
    'right_error' => '<p class="error_description">Vous ne possédez pas le droit d\'ajouter des éléments à la liste.</p>',
    'request_error' => '<p class="error_description">Les arguments de votre requête <b>push</b> sont invalides.</p>',
    'add_time_format' => '<br/>Ajouté le <b>%d/%m/%Y à %H:%M:%S</b>',
    'log' => 'A ajouté un objectif',
    ),
  'update' => array(
    'title' => 'UPDATE',
    'head' => 'Information',
    'class' => 'success',
    'message' => '<p class="description">L\'objectif a bien été mis à jour !</p>',
    'right_error' => '<p class="error_description">Vous ne possédez pas le droit de mettre à jour les objectifs.</p>',
    'request_error' => '<p class="error_description">Les arguments de votre requête <b>update</b> sont invalides.</p>',
    'edit_time_format' => '<br/>Modifié le <b>%d/%m/%Y à %H:%M:%S</b>',
    'log' => 'A mis à jour l\'objectif #',
    ),
  'pop' => array(
    'title' => 'POP',
    'head' => 'Information',
    'class' => 'success',
    'message' => '<p class="description">L\'objectif a bien été supprimé !</p>',
    'right_error' => '<p class="error_description">Vous ne possédez pas le droit de retirer des objectifs de la liste.</p>',
    'request_error' => '<p class="error_description">Les arguments de votre requête <b>pop</b> sont invalides.</p>',
    'log' => 'A supprimé un objectif',
    ),
  'clear' => array(
    'new_file' => "Liste des objectifs vidée, supprimez ou validez cette entrée.\n",
    'right_error' => '<p class="error_description">Vous ne possédez pas le droit de vider la TODO List.<br/>Votre action a été reporté.</p>',
    'log' => 'A vidé la liste.',
    'log_attempt' => 'A tenté de vider la liste.',
    ),
  'download' => array(
    'right_error' => '<p class="error_description">Vous ne possédez pas le droit de télécharger la liste des objectifs.</p>',
    'request_error' => '<p class="error_description">Une erreur est survenue lors du traitement de votre requette.</p>',
    ),
  'todo_regeneration_log' => 'Régénération du fichier des objectifs : ',
  'default_todo_file_data' => "<imp>Administrateurs : Vérifier les logs.\n",
  'unexistant_todo_file_text' => '<p class="error_description">Le fichier contenant les objectifs a disparu ou n\'a jamais existé.<br/>Une entrée dans les logs a été ajoutée et un fichier TODO vient d\'être généré.</p>',
  'bootstrap' => '
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">',
  
);
?>