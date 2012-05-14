<!DOCTYPE html>
<html lang="fr">
<meta charset="utf-8" />
<?php link_to_css("style2");?>
<head>
        <title>Pokémon - done</title>
</head>
<body>
<h1>Suppression d'une tâche :</h1>
<p>
<?php
if($params["tache"]!=null){
	echo "La tâche ".$params["tache"]." a été supprimée de la TodoList";
	echo '<br /><br />';
	echo '<p><img src = "/pokemon/static/images/task_done.jpg"></p>';
}else{
	echo "La tâche n'est pas dans la TodoList.";
	echo '<p><img src = "/pokemon/static/images/erreur.gif"></p>';
	
}
?>
</p>
<strong>Revenir à l'<a href="/pokemon/todolist/index">index</a></strong>

</body>
</html>
