<?php
/* 
 * Database Connection Parameters.
 * Functions must be in this form: get.DB Type.Params().
 * Those must return an associative array:
 * array['dsn']: data source name, which contains the driver, the host, the port and the database. cf http://www.php.net/manual/fr/pdo.construct.php
 * array['login']
 * array['pwd']
 */
 
$defaultDatabaseConnectionProfile = 'Pmol';
 
/* Get parameters for MySQL DB @pmol.fr */ 
function getPmolParams() {
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

/* Get parameters for MySQL DBs @localhost */ 
function getLocalhostParams() {
  $driver = 'mysql';
  $host = 'localhost';
  $port = 3306;
  $db = 'monsignor';
  $login = 'monsignor';
  $pwd = 'insa';
  return array( 'dsn' => $driver.':host='.$host.';port='.$port.';dbname='.$db,
                'login' => $login,
                'pwd' => $pwd);
}
?>
