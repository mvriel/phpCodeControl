<?php

/**
 * FileChange form base class.
 *
 * @method FileChange getObject() Returns the current form's model object
 *
 * @package    phpCodeControl
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFileChangeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'commit_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Commit'), 'add_empty' => true)),
      'file_path'   => new sfWidgetFormTextarea(),
      'change_type' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'commit_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Commit'), 'required' => false)),
      'file_path'   => new sfValidatorString(array('required' => false)),
      'change_type' => new sfValidatorString(array('required' => false)),
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
