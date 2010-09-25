<?php

require_once dirname(__FILE__).'/../lib/twSettingsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/twSettingsGeneratorHelper.class.php';

/**
 * twSettings actions.
 *
 * @package    twCore
 * @subpackage twSettings
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3346 2010-09-25 16:58:28Z ldath $
 */
class twSettingsActions extends autoTwSettingsActions
{
	public function preExecute() {
		sfConfig::set('tw_admin:default:module', 'administration');
		sfConfig::set('tw_admin:default:category', 'settings');
		return parent::preExecute();
	}
}
