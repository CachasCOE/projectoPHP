<?php

class Model_Listas extends Orm\Model
{
	protected static $_table_name = 'listas';
    protected static $_properties = array('id', 'title', 'id_usuario');
}