<?php
namespace Fuel\Migrations;

class canciones
{

    function up()
    {        
        \DBUtil::create_table('canciones', array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'direccion_youtube' => array('type' => 'varchar', 'constraint' => 100),
                'titulo' => array('type' => 'varchar', 'constraint' => 100),
            ), array('id')
        );   
    }

    function down()
    {
       \DBUtil::drop_table('canciones');
    }
}