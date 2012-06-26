<?php

/**
 * twCorePlugin configuration.
 *
 * @package     twCorePlugin
 * @subpackage  config
 * @author      Arkadiusz Tułodziecki
 */
class twCorePluginConfiguration extends sfPluginConfiguration {
	const VERSION = '0.9.10.20120627 (BETA 4)';
	
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
		sfConfig::set('sf_enabled_modules', $enabled);
		
		if (!sfConfig::get('tw_admin_module_web_dir')) {
			sfConfig::set('tw_admin_module_web_dir', '/twAdminPlugin');
		}
		
		if (sfConfig::get('app_tw_core_version_load', false)) {
			twVersionLoader::load();
		}
	}
}
