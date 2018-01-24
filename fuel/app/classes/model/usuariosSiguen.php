<?php

class Model_UsuariosSiguen extends Orm\Model
{
	protected static $_table_name = 'usuariosSiguen';
    protected static $_properties = array('id_usuario', 'id_usuarioSeguido');
    protected static $_primary_key = array('id_usuario','id_usuarioSeguido');
}