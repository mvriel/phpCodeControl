<?php

/**
 * Scm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    phpCodeControl
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7021 2010-01-12 20:39:49Z lsmith $
 */
class Scm extends BaseScm
{

  /**
   * Returns a SCM adapter object belonging to this type of SCM
   *
   * @return PccScmAdapterAbstract
   */
  public function getAdapter()
  {
    // construct class name
    $class = 'PccScmAdapter'.ucfirst(sfInflector::camelize(str_replace(' ', '_', $this->getScmType()->getName())));
    
    return new $class($this);
  }

  public function __toString()
  {
    return $this->getHost().$this->getPath();
  }
}
