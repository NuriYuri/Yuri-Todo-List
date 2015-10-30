<?php
//---
// Gestionnaire de Todo List pour Pokémon Gemme
// Ecrit par Nuri Yuri
//   login_check : Gestionnaire de session pour l'utilisation de la todo_list
//---

// Chargement des configurations
include('configuration.php');

$current_user = array('name' => '', 'rights' => array());
session_start();

//---
// check_user_login
// Fonction de vérification de l'utilisateur
//  Si l'utilisateur est loggé il aura l'accès au système sinon il sera bloqué
//---
function check_user_login()
{
  global $current_user, $_LOGIN_TABLE, $_RIGHT_TABLE, $session_password, $session_login;
  // Si l'utilisateur demande la deconnexion
  if(isset($_GET['disconnect']))
  {
    unset($_SESSION[$session_login]);
    unset($_SESSION[$session_password]);
    return false;
  }
  // Si l'utilisateur est potentiellement connecté
  if(isset($_SESSION[$session_login]) && isset($_SESSION[$session_password]))
  {
    //On vérifie ses informations
    if(isset($_LOGIN_TABLE[$_SESSION[$session_login]]) && isset($_RIGHT_TABLE[$_SESSION[$session_login]]) && $_LOGIN_TABLE[$_SESSION[$session_login]] == $_SESSION[$session_password])
    {
      $current_user['name'] = $_SESSION[$session_login];
      $current_user['rights'] = $_RIGHT_TABLE[$_SESSION[$session_login]];
      return true;
    }
    else
    {
      unset($_SESSION[$session_login]);
      unset($_SESSION[$session_password]);
      return false;
    }
  }
  // Sinon, si l'utilisateur tente de se log
  else if(isset($_POST['login']) && isset($_POST['password']))
  {
    // On vérifie les informations
    //On vérifie ses informations
    if(isset($_LOGIN_TABLE[$_POST['login']]) && isset($_RIGHT_TABLE[$_POST['login']]) && $_LOGIN_TABLE[$_POST['login']] == $_POST['password'])
    {
      $current_user['name'] = $_POST['login'];
      $current_user['rights'] = $_RIGHT_TABLE[$_POST['login']];
      $_SESSION[$session_login] = $_POST['login'];
      $_SESSION[$session_password] = $_POST['password'];
      return true;
    }
    else
      return false;
  }
}

//---
// is_allowed_to($permission)
// Vérification des permissions de l'utilisateur
//---
function is_allowed_to($permission)
{
  global $current_user;
  if(isset($current_user['rights']) && in_array($permission, $current_user['rights']))
    return true;
  return false;
}
?>