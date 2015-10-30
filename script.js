var max_todo = null;
//---
// Ajout d'un entrée
//---
function todo_push()
{
  CSP_load_modal('push');
}
//---
// Gestion de la suppression
//---
function todo_pop()
{
  if(!max_todo)
    max_todo = parseInt(document.getElementById('todo_list').title);
  CSP_load_modal('pop');
}
function todo_pop_ok()
{
  var  update_id = document.getElementById('todo_pop_id');
  update_id.value = max_todo;
  update_id.max = max_todo;
  todo_pop_change();
}
function todo_pop_change()
{
  var update_id = document.getElementById('todo_pop_id');
  var update_objective = document.getElementById('todo_pop_objective');
  var update_button = document.getElementById('todo_btn_pop');
  var id = update_id.value;
  if(id > max_todo || id < 1)
  {
    update_objective.value = "Erreur, numéro d'objectif inexistant. Max : "+max_todo;
    update_button.disabled = true;
  }
  else
  {
    document.getElementById('todo_hiden_pop_id').value = id;
    update_objective.value = todo_update_parse_text(document.getElementById('todo_'+id).innerHTML);
    update_button.disabled = false;
  }
}
function todo_clear()
{
  CSP_load_modal('clear');
}

//---
// Gestion de la mise à jour
//---
function todo_update()
{
  if(!max_todo)
    max_todo = parseInt(document.getElementById('todo_list').title);
  CSP_load_modal('update');
}

function todo_update_ok()
{
  var  update_id = document.getElementById('todo_update_id');
  update_id.value = max_todo;
  update_id.max = max_todo;
  todo_update_change();
}
function todo_update_change()
{
  var update_id = document.getElementById('todo_update_id');
  var update_objective = document.getElementById('todo_update_objective');
  var update_button = document.getElementById('todo_btn_update');
  var objective = null;
  var state = document.getElementById('todo_state')
  var id = update_id.value;
  if(id > max_todo || id < 1)
  {
    update_objective.value = "Erreur, numéro d'objectif inexistant. Max : "+max_todo;
    update_objective.disabled = true;
    update_button.disabled = true;
    state.disabled = true;
  }
  else
  {
    document.getElementById('todo_hiden_id').value = id;
    objective = document.getElementById('todo_'+id);
    update_objective.value = todo_update_parse_text(objective.innerHTML);
    state.value = objective.className == 'todo' ? 'undone' : objective.className == 'todo_important' ? 'important' : 'done'
    update_objective.disabled = false;
    update_button.disabled = false;
    state.disabled = false;
  }
}
function todo_update_parse_text(str)
{
  str = str.replace(/<a href="([^"]*)">/gi, "[url=$1]")
  str = str.replace(/<\/a>/gi, "[/url]")
  str = str.replace(/<(|\/)br(|\/)>/gi, "\n")
  str = str.replace(/<\/li><li>/gi, "[/li]\n[li]")
  str = str.replace(/<(|\/)(b|i|em|strong|center|ul|li)>/gi, "[$1$2]")
  return str;
}

//---
// Chargement d'un modal
//---
function CSP_load_modal(id)
{
  var request = new XMLHttpRequest();
  var el;
  if(document.getElementById(id))
  {
    $("#"+id).modal("show");
    return;
  }
  request.open("GET", "./modal/"+id+".html", true);
  request.onreadystatechange = function() {
    if(request.readyState == 4 && request.status == 200) 
    {
      el = document.createElement("div");
      el.id = id;
      el.setAttribute("role","dialog");
      el.setAttribute("class","modal fade");
      el.innerHTML = request.responseText;
      document.body.appendChild(el);
      $("#"+id).modal("show");
      if(id == 'update')
        todo_update_ok();
      else if(id == 'pop')
        todo_pop_ok();
    }
  }
  request.send();
}