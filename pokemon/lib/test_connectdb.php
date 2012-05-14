<?php
/*
FICHIER DE TEST DE CONNEXION A LA BASE DE DONNEES
(include params.php necessaire)
*/
printf("~~~~~ Init ~~~~~ <br />");
$link = dbconnect($url, $port, $login, $mdp);
echo 'Connected. <br />';
dbclose($link);
printf("~~~~~ End ~~~~~<br />");
?>
