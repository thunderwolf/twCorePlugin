<?php

/**
 * twCorePlugin configuration.
 *
 * @package     twCorePlugin
 * @subpackage  config
 * @author      Arkadiusz Tułodziecki
 */
class twCorePluginConfiguration extends sfPluginConfiguration {
	const VERSION = '0.9.9.20120425 (BETA 3)';

	/**
	 * @see sfPluginConfiguration
	 */
	public function initialize() {
		if (!defined('THUNDERWOLF_VER')) {
			define('THUNDERWOLF_VER', self::VERSION);
		}
		
		/* Enabling all need modules */
		$enabled = sfConfig::get('sf_enabled_modules', array());
		array_push($enabled, 'twDefault');
		array_push($enabled, 'twSettings');
		if (sfConfig::get('sf_tw_admin', false) == true) {
			array_push($enabled, 'twUpload');
		}
		sfConfig::set('sf_enabled_modules', $enabled);

		if (!sfConfig::get('tw_admin_module_web_dir')) {
			sfConfig::set('tw_admin_module_web_dir', '/twAdminPlugin');
		}

		// ADMIN ROUTING
		if (sfConfig::get('sf_tw_admin', false) == true) {
			foreach (array('twSettings') as $module) {
				if (in_array($module, sfConfig::get('sf_enabled_modules'))) {
					$this->dispatcher->connect('routing.load_configuration', array('twCoreBaseAdminRouting', 'addRouteFor'.str_replace('tw', '', $module)));
				}
			}
		}
	}
}
