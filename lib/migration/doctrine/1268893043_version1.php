<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version1 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('scm', 'name', 'string', '100', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('scm', 'name');
    }
}
