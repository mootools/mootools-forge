<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PluginScreenshot filter form base class.
 *
 * @package    mooforge
 * @subpackage filter
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginScreenshotFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'plugin_id'  => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'title'      => new sfWidgetFormFilterInput(),
      'url'        => new sfWidgetFormFilterInput(),
      'primary'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'plugin_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Plugin', 'column' => 'id')),
      'title'      => new sfValidatorPass(array('required' => false)),
      'url'        => new sfValidatorPass(array('required' => false)),
      'primary'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('plugin_screenshot_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginScreenshot';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'plugin_id'  => 'ForeignKey',
      'title'      => 'Text',
      'url'        => 'Text',
      'primary'    => 'Boolean',
      'created_at' => 'Date',
    );
  }
}
