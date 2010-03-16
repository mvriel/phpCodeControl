<?php

/**
 * change actions.
 *
 * @package    phpCodeControl
 * @subpackage change
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class changeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->file_changes = $this->getRoute()->getObjects();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->file_change = $this->getRoute()->getObject();
  }

}
