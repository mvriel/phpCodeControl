<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version2 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('scm', 'username', 'string', '255', array());
        $this->changeColumn('scm', 'password', 'string', '255', array());
    }

    public function down()
    {
        $this->changeColumn('scm', 'username', 'string', '255', array('notnull' => true));
        $this->changeColumn('scm', 'password', 'string', '255', array('notnull' => true));
    }
}
