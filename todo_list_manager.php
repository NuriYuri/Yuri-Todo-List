<?php
//---
// Gestionnaire de Todo List pour Pokémon Gemme
// Ecrit par Nuri Yuri
//   todo_list_manager : Gestionnaire complet de la todo list
//---

//---
// todo_see : voir la liste
//---
function todo_see()
{
  global $txt;
  $texts = $txt['see'];
  //Si l'utilisateur est autorisé à voir la liste
  if(is_allowed_to('see'))
  {
    //Récupération de la liste
    $arr = todo_list_get();
    //Affichage du début de la page
    show_title($texts['title']);
    begin_page($texts['head'],$texts['class']);
    //Identificateur externe
    $id = count($arr);
    //Variables d'affichage
    $end_tag = $texts['end_tag'];
    $todo_done = $texts['todo_done'];
    $todo_imp = $texts['todo_important'];
    $todo_und = $texts['todo'];
    $todo_title = $texts['todo_title'];
    $todo_list_end = $texts['todo_list_end'];
    $done = $txt['todo_done'];
    $imp = $txt['todo_important'];
    //Affichage de la liste
    echo $texts['ul_beg'], $id, $end_tag;
    foreach($arr as $todo)
    {
      if(strrpos($todo, $done) === 0)
        echo $todo_done, $id, $todo_title,$id, $end_tag, str_replace($done, '', $todo), $todo_list_end;
      else if(strrpos($todo, $imp) === 0)
        echo $todo_imp, $id, $todo_title,$id, $end_tag, str_replace($imp, '', $todo), $todo_list_end;
      else
        echo $todo_und, $id, $todo_title,$id, $end_tag, $todo, $todo_list_end;
      $id -= 1;
    }
    echo $texts['ul_end'];
    //Fin de page
    end_page();
  }
  else
    error_display($texts, true);
}

//---
// todo_push : Ajouter un objectif
//---
function todo_push()
{
  global $_TODO_FILENAME, $txt;
  $texts = $txt['push'];
  //Si l'utilisateur est autorisé à ajouter des objectifs
  if(is_allowed_to('push'))
  {
    //Vérification de la présence du fichier TODO
    if(!file_exists($_TODO_FILENAME))
    {
      todo_file_exist_error();
      return;
    }
    //Vérification des données envoyées
    if(!empty($_POST['objective']) && isset($_POST['state']))
    {
      //Ecriture de la nouvelle entrée
      $state = ($_POST['state'] == $txt['value_done'] ? $txt['todo_done'] : ($_POST['state'] == $txt['value_important'] ? $txt['todo_important'] : ''));
      $f = fopen($_TODO_FILENAME, 'a');
      fwrite($f, $state.parse_string($_POST['objective']).strftime($texts['add_time_format'],time())."\n");
      fclose($f);
      log_write($texts['log']);
      //Redirection vers la vue de la liste
      header('Location: index.php');
      return;
    }
    //En cas d'erreur d'arguments
    error_display($texts, false);
  }
  else
    error_display($texts, true);
}

//---
// todo_update : Mettre à jour un objectif
//---
function todo_update()
{
  global $_TODO_FILENAME, $txt;
  $texts = $txt['update'];
  //Si l'utilisateur a le droit de mettre à jour et d'ajouter
  if(is_allowed_to('update') && is_allowed_to('push'))
  {
    //Vérification des arguments
    if(isset($_POST['id']) && !empty($_POST['objective']) && isset($_POST['state']))
    {
      //Index réel de l'objectif
      $index = $_POST['id'] - 1;
      //Récupération de la liste dans le bon ordre
      $arr = array_reverse(todo_list_get());
      //Vérification de l'existence de l'objectif
      if(count($arr) > $index && $index >= 0)
      {
        //Réécriture de l'entrée
        $state = ($_POST['state'] == $txt['value_done'] ? $txt['todo_done'] : ($_POST['state'] == $txt['value_important'] ? $txt['todo_important'] : ''));
        $arr[$index] = $state.parse_string($_POST['objective']).strftime($texts['edit_time_format'],time());
        $f = fopen($_TODO_FILENAME, 'wb');
        fwrite($f, implode("\n", $arr)."\n");
        fclose($f);
        //Information du succès
        log_write($texts['log'].$index);
        show_title($texts['title']);
        begin_page($texts['head'], $texts['class']);
        echo $texts['message'];
        end_page();
        return;
      }
    }
    //En cas de non validation des conditions précédentes
    error_display($texts, false);
  }
  else
    error_display($texts, true);
}

