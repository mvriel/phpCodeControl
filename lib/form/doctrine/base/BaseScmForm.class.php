<?php

/**
 * Scm form base class.
 *
 * @method Scm getObject() Returns the current form's model object
 *
 * @package    phpCodeControl
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseScmForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'type'     => new sfWidgetFormTextarea(),
      'host'     => new sfWidgetFormTextarea(),
      'username' => new sfWidgetFormTextarea(),
      'password' => new sfWidgetFormTextarea(),
      'path'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'type'     => new sfValidatorString(array('required' => false)),
      'host'     => new sfValidatorString(array('required' => false)),
      'username' => new sfValidatorString(array('required' => false)),
      'password' => new sfValidatorString(array('required' => false)),
      'path'     => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('scm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Scm';
  }

}
