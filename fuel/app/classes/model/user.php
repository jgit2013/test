<?php
/**
 * User Model class
 *
 * 讀取users資料表的model
 *
 * @author    J
 */
class Model_User extends \Model_Crud
{
    /**
     * @var string 所使用的資料表名稱
     */
	protected static $_table_name = 'users';
	
	/**
	 * @var array 所使用的資料表內的欄位
	 */
	protected static $_properties = array(
		'id',
		'username',
		'password',
	    'is_admin',
		'created_at',
		'updated_at',
	);
	
	/**
	 * @var array 輸入欄位的驗證規則
	 */
	protected static $_rules = array(
	    'username' => 'required',
	    'password' => 'required',
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
		$val->add_field('password', 'Password', 'required|min_length[4]|max_length[12]');
		
		return $val;
	}
	
	/**
	 * 回傳一個此model的驗證物件
	 *
	 * @param  string  $username 使用者名稱
	 * @param  string  $password 使用者密碼
	 * @param  string  $check_type 所要判斷的類型('IN USE'表示要判斷使用者名稱是否己經使用，
	 *                                                                         'IN TABLE'表示要判斷使用者名稱與密碼是否在資料表中)
	 * @return  當使用者名稱或密碼長度太短時，回傳字串'ERROR'；
	 *                 當使用者名稱已存在資料表中時，回傳字串'IN USE'，反之則回傳包含該使用者名稱與密碼的Model_User物件；
	 *                 當使用者名稱與密碼不存在資料表中時，回傳字串'NOT IN TABLE'，反之則回傳包含該使用者資料的Model_User物件；
	 *                 當輸入的$check_type不在選項中則回傳false
	 */
	public static function check_user(
	    $username,
	    $password,
	    $check_type
	) {
	    $val = Model_User::validate('username_and_password');
	    
	    if ( ! $val->run()) {
	        return 'ERROR';
	    } else {
	        switch ($check_type) {
	            case 'IN USE' : {
	                $user_in_use = Model_User::find(array(
	                    'select' => array('username'),
	                    'where' => array(
	                        array('username', '=', $username),
	                    ),
	                ));
	                 
	                if ( ! is_null($user_in_use)) {
	                    return 'IN USE';
	                }
	                 
	                $password = md5($password);
	                 
	                $new_user = Model_User::forge(array(
	                    'username' => $username,
	                    'password' => $password,
	                ));
	                 
	                return $new_user;
	            }
	            
	            case 'IN TABLE' : {
	                $password = md5($password);
	                 
	                $user_in_table = Model_User::find(array(
	                    'select' => array(
	                        'id',
	                        'username',
	                        'password',
	                        'is_admin'
	                    ),
	                    'where' => array(
	                        array('username', '=', $username),
	                        array('password', '=', $password),
	                    ),
	                ));
	                 
	                if (is_null($user_in_table)) {
	                    return 'NOT IN TABLE';
	                } else {
	                    $found_user = Model_User::forge(array(
	                        'id' => $user_in_table[0]->id,
	                        'username' => $user_in_table[0]->username,
	                        'is_admin' => $user_in_table[0]->is_admin,
	                    ));
	                     
	                    return $found_user;
	                }
	            }
	            
	            default : {
	                return false;
	            }
	        }
	    }
	}
}
