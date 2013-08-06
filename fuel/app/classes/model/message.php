<?php
class Model_Message extends \Model_Crud {
	protected static $_table_name = 'messages';
	
	protected static $_properties = array(
		'id',
		'time',
		'title',
		'message',
		'created_at',
		'updated_at',
	);
	
	protected static $_rules = array(
		'title' => 'required',
		'message' => 'required',
	);
	
	/* protected static $_labels = array(
			'title' => 'Your Title',
			'message' => 'Your Message',
	); */
	
	/* protected static $_defaults = array(
			'title' => 'Input your title here',
			'message' => 'Input your message here',
	); */
	
	protected static $_created_at = 'created_at';
	protected static $_updated_at = 'updated_at';
	
	public static function validate($factory) {
		$val = Validation::forge($factory);
		
		//$val->add_field('id', 'Id', 'required|valid_string[numeric]');
		$val->add_field('title', 'Title', 'required|max_length[50]');
		$val->add_field('message', 'Message', 'required');
		
		return $val;
	}
}
