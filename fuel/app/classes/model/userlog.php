<?php
/**
 * UserLog Model class
 *
 * 讀取user_logs資料表的model
 *
 * @author    J
 */
class Model_UserLog extends \Model_Crud
{
    /**
     * @var string 所使用的資料表名稱
     */
    protected static $_table_name = 'user_logs';
    
    /**
     * @var array 所使用的資料表內的欄位
     */
	protected static $_properties = array(
		'id',
	    'username',
	    'sign_in_time',
	    'sign_out_time',
		'created_at',
		'updated_at',
	);
	
	/**
	 * @var array 輸入欄位的驗證規則
	 */
	protected static $_rules = array(
	    'username' => 'required',
	    'sign_in_time' => 'required',
	    'sign_out_time' => 'required',
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
	    $val->add_field('sign_in_time', 'Sign In Time', 'required');
	    $val->add_field('sign_out_time', 'Sign Out Time', 'required');
	    
	    return $val;
	}
}
