<!DOCTYPE html>
<html lang="fr">
<meta charset="utf-8" />
<?php link_to_css("style2");?>

<script type="text/javascript">
function save(){
	var task = document.getElementById('task');
	if(task.value == ""){
		alert("Aucune tache entrée");
	}else{
		var url = "/pokemon/todolist/save/"+ task.value;
		window.location = url;
	}
}
</script>

<head>
        <title>Pokémon - index</title>
</head>
<body>
<h1>Liste des éléments de la TodoList :</h1>

<p>
<?php 
	$nb_tache=$params["nb_tache"];
	$tache_table=$params["tache_table"];
	if ($nb_tache!=null){
		echo "<table>";
		echo "<tr>";
		echo "<th align=\"center\"><u>Tâche</u></th>";
		echo "</tr>";
		echo "<tr>";
		echo "</tr>";
		foreach($tache_table as $cle => $valeur){
			if ($cle!="nb_tache"){
				echo "<tr>";
				echo "<td align=\"center\">".$valeur."</td>";
				echo "<td align=\"center\"><a href=\"/pokemon/todolist/done/".$valeur."\"><img src = \"/pokemon/static/images/suppr.png\"></a></td>";
				echo "</tr>";
				echo "<tr>";
				echo "</tr>";
			}
		}
		echo "</table>";
		echo '<p><img src = "/pokemon/static/images/sacha_pika.jpg"></p>';
		echo '<strong><a href="/pokemon/todolist/reset">Remettre à zéro la todolist</a></strong>';
	}else{
		echo "<strong>Aucun &eacute;l&eacute;ment</strong>";
		echo '<p><img src = "/pokemon/static/images/pika_dis.jpg"></p>';
	}
?>
</p>

<strong>Ajouter une tâche:</strong>
<input type="text" name=tache class="input-small" placeholder="Tâche à ajouter" id="task"/>
<input type="button" class="btn" onclick="save()" value="Ajouter !"/>

</body>
</html>
