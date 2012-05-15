<?php 
  require_once 'lib/PDOConnection.php';
  
class DatabaseConnectionFactory {
  public static function get($type) {
    require_once('lib/databaseConnectionParams.php');
    $getParams = 'get'.$type.'Params';
    return PDOConnection::getInstance($getParams());
  }
}
?>


