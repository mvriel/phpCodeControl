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
      $this->code = $scm_adapter->getFileContents($this->file_change->getFilePath(), $this->file_change->getCommitId());
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
        $old = $scm_adapter->getFileContents($this->file_change->getFilePath(), $this->previous_commit->getId());
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

      // execute and store diff
      $this->inline_diff = $inline_renderer->render($diff);
      $this->unified_diff = $unified_renderer->render($diff);
    }
  }

}