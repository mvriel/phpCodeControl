<?php
/**
 * PccScmAdapterInterface offers a basic set of methods which must at least be implemented by an SCM adapter
 *
 * @author Mike van Riel <mike.vanriel@naenius.com>
 */
interface PccScmAdapterInterface
{
  /**
   * Construct this adapter with the provided Scm details
   * 
   * @param Scm $scm 
   */
  public function __construct(Scm $scm);

  /**
   * Returns the last revision identifier
   * 
   * @return mixed
   */
  public function getLastRevisionId();

  /**
   * Returns an array of Commit objects.
   *
   * @param mixed $since the revision where to start retrieving objects
   *
   * @return array of Commit objects
   */
  public function getCommits($since = null);

  /**
   * Returns the contents of a specific file at a specific revision (defaults to HEAD).
   *
   * @param string $file
   * @param string $revision
   */
  public function getFileContents($file, $revision = 'HEAD');
  
}