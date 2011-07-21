<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * TermRelationship filter form base class.
 *
 * @package    mooforge
 * @subpackage filter
 * @author     Guillermo Rauch
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseTermRelationshipFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'plugin_id' => new sfWidgetFormPropelChoice(array('model' => 'Plugin', 'add_empty' => true)),
      'term_id'   => new sfWidgetFormPropelChoice(array('model' => 'Term', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'plugin_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Plugin', 'column' => 'id')),
      'term_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Term', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('term_relationship_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TermRelationship';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'plugin_id' => 'ForeignKey',
      'term_id'   => 'ForeignKey',
    );
  }
}
