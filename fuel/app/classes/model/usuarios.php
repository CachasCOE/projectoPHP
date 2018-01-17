<?php

class Model_Usuarios extends Orm\Model
{
	protected static $_table_name = 'usuarios';
    protected static $_properties = array('id', 'username','email', 'password', 'id_rol', 'id_device', 'profile_photo', 'x','y','birthday','city','description','id_privacity');
    protected static $_has_many = array(
	    'listas' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Listas',
	        'key_to' => 'id_usuario',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    ),
	    'noticias' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Noticias',
	        'key_to' => 'id_usuario',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    )
	);
	protected static $_belongs_to = array(
	    'rol' => array(
	        'key_from' => 'id_usuario',
	        'model_to' => 'Model_Usuarios',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    )
	);
	protected static $_many_many = array(
	    'usuarios' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_usuario', 
	        'table_through' => 'usuariosSiguen', 
	        'key_through_to' => 'id_usuarioSeguido', 
	        'model_to' => 'Model_Usuarios',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    ),
	    'usuarios' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_usuarioSeguido', 
	        'table_through' => 'usuariosSiguen', 
	        'key_through_to' => 'id_usuario', 
	        'model_to' => 'Model_Usuarios',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    )
	);
}