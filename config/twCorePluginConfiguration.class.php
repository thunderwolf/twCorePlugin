<?php

/**
 * twCorePlugin configuration.
 *
 * @package     twCorePlugin
 * @subpackage  config
 * @author      Arkadiusz Tułodziecki
 */
class twCorePluginConfiguration extends sfPluginConfiguration {
	const VERSION = '0.9.10.20120612 (BETA 4)';

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
		if (sfConfig::get('sf_tw_admin', false) == true) {
			array_push($enabled, 'twUpload');
		}
		sfConfig::set('sf_enabled_modules', $enabled);

		if (!sfConfig::get('tw_admin_module_web_dir')) {
			sfConfig::set('tw_admin_module_web_dir', '/twAdminPlugin');
		}
	}
}
