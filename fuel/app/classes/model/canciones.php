<?php

class Model_Canciones extends Orm\Model
{
	protected static $_table_name = 'canciones';
    protected static $_properties = array('id', 'direccion_youtube','titulo');
    protected static $_many_many = array(
	    'listas' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_cancion', 
	        'table_through' => 'listasTienen', 
	        'key_through_to' => 'id_lista', 
	        'model_to' => 'Model_Listas',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    )
	);
}