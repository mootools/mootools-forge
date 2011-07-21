<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Plugin filter form base class.
 *
 * @package    mooforge
 * @subpackage filter
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasePluginFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'author_id'         => new sfWidgetFormPropelChoice(array('model' => 'Author', 'add_empty' => true)),
      'category_id'       => new sfWidgetFormPropelChoice(array('model' => 'Term', 'add_empty' => true)),
      'stable_tag_id'     => new sfWidgetFormPropelChoice(array('model' => 'PluginTag', 'add_empty' => true)),
      'title'             => new sfWidgetFormFilterInput(),
      'slug'              => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'description_clean' => new sfWidgetFormFilterInput(),
      'official'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'docsurl'           => new sfWidgetFormFilterInput(),
      'demourl'           => new sfWidgetFormFilterInput(),
      'githubuser'        => new sfWidgetFormFilterInput(),
      'githubrepo'        => new sfWidgetFormFilterInput(),
      'howtouse'          => new sfWidgetFormFilterInput(),
      'comments_count'    => new sfWidgetFormFilterInput(),
      'downloads_count'   => new sfWidgetFormFilterInput(),
      'retrieved_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'author_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Author', 'column' => 'id')),
      'category_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Term', 'column' => 'id')),
      'stable_tag_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PluginTag', 'column' => 'id')),
      'title'             => new sfValidatorPass(array('required' => false)),
      'slug'              => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'description_clean' => new sfValidatorPass(array('required' => false)),
      'official'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'docsurl'           => new sfValidatorPass(array('required' => false)),
      'demourl'           => new sfValidatorPass(array('required' => false)),
      'githubuser'        => new sfValidatorPass(array('required' => false)),
      'githubrepo'        => new sfValidatorPass(array('required' => false)),
      'howtouse'          => new sfValidatorPass(array('required' => false)),
      'comments_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'downloads_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'retrieved_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('plugin_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Plugin';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'author_id'         => 'ForeignKey',
      'category_id'       => 'ForeignKey',
      'stable_tag_id'     => 'ForeignKey',
      'title'             => 'Text',
      'slug'              => 'Text',
      'description'       => 'Text',
      'description_clean' => 'Text',
      'official'          => 'Boolean',
      'docsurl'           => 'Text',
      'demourl'           => 'Text',
      'githubuser'        => 'Text',
      'githubrepo'        => 'Text',
      'howtouse'          => 'Text',
      'comments_count'    => 'Number',
      'downloads_count'   => 'Number',
      'retrieved_at'      => 'Date',
      'updated_at'        => 'Date',
      'created_at'        => 'Date',
    );
  }
}
