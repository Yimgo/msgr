<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <?php link_to_css("style2");?>
  <title>Pokémon - save</title>
  </head>
  <body>
 <h1>Ajout d'une tâche :</h1>

<p>
<?php
switch ($params["state"]){
	case "OK":
		echo "La tâche ".$params["tache"]." a été ajoutée à la TodoList !";
		break;
	case "EXISTE":
		echo "La tâche ".$params["tache"]." existe déja dans la TodoList !";
		break;
	case "VIDE":
		echo "Aucune tâche entrée";
		break;
}
?>
</p>
<br /><br />
<p>
<img src = "/pokemon/static/images/pokeball.png">
</p>
<strong>Revenir à l'<a href="/pokemon/todolist/index">index</a></strong>

</body>

</html>
