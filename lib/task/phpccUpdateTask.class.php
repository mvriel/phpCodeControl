<?php

class phpccUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('scm', sfCommandArgument::OPTIONAL, 'The id of the scm definition to update'),
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

  protected function getLastCommitIdFromDatabase($scm_id)
  {
    $last_db_commit = Doctrine_Query::create()->
      select('revision')->from('Commit')->
      where('scm_id=?', $scm_id)->
      orderBy('timestamp DESC')->limit(1)->
      fetchOne();

    if ($last_db_commit)
    {
      return $last_db_commit->getRevision();
    }

    return null;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    if ($arguments['scm'])
    {
      $scms = Doctrine::getTable('Scm')->findById($arguments['scm']);
    }
    else
    {
      $scms = Doctrine::getTable('Scm')->findAll();
    }

    foreach($scms as $scm)
    {
      $this->updateForScm($scm);
    }
  }

  protected function updateForScm(Scm $scm)
  {
    $scm_object = $scm->getAdapter();

    $this->logSection('info', 'Determining latest revision number for '.$scm->getName());
    $last_commit_id = $scm_object->getLastRevisionId();
    $last_commit_id_db = $this->getLastCommitIdFromDatabase($scm->getId());

    if ($last_commit_id == $last_commit_id_db)
    {
      $this->logSection('info', 'Database is up to date');
      return;
    }

    if ($last_commit_id_db)
    {
      $this->logSection('info', 'Database reports revision '.$last_commit_id_db.' as latest and SCM reports '.$last_commit_id);
      $this->logSection('info', 'Importing commits from '.$scm->getScmType()->getName().' starting at revision '.$last_commit_id_db.' (please wait as this can take some time)');
    }
    else
    {
      $this->logSection('warning', 'This appears to be your first import of this project, this can take several minutes depending on the size of your project.');
      $this->logSection('info', 'Importing commits from '.$scm->getScmType()->getName().'.');
    }

    $commits = $scm_object->getCommits($last_commit_id_db, true);
//    foreach ($commits as $commit)
//    {
//      $commit->setScmId($arguments['scm']);
//      $commit->save();
//      foreach($commit->getFileChange() as $change)
//      {
//        $change->save();
//      }
//      $this->logSection('info', 'Processed revision '.$commit->getId().' ('.count($commit->getFileChange()).' files touched)');
//    }
    $this->logSection('info', 'Finished importing commits');
  }

}
