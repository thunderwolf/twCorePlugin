<?php

/**
 * twPluginModule form base class.
 *
 * @method twPluginModule getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BasetwPluginModuleForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'plugin_id'   => new sfWidgetFormPropelChoice(array('model' => 'twPlugin', 'add_empty' => false)),
      'status_id'   => new sfWidgetFormPropelChoice(array('model' => 'twPluginStatus', 'add_empty' => false)),
      'route'       => new sfWidgetFormInputText(),
      'credentials' => new sfWidgetFormTextarea(),
      'code'        => new sfWidgetFormInputText(),
      'pos'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'twPluginModule', 'column' => 'id', 'required' => false)),
      'plugin_id'   => new sfValidatorPropelChoice(array('model' => 'twPlugin', 'column' => 'id')),
      'status_id'   => new sfValidatorPropelChoice(array('model' => 'twPluginStatus', 'column' => 'id')),
      'route'       => new sfValidatorString(array('max_length' => 50)),
      'credentials' => new sfValidatorString(array('required' => false)),
      'code'        => new sfValidatorString(array('max_length' => 50)),
      'pos'         => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'twPluginModule', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('tw_plugin_module[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'twPluginModule';
  }

  public function getI18nModelName()
  {
    return 'twPluginModuleI18n';
  }

  public function getI18nFormClass()
  {
    return 'twPluginModuleI18nForm';
  }

}
