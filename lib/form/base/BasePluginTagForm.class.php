<?php

/**
 * PluginTag form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginTagForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'plugin_id'       => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'name'            => new sfWidgetFormInput(),
      'downloads_count' => new sfWidgetFormInput(),
      'current'         => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'PluginTag', 'column' => 'id', 'required' => false)),
      'plugin_id'       => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'downloads_count' => new sfValidatorInteger(array('required' => false)),
      'current'         => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'PluginTag', 'column' => array('plugin_id', 'name')))
    );

    $this->widgetSchema->setNameFormat('plugin_tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PluginTag';
  }


}
