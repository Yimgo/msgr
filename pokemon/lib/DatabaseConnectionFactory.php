<?php 
  class DatabaseConnectionFactory {
  public static function get($connectionType) {
    $class = $connectionType.'Connection';
    require_once('lib/databaseConnectionParams.php');
    require_once('lib/'.$class.'.php');
    $getParams = 'get'.$connectionType.'Params';
    return $class::getInstance($getParams());
  }
}
?>


