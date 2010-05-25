<?php

/**
 * BaseFileChange
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $commit_revision
 * @property string $file_path
 * @property integer $file_change_type_id
 * @property integer $insertions
 * @property integer $deletions
 * @property Commit $Commit
 * @property FileChangeType $FileChangeType
 * 
 * @method string         getCommitRevision()      Returns the current record's "commit_revision" value
 * @method string         getFilePath()            Returns the current record's "file_path" value
 * @method integer        getFileChangeTypeId()    Returns the current record's "file_change_type_id" value
 * @method integer        getInsertions()          Returns the current record's "insertions" value
 * @method integer        getDeletions()           Returns the current record's "deletions" value
 * @method Commit         getCommit()              Returns the current record's "Commit" value
 * @method FileChangeType getFileChangeType()      Returns the current record's "FileChangeType" value
 * @method FileChange     setCommitRevision()      Sets the current record's "commit_revision" value
 * @method FileChange     setFilePath()            Sets the current record's "file_path" value
 * @method FileChange     setFileChangeTypeId()    Sets the current record's "file_change_type_id" value
 * @method FileChange     setInsertions()          Sets the current record's "insertions" value
 * @method FileChange     setDeletions()           Sets the current record's "deletions" value
 * @method FileChange     setCommit()              Sets the current record's "Commit" value
 * @method FileChange     setFileChangeType()      Sets the current record's "FileChangeType" value
 * 
 * @package    phpCodeControl
 * @subpackage model
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: Builder.php 7380 2010-03-15 21:07:50Z jwage $
 */
abstract class BaseFileChange extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('file_change');
        $this->hasColumn('commit_revision', 'string', 200, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '200',
             ));
        $this->hasColumn('file_path', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('file_change_type_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('insertions', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('deletions', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Commit', array(
             'local' => 'commit_revision',
             'foreign' => 'revision'));

        $this->hasOne('FileChangeType', array(
             'local' => 'file_change_type_id',
             'foreign' => 'id'));
    }
}