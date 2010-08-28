<?php

/**
 * sfGuardUserProfile form base class.
 *
 * @method sfGuardUserProfile getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BasesfGuardUserProfileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'    => new sfWidgetFormInputHidden(),
      'asset_id'   => new sfWidgetFormPropelChoice(array('model' => 'twAsset', 'add_empty' => true)),
      'email'      => new sfWidgetFormInputText(),
      'first_name' => new sfWidgetFormInputText(),
      'last_name'  => new sfWidgetFormInputText(),
      'birthday'   => new sfWidgetFormDate(),
      'alias'      => new sfWidgetFormInputText(),
      'culture'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user_id'    => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id', 'required' => false)),
      'asset_id'   => new sfValidatorPropelChoice(array('model' => 'twAsset', 'column' => 'id', 'required' => false)),
      'email'      => new sfValidatorString(array('max_length' => 150)),
      'first_name' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'last_name'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'birthday'   => new sfValidatorDate(array('required' => false)),
      'alias'      => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'culture'    => new sfValidatorString(array('max_length' => 10, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }


}
