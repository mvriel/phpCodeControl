<?php

/**
 * 
 *
 * @author Mike van Riel <mike.vanriel@naenius.com>
 */
class PccScmAdapterSubversion implements PccScmAdapterInterface
{
  /**
   * Details about the selected SCM
   * 
   * @var Scm
   */
  protected $scm = null;

  /**
   * Construct this adapter with the provided Scm details
   *
   * @param Scm $scm
   */
  public function  __construct(Scm $scm)
  {
    $this->scm = $scm;
  }

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
    $host = $this->scm->getHost();
    $path = $this->scm->getPath();
    $username = $this->scm->getUsername();
    $password = $this->scm->getPassword();

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
    exec($command, $output);
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
   * Returns an array of Commit objects.
   *
   * The returned Commit objects are not saved to the database to prevent key conflicts and provide the
   * possibility to be manipulated.
   *
   * Note: The project id is not set, before the commits can be saved this need to be set first
   *
   * @param mixed $since the revision where to start retrieving objects
   *
   * @return array of Commit objects
   */
  public function getCommits($since = null)
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
      $commit->setAuthor((string)$entry->author);
      $commit->setTimestamp(date("Y-m-d H:i:s", strtotime($entry->date)));
      $commit->setMessage((string)$entry->msg);
      foreach($entry->paths->path as $path)
      {
        $change = new FileChange();
        $change->setCommit($commit);
        $change->setFilePath((string)$path);
        $change->setChangeType((string)$path['action']);
        $commit->getFileChange()->add($change);
      }

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