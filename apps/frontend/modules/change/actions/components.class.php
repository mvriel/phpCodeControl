<?php
class changeComponents extends sfComponents
{

  public function executeShow(sfWebRequest $request)
  {
    include_once('Text/Diff.php');
    include_once('Text/Diff/Renderer/inline.php');
    include_once('Text/Diff/Renderer/unified.php');
    include_once('Text/Diff/Engine/string.php');

    $this->previous_commit = $this->file_change->findPrevious();
    $this->inline_diff = null;
    $this->unified_diff = null;
    
    // get the SCM adapter
    $scm = $this->file_change->getCommit()->getScm();
    $scm_adapter = $scm->getAdapter();

    // get the code of the request version
    try
    {
      $this->code = (!sfConfig::get('app_simulate_scm', false)) ?
        $scm_adapter->getFileContents($this->file_change->getFilePath(), $this->file_change->getCommit()->getRevision()) :
        file_get_contents(sfConfig::get('sf_data_dir').'/example_final.diff');
    }
    catch(Exception $e)
    {
      $this->getUser()->setFlash('error', 'Unable to retrieve the source code from the SCM server; perhaps it is unavailable?');
      return;
    }

    // if a previous version exists, retrieve the file and diff the result
    if ($this->previous_commit)
    {
      try
      {
        $old = (!sfConfig::get('app_simulate_scm', false)) ?
          $scm_adapter->getFileContents($this->file_change->getFilePath(), $this->previous_commit->getRevision()) :
          file_get_contents(sfConfig::get('sf_data_dir').'/example.diff');
      }
      catch(Exception $e)
      {
        $this->getUser()->setFlash('error', 'Unable to retrieve the source code from the SCM server; perhaps it is unavailable?');
        return;
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
    }
  }

}