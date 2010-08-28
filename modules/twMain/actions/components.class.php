<?php

class twMainComponents extends sfComponents
{
	public function executeSidebar()
	{
		if ($this->getUser()->isAuthenticated()) {
			$this->member_id = $this->getUser()->getAttribute('member_id', null, 'sfGuardSecurityUser');
		}

		$status_array = sfConfig::get('plugin.status.array', array());
		$this->status_array = $status_array;

		$plugins_array = sfConfig::get('plugin.active.array', array());
		$this->plugins_array = $plugins_array;

		$modules_obj_array = $this->getModulesObjArray();

		$sidebar = array();
		foreach ($modules_obj_array as $module_obj) {
			$credentials_string = trim($module_obj->getCredentials());
			if (!empty($credentials_string)) {
				$credentials = explode(',', $credentials_string);
				array_walk($credentials, 'trim');
				if ($this->getUser()->hasCredential($credentials, false)) {
					$sidebar[$module_obj->getPluginId()][] = $module_obj;
				}
			} else {
				$sidebar[$module_obj->getPluginId()][] = $module_obj;
			}
		}
		$this->sidebar = $sidebar;
	}

	protected function getModulesObjArray() {
		$memcache = sfConfig::get('tw_memcache', false);
		$status_array_key = sfConfig::get('tw_memcache_skp', null).'.plugin.modules.obj.array';
		if ($memcache != false) {
			$modules_obj_array = $memcache->get($status_array_key);
			if ($modules_obj_array === false) {
				$modules_obj_array = $this->getModulesObjArrayFromDB();
				$memcache->set($status_array_key, $modules_obj_array);
			}
			return $modules_obj_array;
		} else {
			return $this->getModulesObjArrayFromDB();
		}
	}

	protected function getModulesObjArrayFromDB() {
		$c = new Criteria();
		// TODO: sprawdzić czasem array_keys działa dziwnie jak dla mnie
		$c->add(twPluginModulePeer::PLUGIN_ID, array_keys($this->plugins_array), Criteria::IN);
		$c->addJoin(twPluginModulePeer::STATUS_ID, twPluginStatusPeer::ID);
		$c->add(twPluginStatusPeer::CODE, 'activated');
		$c->addAscendingOrderByColumn(twPluginModulePeer::POS);
		return twPluginModulePeer::doSelectWithI18n($c);
	}

	public function executeLocation()
	{
	}

	public function executePerson()
	{
	}

	public function executeUser()
	{
	}

	public function executeLanguage($request)
	{
		$this->form = new sfFormLanguage($this->getUser(), array('languages' => array('en', 'pl')), false);
	}

	public function executeMessage()
	{
		$info = array();
		$warning = array();
		$error = array();
		$critical = array();

		// Standardowy Flash
		if ($this->getUser()->hasFlash('info')) {
			array_push($info, $this->getUser()->getFlash('info'));
		}
		if ($this->getUser()->hasFlash('warning')) {
			array_push($warning, $this->getUser()->getFlash('warning'));
		}
		if ($this->getUser()->hasFlash('error')) {
			array_push($error, $this->getUser()->getFlash('error'));
		}
		if ($this->getUser()->hasFlash('critical')) {
			array_push($critical, $this->getUser()->getFlash('critical'));
		}

		// Specjalne parametry w sesji
//		if ($this->getUser()->hasAttribute('info_messages')) {
//			$info_messages = $this->getUser()->getAttribute('info_messages');
//			array_merge($info, $info_messages);
//			$this->getUser()->getAttributeHolder()->remove('info_messages');
//		}
//		if ($this->getUser()->hasAttribute('warning_messages')) {
//			$warning_messages = $this->getUser()->getAttribute('warning_messages');
//			array_merge($warning, $warning_messages);
//			$this->getUser()->getAttributeHolder()->remove('warning_messages');
//		}
//		if ($this->getUser()->hasAttribute('error_messages')) {
//			$error_messages = $this->getUser()->getAttribute('error_messages');
//			array_merge($error, $error_messages);
//			$this->getUser()->getAttributeHolder()->remove('error_messages');
//		}
//		if ($this->getUser()->hasAttribute('critical_messages')) {
//			$critical_messages = $this->getUser()->getAttribute('critical_messages');
//			array_merge($critical, $critical_messages);
//			$this->getUser()->getAttributeHolder()->remove('critical_messages');
//		}
		if (empty($info)) {
			$info = null;
		}
		if (empty($warning)) {
			$warning = null;
		}
		if (empty($error)) {
			$error = null;
		}
		if (empty($critical)) {
			$critical = null;
		}

		// Koncowe ustawienie
		$this->info = $info;
		$this->warning = $warning;
		$this->error = $error;
		$this->critical = $critical;
	}

}
