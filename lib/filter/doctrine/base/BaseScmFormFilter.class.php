<?php

/**
 * Scm filter form base class.
 *
 * @package    phpCodeControl
 * @subpackage filter
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseScmFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'scm_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ScmType'), 'add_empty' => true)),
      'host'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'port'        => new sfWidgetFormFilterInput(),
      'username'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'scm_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ScmType'), 'column' => 'id')),
      'host'        => new sfValidatorPass(array('required' => false)),
      'port'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'    => new sfValidatorPass(array('required' => false)),
      'password'    => new sfValidatorPass(array('required' => false)),
      'path'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('scm_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Scm';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'scm_type_id' => 'ForeignKey',
      'host'        => 'Text',
      'port'        => 'Number',
      'username'    => 'Text',
      'password'    => 'Text',
      'path'        => 'Text',
    );
  }
}
