<?php

/**
 * Commit form base class.
 *
 * @method Commit getObject() Returns the current form's model object
 *
 * @package    phpCodeControl
 * @subpackage form
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCommitForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'revision'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FileChange'), 'add_empty' => false)),
      'scm_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Scm'), 'add_empty' => false)),
      'author'    => new sfWidgetFormInputText(),
      'timestamp' => new sfWidgetFormDateTime(),
      'message'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'revision'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FileChange'))),
      'scm_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Scm'))),
      'author'    => new sfValidatorString(array('max_length' => 100)),
      'timestamp' => new sfValidatorDateTime(),
      'message'   => new sfValidatorString(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Commit', 'column' => array('revision', 'scm_id')))
    );

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
