<?php

/**
 * twPlugin form base class.
 *
 * @method twPlugin getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BasetwPluginForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'status_id' => new sfWidgetFormPropelChoice(array('model' => 'twPluginStatus', 'add_empty' => false)),
      'code'      => new sfWidgetFormInputText(),
      'pos'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'twPlugin', 'column' => 'id', 'required' => false)),
      'status_id' => new sfValidatorPropelChoice(array('model' => 'twPluginStatus', 'column' => 'id')),
      'code'      => new sfValidatorString(array('max_length' => 20)),
      'pos'       => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'twPlugin', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('tw_plugin[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'twPlugin';
  }

  public function getI18nModelName()
  {
    return 'twPluginI18n';
  }

  public function getI18nFormClass()
  {
    return 'twPluginI18nForm';
  }

}
