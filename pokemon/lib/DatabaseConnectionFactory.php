<?php

require_once 'lib/PDOConnection.php';
require_once 'lib/Config.php';
  
class DatabaseConnectionFactory {
	public static function get($profile) {
		$params = array();
		$config = new Config('lib/db_profiles/'.$profile.'.ini');
		
		$params['dsn'] = self::getDSN($config->getParam('General', 'driver'), $config->getParam('General', 'host'), $config->getParam('General','port'), $config->getParam('General', 'dbname'));
		$params['username'] = $config->getParam('General', 'username');
		$params['password'] = $config->getParam('General', 'password');
		if($config->getCategory('DriverOptions') != FALSE) {
			$params['driver_options'] = self::getDriverOptions($config->getCategory('DriverOptions'));
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
