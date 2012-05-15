<?php
/*
PARAMETRES DE CONNEXION A LA BASE DE DONNEES
*/

function getMySQLParams() {
  $driver = 'mysql';
  $host = 'pmol.fr';
  $port = 3306;
  $db = 'monsignor';
  $login = 'monsignor';
  $pwd = 'insa';
  return array( 'dsn' => $driver.':host='.$host.';port='.$port.';dbname='.$db,
                'login' => $login,
                'pwd' => $pwd);
}
?>
