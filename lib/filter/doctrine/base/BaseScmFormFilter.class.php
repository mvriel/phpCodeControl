<?php

/**
 * Scm filter form base class.
 *
 * @package    phpCodeControl
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseScmFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'     => new sfWidgetFormFilterInput(),
      'host'     => new sfWidgetFormFilterInput(),
      'username' => new sfWidgetFormFilterInput(),
      'password' => new sfWidgetFormFilterInput(),
      'path'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'     => new sfValidatorPass(array('required' => false)),
      'host'     => new sfValidatorPass(array('required' => false)),
      'username' => new sfValidatorPass(array('required' => false)),
      'password' => new sfValidatorPass(array('required' => false)),
      'path'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('scm_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Scm';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'type'     => 'Text',
      'host'     => 'Text',
      'username' => 'Text',
      'password' => 'Text',
      'path'     => 'Text',
    );
  }
}
