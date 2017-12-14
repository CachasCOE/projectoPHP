<?php
namespace Fuel\Migrations;

class usuariosSiguen
{

    function up()
    {
        \DBUtil::create_table('usuariosSiguen', array(
            'id_usuario' => array('type' => 'int', 'constraint' => 11),
            'id_usuarioSeguido' => array('type' => 'int', 'constraint' => 11),
        ), array('id_usuario','id_usuarioSeguido'),false,'InnoDB', 'utf8_unicode_ci',array(
        array(
            'constraint' => 'claveAjenaUsuariosSiguenAUsuarios',
            'key' => 'id_usuario',
            'reference' => array(
                'table' => 'usuarios',
                'column' => 'id',
            ),
            'on_update' => 'CASCADE',
            'on_delete' => 'RESTRICT'
                ),
        array(
            'constraint' => 'claveAjenaUsuariosSiguenAUsuarios2',
            'key' => 'id_usuarioSeguido',
            'reference' => array(
                'table' => 'usuarios',
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
       \DBUtil::drop_table('usuariosSiguen');
    }
}