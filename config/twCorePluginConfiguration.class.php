<?php

/**
 * twCorePlugin configuration.
 *
 * @package     twCorePlugin
 * @subpackage  config
 * @author      Arkadiusz Tułodziecki
 */
class twCorePluginConfiguration extends sfPluginConfiguration {
	const VERSION = '1.0.0-DEV';

	/**
	 * @see sfPluginConfiguration
	 */
	public function initialize() {
		if (!defined('THUNDERWOLF_VER')) {
			define('THUNDERWOLF_VER', '0.9.8.20101019 (BETA 1)');
		}
//		$plugins = $this->configuration->getPlugins();
//		var_dump($plugins);

		/* Enabling all need modules */
		$enabled = sfConfig::get('sf_enabled_modules', array());
		array_push($enabled, 'twDefault');
		array_push($enabled, 'twPlugin');
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
			foreach (array('twPlugin', 'twSettings') as $module) {
				if (in_array($module, sfConfig::get('sf_enabled_modules'))) {
					$this->dispatcher->connect('routing.load_configuration', array('twCoreBaseAdminRouting', 'addRouteFor'.str_replace('tw', '', $module)));
				}
			}
		}
	}
}
