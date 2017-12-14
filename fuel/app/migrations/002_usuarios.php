<?php
namespace Fuel\Migrations;

class usuarios
{

    function up()
    {
        \DBUtil::create_table('usuarios', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'username' => array('type' => 'varchar', 'constraint' => 100),
            'password' => array('type' => 'varchar', 'constraint' => 100),
            'id_rol' => array('type' => 'int', 'constraint' => 11),
        ), array('id'), false, 'InnoDB', 'utf8_unicode_ci',
                array(
                    array(
                        'constraint' => 'claveAjenaUsuariosARol',
                        'key' => 'id_rol',
                        'reference' => array(
                            'table' => 'rol',
                            'column' => 'id',
                        ),
                        'on_update' => 'CASCADE',
                        'on_delete' => 'RESTRICT'
                    )
                    
                )
		);
    }

    function down()
    {
       \DBUtil::drop_table('usuarios');
    }
}