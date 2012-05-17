<?php
/*
 * This class makes sure that only one connection is provided for (host, port, login)
 */
 
class PDOConnection extends PDO {
  private static $instances = array();
  
  
  public static function getInstance($params) {
    if(!isset(self::$instances[$params['dsn']][$params['login']])) {
      self::$instances[$params['dsn']][$params['login']] = new PDOConnection($params['dsn'], $params['login'], 
$params['pwd'], $params['driver_options']);
    }
    return self::$instances[$params['dsn']][$params['login']];
  }
}
?>
