<?php

/**
 * Term form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseTermForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'title'    => new sfWidgetFormInput(),
      'slug'     => new sfWidgetFormInput(),
      'count'    => new sfWidgetFormInput(),
      'category' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorPropelChoice(array('model' => 'Term', 'column' => 'id', 'required' => false)),
      'title'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'slug'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'count'    => new sfValidatorInteger(array('required' => false)),
      'category' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'Term', 'column' => array('title'))),
        new sfValidatorPropelUnique(array('model' => 'Term', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('term[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Term';
  }


}
