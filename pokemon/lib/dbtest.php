<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
	<title>DB TEST</title>
  </head>
  <body>
	<?php

	include 'params.php';
	include 'mysql_connect.php';

	$link = dbconnect($url, $port, $login, $mdp);
	
	if (!mysql_select_db('monsignor', $link)) {
	   echo "Impossible de sélectionner la base mydbname : " . mysql_error();
	   exit;
	}
	
	$sql = 'SELECT * FROM tag';
	
	$result = mysql_query($sql); 
	if (!$result) {
	   echo "Impossible d'exécuter la requête ($sql) dans la base : " . mysql_error();
	   exit;
	}

	while ($row = mysql_fetch_assoc($result)) {
	   echo 'id_tag '.$row["tag_id"].' -> ';
	   echo ''.$row["tag_nom"].'';
	   echo '<br />';
	}

	mysql_free_result($result);
	dbclose($link);
	?>
  </body>
</html>
