<?php
/**
 * Message Model class
 *
 * 讀取messages資料表的model
 *
 * @author    J
 */
class Model_Message extends \Model_Crud
{
    /**
     * @var string 所使用的資料表名稱
     */
	protected static $_table_name = 'messages';
	
	/**
	 * @var array 所使用的資料表內的欄位
	 */
	protected static $_properties = array(
		'id',
		'time',
	    'username',
		'title',
		'message',
		'created_at',
		'updated_at',
	);
	
	/**
	 * @var array 輸入欄位的驗證規則
	 */
	protected static $_rules = array(
		'title' => 'required',
		'message' => 'required',
	);
	
	/**
	 * @var string 給'created at' 欄位的名稱
	 */
	protected static $_created_at = 'created_at';
	
	/**
	 * @var string 給'updated at' 欄位的名稱
	 */
	protected static $_updated_at = 'updated_at';
	
	/**
	 * 回傳一個此model的驗證物件
	 *
	 * @param  string  名稱或要連結的Fieldset實體
	 * @return  object  Validation物件
	 */
	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		
		$val->add_field('title', 'Title', 'required|min_length[1]|max_length[50]');
		$val->add_field('message', 'Message', 'required|min_length[1]');
		
		return $val;
	}
	
	/* public static function check_message(
	    $username,
	    $title,
	    $message,
	    $check_type
	) {
	    $val = Model_Message::validate('title_and_message');
	    
	    if ( ! $val->run()) {
	        return 'ERROR';
	    } else {
	        switch ($check_type) {
	            case 'ADD' : {
	                $message = Model_Message::forge(array(
	                    'username' => $username,
	                    'title' => $title,
	                    'message' => $message,
	                ));
	                
	                if ($message && $message->save()) {
	                    Model_MessageLog::save_log(Session::get('username'), 'C', '', Input::post('title'), '', Input::post('message'), '1');
	                    
	                    return 'ADD OK';
	                } else {
	                    Model_MessageLog::save_log(Session::get('username'), 'C', '', Input::post('title'), '', Input::post('message'), '0');
	                    
	                }
	            }
	            
	            case 'EDIT' : {
	                
	            }
	            
	            default : {
	                return false;
	            }
	        }
	    }
	} */
}
