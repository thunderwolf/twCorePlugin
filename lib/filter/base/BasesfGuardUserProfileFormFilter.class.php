<?php

/**
 * sfGuardUserProfile filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BasesfGuardUserProfileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'asset_id'   => new sfWidgetFormPropelChoice(array('model' => 'twAsset', 'add_empty' => true)),
      'email'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'first_name' => new sfWidgetFormFilterInput(),
      'last_name'  => new sfWidgetFormFilterInput(),
      'birthday'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'alias'      => new sfWidgetFormFilterInput(),
      'culture'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'asset_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'twAsset', 'column' => 'id')),
      'email'      => new sfValidatorPass(array('required' => false)),
      'first_name' => new sfValidatorPass(array('required' => false)),
      'last_name'  => new sfValidatorPass(array('required' => false)),
      'birthday'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'alias'      => new sfValidatorPass(array('required' => false)),
      'culture'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }

  public function getFields()
  {
    return array(
      'user_id'    => 'ForeignKey',
      'asset_id'   => 'ForeignKey',
      'email'      => 'Text',
      'first_name' => 'Text',
      'last_name'  => 'Text',
      'birthday'   => 'Date',
      'alias'      => 'Text',
      'culture'    => 'Text',
    );
  }
}
