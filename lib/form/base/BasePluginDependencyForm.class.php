<?php

/**
 * PluginDependency form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginDependencyForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'plugin_id'     => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'plugin_tag_id' => new sfWidgetFormPropelChoice(array('model' => 'PluginTag', 'add_empty' => true)),
      'scope'         => new sfWidgetFormInput(),
      'version'       => new sfWidgetFormInput(),
      'component'     => new sfWidgetFormInput(),
      'created_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'PluginDependency', 'column' => 'id', 'required' => false)),
      'plugin_id'     => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
      'plugin_tag_id' => new sfValidatorPropelChoice(array('model' => 'PluginTag', 'column' => 'id', 'required' => false)),
      'scope'         => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'version'       => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'component'     => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('plugin_dependency[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginDependency';
  }


}
