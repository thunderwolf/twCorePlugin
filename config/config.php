<?php
if (!defined('THUNDERWOLF_VER')) {
	define('THUNDERWOLF_VER', '0.9.6.20100406 (ALPHA6)');
}

/* Enabling all need modules */
$enabled = sfConfig::get('sf_enabled_modules');
array_push($enabled, 'twMain');
array_push($enabled, 'twDefault');
array_push($enabled, 'twPlugin');
array_push($enabled, 'twSettings');
if (sfConfig::get('sf_tw_admin', false) == true) {
	array_push($enabled, 'twUpload');
} else {
	// Fronttend
}
sfConfig::set('sf_enabled_modules', $enabled);

if (!sfConfig::get('tw_admin_module_web_dir')) {
	sfConfig::set('tw_admin_module_web_dir', '/twCorePlugin');
}

if (sfConfig::get('app_tw_core_base_plugin_routes_register', true) && in_array('twMain', sfConfig::get('sf_enabled_modules', array()))) {
	$this->dispatcher->connect('routing.load_configuration', array('twCoreBaseRouting', 'listenToRoutingLoadConfigurationEvent'));
}

foreach (array('twPlugin', 'twSettings') as $module) {
	if (in_array($module, sfConfig::get('sf_enabled_modules'))) {
		$this->dispatcher->connect('routing.load_configuration', array('twCoreBaseRouting', 'addRouteFor'.str_replace('tw', '', $module)));
	}
}
