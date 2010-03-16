<?php

/**
 * Commit filter form base class.
 *
 * @package    phpCodeControl
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCommitFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true)),
      'author'     => new sfWidgetFormFilterInput(),
      'timestamp'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'message'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'project_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Project'), 'column' => 'id')),
      'author'     => new sfValidatorPass(array('required' => false)),
      'timestamp'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'message'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('commit_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Commit';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Text',
      'project_id' => 'ForeignKey',
      'author'     => 'Text',
      'timestamp'  => 'Date',
      'message'    => 'Text',
    );
  }
}
