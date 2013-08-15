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
	    'during',
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
	    'during' => 'required',
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
	    $val->add_field('during', 'During', 'required|max_length[20]');
	    
	    return $val;
	}
	
	/**
	 * 計算經過的時間
	 *
	 * @param  int       $seconds 經過的秒數
	 * @return  string  回傳經過計算後的時間，格式如(y w d h m s)
	 */
	public static function time_elapsed($seconds)
	{
	    $bit = array(
	        'y' => $seconds / 31556926 % 12,
	        'w' => $seconds / 604800 % 52,
	        'd' => $seconds / 86400 % 7,
	        'h' => $seconds / 3600 % 24,
	        'm' => $seconds / 60 % 60,
	        's' => $seconds % 60
	    );
	    
	    foreach ($bit as $k => $v) {
	        if ($v > 0) {
	            $ret[] = $v . $k;
	        }
	    }
	    
	    return join(' ', $ret);
	}
	
	/**
	 * 儲存使用者的log
	 *
	 * @param  string  $username 使用者名稱
	 * @param  string  $sign_in_time 該名使用者登入的時間
	 * @param  string  $sign_out_time 該名使用者登出的時間
	 * @param  string  $during 該名使用者登入的總時間
	 * @return  boolean  是否儲存成功，成功回傳true，失敗則回傳false
	 */
	public static function save_log(
	    $username,
	    $sign_in_time,
	    $sign_out_time,
	    $during
	) {
	    $user_log = Model_UserLog::forge(array(
	        'username' => $username,
	        'sign_in_time' => $sign_in_time,
	        'sign_out_time' => $sign_out_time,
	        'during' => $during,
	    ));
	    
	    if ($user_log && $user_log->save()) {
	        return true;
	    } else {
	        return false;
	    }
	}
}
