<?php

class twCore {
	static $conn = null;
	static $memcache = false;
	
	static public function getDatabaseConnection() {
		if (!is_null(self::$conn)) {
			return self::$conn;
		}
		
		$configFile = sfConfig::get('sf_config_dir') . '/databases.yml';
		if (is_readable($configFile)) {
			$config = sfYaml::load($configFile);
			$config = sfToolkit::arrayDeepMerge(isset($config['default']) && is_array($config['default']) ? $config['default'] : array(),
				isset($config['all']) && is_array($config['all']) ? $config['all'] : array(),
				isset($config[sfConfig::get('sf_environment')]) && is_array($config[sfConfig::get('sf_environment')]) ? $config[sfConfig::get('sf_environment')]
					: array());
			if (isset($config['propel']['param'])) {
				$config['propel']['param']['classname'] = 'PDO';
				$dbhandler = new sfPDODatabase($config['propel']['param']);
				self::$conn = $dbhandler->getConnection();
				sfConfig::set('tw_core_dbconn', true);
				return self::$conn;
			}
		}
		throw new sfException('Can\'t connect to database - config file not readable');
	}
	
	static public function getMemecacheConnection() {
		if (self::$memcache) {
			return self::$memcache;
		}
		$memcache_settings = sfConfig::get('app_tw_core_memcache', array());
		if (!in_array('active', $memcache_settings) || !in_array('conf', $memcache_settings)) {
			return false;
		}
		if (!$memcache_settings['active'] || !is_array($memcache_settings['conf'])) {
			return false;
		}
		try {
			self::$memcache = new twCoreMemcache($memcache_settings['conf']);
		} catch (Exception $e) {
			return false;
		}
		sfConfig::set('tw_core_memconn', true);
		return self::$memcache;
	}
}