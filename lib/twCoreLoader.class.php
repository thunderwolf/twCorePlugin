<?php
require_once dirname(__FILE__) . '/twCoreMemcache.class.php';

class twCoreLoader
{
	static $conn = null;

	static public function load() {
		try {
			$memcache_config = array(
				array('host' => 'localhost', 'port' => 11211),
			);
			$memcache = new twCoreMemcache($memcache_config);
			self::loadTwVersionArray($memcache);
			self::loadTwSettingsArray($memcache);
			self::loadTwRoutingArray($memcache);

		} catch (Exception $e) {
			return $e->getMessage();
		}
		return true;
	}

	static protected function loadTwVersionArray($memcache) {
		if ($memcache != false) {
			$version_key = sfConfig::get('tw_memcache_kp').'.core.version';
			$versions = $memcache->get($version_key);
			if (empty($versions)) {
				$versions = self::loadTwVersionArrayFromDB();
				if (!empty($versions)) {
					$memcache->set($version_key, $versions);
				}
			}
		} else {
			$versions = self::loadTwVersionArrayFromDB();
		}
		if (!is_array($versions)) {
			$versions = array();
		}
		sfConfig::set('tw_version_array', $versions);
	}

	static protected function loadTwSettingsArray($memcache) {
		if ($memcache != false) {
			$settings_key = sfConfig::get('tw_memcache_kp').'.core.settings';
			$settings = $memcache->get($settings_key);
			if (empty($settings)) {
				$settings = self::loadTwSettingsArrayFromDB();
				if (!empty($settings)) {
					$memcache->set($settings_key, $settings);
				}
			}
		} else {
			$settings = self::loadTwSettingsArrayFromDB();
		}
		if (!is_array($settings)) {
			$settings = array();
		}
		sfConfig::set('tw_settings_array', $settings);
	}

	static protected function loadTwRoutingArray($memcache) {
		if ($memcache != false) {
			$routing_key = sfConfig::get('tw_memcache_kp').'.core.routing';
			$routing = $memcache->get($routing_key);
			if (empty($routing)) {
				$routing = self::loadTwRoutingArrayFromDB();
				if (!empty($routing)) {
					$memcache->set($routing_key, $routing);
				}
			}
		} else {
			$routing = self::loadTwRoutingArrayFromDB();
		}
		if (!is_array($routing)) {
			$routing = array();
		}
		sfConfig::set('tw_routing_array', $routing);
	}

	static protected function loadTwVersionArrayFromDB() {
		if (is_null(self::$conn)) {
			self::createConnection();
		}
		$sql = 'SELECT * FROM tw_version';
		$sth = self::$conn->prepare($sql);
		$sth->execute();
		$pre = $sth->fetchAll(PDO::FETCH_ASSOC);
		$out = array();
		foreach($pre as $row) {
			$out[$row['name']] = $row['value'];
		}
		return $out;
	}

	static protected function loadTwSettingsArrayFromDB() {
		if (is_null(self::$conn)) {
			self::createConnection();
		}
		$sql = 'SELECT * FROM tw_settings';
		$sth = self::$conn->prepare($sql);
		$sth->execute();
		$pre = $sth->fetchAll(PDO::FETCH_ASSOC);
		$out = array();
		foreach($pre as $row) {
			$out[$row['name']] = $row['value'];
		}
		return $out;
	}

	static protected function loadTwRoutingArrayFromDB() {
		if (is_null(self::$conn)) {
			self::createConnection();
		}
		$sql = 'SELECT * FROM tw_routing ORDER by pos DESC';
		$sth = self::$conn->prepare($sql);
		$sth->execute();
		$out = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $out;
	}

	static protected function createConnection() {
		$configFile = sfConfig::get('sf_config_dir') . '/databases.yml';
		if (is_readable($configFile)) {
			$config = sfYaml::load($configFile);
			$config =  sfToolkit::arrayDeepMerge(
				isset($config['default']) && is_array($config['default']) ? $config['default'] : array(),
				isset($config['all']) && is_array($config['all']) ? $config['all'] : array(),
				isset($config[sfConfig::get('sf_environment')]) && is_array($config[sfConfig::get('sf_environment')]) ? $config[sfConfig::get('sf_environment')] : array()
			);
			if (isset($config['propel']['param'])) {
				$config['propel']['param']['classname'] = 'PDO';
				$dbhandler =  new sfPDODatabase($config['propel']['param']);
				self::$conn = $dbhandler->getConnection();
			}
		}
	}
}