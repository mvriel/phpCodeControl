<?php

class phpccUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('project', sfCommandArgument::REQUIRED, 'The id of the project to update'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'phpcc';
    $this->name             = 'update';
    $this->briefDescription = 'Updates the SCM information of phpCodeControl';
    $this->detailedDescription = <<<EOF
The [phpcc:update|INFO] task does things.
Call it with:

  [php symfony phpcc:update|INFO]
EOF;
  }

  protected function getLastCommitIdFromDatabase($project)
  {
    $last_db_commit = Doctrine_Query::create()->
      select('id')->from('Commit')->
      where('project_id=?', $project)->
      orderBy('timestamp DESC')->limit(1)->
      fetchOne();

    if ($last_db_commit)
    {
      return $last_db_commit->getId();
    }

    return null;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $project = Doctrine::getTable('Project')->findOneById($arguments['project']);
    $scm = $project->getScm();

    $scm_object = new PccScmAdapterSubversion($scm);

    $this->logSection('info', 'Determining latest revision number');
    $last_commit_id = $scm_object->getLastRevisionId();
    $last_commit_id_db = $this->getLastCommitIdFromDatabase($arguments['project']);

    if ($last_commit_id == $last_commit_id_db)
    {
      $this->logSection('info', 'Database is up to date');
      return;
    }
    $this->logSection('info', 'Database reports revision '.$last_commit_id_db.' as latest and SCM reports '.$last_commit_id);
    $this->logSection('info', 'Importing commits from subversion starting at revision '.$last_commit_id_db.' (please wait as this can take some time)');

    $commits = $scm_object->getCommits($last_commit_id_db);
    foreach ($commits as $commit)
    {
      $commit->setProjectId($arguments['project']);
      $commit->save();
      foreach($commit->getFileChange() as $change)
      {
        $change->save();
      }
      $this->logSection('info', 'Processed revision '.$commit->getId().' ('.count($commit->getFileChange()).' files touched)');
    }
    $this->logSection('info', 'Finished importing commits');
  }

}
