<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Author filter form base class.
 *
 * @package    mooforge
 * @subpackage filter
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseAuthorFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'        => new sfWidgetFormFilterInput(),
      'email'           => new sfWidgetFormFilterInput(),
      'password'        => new sfWidgetFormFilterInput(),
      'fullname'        => new sfWidgetFormFilterInput(),
      'location'        => new sfWidgetFormFilterInput(),
      'homepageurl'     => new sfWidgetFormFilterInput(),
      'about'           => new sfWidgetFormFilterInput(),
      'avatar'          => new sfWidgetFormFilterInput(),
      'twitter_id'      => new sfWidgetFormFilterInput(),
      'checkhash'       => new sfWidgetFormFilterInput(),
      'plugins_count'   => new sfWidgetFormFilterInput(),
      'confirmed_email' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'admin'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'logged_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'username'        => new sfValidatorPass(array('required' => false)),
      'email'           => new sfValidatorPass(array('required' => false)),
      'password'        => new sfValidatorPass(array('required' => false)),
      'fullname'        => new sfValidatorPass(array('required' => false)),
      'location'        => new sfValidatorPass(array('required' => false)),
      'homepageurl'     => new sfValidatorPass(array('required' => false)),
      'about'           => new sfValidatorPass(array('required' => false)),
      'avatar'          => new sfValidatorPass(array('required' => false)),
      'twitter_id'      => new sfValidatorPass(array('required' => false)),
      'checkhash'       => new sfValidatorPass(array('required' => false)),
      'plugins_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'confirmed_email' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'admin'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'logged_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('author_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Author';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'username'        => 'Text',
      'email'           => 'Text',
      'password'        => 'Text',
      'fullname'        => 'Text',
      'location'        => 'Text',
      'homepageurl'     => 'Text',
      'about'           => 'Text',
      'avatar'          => 'Text',
      'twitter_id'      => 'Text',
      'checkhash'       => 'Text',
      'plugins_count'   => 'Number',
      'confirmed_email' => 'Boolean',
      'admin'           => 'Boolean',
      'logged_at'       => 'Date',
      'created_at'      => 'Date',
    );
  }
}
