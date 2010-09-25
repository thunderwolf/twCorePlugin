<?php

require_once dirname(__FILE__).'/../lib/twSettingsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/twSettingsGeneratorHelper.class.php';

/**
 * twSettings actions.
 *
 * @package    twCore
 * @subpackage twSettings
 * @author     Your name here
 */
class twSettingsActions extends autoTwSettingsActions
{
	public function preExecute() {
		sfConfig::set('tw_admin:default:module', 'administration');
		sfConfig::set('tw_admin:default:category', 'settings');
		return parent::preExecute();
	}
}
