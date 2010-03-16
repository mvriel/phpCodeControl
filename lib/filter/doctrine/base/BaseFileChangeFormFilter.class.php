<?php

/**
 * FileChange filter form base class.
 *
 * @package    phpCodeControl
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFileChangeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'commit_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Commit'), 'add_empty' => true)),
      'file_path'   => new sfWidgetFormFilterInput(),
      'change_type' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'commit_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Commit'), 'column' => 'id')),
      'file_path'   => new sfValidatorPass(array('required' => false)),
      'change_type' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('file_change_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FileChange';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'commit_id'   => 'ForeignKey',
      'file_path'   => 'Text',
      'change_type' => 'Text',
    );
  }
}
