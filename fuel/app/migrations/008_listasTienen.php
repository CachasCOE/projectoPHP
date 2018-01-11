<?php
namespace Fuel\Migrations;

class listasTienen
{

    function up()
    {
        \DBUtil::create_table('listasTienen', array(
            'id_lista' => array('type' => 'int', 'constraint' => 5),
            'id_cancion' => array('type' => 'int', 'constraint' =>11),
        ), array('id_lista','id_cancion'),false,'InnoDB', 'utf8_unicode_ci',array(
        array(
            'constraint' => 'claveAjenaListasTienenAListas',
            'key' => 'id_lista',
            'reference' => array(
                'table' => 'listas',
                'column' => 'id',
            ),
            'on_update' => 'CASCADE',
            'on_delete' => 'RESTRICT'
                ),
        array(
            'constraint' => 'claveAjenaListasTienenACanciones',
            'key' => 'id_cancion',
            'reference' => array(
                'table' => 'canciones',
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
       \DBUtil::drop_table('listasTienen');
    }
}