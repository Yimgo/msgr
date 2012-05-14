<?php
/*
FONCTIONS DE CONNEXION/DECONNEXION A LA BASE DE DONNEES. 
(include params.php necessaire)
*/
function dbconnect($url, $port, $login, $mdp){
	$path = $url.':'.$port;
	$link = mysql_connect(''.$path.'', ''.$login.'', ''.$mdp.'');
	if (!$link) {
		die('Connexion impossible : ' . mysql_error());
	}
	return $link;
}
function dbclose($link){
	mysql_close($link);
	echo 'Connection closed. <br />';
}

function execute_query($sql){
	$result = mysql_query($sql); 
	if (!$result) {
	   echo "Impossible d'exécuter la requête ($sql) dans la base : " . mysql_error();
	   exit;
	}
	return $result;
}

$link = dbconnect($url, $port, $login, $mdp);
if (!mysql_select_db('monsignor', $link)) {
   echo "Impossible de sélectionner la base mydbname : " . mysql_error();
   exit;
}

?>
