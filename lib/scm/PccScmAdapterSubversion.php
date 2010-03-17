<?php

/**
 * 
 *
 * @author Mike van Riel <mike.vanriel@naenius.com>
 */
class PccScmAdapterSubversion extends PccScmAdapterAbstract
{
  /**
   * Executes a subversion command with the correct parameters and returns the result as SimpleXMLElement.
   *
   * The command explicitly ignores the auth-cache to prevent overwriting the user's settings.
   *
   * @param string $command
   * @param array $params
   * @return SimpleXMLElement
   */
  protected function execute($command, $arguments = array(), $params = array())
  {
    $host = $this->getScm()->getHost();
    $port = $this->getScm()->getPort();
    if (!$port)
    {
      $port = $this->getScm()->getScmType()->getDefaultPort();
    }
    
    $path = $this->getScm()->getPath();
    $username = $this->getScm()->getUsername();
    $password = $this->getScm()->getPassword();

    // compose command and execute
    foreach ($arguments as &$argument)
    {
      if ($argument == '%ROOT%')
      {
        $argument = $host.$path;
      }
      $argument = str_replace('%HOST%', $host, $argument);
      $argument = escapeshellarg($argument);
    }
    
    $command = 'svn '.escapeshellarg($command).' '.implode(' ', $arguments).' --no-auth-cache --username '.escapeshellarg($username).' --password '.escapeshellarg($password).' --non-interactive';
    foreach ($params as $name => $param)
    {
      $command .= ' --'.escapeshellarg($name).(!empty($param) ? ' '.escapeshellarg($param) : '');
    }

    // execute command and store the output
    $output = array();
    exec($command, $output, $error);

    if ($error != 0)
    {
      throw new Exception('The scm command failed with the following error code and content: '.implode(PHP_EOL, $output).' ('.$error.')');
    }
    return implode(PHP_EOL, $output);
  }

  /**
   * Returns the last revision identifier
   * 
   * @return interface
   */
  public function getLastRevisionId()
  {
    $result = $this->execute('info', array('%ROOT%'), array('xml' => ''));
    $result = simplexml_load_string($result);

    return (string)$result->entry->commit['revision'];
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
    // retrieve commits
    $params = array('verbose' => '', 'xml' => '');
    if (!is_null($since))
    {
      $params['revision'] = ($since+1).':HEAD';
    }
    $content = simplexml_load_string($this->execute('log', array('%ROOT%'), $params));

    // generate commit objects with changes
    $commits = array();
    foreach ($content->logentry as $entry)
    {
      $commit = new Commit();
      $commit->setId((string)$entry['revision']);
      $commit->setScmId($this->getScm()->getId());
      $commit->setAuthor((string)$entry->author);
      $commit->setTimestamp(date("Y-m-d H:i:s", strtotime($entry->date)));
      $commit->setMessage((string)$entry->msg);
      foreach($entry->paths->path as $path)
      {
        $change = new FileChange();
        $change->setCommit($commit);
        $change->setFilePath((string)$path);
        switch ((string)$path['action'])
        {
          case 'A': $change->setFileChangeTypeId(1); break;
          case 'M': $change->setFileChangeTypeId(2); break;
          case 'D': $change->setFileChangeTypeId(3); break;
          default:
            $change->setFileChangeTypeId(4); break;
//            throw new Exception('An unknown file change type was encountered: '.(string)$path['action']);
        }

//        $params = array('change' => $commit->getId(), 'extensions' => '-b -w --ignore-eol-style');
//        $content = $this->execute('diff', array('%HOST%'.(string)$path), $params);
//        
//        // all unified diffs start with a - and +, thus we offset the additions and deletions
//        $insertions = -1;
//        $deletions = -1;
//
//        // iterate through the unified diff and collect each line starting with a - and +
//        foreach(explode(PHP_EOL, $content) as $line)
//        {
//          if (!isset($line[0])) continue;
//
//          switch ($line[0])
//          {
//            case '+': $insertions++; break;
//            case '-': $deletions++; break;
//          }
//        }
//        $change->setInsertions($insertions);
//        $change->setDeletions($deletions);
        
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
    return $this->execute('cat', array('%HOST%'.$file.'@'.$revision));
  }

}