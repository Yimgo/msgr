<?php 
  require_once 'lib/PDOConnection.php';
  
class DatabaseConnectionFactory {
  public static function get($profile) {
    $params = array();
    
    if(!file_exists('lib/db_profiles/'.$profile.'.ini')) {
      return FALSE;
    }
    
    $config = parse_ini_file('lib/db_profiles/'.$profile.'.ini', true);

    $params['dsn'] = self::getDSN($config['General']['driver'], $config['General']['host'], $config['General']['port'], $config['General']['dbname']);
    $params['username'] = $config['General']['username'];
    $params['password'] = $config['General']['password'];
    if(isset($config['DriverOptions'])) {
      $params['driver_options'] = self::getDriverOptions($config['DriverOptions']);
    }
    else {
      $params['driver_options']=array();
    }
    
    return PDOConnection::getInstance($params);
  }
  
  public static function getDSN($driver, $host, $port, $dbname) {
    return $driver.':host='.$host.';port='.$port.';dbname='.$dbname;
  }
  
  public static function getDriverOptions($config) {
    $optionsArray = array();
    $pdoClass = new ReflectionClass('PDO');
    foreach($config as $key => $value) {
      $optionsArray[$pdoClass->getConstant($key)] = $value;
    }
    
    return $optionsArray;
  }
}
?>
