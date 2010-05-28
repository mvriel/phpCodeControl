<?php

/**
 * The actions belonging to a FileChange.
 *
 * @package    phpCodeControl
 * @subpackage change
 * @author     Mike van Riel
 */
class changeActions extends sfActions
{

  /**
   * Shows a list of file changes.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->file_changes = $this->getRoute()->getObjects();
  }

  /**
   * View the details of a file change.
   *
   * Mostly used as AJAX component for the show page of the Commit module.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->file_change = $this->getRoute()->getObject();
  }

  /**
   * Download the file related to a specific FileChange (thus revision and path).
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeDownload(sfWebRequest $request)
  {
    // attempt to retrieve the id
    $file_change_id = $request->getParameter('file_change_id', null);
    $this->forward404Unless($file_change_id, 'Expected a file change identifier but received none');

    // attempt to retrieve the object
    $file_change = Doctrine::getTable('FileChange')->find($file_change_id);
    $this->forward404Unless($file_change, 'Tried to find file change #'.$file_change_id.' but no results were received');

    // get the Scm Adapter in order to later retrieve the file contents
    $scm = $file_change->getCommit()->getScm();
    $scm_adapter = $scm->getAdapter();

    try
    {
      // get the file contents; or when simulation is on: the simulation file
      $contents = (!sfConfig::get('app_simulate_scm', false)) ?
        $scm_adapter->getFileContents($file_change->getFilePath(), $file_change->getCommit()->getRevision()) :
        file_get_contents(sfConfig::get('sf_data_dir').'/'.sfConfig::get('app_simulate_file', ''));
    }
    catch(Exception $e)
    {
      $this->forward404('Unable to retrieve the source code from the SCM server; perhaps it is unavailable?');
    }

    // set the content, type and disposition
    $response = $this->getResponse();
    $response->setContentType($file_change->getMimeContentType());
    $response->setHttpHeader('Content-Disposition', ' attachment; filename='.basename($file_change->getFilePath()));
    $this->getResponse()->setContent($contents);

    // disable the web debug bar to preven pollution on debugging systems
    sfConfig::set('sf_web_debug', false);

    // only the content is wanted; nothing else
    return sfView::NONE;
  }

}
