<?php
/**
 * Main Controller class
 *
 * 登入頁面的主要Controller
 *
 * @author    J
 */
class Controller_Main extends \Controller_Template
{
    private $captcha_driver = 'recaptcha';
    
    /**
     * 將頁面導向views/main/index.php，
     * 內容為主要的頁面，可選擇登入或建立新的使用者
     */
    public function action_index()
    {
        if ( ! is_null(Session::get('is_sign_in'))) {
            if (Session::get('is_admin') == '1') {
                Response::redirect('admin');
            } else {
                Response::redirect('message');
            }
        }
        
        $this->template->title = "Main Page";
        $this->template->content = View::forge('main/index');
    }
    
    /**
     * 將頁面導向views/main/sign_in.php，
     * 若未登入時顯示登入頁面， 
     * 若使用者名稱或密碼錯誤時顯示錯誤訊息
     */
    public function action_sign_in()
    {
        $is_captcha_incorrect = false;
        $is_username_or_password_incorrect = false;
        
        if (Input::method() == 'POST') {
            $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
            
            if ( ! $is_captcha_incorrect) {
                $found_user = Model_User::check_user(
                    Input::post('username'),
                    Input::post('password'),
                    'IN TABLE'
                );
                
                if (($found_user == 'ERROR') || ($found_user == 'NOT IN TABLE')) {
                    $is_username_or_password_incorrect = true;
                } else {
                    Session::set('ip_address', Input::real_ip());
                    
                    $date = Date::forge();
                    
                    $date->set_timezone('Asia/Taipei');
                    
                    Session::set('sign_in_timestamp', $date->get_timestamp());
                    Session::set('sign_in_time', $date->format('%Y-%m-%d %H:%M:%S'));
                    
                    Session::set('user_id', $found_user->id);
                    Session::set('username', $found_user->username);
                    Session::set('is_sign_in', 'True');
                
                    if ($found_user->is_admin == 1) {
                        Session::set('is_admin', '1');
                    
                        Response::redirect('admin');
                    } else {
                        Session::set('is_admin', '0');
                    
                        Response::redirect('message');
                    }
                }
            }
        }
        
        $this->template->set_global('captcha_driver', $this->captcha_driver, false);
        
        if ($is_captcha_incorrect || $is_username_or_password_incorrect) {
            $this->template->title = "Sign In >> Incorrect Username Or Password";
            $this->template->content = View::forge('main/sign_in');
        } else {
            $this->template->title = "Sign In >> Admin User: Username=admin, Password=admin";
            $this->template->content = View::forge('main/sign_in');
        }
    }
    
    /**
     * 登出使用者並銷毀該次的Session物件
     */
    public function action_sign_out()
    {
        $ip_address = Session::get('ip_address');
        
        $username = Session::get('username');
        
        $date = Date::forge();
        
        $date->set_timezone('Asia/Taipei');
        
        $sign_in_time = Session::get('sign_in_time');
        $sign_out_time = $date->format('%Y-%m-%d %H:%M:%S');
        
        $sign_in_timestamp = (int) Session::get('sign_in_timestamp');
        $sign_out_timestamp = $date->get_timestamp();
        
        $during = Model_UserLog::time_elapsed($sign_out_timestamp - $sign_in_timestamp);
        
        Model_UserLog::save_log(
            $ip_address,
            $username,
            $sign_in_time,
            $sign_out_time,
            $during
        );
        
        Session::destroy();
        
        $this->template->title = "Goodbye  ~~~~ \"".$username."\"";
        $this->template->content = View::forge('main/index');
    }
    
    /**
     * 將頁面導向views/main/create_user.php，
     * 若未建立新使用者時顯示建立新使用者的頁面， 
     * 若使用者名稱或密碼長度不足時顯示錯誤訊息
     */
    public function action_create_user()
    {
        $is_captcha_incorrect = false;
        $is_username_in_use = false;
        $is_username_or_password_too_short = false;
        
        if (Input::method() == 'POST') {
            $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
            
            if ( ! $is_captcha_incorrect) {
                $new_user = Model_User::check_user(
                    Input::post('username'),
                    Input::post('password'),
                    'IN USE'
                );
                
                if ($new_user == 'ERROR') {
                    $is_username_or_password_too_short = true;
                } else if ($new_user == 'IN USE') {
                    $is_username_in_use = true;
                } else {
                    $new_user->save();
                    
                    Response::redirect('main/go');
                }
            }
        }
        
        $this->template->set_global('captcha_driver', $this->captcha_driver, false);
        
        if ($is_captcha_incorrect || $is_username_or_password_too_short) {
            $this->template->title = "Create A New User >> Your Username Should Be At Least \"1\" Character,
                                                     And Your Password Should Be At Least \"4\" Characters";
            $this->template->content = View::forge('main/create_user');
        } else {
            if ($is_username_in_use) {
                $this->template->title = "Create A New User >> Your Username Is Already In Use";
                $this->template->content = View::forge('main/create_user');
            } else {
                $this->template->title = "Create A New User";
                $this->template->content = View::forge('main/create_user');
            }
        }
    }
    
    /**
     * 將頁面導向views/main/go.php
     */
    public function action_go()
    {
        $this->template->title = "Create Successfully, Please Sign In";
        $this->template->content = View::forge('main/go');
    }
}
