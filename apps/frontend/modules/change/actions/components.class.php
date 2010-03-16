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

    // get the SCM adapter
    $scm = $this->file_change->getCommit()->getProject()->getScm();
    $svn = new PccScmAdapterSubversion($scm);

    // get the code of the request version
    $this->code = $svn->getFileContents($this->file_change->getFilePath(), $this->file_change->getCommitId());

    // if a previous version exists, retrieve the file and diff the result
    if ($this->previous_commit)
    {
      $old = $svn->getFileContents($this->file_change->getFilePath(), $this->previous_commit->getId());

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