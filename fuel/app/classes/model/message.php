<?php
class Model_Message extends \Model_Crud
{
	protected static $_table_name = 'messages';
	
	protected static $_properties = array(
		'id',
		'time',
	    'username',
		'title',
		'message',
		'created_at',
		'updated_at',
	);
	
	protected static $_rules = array(
		'title' => 'required',
		'message' => 'required',
	);
	
	protected static $_created_at = 'created_at';
	protected static $_updated_at = 'updated_at';
	
	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		
		$val->add_field('title', 'Title', 'required|min_length[1]|max_length[50]');
		$val->add_field('message', 'Message', 'required|min_length[1]');
		
		return $val;
	}
}
