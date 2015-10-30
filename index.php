<?php
//---
// Gestionnaire de Todo List pour Pokémon Gemme
// Ecrit par Nuri Yuri
//   Index
//---

//Inclusion des fonctions relatives au login et à l'utilisateur
require("login_check.php");
//Vérification de l'état de l'utilisateur
if(!check_user_login())
{
  //L'utilisateur n'est pas connecté, on demande donc le login
  header('Location: user_login.html');
  exit(0);
}
// On peut continuer le travail
// Inclusion de la lib' de gestion de la todo list
require("todo_list_manager.php");

// Traitement des demandes
if(isset($_GET['action']) && $_GET['action'] != 'see')
{
  $todo_class = $_GET['action'];
  switch($_GET['action'])
  {
    case 'push':
      todo_push();
      break;
    case 'pop':
      todo_pop();
      break;
    case 'update':
      todo_update();
      break;
    case 'clear':
      todo_clear();
      break;
    case 'download':
      todo_download();
      break;
    default:
      show_title($txt['error']);
      begin_page($txt['error'], $txt['error_class']);
      echo $txt['undefined_action'];
      end_page();
  }
}
else
{
  $todo_class = 'see';
  todo_see();
}

?>