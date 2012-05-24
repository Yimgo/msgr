<?php

class Config {
	const globalConfigFilename = 'lib/config.ini';
	const overrideFilename = 'lib/override.ini';
	
	private $config;
	
	public function __construct($filename) {
		$this->config = parse_ini_file($filename, true);
	}
	
	public static function getConfigParam($category, $param)
	{
		$config = parse_ini_file(self::globalConfigFilename, true);
		
		if(isset($config['General']['debug']) && $config['General']['debug'] && file_exists(self::overrideFilename)) {
			$override = parse_ini_file(self::overrideFilename, true);
			if(isset($override[$category][$param])) {
				return $override[$category][$param];
			}
		}
		
		if(isset($config[$category][$param])) {
			return $config[$category][$param];
		}
		return FALSE;
	}
	
	public function getParam($category, $param) {
		if(isset($this->config[$category][$param])) {
			return $this->config[$category][$param];
		}    
		return FALSE;
	}
	
	public function getCategory($category) {
		if(isset($this->config[$category])) {
			return $this->config[$category];
		}    
		return FALSE;
	}
}

?>
