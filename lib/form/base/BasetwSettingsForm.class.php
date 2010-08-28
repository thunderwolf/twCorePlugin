<?php

/**
 * twSettings form base class.
 *
 * @method twSettings getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BasetwSettingsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'  => new sfWidgetFormInputText(),
      'value' => new sfWidgetFormTextarea(),
      'id'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'name'  => new sfValidatorString(array('max_length' => 250)),
      'value' => new sfValidatorString(array('required' => false)),
      'id'    => new sfValidatorPropelChoice(array('model' => 'twSettings', 'column' => 'id', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'twSettings', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('tw_settings[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'twSettings';
  }


}
