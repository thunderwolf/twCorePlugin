<?php

/**
 * twPluginModule filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BasetwPluginModuleFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'plugin_id'   => new sfWidgetFormPropelChoice(array('model' => 'twPlugin', 'add_empty' => true)),
      'status_id'   => new sfWidgetFormPropelChoice(array('model' => 'twPluginStatus', 'add_empty' => true)),
      'route'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'credentials' => new sfWidgetFormFilterInput(),
      'code'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pos'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'plugin_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'twPlugin', 'column' => 'id')),
      'status_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'twPluginStatus', 'column' => 'id')),
      'route'       => new sfValidatorPass(array('required' => false)),
      'credentials' => new sfValidatorPass(array('required' => false)),
      'code'        => new sfValidatorPass(array('required' => false)),
      'pos'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('tw_plugin_module_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'twPluginModule';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'plugin_id'   => 'ForeignKey',
      'status_id'   => 'ForeignKey',
      'route'       => 'Text',
      'credentials' => 'Text',
      'code'        => 'Text',
      'pos'         => 'Number',
    );
  }
}
