<?php

/**
 * FileChange filter form base class.
 *
 * @package    phpCodeControl
 * @subpackage filter
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFileChangeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'commit_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Commit'), 'add_empty' => true)),
      'file_path'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'file_change_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FileChangeType'), 'add_empty' => true)),
      'insertions'          => new sfWidgetFormFilterInput(),
      'deletions'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'commit_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Commit'), 'column' => 'id')),
      'file_path'           => new sfValidatorPass(array('required' => false)),
      'file_change_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('FileChangeType'), 'column' => 'id')),
      'insertions'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'deletions'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
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
      'id'                  => 'Number',
      'commit_id'           => 'ForeignKey',
      'file_path'           => 'Text',
      'file_change_type_id' => 'ForeignKey',
      'insertions'          => 'Number',
      'deletions'           => 'Number',
    );
  }
}
