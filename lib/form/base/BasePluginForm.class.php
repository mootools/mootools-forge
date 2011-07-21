<?php

/**
 * Plugin form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'author_id'         => new sfWidgetFormPropelChoice(array('model' => 'Author', 'add_empty' => true)),
      'category_id'       => new sfWidgetFormPropelChoice(array('model' => 'Term', 'add_empty' => true)),
      'stable_tag_id'     => new sfWidgetFormPropelChoice(array('model' => 'PluginTag', 'add_empty' => true)),
      'title'             => new sfWidgetFormInput(),
      'slug'              => new sfWidgetFormInput(),
      'description'       => new sfWidgetFormTextarea(),
      'description_clean' => new sfWidgetFormTextarea(),
      'official'          => new sfWidgetFormInputCheckbox(),
      'docsurl'           => new sfWidgetFormInput(),
      'demourl'           => new sfWidgetFormInput(),
      'githubuser'        => new sfWidgetFormInput(),
      'githubrepo'        => new sfWidgetFormInput(),
      'howtouse'          => new sfWidgetFormTextarea(),
      'comments_count'    => new sfWidgetFormInput(),
      'downloads_count'   => new sfWidgetFormInput(),
      'retrieved_at'      => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'created_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
      'author_id'         => new sfValidatorPropelChoice(array('model' => 'Author', 'column' => 'id', 'required' => false)),
      'category_id'       => new sfValidatorPropelChoice(array('model' => 'Term', 'column' => 'id', 'required' => false)),
      'stable_tag_id'     => new sfValidatorPropelChoice(array('model' => 'PluginTag', 'column' => 'id', 'required' => false)),
      'title'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'slug'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('required' => false)),
      'description_clean' => new sfValidatorString(array('required' => false)),
      'official'          => new sfValidatorBoolean(array('required' => false)),
      'docsurl'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'demourl'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'githubuser'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'githubrepo'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'howtouse'          => new sfValidatorString(array('required' => false)),
      'comments_count'    => new sfValidatorInteger(array('required' => false)),
      'downloads_count'   => new sfValidatorInteger(array('required' => false)),
      'retrieved_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'        => new sfValidatorDateTime(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'Plugin', 'column' => array('title'))),
        new sfValidatorPropelUnique(array('model' => 'Plugin', 'column' => array('slug'))),
        new sfValidatorPropelUnique(array('model' => 'Plugin', 'column' => array('githubuser', 'githubrepo'))),
      ))
    );

    $this->widgetSchema->setNameFormat('plugin[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Plugin';
  }


}
