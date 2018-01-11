<?php
namespace Fuel\Migrations;

class usuarios
{

    function up()
    {
        \DBUtil::create_table('usuarios', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'username' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'email' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'password' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
            'id_rol' => array('type' => 'int', 'constraint' => 11),
            'id_device' => array('type' => 'varchar', 'constraint' => 100),
            'profile_photo' => array('type' => 'varchar', 'constraint' => 100),
            'x' => array('type' => 'decimal', 'constraint' => 50),
            'y' => array('type' => 'decimal', 'constraint' => 50),
            'birthday' => array('type' => 'varchar', 'constraint' => 20),
            'city' => array('type' => 'varchar', 'constraint' => 100),
            'description' => array('type' => 'varchar', 'constraint' => 200),
            'id_privacity' => array('type' => 'int', 'constraint' => 11),
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
                    ),
                    array(
                        'constraint' => 'claveAjenaUsuariosAPrivacidad',
                        'key' => 'id_privacity',
                        'reference' => array(
                            'table' => 'privacidad',
                            'column' => 'id',
                        ),
                        'on_update' => 'CASCADE',
                        'on_delete' => 'RESTRICT'
                    )
            )
		);

        \DB::query("ALTER TABLE `usuarios` ADD UNIQUE (`username`)")->execute();
        \DB::query("ALTER TABLE `usuarios` ADD UNIQUE (`email`)")->execute();
    }

    function down()
    {
       \DBUtil::drop_table('usuarios');
    }
}