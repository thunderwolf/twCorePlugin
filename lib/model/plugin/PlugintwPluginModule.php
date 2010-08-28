<?php

class PlugintwPluginModule extends BasetwPluginModule
{
	public function getStatus() {
		return $this->gettwPluginStatus()->getName();
	}

	public function delete(PropelPDO $con = null) {
		$i18ns = $this->gettwPluginModuleI18ns(null, $con);
		if (!empty($i18ns)) {
			foreach ($i18ns as $i18n) {
				$i18n->delete($con);
			}
		}
		return parent::delete($con);
	}
}

//sfPropelBehavior::add('twPluginModule', array('act_as_sortable' => array('column' => 'pos', 'fk' => 'plugin_id')));