<?php

/**
 * Scm form base class.
 *
 * @method Scm getObject() Returns the current form's model object
 *
 * @package    phpCodeControl
 * @subpackage form
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseScmForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'scm_type_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ScmType'), 'add_empty' => false)),
      'host'        => new sfWidgetFormTextarea(),
      'port'        => new sfWidgetFormInputText(),
      'username'    => new sfWidgetFormInputText(),
      'password'    => new sfWidgetFormInputText(),
      'path'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 100)),
      'scm_type_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ScmType'))),
      'host'        => new sfValidatorString(),
      'port'        => new sfValidatorInteger(array('required' => false)),
      'username'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'password'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'path'        => new sfValidatorString(),
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
