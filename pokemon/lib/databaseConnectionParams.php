<?php
/*
PARAMETRES DE CONNEXION A LA BASE DE DONNEES
*/

function getMySQLParams() {

  $host = 'pmol.fr';
  $port = 3306;
  $base = 'monsignor';
  $login = 'monsignor';
  $pwd = 'insa';
  return array( 'host' => $host,
                'port' => $port,
                'base' => $base,
                'login' => $login,
                'pwd' => $pwd);
}
?>
