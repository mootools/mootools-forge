<?php

/**
 * TermRelationship form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseTermRelationshipForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'plugin_id' => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'term_id'   => new sfWidgetFormPropelChoice(array('model' => 'Term', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'TermRelationship', 'column' => 'id', 'required' => false)),
      'plugin_id' => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
      'term_id'   => new sfValidatorPropelChoice(array('model' => 'Term', 'column' => 'id', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'TermRelationship', 'column' => array('plugin_id', 'term_id')))
    );

    $this->widgetSchema->setNameFormat('term_relationship[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TermRelationship';
  }


}