//---
// todo_pop : Supprimer un objectif
//---
function todo_pop()
{
  global $_TODO_FILENAME, $txt;
  $texts = $txt['pop'];
  //Si l'utilisateur est autorisé à retirer les objectifs
  if(is_allowed_to('pop'))
  {
    //Vérification de l'argument
    if(isset($_GET['id']))
    {
      //Index réel
      $index = $_GET['id'] - 1;
      //Récupération des objectifs dans l'ordre réel
      $arr = array_reverse(todo_list_get());
      //Vérification de l'existence de l'objectif
      if(count($arr) > $index && $index >= 0)
      {
        //Effacement bête et méchant
        unset($arr[$index]);
        //Réécriture du fichier
        $f = fopen($_TODO_FILENAME, 'wb');
        fwrite($f, implode("\n", $arr)."\n");
        fclose($f);
        log_write($texts['log']);
        show_title($texts['title']);
        begin_page($texts['head'], $texts['class']);
        echo $texts['message'];
        end_page();
        return;
      }
    }
    error_display($texts, false);
  }
  else
    error_display($texts, true);
}

//---
// todo_clear : Vider la liste
//---
function todo_clear()
{
  global $_TODO_FILENAME, $txt;
  $texts = $txt['clear'];
  //Si l'utilisateur a le droit de clear et de pop
  if(is_allowed_to('clear') && is_allowed_to('pop'))
  {
    $f = fopen($_TODO_FILENAME, 'wb');
    fwrite($f, $texts['new_file']);
    fclose($f);
    log_write($texts['log']);
    header('Location: index.php');
  }
  else
  {
    error_display($texts, true);
    log_write($texts['log_attempt']);
  }
}

//---
// todo_download : Télécharger la TODO List
//---
function todo_download()
{
  global $_TODO_FILENAME, $txt;
  $texts = $txt['download'];
  if(is_allowed_to('download') && is_allowed_to('see'))
  {
    if(isset($_GET['parsed']))
    {
      $arr = todo_list_get();
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="parsed_'.$_TODO_FILENAME.'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      foreach($arr as $todo)
      {
        if(strrpos($todo, '<done>') === 0)
          echo "\t", parse_todo($todo), "\r\n\r\n";
        else if(strrpos($todo, '<imp>') === 0)
          echo '/!\\ ', parse_todo($todo), "\r\n\r\n";
        else
          echo parse_todo($todo), "\r\n\r\n";
      }
      exit();
    }
    else
    {
      if(file_exists($_TODO_FILENAME))
      {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$_TODO_FILENAME.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($_TODO_FILENAME));
        readfile($_TODO_FILENAME);
        exit();
      }
    }
    error_display($texts, false);
  }
  else
    error_display($texts, true);
}

//---
// Affichage d'une erreur
//---
function error_display($texts, $right_error)
{
  global $txt;
  show_title($txt['error']);
  begin_page($txt['error'], $txt['error_class']);
  echo $texts[$right_error ? 'right_error' : 'request_error'];
  end_page();
}

