<?php

/**
 * Project filter form base class.
 *
 * @package    phpCodeControl
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseProjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'   => new sfWidgetFormFilterInput(),
      'scm_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Scm'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'   => new sfValidatorPass(array('required' => false)),
      'scm_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Scm'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('project_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Project';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'name'   => 'Text',
      'scm_id' => 'ForeignKey',
    );
  }
}
