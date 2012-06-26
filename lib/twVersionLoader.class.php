<?php

class twVersionLoader {
	static public function load() {
		$memcache = twCore::getMemecacheConnection();
		if ($memcache != false) {
			$version_key = sfConfig::get('tw_memcache_kp') . '.core.version';
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
	
	static protected function loadTwVersionArrayFromDB() {
		$conn = twCore::getDatabaseConnection();
		$sql = 'SELECT * FROM tw_version';
		$sth = $conn->prepare($sql);
		$sth->execute();
		$pre = $sth->fetchAll(PDO::FETCH_ASSOC);
		$out = array();
		foreach ($pre as $row) {
			$out[$row['name']] = $row['value'];
		}
		return $out;
	}
}
