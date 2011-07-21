<?php

/**
 * PluginSection form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginSectionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'plugin_id'  => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'title'      => new sfWidgetFormInput(),
      'content'    => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'PluginSection', 'column' => 'id', 'required' => false)),
      'plugin_id'  => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'    => new sfValidatorString(array('required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('plugin_section[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginSection';
  }


}
