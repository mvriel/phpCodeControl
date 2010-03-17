<?php

/**
 * FileChange form base class.
 *
 * @method FileChange getObject() Returns the current form's model object
 *
 * @package    phpCodeControl
 * @subpackage form
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFileChangeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'commit_revision'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Commit'), 'add_empty' => false)),
      'file_path'           => new sfWidgetFormTextarea(),
      'file_change_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FileChangeType'), 'add_empty' => false)),
      'insertions'          => new sfWidgetFormInputText(),
      'deletions'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'commit_revision'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Commit'))),
      'file_path'           => new sfValidatorString(),
      'file_change_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FileChangeType'))),
      'insertions'          => new sfValidatorInteger(array('required' => false)),
      'deletions'           => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('file_change[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FileChange';
  }

}
