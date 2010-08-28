<?php

/**
 * Subclass for representing a row from the 'tw_plugin' table.
 *
 *
 *
 * @package plugins.twCorePlugin.lib.model
 */
class PlugintwPlugin extends BasetwPlugin
{
	public function __toString() {
		return $this->getCode();
	}

	public function getStatus()
	{
		$status_array = sfConfig::get('plugin.status.array', array());
		return $status_array[$this->getStatusId()];
	}

	public function __call($m, $a)
	{
		$data = @split('I18n', $m, 2);
		if( count($data) != 2 ) {
			throw new Exception('Tried to call unknown method '.get_class($this).'::'.$m);
		}
		list( $method, $culture ) = $data;
		if (strlen($culture)==4) {
			$culture=strtolower(substr($culture,0,2)).'_'.strtoupper(substr($culture,2,2));
		} else {
			$culture=strtolower($culture);
		}
		$this->setCulture( $culture );
		return call_user_func_array(array($this, $method), $a);
	}

	public function delete(PropelPDO $con = null) {
		$modules = $this->gettwPluginModules(null, $con);
		if (!empty($modules)) {
			foreach ($modules as $module) {
				$module->delete($con);
			}
		}
		$i18ns = $this->gettwPluginI18ns(null, $con);
		if (!empty($i18ns)) {
			foreach ($i18ns as $i18n) {
				$i18n->delete($con);
			}
		}
		return parent::delete($con);
	}

}

//sfPropelBehavior::add('twPlugin', array('act_as_sortable' => array('column' => 'pos')));