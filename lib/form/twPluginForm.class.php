<?php

/**
 * twPlugin form.
 *
 * @package    form
 * @subpackage tw_plugin
 */
class twPluginForm extends BasetwPluginForm
{
	public function configure()
	{
		$this->embedI18n(array('en', 'pl'));
//		$this->embedI18n(array('pl'));
	}
}
