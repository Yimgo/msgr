<?php link_to_css("style.css"); ?> 


Voici la liste de courses :

<?php if ($params == null) { echo "LISTE VIDE"; }
   else	{ 
   	echo "<ul>";

   	for($i=0; $i<count($params); $i++){
		echo "<li>";
		echo $params[$i];	   
   		echo "</li>";
	}

	echo "</ul>";
   }
?>
