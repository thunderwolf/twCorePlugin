<?php

/**
 * twPluginI18n form.
 *
 * @package    form
 * @subpackage tw_plugin_i18n
 */
class twPluginI18nForm extends BasetwPluginI18nForm
{
	public function configure()
	{
		$this->widgetSchema['name'] = new sfWidgetFormTextarea(array(), array('rows'=> '1', 'cols'=>'100', 'style' => 'height: 20px;'));
		$this->widgetSchema['description'] = new sfWidgetFormTextareaFCKEditor(array('width'=>'100%', 'height'=>'400', 'CustomConfigurationsPath'=> '/twCorePlugin/js/fck_plugin_config_description.js'));
	}
}
