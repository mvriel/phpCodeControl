<?php

/**
 * BaseScm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $type
 * @property string $host
 * @property string $username
 * @property string $password
 * @property string $path
 * @property Doctrine_Collection $Project
 * 
 * @method string              getType()     Returns the current record's "type" value
 * @method string              getHost()     Returns the current record's "host" value
 * @method string              getUsername() Returns the current record's "username" value
 * @method string              getPassword() Returns the current record's "password" value
 * @method string              getPath()     Returns the current record's "path" value
 * @method Doctrine_Collection getProject()  Returns the current record's "Project" collection
 * @method Scm                 setType()     Sets the current record's "type" value
 * @method Scm                 setHost()     Sets the current record's "host" value
 * @method Scm                 setUsername() Sets the current record's "username" value
 * @method Scm                 setPassword() Sets the current record's "password" value
 * @method Scm                 setPath()     Sets the current record's "path" value
 * @method Scm                 setProject()  Sets the current record's "Project" collection
 * 
 * @package    phpCodeControl
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7021 2010-01-12 20:39:49Z lsmith $
 */
abstract class BaseScm extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('scm');
        $this->hasColumn('type', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('host', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('username', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('password', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('path', 'string', null, array(
             'type' => 'string',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Project', array(
             'local' => 'id',
             'foreign' => 'scm_id'));
    }
}