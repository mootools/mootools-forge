<?php

/**
 * PluginScreenshot form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginScreenshotForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'plugin_id'  => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'title'      => new sfWidgetFormInput(),
      'url'        => new sfWidgetFormInput(),
      'primary'    => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'PluginScreenshot', 'column' => 'id', 'required' => false)),
      'plugin_id'  => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'primary'    => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('plugin_screenshot[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginScreenshot';
  }


}
