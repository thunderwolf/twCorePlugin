<?php

/**
 * twPlugin filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BasetwPluginFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'status_id' => new sfWidgetFormPropelChoice(array('model' => 'twPluginStatus', 'add_empty' => true)),
      'code'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pos'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'status_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'twPluginStatus', 'column' => 'id')),
      'code'      => new sfValidatorPass(array('required' => false)),
      'pos'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('tw_plugin_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'twPlugin';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'status_id' => 'ForeignKey',
      'code'      => 'Text',
      'pos'       => 'Number',
    );
  }
}
