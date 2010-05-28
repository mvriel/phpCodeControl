<?php
class changeComponents extends sfComponents
{
  /**
   * Constructs and displays the show page body for a file change.
   *
   * Before the file change can be displayed there must be determined whether an older edition exists and if so
   * get the difference between this version and the previous.
   * In order to do this this package depends on the Text_Diff PEAR package
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeShow(sfWebRequest $request)
  {
    // include the Text_Diff files that we need
    include_once('Text/Diff.php');
    include_once('Text/Diff/Renderer/inline.php');
    include_once('Text/Diff/Renderer/unified.php');
    include_once('Text/Diff/Engine/string.php');

    // initialize the basic variables
    $this->previous_commit = $this->file_change->findPrevious();
    $this->previous_image  = '';
    $this->inline_diff     = null;
    $this->unified_diff    = null;
    $this->code            = '';
    $this->type            = 'text';
    $old                   = '';

    // get the SCM adapter
    $scm          = $this->file_change->getCommit()->getScm();
    $scm_adapter  = $scm->getAdapter();

    try
    {
      // get the code of the request version of if simulation is on the simulated version
      $this->code = (!sfConfig::get('app_simulate_scm', false)) ?
        $scm_adapter->getFileContents($this->file_change->getFilePath(), $this->file_change->getCommit()->getRevision()) :
        file_get_contents(sfConfig::get('sf_data_dir').'/'.sfConfig::get('app_simulate_file', ''));
    }
    catch(Exception $e)
    {
      // if an error occurs we provide a flash message
      $this->getUser()->setFlash('inline-error', 'Unable to retrieve the source code from the SCM server; perhaps it is unavailable?');
      return;
    }

    // if a previous version exists, retrieve the file and diff the result
    if ($this->previous_commit)
    {
      try
      {
        // get the code of the request version of if simulation is on the simulated version
        $old = (!sfConfig::get('app_simulate_scm', false)) ?
          $scm_adapter->getFileContents($this->file_change->getFilePath(), $this->previous_commit->getRevision()) :
          file_get_contents(sfConfig::get('sf_data_dir').'/'.sfConfig::get('app_simulate_previous_file', ''));
      }
      catch(Exception $e)
      {
        // if an error occurs we provide a flash message
        $this->getUser()->setFlash('inline-error', 'Unable to retrieve the source code from the SCM server; perhaps it is unavailable?');
        return;
      }
    }

    // act differently depending on the type of file
    switch($this->file_change->getMimeContentType())
    {
      // plain text files should be diffed and shown
      case 'text/plain':
      case 'text/html':
      case 'text/css':
      case 'application/javascript':
      case 'application/json':
      case 'application/xml':
        $this->type = 'text';

        // if there is no old commit; no need to execute a diff
        if (!$old)
        {
          break;
        }

        // prepare diff
        $diff = new Text_Diff('auto', array(explode(PHP_EOL, $old), explode(PHP_EOL, $this->code)));
        $inline_renderer = new Text_Diff_Renderer_Inline();
        $unified_renderer = new Text_Diff_Renderer_Unified();
        $this->code = sfGeshi::parse_single($this->code, 'php');

        // execute and store diff
        $this->inline_diff = $inline_renderer->render($diff);

        // if the diff is empty, no changes can be found (which is unusual)
        if (!$this->inline_diff)
        {
          $this->inline_diff = $this->code;
        }

        $this->unified_diff = $unified_renderer->render($diff);
        break;

      // images should be displayed as an old and new version on two different tabs
      case 'image/png':
      case 'image/jpeg':
      case 'image/gif':
      case 'image/bmp':
      case 'image/vnd.microsoft.icon':
      case 'image/tiff':
      case 'image/svg+xml':
        $this->type = 'image';
        $this->previous_image = $old;
        break;

      // 'other' files (assumed binaries) will only be downloadable
      default:
        $this->type = 'binary';
        break;
    }
  }

}