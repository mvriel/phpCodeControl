<?php

/**
 * Commit form base class.
 *
 * @method Commit getObject() Returns the current form's model object
 *
 * @package    phpCodeControl
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCommitForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'project_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true)),
      'author'     => new sfWidgetFormTextarea(),
      'timestamp'  => new sfWidgetFormDateTime(),
      'message'    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'project_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'required' => false)),
      'author'     => new sfValidatorString(array('required' => false)),
      'timestamp'  => new sfValidatorDateTime(array('required' => false)),
      'message'    => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('commit[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Commit';
  }

}
