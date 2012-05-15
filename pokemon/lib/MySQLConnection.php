<?php
  class MySQLConnection extends PDO {
  private static $instance;
  
  public static function getInstance($params) {
    if(!isset(self::$instance)) {
      self::$instance = new MySQLConnection($params['dsn'], $params['login'], $params['pwd']);
    }
    return self::$instance;
  }
}
?>
