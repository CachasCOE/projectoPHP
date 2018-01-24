<?php

class Model_Privacidad extends Orm\Model
{
	protected static $_table_name = 'privacidad';
    protected static $_properties = array('id', 'profile','friends','lists','notifications','localization');
	protected static $_primary_key = array('id');
	
}