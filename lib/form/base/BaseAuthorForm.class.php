<?php

/**
 * Author form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseAuthorForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'username'        => new sfWidgetFormInput(),
      'email'           => new sfWidgetFormInput(),
      'password'        => new sfWidgetFormInput(),
      'fullname'        => new sfWidgetFormInput(),
      'location'        => new sfWidgetFormInput(),
      'homepageurl'     => new sfWidgetFormInput(),
      'about'           => new sfWidgetFormInput(),
      'avatar'          => new sfWidgetFormInput(),
      'twitter_id'      => new sfWidgetFormInput(),
      'checkhash'       => new sfWidgetFormInput(),
      'plugins_count'   => new sfWidgetFormInput(),
      'confirmed_email' => new sfWidgetFormInputCheckbox(),
      'admin'           => new sfWidgetFormInputCheckbox(),
      'logged_at'       => new sfWidgetFormDateTime(),
      'created_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'Author', 'column' => 'id', 'required' => false)),
      'username'        => new sfValidatorString(array('max_length' => 100)),
      'email'           => new sfValidatorString(array('max_length' => 100)),
      'password'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'fullname'        => new sfValidatorString(array('max_length' => 100)),
      'location'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'homepageurl'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'about'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'avatar'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'twitter_id'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'checkhash'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'plugins_count'   => new sfValidatorInteger(array('required' => false)),
      'confirmed_email' => new sfValidatorBoolean(array('required' => false)),
      'admin'           => new sfValidatorBoolean(array('required' => false)),
      'logged_at'       => new sfValidatorDateTime(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'Author', 'column' => array('email'))),
        new sfValidatorPropelUnique(array('model' => 'Author', 'column' => array('username'))),
      ))
    );

    $this->widgetSchema->setNameFormat('author[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Author';
  }


}
