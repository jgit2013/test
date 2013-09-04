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
    private $captcha_driver = 'simplecaptcha';
    
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
                Response::redirect('user');
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
        $error_message = null;
        
        if (Input::method() == 'POST') {
            $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
            
            if ( ! $is_captcha_incorrect) {
                $response = Tool_Ask::request_curl(
                    'api/sign_in',
                    'json',
                    'post',
                    array(
                        'username' => Input::post('username'),
                        'password' => Input::post('password')
                    )
                );
                
                $body_json = $response->body();
                
                $body_array = json_decode($body_json);
                
                if ($body_array->success == 'false') {
                    $error_message = $body_array->msg;
                } else {
                    $found_user = $body_array->data;
                    
                    Session::set('ip_address', Input::real_ip());
                    
                    $date = Date::forge();
                    
                    $date->set_timezone('Asia/Taipei');
                    
                    Session::set('sign_in_timestamp', $date->get_timestamp());
                    Session::set('sign_in_time', $date->format('%Y-%m-%d %H:%M:%S'));
                    
                    Session::set('user_id', $found_user->id);
                    Session::set('username', $found_user->username);
                    Session::set('is_sign_in', 'True');
                    
                    if ($found_user->is_admin == '1') {
                        Session::set('is_admin', '1');
                    
                        Response::redirect('admin');
                    } else {
                        Session::set('is_admin', '0');
                    
                        Response::redirect('user');
                    }
                }
            } else {
                $error_message = 'CAPTCHA Is Incorrect';
            }
        }
        
        $view = View::forge('main/sign_in');
        
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
        
        $this->template->set_global('captcha_driver', $this->captcha_driver, false);
        
        $this->template->title = "Sign In";
        $this->template->content = $view;
    }
    
    /**
     * 登出使用者並銷毀該次的Session物件
     */
    public function action_sign_out()
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('main');
        
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
     * 將頁面導向views/main/sign_up.php，
     * 若未建立新使用者時顯示建立新使用者的頁面， 
     * 若使用者名稱或密碼長度不足時顯示錯誤訊息
     */
    public function action_sign_up()
    {
        $error_message = null;
        
        if (Input::method() == 'POST') {
            $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
            
            if ( ! $is_captcha_incorrect) {
                $response = Tool_Ask::request_curl(
                    'api/sign_up',
                    'json',
                    'post',
                    array(
                        'username' => Input::post('username'),
                        'password' => Input::post('password')
                    )
                );
                
                $body_json = $response->body();
                
                $body_array = json_decode($body_json);
                
                if ($body_array->success == 'false') {
                    $error_message = $body_array->msg;
                } else {
                    Response::redirect('go');
                }
            } else {
                $error_message = 'CAPTCHA Is Incorrect';
            }
        }
        
        $view = View::forge('main/sign_up');
        
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
        
        $this->template->set_global('captcha_driver', $this->captcha_driver, false);
        
        $this->template->title = "Sign Up";
        $this->template->content = $view;
    }
    
    /**
     * 將頁面導向views/main/go.php
     */
    public function action_go()
    {
        $this->template->title = "Success, Please Sign In";
        $this->template->content = View::forge('main/go');
    }
}
