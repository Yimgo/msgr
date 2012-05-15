<?php
  class MySQLConnection {
  private static $instance;
  private $connection;
  
  private function __construct($params) {
    try { 
      $this->connection = new PDO('mysql:host='.$params['host'].';dbname='.$params['base'].';port='.$params['port'], $params['login'], $params['pwd']);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }
  
  public static function getInstance($params) {
    if(!isset(self::$instance)) {
      self::$instance = new MySQLConnection($params);
    }
    return self::$instance;
  }
  
  public function query($sql) {
    return $this->connection->query($sql);
  }
  
  
}
?>
