<?php

/**
 *
 *
 * @author Stefan Koopmanschap <left@leftontheweb.com>
 */
class PccScmAdapterGit extends PccScmAdapterAbstract
{
  /**
   * Executes a git command with the correct parameters and returns the result as SimpleXMLElement.
   *
   * @param string $command
   * @param array $params
   *
   * @return array
   */
  protected function execute($command, $arguments = array(), $params = array())
  {
    // compose command and execute
    $command = 'git '.$command.' '.implode(' ', $arguments);
    foreach ($params as $name => $param)
    {
      $command .= ' --'.$name.(!empty($param) ? '='.escapeshellarg($param) : '');
    }

    // execute command and store the output
    $output = array();
    exec($command, $output, $error);

    if ($error != 0)
    {
      throw new Exception('The scm command failed with the following error code and content: '.implode(PHP_EOL, $output).' ('.$error.')');
    }
    return $output;
  }

  /**
   * Returns the last revision identifier
   *
   * @return string
   */
  public function getLastRevisionId()
  {
    $this->pull();
    $result = $this->execute('log', array('-n1'), array());
    foreach($result as $line)
    {
      if ('commit' == substr($line, 0, 6))
      {
        $parts = explode(' ', $line);
        return (string)$parts[1];
      }
    }
  }

  /**
   * Retrieves the commits since the given revision and populates a set of Commit Models.
   *
   * By default the commit models are not saved unless the $save parameter is set to true.
   *
   * @param mixed $since the revision where to start retrieving objects
   * @param boolean $save
   *
   * @return array of Commit objects
   */
  public function getCommits($since = null, $save = false)
  {
    // first, load the most recent version
    $this->pull();

    // retrieve commits
    if (!is_null($since))
    {
      $parameters = array(($since).'..');
    }
    else
    {
      $parameters = array();
    }
    $cwd = getcwd();
    chdir($this->getLocalRepoPath());
    $content = $this->execute('log', $parameters, array('raw' => ''));
    chdir($cwd);

    $changes = $this->parseGitLogRawOutput($content);

    // generate commit objects with changes
    $commits = array();
    foreach ($changes as $revision => $change)
    {
      $commit = new Commit();
      $commit->setRevision($revision);
      $commit->setScmId($this->getScm()->getId());
      $commit->setAuthor($change['author']);
      $commit->setTimestamp(date("Y-m-d H:i:s", strtotime($change['timestamp'])));
      $commit->setMessage($change['message']);
      foreach($change['filechanges'] as $path => $action)
      {
        $change = new FileChange();
        $change->setCommit($commit);
        $change->setFilePath($path);
        switch ($action)
        {
          case 'A': $change->setFileChangeTypeId(1); break;
          case 'M': $change->setFileChangeTypeId(2); break;
          case 'D': $change->setFileChangeTypeId(3); break;
          default:
            $change->setFileChangeTypeId(4); break;
        }

        $commit->getFileChange()->add($change);
      }

      if ($save)
      {
        $commit->save();
      }
      echo '.';
      $commits[] = $commit;
    }

    return $commits;
  }

  /**
   * Returns the contents of a specific file at a specific revision (defaults to HEAD).
   *
   * @param string $file
   * @param string $revision
   */
  public function getFileContents($file, $revision = 'HEAD')
  {
    $revision_output = $this->execute('show', array('-p', $revision));
    $current_index = null;
    foreach($revision_output as $line)
    {
      if ('index' == substr($line, 0, 5))
      {
        $parts = explode(' ', $line);
        $hashes = explode('..', $parts[1]);
        $current_index = $hashes[1];
      }
      if ('---' == substr($line, 0, 3))
      {
        return implode("\n", $this->execute('show', array('-p', $current_index)));
      }
    }
  }

  /**
   * Pull all changes to the local repository
   */
  protected function pull()
  {
    $local_repo = $this->getLocalRepoPath();
    if (!is_dir($local_repo))
    {
      if(!mkdir($local_repo, 0777, true))
      {
          throw new Exception('Could not create directory '.$local_repo);
      }
      $this->execute('clone', array($this->getRemoteScmUrl(), $local_repo));
    }
    else
    {
      $cwd = getcwd();
      chdir($local_repo);
      $this->execute('pull', array(), array());
      chdir($cwd);
    }
  }

  /**
   * Get the remote URL of the git repo we're working with here
   *
   * @return string
   */
  protected function getRemoteScmUrl()
  {
    $scm = $this->getScm();
    $url = '';
    if ($scm->getUsername())
    {
      $url .= $scm->getUsername().'@';
    }
    $url .= $scm->getHost();
    if ($scm->getPort())
    {
      $url .= ':'.$scm->getPort();
    }
    $url .= $scm->getPath();
    return $url;
  }

  /**
   * Get the path of the local git clone
   *
   * @return string
   */
  protected function getLocalRepoPath()
  {
    return sfConfig::get('sf_data_dir').'/'.str_replace(array('/',':'), '_', $this->getScm()->getHost()).'/'.str_replace(array('/'), '_', $this->getScm()->getPath());
  }

  protected function parseGitLogRawOutput($input)
  {
    $current_line = 0;
    $output = array();
    $current_revision = null;
    foreach($input as $line)
    {
      // if a new commit is coming by
      if ('commit' == substr($line, 0, 6))
      {
        $parts = explode(' ', $line);
        $current_revision = $parts[1];
        $output[$current_revision] = array();
        $output[$current_revision]['filechanges'] = array();
        $current_line = 0;
      }

      // set the author if this is the author line
      if (1 == $current_line)
      {
        $contents = trim(substr($line, 7));
        $output[$current_revision]['author'] = $contents;
      }

      // parse the date line
      if (2 == $current_line)
      {
        $contents = trim(substr($line, 5));
        $output[$current_revision]['timestamp'] = $contents;
      }

      // parse the commit message
      if (4 == $current_line)
      {
        $output[$current_revision]['message'] = trim($line);
      }

      // parse a filechange line
      if (':' == substr($line, 0, 1))
      {
        // explode parts
        // save the path in the key and the type of change in the value
        $parts = explode(' ', $line);
        $output[$current_revision]['filechanges'][trim(substr($parts[4], 1))] = substr($parts[4], 0, 1);
      }

      // add one to the line counter
      $current_line++;
    }

    return $output;
  }

  /**
   * Returns the unified diff of a specific file at a specific revision (defaults to HEAD).
   *
   * @param string $file
   * @param string $revision
   */
  public function getUnifiedDiff($file, $revision = 'HEAD')
  {
    return $this->execute('diff', array('--combined', '-p', $revision, $file));
  }

}