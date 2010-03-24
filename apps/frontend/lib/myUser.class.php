<?php

class myUser extends sfBasicSecurityUser
{

  /**
   * Sets the currently seleted scm id
   *
   * @param integer $scm_id
   *
   * @return void
   */
  public function setSelectedScmId($scm_id)
  {
    $this->setAttribute('scm_id', $scm_id);
  }

  /**
   * Retrieve the currently selected SCM.
   *
   * If none is selected, select the first one.
   *
   * @return integer
   */
  public function getSelectedScmId()
  {
    $scm_id = $this->getAttribute('scm_id');

    if (is_null($scm_id))
    {
      $scm = Doctrine::getTable('scm')->findAll()->getFirst();

      $this->setSelectedScmId($scm->getId());
      $scm_id = $scm->getId();
    }

    return $scm_id;
  }

  /**
   * Returns the selected SCM object
   *
   * @return Scm
   */
  public function getSelectedScm()
  {
    return Doctrine::getTable('scm')->find($this->getSelectedScmId());
  }
}
