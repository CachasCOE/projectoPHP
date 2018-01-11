<?php
namespace Fuel\Migrations;

class privacidad
{

    function up()
    {
        \DBUtil::create_table('privacidad', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'profile' => array('type' => 'bool'),
            'friends' => array('type' => 'bool'),
            'lists' => array('type' => 'bool'),
            'notifications' => array('type' => 'bool'),
            'localization' => array('type' => 'bool'),
        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci'
        );
    }

    function down()
    {
       \DBUtil::drop_table('privacidad');
    }
}