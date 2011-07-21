<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PluginTag filter form base class.
 *
 * @package    mooforge
 * @subpackage filter
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginTagFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'plugin_id'       => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'name'            => new sfWidgetFormFilterInput(),
      'downloads_count' => new sfWidgetFormFilterInput(),
      'current'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'plugin_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Plugin', 'column' => 'id')),
      'name'            => new sfValidatorPass(array('required' => false)),
      'downloads_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'current'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('plugin_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginTag';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'plugin_id'       => 'ForeignKey',
      'name'            => 'Text',
      'downloads_count' => 'Number',
      'current'         => 'Boolean',
      'created_at'      => 'Date',
    );
  }
}
