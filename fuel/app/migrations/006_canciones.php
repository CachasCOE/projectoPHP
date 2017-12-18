<?php
namespace Fuel\Migrations;

class canciones
{

    function up()
    {        
        \DBUtil::create_table('canciones', array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'direccion_youtube' => array('type' => 'varchar', 'constraint' => 100),
                'artista' => array('type' => 'varchar', 'constraint' => 100),
                'titulo' => array('type' => 'varchar', 'constraint' => 100),
            ), array('id')
        ); 
        \DB::query("ALTER TABLE `canciones` ADD UNIQUE (`direccion_youtube`)")->execute();  
    }

    function down()
    {
       \DBUtil::drop_table('canciones');
    }
}