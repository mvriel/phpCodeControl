<?php
/**
 * PccScmAdapterAbstract offers a basic set of methods which must at least be implemented by an SCM adapter
 *
 * @author Mike van Riel <mike.vanriel@naenius.com>
 */
abstract class PccScmAdapterAbstract
{
  /**
   * Stores the SCM object to which this class belongs
   *
   * @var Scm
   */
  private $scm = null;

  /**
   * Construct this adapter with the provided Scm details
   *
   * @param Scm $scm
   */
  public function __construct(Scm $scm)
  {
    $this->setScm($scm);
  }

  /**
   * Returns the associated SCM
   *
   * @return Scm
   */
  public function getScm()
  {
    return $this->scm;
  }

  /**
   * Sets the associated SCM
   *
   * @param Scm $scm
   *
   * @return void
   */
  public function setScm(Scm $scm)
  {
    $this->scm = $scm;
  }

  /**
   * Returns the last revision identifier
   *
   * @return mixed
   */
  abstract public function getLastRevisionId();

  /**
   * Returns an array of Commit objects.
   *
   * @param mixed $since the revision where to start retrieving objects
   *
   * @return array of Commit objects
   */
  abstract public function getCommits($since = null);

  /**
   * Returns the contents of a specific file at a specific revision (defaults to HEAD).
   *
   * @param string $file
   * @param string $revision
   */
  abstract public function getFileContents($file, $revision = 'HEAD');

  /**
   * Returns the unified diff of a specific file at a specific revision (defaults to HEAD).
   *
   * @param string $file
   * @param string $revision
   */
  abstract public function getUnifiedDiff($file, $revision = 'HEAD');
  
}