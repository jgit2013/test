<?php
class Model_User extends \Model_Crud
{
	protected static $_table_name = 'users';
	
	protected static $_properties = array(
		'id',
		'username',
		'password',
	    'is_admin',
		'created_at',
		'updated_at',
	);
	
	protected static $_rules = array(
	    'username' => 'required',
	    'password' => 'required',
	);
	
	protected static $_created_at = 'created_at';
	protected static $_updated_at = 'updated_at';
	
	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		
		$val->add_field('username', 'Username', 'required|min_length[1]|max_length[20]');
		$val->add_field('password', 'Password', 'required|min_length[4]|max_length[20]');
		//$val->add_field('is_admin', 'Is Admin', 'required|min_length[1]|max_length[1]');
		
		return $val;
	}
}
