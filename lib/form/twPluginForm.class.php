<?php

/**
 * twPlugin form.
 *
 * @package    form
 * @subpackage tw_plugin
 * @version    SVN: $Id: twPluginForm.class.php 2760 2009-02-16 22:27:09Z ldath $
 */
class twPluginForm extends BasetwPluginForm
{
	public function configure()
	{
		$this->embedI18n(array('en', 'pl'));
//		$this->embedI18n(array('pl'));
	}
}
