<?php

/**
 * twPluginStatus form base class.
 *
 * @method twPluginStatus getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BasetwPluginStatusForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInputHidden(),
      'code' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorPropelChoice(array('model' => 'twPluginStatus', 'column' => 'id', 'required' => false)),
      'code' => new sfValidatorString(array('max_length' => 20)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'twPluginStatus', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('tw_plugin_status[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'twPluginStatus';
  }

  public function getI18nModelName()
  {
    return 'twPluginStatusI18n';
  }

  public function getI18nFormClass()
  {
    return 'twPluginStatusI18nForm';
  }

}