//---
// Afficher le titre de la page avec le header
//---
function show_title($title)
{
  global $todo_class, $txt;
  echo '<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>',$title,' :: ',$txt['app_title'],'</title>
    <meta name="author" content="Nuri Yuri">
    <meta name="description" content="',$title,'">
    <meta name="application-name" content="todo.manager.',$todo_class,'">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>', $txt['bootstrap'],'
  </head>';
}
//---
// Début la page du todo
//---
function begin_page($title, $class_name = 'default')
{
  global $txt;
  echo '
  <body>
    <div class="container">';
  
  if(is_allowed_to('push') || is_allowed_to('pop') || is_allowed_to('clear'))
  {
    echo '
      <div class="panel panel-info">
        <div class="panel-heading">',$txt['todo_manage'],'</div>
        <div class="panel-body">
          <div class="btn-group">';
    if(!isset($_GET['action']) || $_GET['action'] == 'see')
    {
      if(is_allowed_to('push'))
      {
        echo $txt['add_button'];
        if(is_allowed_to('update'))
          echo $txt['update_button'];
      }
      if(is_allowed_to('pop'))
      {
        echo $txt['pop_button'];
        if(is_allowed_to('clear'))
          echo $txt['delete_all_button'];
      }
      if(is_allowed_to('download'))
      {
        echo $txt['download_button'];
        echo $txt['parsed_download_button'];
      }
    }
    else
    {
      if(is_allowed_to('see'))
        echo $txt['see_button'];
      else
        echo $txt['cant_see'];
    }
    echo '
          </div>
        </div>
      </div>';
  }
  echo '
      <div class="panel panel-',$class_name,'">
        <div class="panel-heading"><h2>',$title,'</h2></div>
        <div class="panel-body">';
}

//---
// Fin de la page du todo
//---
function end_page()
{
  global $txt;
  echo '
        </div>
      </div>
    </div>
    <div id="logoff"><a href="?disconnect" class="btn btn-danger">',$txt['logoff'],'</a></div>
  </body>
</html>';
}

//---
// Erreur de la présence du fichier de la TODO List
//---
function todo_file_exist_error()
{
  global $_TODO_FILENAME, $txt;
  show_title($txt['fatal_error']);
  begin_page($txt['fatal_error'], $txt['fatal_error_class']);
  echo $txt['unexistant_todo_file_text'];
  end_page();
  log_write($txt['todo_regeneration_log'].$_TODO_FILENAME);
  $f = fopen($_TODO_FILENAME, "wb");
  fwrite($f,$txt['default_todo_file_data']);
  fclose($f);
}

//---
// Ecriture d'une action dans le log
//---
function log_write($action)
{
  global $current_user, $write_log, $txt;
  if($write_log)
  {
    $f = fopen("todo.log","a");
    fwrite($f, '['.strftime($txt['time_format'],time()).'] ('.$current_user['name'].' - '.$_SERVER["REMOTE_ADDR"].') : '.$action."\r\n");
    fclose($f);
  }
}

//---
// Récupération du contenu de la TODO List
//---
function todo_list_get()
{
  global $_TODO_FILENAME;
  if(!file_exists($_TODO_FILENAME))
  {
    todo_file_exist_error();
    exit(0);
  }
  $f = fopen($_TODO_FILENAME, 'rb');
  $list = fread($f, filesize($_TODO_FILENAME));
  fclose($f);
  $arr = array_reverse(split("\n",$list));
  array_shift($arr);
  return $arr;
}

//---
// Traiter le contenu d'un string
//---
function parse_string($str)
{
  return preg_replace(array(
      '/<([^>]*)>/',
      '/\r*\n\[li\]/i',
      '/\[(|\/)(b|i|em|strong|center|ul|li)\]/i',
      '/\[url=([^\]]*)\]/i',
      '/\[\/url\]/',
      '/\r*\n/',
      '/&lt;(|\/)br(|\/)&gt;/i',
    ),
    array(
      '&lt;$1&gt;',
      '<li>',
      '<$1$2>',
      '<a href="$1">',
      '</a>',
      '<br/>',
      '<br/>'
    ),
    $str);
}

//---
// Traitement d'un todo pour qu'il soit lisible
//--
function parse_todo($todo)
{
  return preg_replace(array(
      '/<li>/',
      '/<a href="([^"])*">/',
      '/<(|\/)br(|\/)>/i',
      '/<[^>]*>/'
    ),
    array(
      "\r\n*\t",
      '[URL : $1] < ',
      "\r\n--\t",
      ''
    ),
    $todo);
}
?>