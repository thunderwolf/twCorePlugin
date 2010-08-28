<?php
class twCoreLoadFilter extends sfFilter
{
	public function execute($filterChain)
	{
		$memcache = sfConfig::get('tw_memcache', false);
		$strict_key_prefix = sfConfig::get('tw_memcache_skp', null);
		$this->getStatusArray($memcache, $strict_key_prefix);
		$this->getPluginArray($memcache, $strict_key_prefix);

		$filterChain->execute();
	}

	protected function getStatusArray($memcache, $strict_key_prefix) {
		$status_array_key = $strict_key_prefix.'.plugin.status.array';
		if ($memcache != false) {
			$status_array = $memcache->get($status_array_key);
			if ($status_array === false) {
				$status_array = $this->getStatusArrayFromDB();
				$memcache->set($status_array_key, $status_array, 0, 3600);
			}
		} else {
			$status_array = $this->getStatusArrayFromDB();
		}
		sfConfig::set('plugin.status.array', $status_array);
	}

	protected function getStatusArrayFromDB() {
		$c = new Criteria();
		$status_obj_array = twPluginStatusPeer::doSelectWithI18n($c);
		$status_array = array();
		foreach ($status_obj_array as $status_obj) {
			$status_array[$status_obj->getId()] = $status_obj->getName();
		}
		return $status_array;
	}

	protected function getPluginArray($memcache, $strict_key_prefix) {
		$plugin_array_key = $strict_key_prefix.'.plugin.active.array';
		if ($memcache != false) {
			$plugins_array = $memcache->get($plugin_array_key);
			if ($plugins_array === false) {
				$plugins_array = $this->getPluginArrayFromDB();
				$memcache->set($plugin_array_key, $plugins_array, 0, 3600);
			}
		} else {
			$plugins_array = $this->getPluginArrayFromDB();
		}
		sfConfig::set('plugin.active.array', $plugins_array);
	}

	protected function getPluginArrayFromDB() {
		$c = new Criteria();
		$c->addJoin(twPluginPeer::STATUS_ID, twPluginStatusPeer::ID);
		$c->add(twPluginStatusPeer::CODE, 'activated');
		$c->addAscendingOrderByColumn(twPluginPeer::POS);
		$plugins_obj_array = twPluginPeer::doSelectWithI18n($c);
		$plugins_array = array();
		foreach ($plugins_obj_array as $plugin_obj) {
			$plugins_array[$plugin_obj->getId()] = $plugin_obj->getName();
		}
		return $plugins_array;
	}
}

?>