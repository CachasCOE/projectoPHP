<?php

class Model_Usuarios extends Orm\Model
{
	protected static $_table_name = 'usuarios';
    protected static $_properties = array('id', 'username', 'password');
}