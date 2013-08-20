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
	
	public static function check_user_in_use(
	    $username,
	    $password
	) {
	    $val = Model_User::validate('new_user');
	    
	    if ( ! $val->run()) {
	        return 'ERROR';
	        
	        
	        
	        /* $input = Model_User::forge(array(
	            'username' => Input::post('username'),
	            'password' => Input::post('password'),
	        ));
	    
	        $users = Model_User::find(array(
	            'select' => array('username'),
	        ));
	    
	        foreach ($users as $user) {
	            if ($input->username == $user->username) {
	                $is_username_in_use = true;
	    
	                break;
	            }
	        }
	    
	        if ( ! $is_username_in_use) {
	            if ($input and $input->save()) {
	                Session::set_flash('success', 'Added user # '.$input->id.'.');
	    
	                Response::redirect('admin');
	            } else {
	                Session::set_flash('error', 'Could not save user.');
	            }
	        } */
	    } else {
	        $user = Model_User::find(array(
	            'select' => array('username'),
	            'where' => array(
	                array('username', '=', $username),
	            ),
	        ));
	        
	        if ( ! is_null($user)) {
	            return 'IN USE';
	        }
	        
	        $password = md5($password);
	        
	        $new_user = Model_User::forge(array(
	            'username' => $username,
	            'password' => $password,
	        ));
	        
	        $new_user->save();
	        
	        return false;
	    }
	}
}
