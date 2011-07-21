<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PluginDependency filter form base class.
 *
 * @package    mooforge
 * @subpackage filter
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginDependencyFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'plugin_id'     => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'plugin_tag_id' => new sfWidgetFormPropelChoice(array('model' => 'PluginTag', 'add_empty' => true)),
      'scope'         => new sfWidgetFormFilterInput(),
      'version'       => new sfWidgetFormFilterInput(),
      'component'     => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'plugin_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Plugin', 'column' => 'id')),
      'plugin_tag_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PluginTag', 'column' => 'id')),
      'scope'         => new sfValidatorPass(array('required' => false)),
      'version'       => new sfValidatorPass(array('required' => false)),
      'component'     => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('plugin_dependency_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginDependency';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'plugin_id'     => 'ForeignKey',
      'plugin_tag_id' => 'ForeignKey',
      'scope'         => 'Text',
      'version'       => 'Text',
      'component'     => 'Text',
      'created_at'    => 'Date',
    );
  }
}
