<?php

/**
 * scm actions.
 *
 * @package    phpCodeControl
 * @subpackage scm
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class scmActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->scms = $this->getRoute()->getObjects();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ScmForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new ScmForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->form = new ScmForm($this->getRoute()->getObject());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new ScmForm($this->getRoute()->getObject());

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->getRoute()->getObject()->delete();

    $this->redirect('scm/index');
  }

  public function executeSelect(sfWebRequest $request)
  {
    $scm_id = $request->getParameter('scm_id');
    $this->forward404Unless($scm_id);

    $this->getUser()->setSelectedScmId($scm_id);

    // redirect back to the referrer or if not set; the homepage
    $this->redirect($request->getReferer() ? $request->getReferer() : '@homepage');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $scm = $form->save();

      $this->redirect('scm/edit?id='.$scm->getId());
    }
  }
}
