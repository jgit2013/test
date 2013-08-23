<?php
/**
 * CommentLog Model class
 *
 * 讀取comment_logs資料表的model
 *
 * @author    J
 */
class Model_CommentLog extends \Model_Crud
{
    /**
     * @var string 所使用的資料表名稱
     */
    protected static $_table_name = 'comment_logs';
    
    /**
     * @var array 所使用的資料表內的欄位
     */
	protected static $_properties = array(
		'id',
	    'time',
	    'username',
	    'action',
	    'before_title',
	    'after_title',
	    'before_message',
	    'after_message',
	    'is_succeed',
		'created_at',
		'updated_at',
	);
	
	/**
	 * @var array 輸入欄位的驗證規則
	 */
	protected static $_rules = array(
	    'username' => 'required',
	    'action' => 'required',
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
	    
	    $val->add_field('username', 'Username', 'required|min_length[1]|max_length[20]');
	    $val->add_field('action', 'Action', 'required|min_length[1]|max_length[1]');
	    
	    return $val;
	}
	
	/**
	 * 儲存訊息的log
	 *
	 * @param  string  $username 使用者名稱
	 * @param  string  $action 動作('C'表示建立訊息，'U'表示更新訊息，'D'表示刪除訊息)
	 * @param  string  $before_title 儲存前的標題
	 * @param  string  $after_title 儲存後的標題
	 * @param  string  $before_message 儲存前的訊息
	 * @param  string  $after_message 儲存後的訊息
	 * @param  string  $is_succeed 是否儲存成功('1'表示成功，'0'表示失敗)
	 * @return  boolean  是否儲存成功，成功回傳true，失敗則回傳false
	 */
	public static function save_log(
	    $username,
	    $action,
	    $before_title,
	    $after_title,
	    $before_message,
	    $after_message,
	    $is_succeed
	) {
	    $message_log = Model_CommentLog::forge(array(
	        'username' => $username,
	        'action' => $action,
	        'before_title' => $before_title,
	        'after_title' => $after_title,
	        'before_message' => $before_message,
	        'after_message' => $after_message,
	        'is_succeed' => $is_succeed,
	    ));
	
	    if ($message_log && $message_log->save()) {
	        return true;
	    } else {
	        return false;
	    }
	}
}
